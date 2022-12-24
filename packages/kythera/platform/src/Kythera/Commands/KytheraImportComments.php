<?php namespace Kythera\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Input;

class KytheraImportComments extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'kythera:import_comments';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import comments';

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
		$limit = $this->argument('limit');
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
			array('limit', InputArgument::OPTIONAL, 'Total records to import.', 'ALL'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
	    ////./artisan --dump=1 kythera:import_users 10
		return array(
			array('dump', null, InputOption::VALUE_OPTIONAL, 'Dump queries.', null),
		);
	}

	protected function import($limit) {
	    #options
	    DB::disableQueryLog();

	    #fetch
	    if ($limit == 'ALL') {
    	    $rows = DB::connection('import')
                    ->select(DB::raw("
                            SELECT
                                *
                            FROM
                              comments
                            WHERE
                              language_id IN (1,3)
                            "));
	    } else {
    	    $rows = DB::connection('import')
                    ->select(DB::raw(sprintf("
                            SELECT
                                *
                            FROM
                              comments
                            WHERE
                              language_id IN (1,3)
                            LIMIT %d;
                            ", $limit)));
	    }

	    $i=0;
	    if ($c = count($rows)) {
	        $this->info(sprintf("Comment records to be imported: %d", $c));

	        #truncate
	        DB::table("comments")->truncate();

	        #insert records
	        $errors = array();
	        $infos = array();
	        $queries = array();
	        foreach ($rows as $row) {
	        	$comment = Import::extractAnchor($row->comment_text);

	            $query = sprintf("
	                    INSERT INTO comments
	                    SET
	                        id=%d,
	                        persons_id=%d,
	                        document_id=%d,
	                        created_at=%s,
	                        updated_at=%s,
	                        l='%s',
	                        enabled=%d,
	                        comment=%s
	                        ;
	                    ",
	                    $row->id,
	                    $row->persons_id,
	                    $row->document_id,
	                    ($row->created != '0000-00-00 00:00:00') && ($row->created != '') ? "'{$row->created}'" : 'NULL',
	                    ($row->lastchange != '0000-00-00 00:00:00') && ($row->lastchange != '') ? "'{$row->lastchange}'" : 'NULL',
	                    $row->language_id==1 ? 'en':'gr',
	                    $row->enabled,
	                    $row->comment_text ? DB::getPdo()->quote($comment) : 'NULL'
	                    );
	            $queries[] = $query;

	            if (DB::statement($query)) {
    	            $i++;
    	            if (($i%100==0))
                    $this->info(sprintf("    (%d-%d) inserted id:%d", $i, $c, $row->id));
	            } else {
	                $errors[]=sprintf("Error inserting record ".$row['id']);
	            }
	        }

            $this->info(sprintf("DONE: inserted %d from %d records", count($queries), $c));
            $this->error(implode(chr(13).chr(10), $errors));

            if ($this->option('dump')) {
                $this->info('QUERIES:');
                $this->info(implode(chr(13).chr(10), $queries));
            }
	    } else {
	        $this->error("Import of comments failed: original table is empty.");
	    }

	}


}
