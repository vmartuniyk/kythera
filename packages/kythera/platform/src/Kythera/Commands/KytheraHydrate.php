<?php namespace Kythera\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Input;
use Kythera\Models\HidrateDocument;

class KytheraHydrate extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'kythera:hydrate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Hydrate elastic search';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$limit  = $this->argument('limit');
		$this->import($limit);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
				array('limit', InputArgument::OPTIONAL, 'Total records to hydrate.', 0),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
		);
	}

	protected function import($limit = 0) {
	    #options
		$LOCAL = (php_sapi_name() == 'cli') ? strpos(@$_SERVER['SSH_CLIENT'], '192.168') === 0
											: strpos(@$_SERVER['SERVER_ADDR'], '192.168') === 0;

	    DB::disableQueryLog();
	    set_time_limit(0); $LOCAL ? ini_set('memory_limit', '3072M') : ini_set('memory_limit', '4096M');
	    $time_start = microtime(true);

	    $hd = new HidrateDocument();
	    $index = $hd->getIndexName();

	    $this->info('Allocated memory '.($LOCAL ? '3072M' : '4096M'));
	    $this->info('Hydrating elastic search with documents..');

	    $this->info('    Deleting index '.$index.'..');
	    $hd->getElasticSearchClient()->indices()->delete(array('index' => $index));

	    //curl -XPUT 'localhost:9200/kfn?pretty'
	    $this->info('    Creating index '.$index.' and apply settings..');
	    HidrateDocument::createIndex($shards = null, $replicas = null, $hd->getAnalyzer());

	    $this->info('    Set mappings..');
	    HidrateDocument::putMapping();

	    //$items = $limit ? HidrateDocument::where('document_type_id', '!=', 23)->orderByRaw('document_entities.created_at DESC')->limit($limit)->get()
	    //				: HidrateDocument::where('document_type_id', '!=', 23)->orderByRaw('document_entities.created_at DESC')->get();
	    $items = $limit ? HidrateDocument::where('enabled',1)->orderByRaw('document_entities.created_at DESC')->limit($limit)->get()
	    				: HidrateDocument::where('enabled',1)->orderByRaw('document_entities.created_at DESC')->get();
	    //$items = HidrateDocument::where('enabled',1)->where('persons_id',1)->orderByRaw('document_entities.created_at DESC')->limit($limit)->get();

	    if ($limit < 100) {
		    foreach ($items as $i=>$item) {
		    	if ($i%250==0)
		    	$this->info(sprintf("        %d: %s", $item->id, $item->title));
		    }
	    }
	    $this->info('    Indexed documents: '. count($items));
	    $this->info('    Reindexing...');
	    $items->addToIndex();
	    //HidrateDocument::addAllToIndex();

	    $this->info("    Elapsed ~".((microtime(true) - $time_start)/60)." seconds.");
	    $this->info('DONE');
	}

}
