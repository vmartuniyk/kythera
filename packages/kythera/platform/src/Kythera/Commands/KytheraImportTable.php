<?php namespace Kythera\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Input;

class KytheraImportTable extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'kythera:import_table';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Copy table';

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
		//./artisan kythera:import_table -d1 villages 10
		//./artisan kythera:import_table guestbook
		//./artisan kythera:import_table -d1 newsletter_subscriber
		//./artisan kythera:import_table -d1 messageboard
		//./artisan kythera:import_table -d1 messageboard2

		/*familytree tables
		 * ./artisan kythera:import_table -d1 individuum
		 * */

		$source = $this->argument('source');
		$limit = $this->argument('limit');
		$drop = $this->option('drop');

		$destination = false;
		if ($source=='guestbook') {
			$destination = 'users_guestbook';
		}

		$this->import($source, $destination ? $destination : $source, $limit != 'ALL' ? $limit : 100000, $drop ? 1 : 0);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('source', InputArgument::REQUIRED, 'Name of source table.'),
			array('limit', InputArgument::OPTIONAL, 'Total records to copy.', 'ALL'),
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
			array('drop', 'd', InputOption::VALUE_OPTIONAL, 'Drop destination table.', false),
		);
	}

	protected function import($src, $dst, $limit, $drop) {
		#params
// 		$this->info('SOURCE: '.$src);
// 		$this->info('DESTINATION: '.$dst);
// 		$this->info('LIMIT: '.$limit);
// 		$this->info('DROP: '.$drop);

	    #options
	    DB::disableQueryLog();
	    $merge   = false;

	    #check if source exists
	    try {
	    	$s = DB::connection('import')->table($src)->exists();
	    } catch (Exception $e) {
	    	$s = 0;
	    }
	    if (!$s) {
	    	$this->error(sprintf('Source table %s does not exist. Exiting...', $src));
	    	exit;
	    }

	    #set hard table drops (to convert myisam to innodb)
	    if (in_array($src, array('newsletter_subscriber','messageboard','messageboard2', 'villages'))) $drop = true;
	    if ($drop) {
	    	$this->info(sprintf('Hard dropping table %s for Innodb conversion', $src));
	    }

	    #check if destination exists
	    if (!$drop) {
		    try {
		    	$s = DB::table($dst)->exists();
		    } catch (Exception $e) {
		    	$s = 0;
		    }
		    if (!$s) {
		    	$this->error(sprintf('Destination table %s does not exist. Exiting...', $dst));
		    	exit;
		    }
	    }

	    $src = sprintf('%s.%s', Config::get('database.connections.import.database'), $src);
	    $dst = sprintf('%s.%s', Config::get('database.connections.mysql.database'), $dst);

	    $this->info(sprintf('Copying table %s', $src));
	    $this->info(sprintf('  Rows: %d', $src_count = DB::table($src)->count()));

	    #create table
	    if ($drop) {
	    	$this->info('  Dropping table '.$dst);
	    	DB::statement("drop table if exists $dst");
		    switch($src) {
		    	case 'import_kythera.villages':
		    		$q = "
					CREATE TABLE IF NOT EXISTS `villages` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `village_name` varchar(255) NOT NULL DEFAULT '',
					  `character_set_id` varchar(50) NOT NULL DEFAULT '',
					  `visible` int(1) DEFAULT '1',
					  `lost` int(1) DEFAULT '0',
					  `latitude` double DEFAULT NULL,
					  `longitude` double DEFAULT NULL,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `id` (`id`),
					  UNIQUE KEY `name` (`village_name`,`character_set_id`)
					) ENGINE=Innodb DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		    		$created = DB::statement($q);
	    		break;
		    	case 'import_kythera.page_visitors':
		    		$q = "
					CREATE TABLE IF NOT EXISTS `page_visitors` (
					  `ip` varchar(255) NOT NULL DEFAULT '',
					  `time_lastaction` int(11) NOT NULL DEFAULT '0',
					  `hostname` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`ip`)
					) ENGINE=Innodb CHARSET=utf8 COLLATE=utf8_general_ci;";
		    		$created = DB::statement($q);
	    		break;
		    	case 'import_kythera.guestbook':
		    		$q = "
					CREATE TABLE IF NOT EXISTS `users_guestbook` (
					  `documents_id` int(11) NOT NULL DEFAULT '0',
					  `firstname` varchar(255) DEFAULT NULL,
					  `surname` varchar(255) DEFAULT NULL,
					  `city` varchar(255) DEFAULT NULL,
					  `email` varchar(255) DEFAULT NULL,
					  `receiveNewsletter` int(1) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`documents_id`)
					) ENGINE=Innodb DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		    		$created = DB::statement($q);
	    		break;
		    	case 'import_kythera.individuum':
		    		$q = "
					CREATE TABLE IF NOT EXISTS `individuum` (
					  `persons_id` int(11) NOT NULL DEFAULT '0',
					  `entry_id` int(11) NOT NULL DEFAULT '0',
					  `image_id` int(11) DEFAULT NULL,
					  `data` mediumtext,
					  PRIMARY KEY (`persons_id`),
					  UNIQUE KEY `entry_id` (`entry_id`),
					  KEY `document_index` (`entry_id`),
					  FULLTEXT KEY `data` (`data`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		    		$created = DB::statement($q);
	    		break;
		    	case 'import_kythera.names':
		    		$q = "
					CREATE TABLE IF NOT EXISTS `names` (
					  `persons_id` int(11) NOT NULL DEFAULT '0',
					  `character_set_id` varchar(50) NOT NULL DEFAULT '',
					  `firstname` varchar(255) DEFAULT NULL,
					  `middlename` varchar(255) DEFAULT NULL,
					  `lastname` varchar(255) DEFAULT NULL,
					  `nickname` varchar(255) DEFAULT NULL,
					  `maidenname` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`persons_id`,`character_set_id`),
					  UNIQUE KEY `id` (`persons_id`,`character_set_id`)
					) ENGINE=Innodb DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		    		$created = DB::statement($q);
	    		break;
		    	case 'import_kythera.letters':
		    		$q = "
					CREATE TABLE IF NOT EXISTS `letters` (
					  `document_id` int(11) NOT NULL DEFAULT '0',
					  `sender_id` int(11) DEFAULT NULL,
					  `addressee_id` int(11) DEFAULT NULL,
					  `year` int(11) DEFAULT '0',
					  `date` date DEFAULT '0000-00-00',
					  PRIMARY KEY (`document_id`),
					  UNIQUE KEY `id` (`document_id`)
					) ENGINE=Innodb DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		    		$created = DB::statement($q);
	    		break;
		    	case 'import_kythera.persons':
		    		$q = "
					CREATE TABLE IF NOT EXISTS `persons` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `hide` int(1) NOT NULL DEFAULT '0',
					  `gender` enum('U','M','F') DEFAULT 'U',
					  `date_of_birth` datetime DEFAULT NULL,
					  `date_of_death` datetime DEFAULT NULL,
					  `place_of_birth` int(11) DEFAULT NULL,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `id` (`id`)
					) ENGINE=Innodb DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
		    		$created = DB::statement($q);
	    		break;
		    	case 'import_kythera.family':
		    		$q = "
					CREATE TABLE IF NOT EXISTS `family` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `parents_id` int(11) NOT NULL DEFAULT '0',
					  `kind` int(11) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `parents_id` (`parents_id`,`kind`),
					  KEY `child_index` (`kind`)
					) ENGINE=Innodb DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		    		";
		    		$created = DB::statement($q);
	    		break;
		    	case 'import_kythera.parents':
		    		$q = "
					CREATE TABLE IF NOT EXISTS `parents` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `partner1` int(11) DEFAULT NULL,
					  `partner2` int(11) DEFAULT NULL,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `partner1` (`partner1`,`partner2`),
					  KEY `partner1_index` (`partner1`),
					  KEY `partner2_index` (`partner2`),
					  KEY `partner_index` (`partner1`,`partner2`)
					) ENGINE=Innodb DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		    		";
		    		$created = DB::statement($q);
	    		break;
		    	case 'import_kythera.newsletter_subscriber':
		    		$q = "
		    		CREATE TABLE IF NOT EXISTS `newsletter_subscriber` (
		    		 `persons_id` int(11) DEFAULT '0',
		    		 `register_token` varchar(40) DEFAULT NULL,
		    		 `email` varchar(255) DEFAULT NULL,
		    		 `firstname` varchar(255) DEFAULT NULL,
		    		 `lastname` varchar(255) DEFAULT NULL,
		    		 `enabled` int(1) DEFAULT '0',
		    		 UNIQUE KEY `id` (`persons_id`,`email`)
		    		) ENGINE=Innodb DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		    		";
		    		$created = DB::statement($q);
	    		break;
		    	case 'import_kythera.messageboard':
		    		$q = "
					CREATE TABLE IF NOT EXISTS `messageboard` (
					  `documents_id` int(11) NOT NULL DEFAULT '0',
					  `parent_id` int(11) DEFAULT NULL,
					  PRIMARY KEY (`documents_id`)
					) ENGINE=Innodb DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		    		";
		    		$created = DB::statement($q);
	    		break;
		    	case 'import_kythera.namebox_entries':
		    		$q = "
					CREATE TABLE IF NOT EXISTS `namebox_entries` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `name` varchar(255) DEFAULT NULL,
					  `character_set_id` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT '',
					  `visible` int(1) DEFAULT '1',
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `id` (`id`),
					  KEY `name_index` (`name`)
					) ENGINE=Innodb DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		    		";
		    		$created = DB::statement($q);
	    		break;
		    	case 'import_kythera.related_names':
		    		$q = "
					CREATE TABLE IF NOT EXISTS `related_names` (
					  `namebox_entry_id` int(11) NOT NULL DEFAULT '0',
					  `document_id` int(11) NOT NULL DEFAULT '0',
					  UNIQUE KEY `namebox_entry_id` (`namebox_entry_id`,`document_id`),
					  KEY `index_document_id` (`document_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		    		";
		    		$created = DB::statement($q);
	    		break;
	    		case 'import_kythera.messageboard2':
		    		$q = "
					CREATE TABLE IF NOT EXISTS `messageboard2` (
					  `documents_id` int(11) NOT NULL DEFAULT '0',
					  `parent_id` int(11) DEFAULT NULL,
					  PRIMARY KEY (`documents_id`)
					) ENGINE=Innodb DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		    		";
		    		$created = DB::statement($q);
		    		$merge   = $created;
	    		break;
		    	default:
		    		throw new Exception(sprintf('Table not implemented: %s', $src));
		    }
	    } else {
	    	$this->info('  Truncating table '.$dst);
	    	DB::table($dst)->truncate();
	    	$created = true;
	    }
	    $this->info($created ? '    OK' : '    ERROR');
	    if (!$created) {
	    	throw new Exception("Destination table $dst could not be created.");
	    }

	    #insert data
	    switch($src) {
	    	case 'import_kythera.villages':
		    	$columns = array('id', 'village_name', 'character_set_id', 'visible', 'lost');
		    	$this->info(sprintf('  Inserting data from %s into %s', $src, $dst));
		    	$query = sprintf("INSERT INTO %s (%s) SELECT %s FROM %s LIMIT ?;", $dst, implode(',', $columns), implode(',', $columns), $src);
		    	$inserted = DB::statement($query, array($limit));
		    	$this->info($inserted ? '    OK' : '    ERROR');
	    	break;
	    	case 'import_kythera.individuum':
	    		//copy data
	    		$this->info(sprintf('  Inserting data from %s into %s', $src, $dst));
	    		$inserted = DB::statement("INSERT INTO $dst SELECT * FROM $src LIMIT ?;", array($limit));
	    		$this->info($inserted ? '    OK' : '    ERROR');

	    		//convert data field
	    		if ($items = DB::select(DB::raw("SELECT * FROM individuum;"))) {
	    			foreach ($items as $item) {
	    				if ($item->data) {
	    					$data = array();
	    					$simple = '<root>'.$item->data.'</root>';
	    					$p = xml_parser_create();
	    					xml_parse_into_struct($p, $simple, $vals, $index);
	    					xml_parser_free($p);
	    					foreach ($vals as $v) {
	    						$key = strtolower($v['tag']);
	    						if ($key=='root') continue;
	    						$value = isset($v['value']) ? $v['value'] : '';
	    						$data[$key] = $value;
	    					}

	    					$json = json_encode($data);
	    					$query = sprintf(
	    							"UPDATE %s SET data='%s' WHERE persons_id=%d LIMIT 1;", $dst, $json, $item->persons_id);
	    					$inserted = DB::statement($query);
	    					$this->info("		Converting {$item->persons_id} ...");
	    					$this->info($inserted ? '    OK' : '    ERROR');
	    				}
	    			}
	    		}
	    	break;
	    	case 'import_kythera.namebox_entries':
	    		//delete empty values first
	    		DB::statement('DELETE FROM import_kythera.namebox_entries WHERE name = "";');
	    	default:
	    		$this->info(sprintf('  Inserting data from %s into %s', $src, $dst));
	    		$inserted = DB::statement("INSERT INTO $dst SELECT * FROM $src LIMIT ?;", array($limit));
	    		$this->info($inserted ? '    OK' : '    ERROR');
	    }

	    if ($inserted) {
	    	$count = DB::table($dst)->count();
	    	$this->info(sprintf('  Rows copied: %d, Errors: %d', $count, $src_count-$count));
	    }

	    #merge messageboard tables
	    if ($merge) {
		    $merged = DB::statement("INSERT INTO messageboard SELECT * FROM messageboard2;");
		    if ($merged) {
		    	$this->info(sprintf('  Messageboard tables merged.'));
		    	DB::table($dst)->truncate();
		    }
	    }

	    $this->info(sprintf('DONE'));
	}

}
