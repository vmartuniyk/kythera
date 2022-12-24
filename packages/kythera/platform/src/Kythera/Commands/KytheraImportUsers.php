<?php namespace Kythera\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Input;

class KytheraImportUsers extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'kythera:import_users';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import & replace original Kythera users';

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
                                U.persons_id as id, email, login as username, password,
                                firstname, middlename, lastname, nickname, maidenname,
                                hide, gender, date_of_birth, date_of_death, place_of_birth
                            FROM users U
                            LEFT JOIN names N ON U.persons_id = N.persons_id
                            LEFT JOIN persons P ON U.persons_id = P.id
                            WHERE character_set_id='latin'
                            "));
	    } else {
    	    $rows = DB::connection('import')
                    ->select(DB::raw(sprintf("
                            SELECT
                                U.persons_id as id, email, login as username, password,
                                firstname, middlename, lastname, nickname, maidenname,
                                hide, gender, date_of_birth, date_of_death, place_of_birth
                            FROM users U
                            LEFT JOIN names N ON U.persons_id = N.persons_id
                            LEFT JOIN persons P ON U.persons_id = P.id
                            WHERE character_set_id='latin'
                            LIMIT %d;
                            ", $limit)));
	    }

	    $i=0;
	    if ($c = count($rows)) {
	        $this->info(sprintf("Users to be imported: %d", $c));

	        #truncate
	        DB::statement("SET FOREIGN_KEY_CHECKS=0");
	        DB::table("users")->truncate();

	        #insert users and auto confirm all
	        $hash = App::make('hash');
	        $errors = array();
	        $infos = array();
	        $queries = array();
	        foreach ($rows as $row) {
	            $query = sprintf("
	                    INSERT INTO users
	                    SET
	                        id=%d,
	                        hide=%d,
	                        gender='%s',
	                        date_of_birth=%s,
	                        date_of_death=%s,
	                        place_of_birth=%d,
	                        firstname=%s,
	                        middlename=%s,
	                        lastname=%s,
	                        nickname=%s,
	                        maidenname=%s,
	                        username=%s,
	                        email='%s',
	                        password='%s',
	                        confirmation_code='%s',
	                        confirmed=1,
	                        created_at=NOW(),
	                        updated_at=NOW();
	                    ",
	                    $row->id,
	                    $row->hide,
	                    $row->gender,
	                    ($row->date_of_birth != '0000-00-00 00:00:00') && ($row->date_of_birth != '') ? "'{$row->date_of_birth}'" : 'NULL',
	                    ($row->date_of_death != '0000-00-00 00:00:00') && ($row->date_of_death != '') ? "'{$row->date_of_death}'" : 'NULL',
	                    $row->place_of_birth,
	                    $row->firstname ? DB::getPdo()->quote($row->firstname) : 'NULL',
	                    $row->middlename ? DB::getPdo()->quote($row->middlename) : 'NULL',
	                    $row->lastname ? DB::getPdo()->quote($row->lastname) : 'NULL',
	                    $row->nickname ? DB::getPdo()->quote($row->nickname) : 'NULL',
	                    $row->maidenname ? DB::getPdo()->quote($row->maidenname) : 'NULL',
                        DB::getPdo()->quote($row->username),
	                    Str::lower($row->email),
	                    $hash->make($row->password), //UserValidator.php
	                    md5(uniqid(mt_rand(), true)) //UserRepository.php
	                    );
	            $queries[] = $query;

	            if (DB::statement($query)) {
    	            $i++;
    	            if (Config::get('app.debug')) {
    	                $this->info(sprintf("(%d-%d) inserted id:%d, %s, %s, %s, %s", $i, $c, $row->id, $row->lastname, $row->username, $row->email, $row->password));
    	            } else {
    	                $this->info(sprintf("(%d-%d) inserted id:%d, %s, %s, %s", $i, $c, $row->id, $row->lastname, $row->username, $row->email));
    	            }
	            } else {
	                $errors[]=sprintf("Error inserting user ".$row['id']);
	            }


	            /*
	            #translations ARE COMPOUNDS (multiple translations)
    	        $translations = DB::connection('import')
                    ->select($query = sprintf("
	                    SELECT * FROM names
	                    WHERE
	                        character_set_id='greek'
	                        AND persons_id=%d;",
                        $row->id));
                    echo __FILE__.__LINE__.'<pre>$translations='.htmlentities(print_r($translations,1)).'</pre>';
    	        */


	        }

            $this->info(sprintf("DONE: inserted %d from %d users", count($queries), $c));
            $this->error(implode(chr(13).chr(10), $errors));

            if ($this->option('dump')) {
                $this->info('QUERIES:');
                $this->info(implode(chr(13).chr(10), $queries));
            }

            DB::statement("SET FOREIGN_KEY_CHECKS=1");
	    } else {
	        $this->error("Import failed: original table is empty.");
	    }

	}

	protected function import2($limit) {
	    #set timezone for date()
	    date_default_timezone_set('Europe/Berlin');
	    DB::disableQueryLog();

	    #fetch
	    if ($limit == 'ALL') {
    	    $rows = DB::connection('import')
                    ->select(DB::raw('
                            SELECT persons_id as id, email, login as username, password
                            FROM users;
                            '));
	    } else {
    	    $rows = DB::connection('import')
                    ->select(DB::raw(sprintf('
                            SELECT persons_id as id, email, login as username, password
                            FROM users
                            LIMIT %d;
                            ', $limit)));
	    }

	    $i=0;
	    if ($c = count($rows)) {
	        $this->info(sprintf("Users to be imported: %d", $c));

	        #truncate
	        DB::table("users")->truncate();

	        #insert
	        $hash  = App::make('hash');
	        $items = array();
            foreach ($rows as $row) {
                $item = array();
                foreach (get_object_vars($row) as $k => $v) {
                    if ($k=='password') {
                        $item[$k] = $hash->make($v);
                    } else
                        $item[$k] = $v;
                }
                $item['created_at'] = date('Y-m-d H:i:s');
                $item['updated_at'] = date('Y-m-d H:i:s');
                $item['confirmed']  = 1;
                $item['confirmation_code'] = md5(uniqid(mt_rand(), true));
                $items[] = $item;
            }
            DB::table("users")->insert($items);

            $this->info(sprintf("DONE"));
	    } else {
	        $this->error("Import failed: original table is empty.");
	    }

	}

}
