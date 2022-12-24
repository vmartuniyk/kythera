<?php namespace Kythera\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Input;

class KytheraImportAudio extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'kythera:import_audio';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import audio';

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


	private function verifyPath($path)
	{
		return str_replace('//', '/', $path.'/');
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
	    #rsync -av -e 'ssh -p 10022' --exclude=bb-download/ --exclude=bb-download/ root@87.106.242.9:/srv/www/vhosts/kythera-family.net/httpdocs ./
	    $src_path = Config::get('app.local') ? '/home/virgil/html/kytherafamilynet/html/'
	    		                             : '/var/www/vhosts/kythera-family.net/import.kythera-family.net/httpdocs/';
	    $dst_path = public_path().'/';

	    #fetch
	    if ($limit == 'ALL') {
    	    $rows = DB::connection('import')
                    ->select(DB::raw("
                            SELECT
                                *
                            FROM
                              audiodocuments
                            "));
	    } else {
    	    $rows = DB::connection('import')
                    ->select(DB::raw(sprintf("
                            SELECT
                                *
                            FROM
                              audiodocuments
                            LIMIT %d;
                            ", $limit)));
	    }

	    $i=0;
	    if ($c = count($rows)) {
	        $this->info(sprintf("Audio records to be imported: %d", $c));

	        #truncate
	        DB::table("document_audio")->truncate();

	        #insert records
	        $errors = array();
	        $infos = array();
	        $queries = array();
	        foreach ($rows as $i=>$row) {
	        	//generate server name
	        	$ext  = strtolower(pathinfo($row->audio_name, PATHINFO_EXTENSION));
	        	$name = time().$i.'.'.$ext;

	            $query = sprintf("
	                    INSERT INTO document_audio
	                    SET
	                        id=%d,
	                        entry_id=%d,
	            			original_audio_name=%s,
	                        audio_name='%s',
	                        audio_path='%s',
	                        recorded=%s
	                        ;
	                    ",
	                    $row->id,
	                    $row->entry_id,
	            		DB::getPdo()->quote($row->audio_name),
	            		$name,
	                    $this->verifyPath($row->audio_path),
	                    ($row->recorded != '0000-00-00') && ($row->recorded != '') ? "'{$row->recorded}'" : 'NULL'
	                    );
	            $queries[$i] = $query;

	            if (DB::statement($query)) {
    	            $i++;
                    $this->info(sprintf("    (%d-%d) inserted id:%d", $i, $c, $row->id));

                    //copy file
                    $src = sprintf('%s%s%s', $src_path, $this->verifyPath($row->audio_path), $row->audio_name);
                    $dst = sprintf('%s%s%s', $dst_path, $this->verifyPath($row->audio_path), $name);
                    @mkdir(sprintf('%s%s', $dst_path, $this->verifyPath($row->audio_path)));

                    if (!file_exists($dst)) {
	                    if (file_exists($src)) {
	                    	copy($src, $dst);
	                    	$file = sprintf('      Copied %s to %s', $row->audio_name, $name);
	                    	$this->info('  '.$file);
	                    } else {
	                    	$errors[]=sprintf("Error copying %s --> %s: %d", $src, $dst, $row->id);
	                    }
                    } else {
                    	$this->info('      Skipped copy, already exists '.$file);
                    }
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
	        $this->error("Import of audio failed: original table is empty.");
	    }

	}

}
