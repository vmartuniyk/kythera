<?php namespace Kythera\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Input;

class KytheraImportFamily extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'kythera:import_family';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import kythera families';

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
		//
		$this->import();
	}

	protected function import()
	{
	    #options
	    $LOCAL = (php_sapi_name() == 'cli') ? strpos(@$_SERVER['SSH_CLIENT'], '192.168') === 0
	                                        : strpos(@$_SERVER['SERVER_ADDR'], '192.168') === 0;

	    set_time_limit(0); $LOCAL ? ini_set('memory_limit', '2048M') : ini_set('memory_limit', '4096M');
		$time_start = microtime(true);

		//$this->call('down');

	    #options
	    DB::disableQueryLog();

	    /*
        * ./artisan kythera:import_table -d1 individuum
        * ./artisan kythera:import_table -d1 names
        * ./artisan kythera:import_table -d1 persons
        * ./artisan kythera:import_table -d1 family
        * ./artisan kythera:import_table -d1 parents
        */
        #copy tables by truncating them first!
        $this->call('kythera:import_table', array('source'=>'individuum'));
        $this->call('kythera:import_table', array('source'=>'names'));
        $this->call('kythera:import_table', array('source'=>'persons'));
        $this->call('kythera:import_table', array('source'=>'family'));
        $this->call('kythera:import_table', array('source'=>'parents'));

	    $entities = array(
	        'FAMILY_TREE_PERSON'
	    );

	    foreach ($entities as $entity)
	    {
	       $this->call('kythera:import_entities', array('category' => $entity, 'silent' => true));
	    }

	    //$this->call('up');

	    $time_end = microtime(true);
	    $time = $time_end - $time_start;
	    $this->info("Elapsed $time seconds.");
	}

}
