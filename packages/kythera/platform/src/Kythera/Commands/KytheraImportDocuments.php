<?php namespace Kythera\Commands;

use App\Models\Translation;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Input;

class KytheraImportDocuments extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'kythera:import_documents';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import & replace original Kythera documents';

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
		$limit = $this->argument('limit');
		$cat   = $this->argument('category');
		$this->import($cat, $limit);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('category', InputArgument::REQUIRED, 'Category string_id.'),
			array('limit', InputArgument::OPTIONAL, 'Total records to import.', 10000),
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
	        array('replace', 'r', InputOption::VALUE_NONE, 'Replace with category (default is add).', null),
			array('dump', 'd', InputOption::VALUE_NONE, 'Dump queries.', null),

		);
	}

	protected function getCatId($cat) {
	    $result = false;
	    if ($row = DB::connection('import')
                    ->select(DB::raw("SELECT id FROM document_types WHERE string_id='{$cat}' LIMIT 1;"))) {
	        $result = $row[0]->id;
	    }
	    return $result;
	}

	protected function hasDuplicate($item, &$result, $fix = false) {
	    $doc_type_id = $item[1]->docTypeId;
	    $value       = Translation::slug($item[1]->language01);
	    if ($row = DB::table('documents')
	                ->where('document_type_id', '=', $doc_type_id)
	                ->where('uri', '=', $value)
	                ->first()) {
	        $result['dup']++;
	        if ($fix) {
	            $item[1]->language01 = $item[1]->language01.'-'.$item[1]->entry_id;
	            $this->info(sprintf('    Duplicate found on ID: %d -> "%s", repaired as "%s"', $row->id, $row->uri, $item[1]->language01));
	        } else {
	            $this->info(sprintf('    Duplicate found on ID: %d -> "%s"', $row->id, $row->uri));
	        }
	    }
	    return $item;
	}

	protected function import($cat, $limit) {
	    #options
	    date_default_timezone_set('Europe/Berlin');
	    DB::disableQueryLog();
	    $result = array('ok'=>0, 'error'=>0, 'ignore'=>0, 'trans'=>0, 'total'=>0, 'dup'=>0);

		if ($this->option('replace')) {
    	    DB::table("documents")->truncate();
    	    DB::statement("delete from translations where t='documents';");
	        $this->info('All records in "documents" are deleted.');
	    } else {
	        if ($catId = $this->getCatId($cat)) {
	            DB::statement("delete from documents where document_type_id={$catId};");
	            $this->info(sprintf('All records of type %s in "documents" are deleted.', $cat));
	        }
	    }

	    $items = Import::getDocuments($cat, $limit*2, $this->option('dump'));
	    $result['total']= $c = count($items);
	    $this->info(sprintf('Found %d records (limit %d) in category: %s', $c, $limit, $cat));

	    $i=0;
	    foreach ($items as $entry_id =>$item) {
	        $this->info(sprintf('Importing record %d - %d: %d', ++$i, $c, $entry_id));

	        //verify format
	        if (count($item)!=2) {
	            echo __FILE__.__LINE__.'<pre>$$item='.htmlentities(print_r($item,1)).'</pre>';
	            $this->error("    Ignoring record {$i}: {$entry_id}");
	            $result['ignore']++;
	            continue;
	        }

	        //duplicate
	        $item = $this->hasDuplicate($item, $result, true);
	        //images
	        $item = Import::extractImages($item, true);
	        //links
	        $item = Import::extractAnchors($item);

            try {
	            $query = sprintf("
	                    INSERT INTO documents
	                    SET
	                        id=%d,
	                        document_type_id=%d,
	                        enabled=%d,
	                        persons_id=%d,
	                        created_at='%s',
	                        updated_at='%s',
	                        uri='%s',
	                        title=%s,
	                        content=%s,
	                        top_article=%d
	                    ",
	                    $entry_id,
	                    $item[1]->docTypeId,
	                    $item[1]->documentEnabled,
	                    $item[1]->persons_id,
	                    $item[1]->org_created,
	                    $item[1]->org_lastchange,
	                    Translation::slug($item[1]->language01),
	                    DB::getPdo()->quote($item[1]->language01),
	                    DB::getPdo()->quote(nl2br($item[2]->language01)),
	                    $item[1]->isTopArticle
	            );
            } catch (Exception $e) {
                $this->error("Inserting ID {$entry_id} failed: ".$e->getMessage());
                die;
            }


            try {
                if(DB::statement($query)) {
                    $result['ok']++;
                } else {
                    $result['error']++;
                }
            } catch (Exception $e) {
    	        $this->error("Inserting ID {$entry_id} failed: ".$e->getMessage());
            }

            #gr / title
            if ($item[1]->language03) {
	            $query = sprintf("
	                    REPLACE INTO translations
	                    SET
                            id=%d,
	                        t='documents',
	                        l='gr',
	                        k='title',
	                        v=%s
	                    ",
	                    $entry_id,
	                    DB::getPdo()->quote($item[1]->language03)
	            );
	            if (DB::statement($query)) {
	                $result['trans']++;
	            }

	            #slug
	            $query = sprintf("
	                    REPLACE INTO translations
	                    SET
                            id=%d,
	                        t='documents',
	                        l='gr',
	                        k='uri',
	                        v='%s'
	                    ",
	                    $entry_id,
	                    Translation::slug($item[1]->language03)
	            );
	            DB::statement($query);

            }
            //echo __FILE__.__LINE__.'<pre>$query='.htmlentities(print_r($query,1)).'</pre>';

            #gr / content
            if ($item[2]->language03) {
	            $query = sprintf("
	                    REPLACE INTO translations
	                    SET
                            id=%d,
	                        t='documents',
	                        l='gr',
	                        k='content',
	                        m=%s
	                    ",
	                    $entry_id,
	                    DB::getPdo()->quote(nl2br($item[2]->language03))
	            );
                if (DB::statement($query)) {
	                $result['trans']++;
	            }
            }
	    }
	    $this->info(sprintf('DONE'));
	    $this->info(print_r($result,1));

	}

}
