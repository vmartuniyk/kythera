<?php namespace Kythera\Commands;

/*
 *
SET FOREIGN_KEY_CHECKS=0;
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `document_categories`;
CREATE TABLE IF NOT EXISTS `document_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` unsigned int(11) NOT NULL,
  `category_id` unsigned int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dc` (`document_id`,`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;
SET FOREIGN_KEY_CHECKS=1;
 *
 * */

/*
 * ./artisan kythera:import_categories
 * */


use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Input;

class KytheraImportCategories extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'kythera:import_categories';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import document categories';

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

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
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

	protected function import() {
	    #options
	    DB::disableQueryLog();

	    #truncate
	    DB::table("document_categories")->truncate();

	    #fetch
    	$rows = DB::connection('import')
                    ->select(DB::raw("
                            SELECT
                    			id,
                    			document_type_id
                            FROM documents
                            "));

	    $i=0;
	    if ($c = count($rows)) {
	        $this->info(sprintf("Categories to be imported: %d", $c));

	        #truncate
	        DB::statement("SET FOREIGN_KEY_CHECKS=0");

	        #insert categories
	        $errors = array();
	        $infos = array();
	        $queries = array();
	        foreach ($rows as $row) {
	            $query = sprintf("
	                    REPLACE INTO document_categories
	                    SET
	                        document_id=%d,
	                        category_id=%d;
	                    ",
	                    $row->id,
	                    $row->document_type_id
	                    );
	            $queries[] = $query;

	            if (DB::statement($query)) {
    	            $i++;
    	            if ($i%1000==0)
   	                $this->info(sprintf("    (%d-%d) inserted id:%d > cat:%d", $i, $c, $row->id, $row->document_type_id));
	            } else {
	                $errors[]=sprintf("    Error inserting document ".$row['id']);
	            }
	        }

            $this->info(sprintf("DONE: inserted %d from %d documents", count($queries), $c));
            $this->error(implode(chr(13).chr(10), $errors));

            /*
            if ($this->option('dump')) {
                $this->info('QUERIES:');
                $this->info(implode(chr(13).chr(10), $queries));
            }
            */

            DB::statement("SET FOREIGN_KEY_CHECKS=1");
	    } else {
	        $this->error("Import failed: original table is empty.");
	    }

	}

}
