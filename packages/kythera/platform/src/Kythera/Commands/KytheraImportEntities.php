<?php namespace Kythera\Commands;

/*
 * ALL RECORDS WILL BE DELETED
./artisan kythera:import_entities -r NICKNAMES 1
./artisan kythera:import_entities -r PEOPLE_LIFESTORIES
./artisan kythera:import_entities -r PEOPLE_OBITUARIES
./artisan kythera:import_entities -r GRAVESTONES
./artisan kythera:import_entities -r GRAVESTONES_AAF
./artisan kythera:import_entities -r GRAVES_LIVATHI
./artisan kythera:import_entities -r GRAVESTONES_POTAMOS
./artisan kythera:import_entities -r GRAVES_GERAKARI



./artisan kythera:import_entities -r PHOTOGRAPHY_ARCHITECTURE
155,147,87,85,23
./artisan kythera:import_entities -r PHOTOGRAPHY_MODERN_PORTRAITS
*/

/*
 * IMPORT PER DOCUMENT TYPE ID
 ./artisan kythera:import_entities NICKNAMES
 ./artisan kythera:import_entities PEOPLE_LIFESTORIES
 ./artisan kythera:import_entities PEOPLE_OBITUARIES
 ./artisan kythera:import_entities FAMOUS_PEOPLE

 ./artisan kythera:import_entities GRAVESTONES
 ./artisan kythera:import_entities GRAVESTONES_AAF
 ./artisan kythera:import_entities GRAVES_LIVATHI
 ./artisan kythera:import_entities GRAVESTONES_POTAMOS
 ./artisan kythera:import_entities GRAVES_GERAKARI
 ./artisan kythera:import_entities GRAVESTONES_AGDESPINA
 ./artisan kythera:import_entities GRAVESTONES_DRYMONAS
 ./artisan kythera:import_entities GRAVESTONES_FRILIGIANIKA
 ./artisan kythera:import_entities GRAVESTONES_HORA
 ./artisan kythera:import_entities GRAVESTONES_KAPSALI
 ./artisan kythera:import_entities GRAVESTONES_KARAVAS
 ./artisan kythera:import_entities GRAVESTONES_MITATA
 ./artisan kythera:import_entities GRAVES_AGANASTASIA
 ./artisan kythera:import_entities GRAVES_AGTHEO
 ./artisan kythera:import_entities GRAVES_ALEXANDRATHES 0
 ./artisan kythera:import_entities GRAVES_ALEXANDRATHES2
 ./artisan kythera:import_entities GRAVES_ARONIATHIKA
 ./artisan kythera:import_entities GRAVES_AUSTRALIA
 ./artisan kythera:import_entities GRAVES_GERAKARI
 ./artisan kythera:import_entities GRAVES_GOUTHIANIKA
 ./artisan kythera:import_entities GRAVES_KERAMOTO
 ./artisan kythera:import_entities GRAVES_LOGOTHETIANIKA
 ./artisan kythera:import_entities GRAVES_PITSINIANIKA
 ./artisan kythera:import_entities GRAVES_TRYFILL
 ./artisan kythera:import_entities GRAVES_USA 0
 ./artisan kythera:import_entities GRAVES_USA2


 ./artisan kythera:import_entities GENERAL_HISTORY
 ./artisan kythera:import_entities ORAL_HISTORY
 ./artisan kythera:import_entities ARCHEOLOGY
 ./artisan kythera:import_entities ARCHIVE
 ./artisan kythera:import_entities MYTHS_AND_LEGENDS
 ./artisan kythera:import_entities HISTORY_ARTEFACTS
 ./artisan kythera:import_entities DOCUMENTS
 ./artisan kythera:import_entities OLD_LETTERS
 ./artisan kythera:import_entities OLD_PHOTOS
 ./artisan kythera:import_entities OLD_MAPS


 ./artisan kythera:import_entities ARCHITECTURE
 ./artisan kythera:import_entities ASSOCIATIONS
 ./artisan kythera:import_entities BIBLIOGRAPHY
 ./artisan kythera:import_entities BLOG
 ./artisan kythera:import_entities CULTURAL_KNOWLEDGE
 ./artisan kythera:import_entities CULTUREKYTHERIANIDENTITY
 ./artisan kythera:import_entities HOME_REMEDIES
 ./artisan kythera:import_entities ISLAND_NEWS
 ./artisan kythera:import_entities "KYTHERIAN BUSINESS GUIDE2"
 ./artisan kythera:import_entities KYTHERIAN_ARTISTS
 ./artisan kythera:import_entities museumsgalleries
 ./artisan kythera:import_entities RELIGION
 ./artisan kythera:import_entities FOOD_AND_RECIPES
 ./artisan kythera:import_entities BOOKS_ABOUT_US
 ./artisan kythera:import_entities SONGS_AND_POEMS
 ./artisan kythera:import_entities STORIES
 ./artisan kythera:import_entities ENVIRONMENT


 ./artisan kythera:import_entities PHOTOGRAPHY_VINTAGE_LANDSCAPES
 ./artisan kythera:import_entities PHOTOGRAPHY_SIGNS_AND_STATUES
 ./artisan kythera:import_entities PHOTOGRAPHY_SCHOOL_PHOTOS
 ./artisan kythera:import_entities PHOTOGRAPHY_NATURE
 ./artisan kythera:import_entities PHOTOGRAPHY_MODERN_PORTRAITS
 ./artisan kythera:import_entities PHOTOGRAPHY_MODERN_LANDSCAPES
 ./artisan kythera:import_entities PHOTOGRAPHY_ISLAND_WORKING_LIFE
 ./artisan kythera:import_entities PHOTOGRAPHY_ISLAND_WEDDINGS_AND_PROXENIA
 ./artisan kythera:import_entities PHOTOGRAPHY_ISLAND_VINTAGE_PEOPLE
 ./artisan kythera:import_entities PHOTOGRAPHY_ISLAND_SOCIAL_LIFE
 ./artisan kythera:import_entities PHOTOGRAPHY_ISLAND_Miscellaneous
 ./artisan kythera:import_entities PHOTOGRAPHY_CHURCHES
 ./artisan kythera:import_entities PHOTOGRAPHY_ARCHITECTURE

 ./artisan kythera:import_entities GreatWalls2


 ./artisan kythera:import_entities PHOTOGRAPHY_CAFES_AND_SHOPS
 ./artisan kythera:import_entities churchesDiaspora
 ./artisan kythera:import_entities PHOTOGRAPHY_DIASPORA_KYTHERIAN_ART
 ./artisan kythera:import_entities PHOTOGRAPHY_DIASPORA_Miscellaneous
 ./artisan kythera:import_entities PHOTOGRAPHY_DIASPORA_SOCIAL_LIFE
 ./artisan kythera:import_entities PHOTOGRAPHY_DIASPORA_SPORTING_LIFE
 ./artisan kythera:import_entities PHOTOGRAPHY_DIASPORA_VINTAGE_PEOPLE
 ./artisan kythera:import_entities PHOTOGRAPHY_DIASPORA_WEDDINGS_AND_PROXENIA
 ./artisan kythera:import_entities PHOTOGRAPHY_DIASPORA_WORKING_LIFE


 ./artisan kythera:import_entities AUDIO_INTERVIEWDIASPORA
 ./artisan kythera:import_entities AUDIO_INTERVIEW_ISLAND
 ./artisan kythera:import_entities AUDIO_KYTHERIANMUSIC
 ./artisan kythera:import_entities AUDIO_SOUNDSOFNATURE
 ./artisan kythera:import_entities VINTAGE_FILMS


 ./artisan kythera:import_entities REAL_ESTATE_HOWTOPOST
 ./artisan kythera:import_entities REAL_ESTATE_HOUSES
 ./artisan kythera:import_entities REAL_ESTATE_LAND
 ./artisan kythera:import_entities REAL_ESTATE_HOLIDAY
 ./artisan kythera:import_entities REAL_ESTATE_SEARCHproperty
 ./artisan kythera:import_entities REAL_ESTATE_search_holiday


 ./artisan kythera:import_entities BIRD_IMAGES
 ./artisan kythera:import_entities FISH_IMAGES
 ./artisan kythera:import_entities FLOWER_IMAGES
 ./artisan kythera:import_entities FOSSIL_IMAGES
 ./artisan kythera:import_entities INSECT_ AND_KIN_IMAGES
 ./artisan kythera:import_entities MAMMAL_IMAGES
 ./artisan kythera:import_entities REPTILE_AND_AMPHIBIAN_IMAGES
 ./artisan kythera:import_entities ROCKS_IMAGES
 ./artisan kythera:import_entities SEASHELL_IMAGES_2
 ./artisan kythera:import_entities SEASHELL_IMAGES
 ./artisan kythera:import_entities SEASHELL_IMAGES_3
 ./artisan kythera:import_entities MARINE_MISCELLANY_IMAGES


 ./artisan kythera:import_entities COMMUNITY_ISLANDINFOS
 ./artisan kythera:import_entities "CALENDAR OF EVENTS2"
 ./artisan kythera:import_entities TOURIST_ONLINE
 ./artisan kythera:import_entities SIGHTSEEING
 ./artisan kythera:import_entities WHERE_TO_EAT
 ./artisan kythera:import_entities WHERE_TO_STAY


 ./artisan kythera:import_entities ACADEMICS_TWO
 ./artisan kythera:import_entities ACADEMICS_ONE
 ./artisan kythera:import_entities ACADEMICS_THREE
 ./artisan kythera:import_entities ACADEMICS_FOUR
 ./artisan kythera:import_entities ACADEMICS_FIVE
 ./artisan kythera:import_entities ACADEMICS_SIX
 ./artisan kythera:import_entities ACADEMICS_SEVEN
 ./artisan kythera:import_entities ACADEMICS_EIGHT
 ./artisan kythera:import_entities ACADEMICS_NINE
 ./artisan kythera:import_entities ACADEMICS_TEN

 ./artisan kythera:import_entities KCA_Activities
 ./artisan kythera:import_entities ASSOCIATION_DOCUMENTS
 ./artisan kythera:import_entities KYTHERA_PHOTOGRAPHIC_ARCHIVE
 ./artisan kythera:import_entities KCA_PHOTO_CATEGORY_ONE


 ./artisan kythera:import_entities GUEST_BOOK

 ./artisan kythera:import_entities MESSAGE_BOARD

 ./artisan kythera:import_entities FAMILY_TREE_PERSON

 ./artisan kythera:import_entities NEWS-ARCHIVE

>> Copy import_kythera.villages to kfn.villages
>> Copy import_kythera.guestbook to kfn.users_guestbook
>> FIXME: Merge messageboard tables


 ./artisan kythera:import_entities ALL
 */

use App\Models\Translation;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Input;
use Kythera\Models\DocumentEntity;

class KytheraImportEntities extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'kythera:import_entities';


	protected $cache = null;

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import & replace original Kythera documents';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		//./artisan kythera:import_entities GUEST_BOOK false 2
		$limit  = $this->argument('limit');
		$cat    = $this->argument('category');
		$silent = $this->argument('silent') == 'true' ? true : false;
		$time_start = microtime(true);

		if ($cat=='ALL') {
			$this->importALL($limit);
		} else {
			$this->import($cat, $limit, $silent);
		}

		$time_end = microtime(true);
		$time = $time_end - $time_start;
		$this->info("Elapsed $time seconds.");
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
	        array('silent', InputArgument::OPTIONAL, 'Break on errors.', false),
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

	protected function hasDuplicateUri($entry_id, $item, &$result, $fix = false) {
	    $doc_type_id = $item[1]->docTypeId;
	    $value       = Translation::slug($item[1]->language01);
	    if ($row = DB::table('document_attributes')
	                //->where('document_entity_id', '=', $entry_id)
	                ->where('document_type_id', '=', $doc_type_id)
	                ->where('key', '=', 'uri')
	                ->where('value', '=', $value)
	                ->first()) {
	        //echo __FILE__.__LINE__.'<pre>$row='.htmlentities(print_r($row,1)).'</pre>';
	        $result['dup']++;
	        if ($fix)
	        {
	            $item[1]->language01 = $item[1]->language01 ? $item[1]->language01.'-'.$item[1]->entry_id : $item[1]->entry_id;
	            $this->info(sprintf('    Duplicate found on ID: %d -> "%s", repaired as "%s"', $row->id, $row->value, $item[1]->language01));
	        } else {
	            $this->info(sprintf('    Duplicate found on ID: %d -> "%s"', $row->id, $row->value));
	        }
	    }
	    return $item;
	}


	protected function hasDuplicateUriEN($entry_id, $item, &$result, $fix = false) {

		//return $this->hasDuplicateUriEN_Cached($entry_id, $item, $result, $fix);


	    $doc_type_id = $item[1]->docTypeId;
	    //$item[1]->uri = Translation::slug($item[1]->language01);
	    $item[1]->uri = $this->getURI($item);// Translation::slug($item[1]->language01);

	    if ($row = DB::table('document_attributes')
	                //->where('document_entity_id', '=', $entry_id)
	                //->where('document_type_id', '=', $doc_type_id)
                    ->where('l', '=', 'en')
	                ->where('key', '=', 'uri')
	                ->where('value', '=', $item[1]->uri)
	                ->first()) {
	        //echo __FILE__.__LINE__.'<pre>$row='.htmlentities(print_r($row,1)).'</pre>';
	        $result['dup']++;
	        if ($fix)
	        {
	            $item[1]->uri = $item[1]->language01 ? $item[1]->language01.'-'.$item[1]->entry_id : $item[1]->entry_id;
	            $this->info(sprintf('    Duplicate found on ID: %d -> "%s", repaired as "%s"', $row->id, $row->value, $item[1]->uri));
	        } else {
	            $this->info(sprintf('    Duplicate found on ID: %d -> "%s"', $row->id, $row->value));
	        }
	    }
	    return $item;
	}


	protected function hasDuplicateUriEN_Cached($entry_id, $item, &$result, $fix = false) {
	    $doc_type_id = $item[1]->docTypeId;
	    //$item[1]->uri = Translation::slug($item[1]->language01);
	    $item[1]->uri = $this->getURI($item);// Translation::slug($item[1]->language01);

	    if (is_null($this->cache)) {
	    	$this->info(sprintf('    Caching....'));
	    	$this->cache = DB::table('document_attributes')->select(array('id', 'value'))
		    	//->where('document_entity_id', '=', $entry_id)
		    	//->where('document_type_id', '=', $doc_type_id)
		    	->where('l', '=', 'en')
		    	->where('key', '=', 'uri')
		    	//->where('value', '=', $item[1]->uri)
		    	->get();
	    	$this->info(sprintf('    DONE'));
	    }

	    foreach ($this->cache as $cache) {
	    	//echo __FILE__.__LINE__.'<pre>$cache='.htmlentities(print_r($cache,1)).'</pre>';die;
	    	if (strcasecmp($cache->value, $item[1]->uri)==0) {
	    		$result['dup']++;
	    		if ($fix)
	    		{
	    			$item[1]->uri = $item[1]->language01 ? $item[1]->language01.'-'.$item[1]->entry_id : $item[1]->entry_id;
	    			$item[1]->uri = strip_tags($item[1]->uri);
	    			$this->info(sprintf('    Duplicate found on ID: %d -> "%s", repaired as "%s"', $cache->id, $cache->value, $item[1]->uri));
	    		} else {
	    			$this->info(sprintf('    Duplicate found on ID: %d -> "%s"', $cache->id, $cache->value));
	    		}
	    		break;
	    	}
	    }

	    //add new url to the cache
	    $url = new stdClass();
	    $url->id = count($this->cache);
	    $url->value = $item[1]->uri;
	    $this->cache[] = $url;

	    return $item;
	}


	protected function fixMessageBoard($entry_id, $item, $fix = false) {
	    /* if language01 is empty fetch it from language02 */
	    if (!$item[1]->language01 && $item[1]->language02)
	    {
	        $this->info(sprintf('    Language FIX on ID: %d -> "%s"', $entry_id, $item[1]->language02));
	        if ($fix)
	        {
	            $item[1]->language01 = $item[1]->language02;
	        }
	    }
	    return $item;
	}


	protected function fixOldMaps($entry_id, $item, $fix = false) {
		if (!isset($item[3])) {
	        $this->info(sprintf('    Item[3] FIX on ID: %d -> "%s"', $entry_id, $item[1]->language01));
	        if ($fix)
	        {
	        	$item[3] = new stdClass();
	            $item[3]->language01 = $item[1]->language01;
	            $item[3]->language02 = '';
	            $item[3]->language03 = '';
	        }
	    }
		return $item;
	}


	protected function fixFamilyTreePerson($entry_id, $item, $fix = false) {
		//echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';
	    /* set item[1]: name
	     * item[2]: lifestory
	     * item[3]: data */
	    if (!isset($item[2]))
	    {
	        $this->info(sprintf('    Item[2] FIX on ID: %d -> "%s"', $entry_id, $item[1]->language01));
	        if ($fix)
	        {
	            $item[2] = new stdClass();
	            $item[2]->language01 = '';
	            $item[2]->language02 = '';
	            $item[2]->language03 = '';
	        }
	    }
	    if (!isset($item[3]))
	    {
	        $this->info(sprintf('    Item[3] FIX on ID: %d -> "%s"', $entry_id, $item[1]->language01));
	        if ($fix)
	        {
	        	$item[3] = new stdClass();
	            $item[3]->language01 = '<still_living></still_living><country_of_birth></country_of_birth><state_of_birth></state_of_birth><city_of_birth></city_of_birth><country_of_death></country_of_death><state_of_death></state_of_death>';
	            $item[3]->language02 = '';
	            $item[3]->language03 = '';
	        }
	    }
	    return $item;
	}


	protected function hasFix($item)
	{
		$fix = false;
		$id = $item[1]->entry_id;
		switch($id) {
			case 16://
				$item[] = $item[1];
				$fix = $item;
				$this->error("    Fixing record {$id}: {$item[1]->language01}");
			break;
			case 155:
			case 147:
			case 89:
			case 85:
			case 23:
				$item[] = $item[1];
				$fix = $item;
				$this->error("    Fixing record {$id}: {$item[1]->language01}");
			break;
			case 73:
			case 74:
			case 75:
			case 95:
			case 100:
			case 109:
			case 110:
			case 142:
			case 143:
			case 144:
				$item[] = $item[1];
				$fix = $item;
				$this->error("    Fixing record {$id}: {$item[1]->language01}");
			break;
			case 152:
			case 153:
			case 154:
				$item[] = $item[1];
				$fix = $item;
				$this->error("    Fixing record {$id}: {$item[1]->language01}");
			break;
		}
		return $fix;
	}


	protected function skips($entry_id) {
		//'PEOPLE_LIFESTORIES:12157':
		//PHOTOGRAPHY_MODERN_LANDSCAPES:6461
		//MESSAGE_BOARD:7586
		if (in_array($entry_id, array(12157, 6461, 7586))) {
			return true;
		}
		return false;
	}


	protected function importALL($limit)
	{
	}


	protected function fixThird($item) {

		if (count($item)==3) {
			unset($item[3]);
			$this->error("    Fixing record {$item[1]->entry_id}: deleted 3rd.");
		}

		return $item;
	}

	protected function fixThirdAdd($item) {
		if (count($item)==2) {
			$item[3] = $item[1];
			$this->error("    Fixing record {$item[1]->entry_id}: added 3rd.");
		}

		return $item;
	}

	protected function fixFamousPerson($item)
	{
	    if (!isset($item[3])) {
	        $item[3] = new stdClass();
	        $item[3]->language01 = '';
	        $item[3]->language03 = '';
	        $this->error("    Fixing record {$item[1]->entry_id}: added 3rd.");
	    }
	    if (!isset($item[4])) {
	        $item[4] = new stdClass();
	        $item[4]->language01 = '';
	        $item[4]->language03 = '';
	        $this->error("    Fixing record {$item[1]->entry_id}: added 4rd.");
	    }
		return $item;
	}


	protected function importLetterImages($images)
	{
		DB::statement("DELETE FROM document_images WHERE letter=1;");

		$src_path = Config::get('app.local') ? '/home/virgil/html/kytherafamilynet/html/'
				: '/var/www/vhosts/kythera-family.net/import.kythera-family.net/httpdocs/';
				$dst_path = public_path().'/';

		foreach ($images as $entry_id => $image) {
			$parts = explode('-', $image);
			if (count($parts)==3) {
				$org = $parts[0];
				$name     = $parts[1];
				$path     = $parts[2];
			} else
			if (count($parts)==4) {
				$org = $parts[0].$parts[1];
				$name     = $parts[2];
				$path     = $parts[3];
			} else
				throw new Exception('Invalid row: '.$image);

				$query = sprintf("
       	                    INSERT INTO document_images
       	                    SET
                                   entry_id=%d,
            	                    original_image_name=%s,
            	                    image_name='%s',
            	                    image_path='%s',
            	                    letter=1;
        	                    ",
						$entry_id,
						DB::getPdo()->quote($org),
						$name,
						$path
				);
				if (!DB::statement($query))
					throw new Exception('Error inserting title');

				//copy files
				$src = sprintf('%s%s%s', $src_path, $path, $name);
				$dst = sprintf('%s%s%s', $dst_path, $path, $name);
				@mkdir(sprintf('%s%s', $dst_path, $path));
				if (file_exists($src)) {
					if (!file_exists($dst)) {
						copy($src, $dst);
						$file = sprintf('    Copied %s to %s', $name, $dst);
						$this->info('  '.$file);

						//copy related
						/*
						 * originalSize_
						 * mediumSize_
						 * textInclude_
						 * thumb_
						 */
						$related_src = sprintf('%s%soriginalSize_%s', $src_path, $path, $name);
						if (file_exists($related_src)) {
							$dst = sprintf('%s%soriginalSize_%s', $dst_path, $path, $name);
							copy($related_src, $dst);
							$this->info('      '.$related_src);
						}
						$related_src = sprintf('%s%smediumSize_%s', $src_path, $path, $name);
						if (file_exists($related_src)) {
							$dst = sprintf('%s%smediumSize_%s', $dst_path, $path, $name);
							copy($related_src, $dst);
							$this->info('      '.$related_src);
						}
						$related_src = sprintf('%s%stextInclude_%s', $src_path, $path, $name);
						if (file_exists($related_src)) {
							$dst = sprintf('%s%stextInclude_%s', $dst_path, $path, $name);
							copy($related_src, $dst);
							$this->info('      '.$related_src);
						}
						$related_src = sprintf('%s%sthumb_%s', $src_path, $path, $name);
						if (file_exists($related_src)) {
							$dst = sprintf('%s%sthumb_%s', $dst_path, $path, $name);
							copy($related_src, $dst);
							$this->info('      '.$related_src);
						}


					} else
						$this->info('  File exist '.$dst);
				} else
					$this->info('  File does not exist '.$src);
		}



	}

	protected function getURI($item, $cat = 0) {

		/*
		if (!$item[1]->language01) {
			//PHOTOGRAPHY_MODERN_PORTRAITS:913
			return Translation::slug($item[1]->language03);
		}
		*/

		$result = Translation::slug($item[1]->language01);
		if (!$result) {
			$result = Translation::slug($item[1]->entry_id);
			$this->error("    Fixing URI: {$item[1]->entry_id} -> {$result}");
		}

		if (!$result) {
			$this->error("    Empty URI!");
			echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';die;
		}

		return $result;
	}


	protected function import($cat, $limit, $silent = false)
	{

	    #options
	    date_default_timezone_set('Europe/Berlin');
	    DB::disableQueryLog();
	    ini_set('memory_limit', '128M');

	    $delete = false;
	    $originals = array();
	    $result = array('ok'=>0, 'error'=>0, 'ignore'=>0, 'trans'=>0, 'total'=>0, 'dup'=>0, 'fix'=>0);
	    $letter_images = array();

 	    $this->info('');
	    $this->info('Src db: '.DB::connection('import')->getDatabaseName());
	    $this->info('Dst db: '. DB::connection()->getDatabaseName());
	    $this->info('Delete originals: '. ($delete ? 'YES': 'no'));
	    $this->info('');
	    if (!$this->confirm('Do you wish to continue? [y|N]')) {
	        exit;
	    }

		if ($this->option('replace')) {
    	    DB::table("document_entities")->truncate();
    	    DB::table("document_attributes")->truncate();
	        $this->info('All records in "document_entities" deleted.');
	    } else {
	        if ($catId = $this->getCatId($cat)) {
	            DB::statement("delete from document_entities where document_type_id={$catId};");
	            DB::statement("delete from document_attributes where document_type_id={$catId};");
	            $this->info(sprintf('All records of type %d:%s deleted.', $catId, $cat));
	        }
	    }


	    /*
	     * INSERT CONTROLLER AND SET 2 or 3 items
	     *
	     */

        $catId = $this->getCatId($cat);
        switch($cat) {
        	/*
        	 * DocumentTextController
        	 */
            #People
            case 'PEOPLE_LIFESTORIES':
                case 'PEOPLE_OBITUARIES':
                case 'NICKNAMES':
            	case 'PEOPLE_SURNAMEBOOK':
            	case 'FAMOUS_PEOPLE':

            #History
            case 'GENERAL_HISTORY':
            	case 'ORAL_HISTORY':
            	case 'ARCHEOLOGY':
            	case 'ARCHIVE':
            	case 'MYTHS_AND_LEGENDS':

       		#Culture
       		case 'ARCHITECTURE':
           		case 'ASSOCIATIONS':
           		case 'BIBLIOGRAPHY':
           		case 'BLOG':
           		case 'CULTURAL_KNOWLEDGE':
           		case 'CULTUREKYTHERIANIDENTITY':
           		case 'HOME_REMEDIES':
           		case 'ISLAND_NEWS':
           		case 'KYTHERIAN BUSINESS GUIDE2':
           		case 'KYTHERIAN_ARTISTS':
           		case 'museumsgalleries':
           		case 'RELIGION':

            #Tourist Information
            case 'COMMUNITY_ISLANDINFOS':
	            case 'CALENDAR OF EVENTS2':
	            case 'TOURIST_ONLINE':
	            #98: Links
	            case 'SIGHTSEEING':
	            #134: Weather & Daylight
	            case 'WHERE_TO_EAT':
	            case 'WHERE_TO_STAY':

            #REAL ESTATE
            case 'REAL_ESTATE_HOWTOPOST':
	            case 'REAL_ESTATE_HOUSES':
	            case 'REAL_ESTATE_LAND':
	            case 'REAL_ESTATE_HOLIDAY':
	            case 'REAL_ESTATE_SEARCHproperty':
	            case 'REAL_ESTATE_search_holiday':

	        #Academic Research
	        case 'ACADEMICS_TWO':
            	case 'ACADEMICS_ONE':
            	case 'ACADEMICS_THREE':
            	case 'ACADEMICS_FOUR':
            	case 'ACADEMICS_FIVE':
            	case 'ACADEMICS_SIX':
            	case 'ACADEMICS_SEVEN':
            	case 'ACADEMICS_EIGHT':
            	case 'ACADEMICS_NINE':
            	case 'ACADEMICS_TEN':

       		#Kythera Cultural Assoc.
       		case 'KCA_Activities':

                //update controller
                DB::statement('UPDATE document_types SET controller="DocumentTextController" WHERE id='.$catId.' LIMIT 1;');
                $limit = $limit * 2;
            break;

            #People
            case 'FAMOUS_PEOPLE':
                //update controller
                DB::statement('UPDATE document_types SET controller="DocumentTextController" WHERE id='.$catId.' LIMIT 1;');
                $limit = $limit * 4;
            break;

       		#NEWS-ARCHIVE
       		case 'NEWS-ARCHIVE':

                //update controller
                DB::statement('UPDATE document_types SET controller="DocumentNewsController" WHERE id='.$catId.' LIMIT 1;');
                $limit = $limit * 2;
            break;


            /*
             * DocumentImageController
             */
            case 'GRAVESTONES':
            	case 'GRAVESTONES_AAF':
            	case 'GRAVES_LIVATHI':
            	case 'GRAVESTONES_POTAMOS':
            	case 'GRAVESTONES2_KARAVAS':
            	case 'GRAVESTONES_AGDESPINA':
            	case 'GRAVESTONES_DRYMONAS':
            	case 'GRAVESTONES_FRILIGIANIKA':
            	case 'GRAVESTONES_HORA':
            	case 'GRAVESTONES_KAPSALI':
            	case 'GRAVESTONES_KARAVAS':
            	case 'GRAVESTONES_MITATA':
            	case 'GRAVES_AGANASTASIA':
            	case 'GRAVES_AGTHEO':
            	case 'GRAVES_ALEXANDRATHES':
            	case 'GRAVES_ALEXANDRATHES2':
            	case 'GRAVES_ARONIATHIKA':
            	case 'GRAVES_AUSTRALIA':
             	case 'GRAVES_GERAKARI':
            	case 'GRAVES_GOUTHIANIKA':
            	case 'GRAVES_KERAMOTO':
            	case 'GRAVES_LOGOTHETIANIKA':
            	case 'GRAVES_PITSINIANIKA':
            	case 'GRAVES_TRYFILL':
            	case 'GRAVES_USA':
            	case 'GRAVES_USA2':

            #Culture
            case 'ENVIRONMENT':

       		#History
       		case 'HISTORY_ARTEFACTS':
           		case 'DOCUMENTS':
           		case 'OLD_PHOTOS':
           		case 'OLD_MAPS':

           	#PHOTOGRAPHY ISLAND
           	case 'PHOTOGRAPHY_VINTAGE_LANDSCAPES':
           		case 'PHOTOGRAPHY_SIGNS_AND_STATUES':
           		case 'PHOTOGRAPHY_SCHOOL_PHOTOS':
           		case 'PHOTOGRAPHY_NATURE':
           		case 'PHOTOGRAPHY_MODERN_PORTRAITS':
           		case 'PHOTOGRAPHY_MODERN_LANDSCAPES':
           		case 'PHOTOGRAPHY_ISLAND_WORKING_LIFE':
           		case 'PHOTOGRAPHY_ISLAND_WEDDINGS_AND_PROXENIA':
           		case 'PHOTOGRAPHY_ISLAND_VINTAGE_PEOPLE':
           		case 'PHOTOGRAPHY_ISLAND_SOCIAL_LIFE':
           		case 'PHOTOGRAPHY_ISLAND_Miscellaneous':
           		case 'PHOTOGRAPHY_CHURCHES':
           		case 'PHOTOGRAPHY_ARCHITECTURE':
           		case 'GreatWalls2':

           	#PHOTOGRAPHY_DIASPORA
           	case 'PHOTOGRAPHY_CAFES_AND_SHOPS':
            	case 'churchesDiaspora':
            	case 'PHOTOGRAPHY_DIASPORA_KYTHERIAN_ART':
            	case 'PHOTOGRAPHY_DIASPORA_Miscellaneous':
            	case 'PHOTOGRAPHY_DIASPORA_SOCIAL_LIFE':
            	case 'PHOTOGRAPHY_DIASPORA_SPORTING_LIFE':
            	case 'PHOTOGRAPHY_DIASPORA_VINTAGE_PEOPLE':
            	case 'PHOTOGRAPHY_DIASPORA_WEDDINGS_AND_PROXENIA':
            	case 'PHOTOGRAPHY_DIASPORA_WORKING_LIFE':

            #Natural History Museum
           	case 'BIRD_IMAGES':
            	case 'FISH_IMAGES':
            	case 'FLOWER_IMAGES':
            	case 'FOSSIL_IMAGES':
            	case 'INSECT_ AND_KIN_IMAGES':
            	case 'MAMMAL_IMAGES':
            	case 'REPTILE_AND_AMPHIBIAN_IMAGES':
            	case 'ROCKS_IMAGES':
            	case 'SEASHELL_IMAGES_2':
            	case 'SEASHELL_IMAGES':
            	case 'SEASHELL_IMAGES_3':
            	case 'MARINE_MISCELLANY_IMAGES':

      		#Kythera Cultural Assoc.
       		case 'ASSOCIATION_DOCUMENTS':
           		case 'KYTHERA_PHOTOGRAPHIC_ARCHIVE':
           		case 'KCA_PHOTO_CATEGORY_ONE':

                //update controller
                DB::statement('UPDATE document_types SET controller="DocumentImageController" WHERE id='.$catId.' LIMIT 1;');
                $limit = $limit * 3;
            break;

            #AUDIO
           	case 'AUDIO_INTERVIEWDIASPORA':
            	case 'AUDIO_INTERVIEW_ISLAND':
            	case 'AUDIO_KYTHERIANMUSIC':
            	case 'AUDIO_SOUNDSOFNATURE':

                //update controller
                DB::statement('UPDATE document_types SET controller="DocumentAudioController" WHERE id='.$catId.' LIMIT 1;');
                $limit = $limit * 3;
            break;
            #VIDEO
           	case 'VINTAGE_FILMS':

                //update controller
                DB::statement('UPDATE document_types SET controller="DocumentVideoController" WHERE id='.$catId.' LIMIT 1;');
                $limit = $limit * 3;
            break;
            case 'MESSAGE_BOARD':
                //update controller
                DB::statement('UPDATE document_types SET controller="DocumentMessageController" WHERE id='.$catId.' LIMIT 1;');
                $limit = $limit * 2;
            break;
            case 'GUEST_BOOK':
                //update controller
                DB::statement('UPDATE document_types SET controller="DocumentGuestbookController" WHERE id='.$catId.' LIMIT 1;');
                $limit = $limit * 2;
            break;
            case 'FAMILY_TREE_PERSON':
                //update controller
                DB::statement('UPDATE document_types SET controller="DocumentFamilyController" WHERE id='.$catId.' LIMIT 1;');
                $limit = $limit * 2;
            break;
            case 'OLD_LETTERS':
                //update controller
                DB::statement('UPDATE document_types SET controller="DocumentLetterController" WHERE id='.$catId.' LIMIT 1;');
                $limit = $limit * 3;
            break;
            #Culture
            case 'FOOD_AND_RECIPES':
                case 'SONGS_AND_POEMS':
                case 'BOOKS_ABOUT_US':
                case 'STORIES':

                //update controller
                DB::statement('UPDATE document_types SET controller="DocumentQuotedTextController" WHERE id='.$catId.' LIMIT 1;');
                $limit = $limit * 3;
            break;

        }

	    $items = Import::getDocuments($cat, $limit, $this->option('dump'));
	    $result['total']= $c = count($items);
	    $this->info(sprintf('    Found %d records (limit %d) in category: %s', $c, $limit, $cat));


	    $i=0;
	    foreach ($items as $entry_id =>$item) {
	        switch($cat) {
	            #People
	            case 'PEOPLE_LIFESTORIES':
    	            case 'PEOPLE_SURNAMEBOOK':
    	            case 'PEOPLE_OBITUARIES':
    	            case 'NICKNAMES':

           		#History
           		case 'GENERAL_HISTORY':
           			case 'ORAL_HISTORY':
           			case 'ARCHEOLOGY':
           			case 'ARCHIVE':
           			case 'MYTHS_AND_LEGENDS':

   				#Culture
   				case 'ARCHITECTURE':
       				case 'ASSOCIATIONS':
      				case 'BIBLIOGRAPHY':
       				case 'BLOG':
       				case 'CULTURAL_KNOWLEDGE':
       				case 'CULTUREKYTHERIANIDENTITY':
       				case 'HOME_REMEDIES':
       				case 'ISLAND_NEWS':
       				case 'KYTHERIAN BUSINESS GUIDE2':
       				case 'KYTHERIAN_ARTISTS':
       				case 'museumsgalleries':
       				case 'RELIGION':

	            case 'MESSAGE_BOARD':

                #Tourist Information
                case 'COMMUNITY_ISLANDINFOS':
	                case 'CALENDAR OF EVENTS2':
	                case 'TOURIST_ONLINE':
	                    #98: Links
	                case 'SIGHTSEEING':
	                    #134: Weather & Daylight
	                case 'WHERE_TO_EAT':
	                case 'WHERE_TO_STAY':

                #REAL ESTATE
                case 'REAL_ESTATE_HOWTOPOST':
	                case 'REAL_ESTATE_HOUSES':
	                case 'REAL_ESTATE_LAND':
	                case 'REAL_ESTATE_HOLIDAY':
	                case 'REAL_ESTATE_SEARCHproperty':
	                case 'REAL_ESTATE_search_holiday':

              	#Academic Research
               	case 'ACADEMICS_TWO':
                	case 'ACADEMICS_ONE':
                	case 'ACADEMICS_THREE':
                	case 'ACADEMICS_FOUR':
                	case 'ACADEMICS_FIVE':
                	case 'ACADEMICS_SIX':
                	case 'ACADEMICS_SEVEN':
                	case 'ACADEMICS_EIGHT':
                	case 'ACADEMICS_NINE':
                	case 'ACADEMICS_TEN':

                #Kythera Cultural Assoc.
                case 'KCA_Activities':

                #guestbook
                case 'GUEST_BOOK':

               	#news archive
               	case 'NEWS-ARCHIVE':

	                if (!$silent)
	                $this->info(sprintf('Importing record %d - %d: %d', ++$i, $c, $entry_id));

	                //delete 3rd item
	                if ($cat == 'BIBLIOGRAPHY')
	                	$item = $this->fixThird($item);
	                if ($cat == 'CULTUREKYTHERIANIDENTITY')
	                	$item = $this->fixThird($item);
	                if ($cat == 'ISLAND_NEWS')
	                	$item = $this->fixThird($item);

        	        //verify format
        	        if (count($item)!=2) {
        	            $this->error("    Ignoring record {$i}: {$entry_id}");
        	            $result['ignore']++;
        	            $result['error']++;
        	            if (!$silent) {
        	            	/*if guestbook stops here noting is wrong*/
        	            }
        	            continue;
        	        }

        	        if ($this->skips($entry_id)) {
        	        	$this->error("    Skip/Ignoring record {$i}: {$entry_id}");
        	        	continue;
        	        }

        	        //MESSAGE_BOARD fix missing english values
        	        if ($cat == 'MESSAGE_BOARD')
        	            $item = $this->fixMessageBoard($entry_id, $item, true);

        	        //duplicate
        	        //$item = $this->hasDuplicateUri($entry_id, $item, $result, true);
        	        $item = $this->hasDuplicateUriEN($entry_id, $item, $result, true);

        	        //images
        	        //$item = Import::extractImages($item, true);
        	        //links
        	        $item = Import::extractAnchors2($item);

        	        //downloads
        	        $item = Import::extractDownloads($item);

                    try {
        	            $query = sprintf("
    	                    INSERT INTO document_entities
    	                    SET
    	                        id=%d,
    	                        document_type_id=%d,
    	                        enabled=%d,
    	                        persons_id=%d,
    	                        created_at='%s',
    	                        updated_at='%s',
    	                        top_article=%d,
    	                        related_village_id=%d
    	                    ",
    	                    $entry_id,
    	                    $item[1]->docTypeId,
    	                    $item[1]->documentEnabled,
    	                    $item[1]->persons_id,
    	                    $item[1]->org_created,
    	                    $item[1]->org_lastchange,
    	                    $item[1]->isTopArticle,
    	                    $item[1]->relatedVillageId
        	            );
        	            if (!DB::statement($query))
        	                throw new Exception('Error inserting entry');
        	            if ($delete) {
        	            	$originals[] = $entry_id;
        	            }

        	            #en / title / uri / content
        	            if ($item[2]->language01) {
	                        $attributes = array(
	                            'uri'=>Translation::slug($item[1]->uri),// .'-'. $entry_id,
	                            //'uri'=>$this->getURI($item, $cat),
	                            'title'=>strip_tags($item[1]->language01),
	                            'content'=>nl2br($item[2]->language01)
	                        );
	        	            foreach($attributes as $k=>$v) {
	            	            $query = sprintf("
	        	                    INSERT INTO document_attributes
	        	                    SET
	                                    document_type_id=%d,
	            	                    document_entity_id=%d,
	        	                        `key`='%s',
	        	                        value=%s;
	        	                    ",
	        	                    $catId,
	        	                    $entry_id,
	        	                    $k,
	        	                    DB::getPdo()->quote($v)
	            	            );
	            	            //echo __FILE__.__LINE__.'<pre>$query='.htmlentities(print_r($query,1)).'</pre>';
	            	            if (!DB::statement($query))
	            	                throw new Exception('Error inserting '.$key);
	        	            }
	                        $result['ok']++;
        	            }

                        #gr / title
                        if ($item[1]->language03) {
            	            $query = sprintf("
        	                    INSERT INTO document_attributes
        	                    SET
                                    document_type_id=%d,
            	                    document_entity_id=%d,
        	                        l='gr',
        	                        `key`='title',
        	                        value=%s
        	                    ",
        	                    $catId,
        	                    $entry_id,
        	                    DB::getPdo()->quote($item[1]->language03)
            	            );
            	            if (!DB::statement($query))
            	                throw new Exception('Error inserting title');
        	                $result['trans']++;

            	            #slug
            	            $query = sprintf("
        	                    INSERT INTO document_attributes
        	                    SET
                                    document_type_id=%d,
            	                    document_entity_id=%d,
        	                        l='gr',
        	                        `key`='uri',
        	                        value='%s'
        	                    ",
        	                    $catId,
        	                    $entry_id,
        	                    Translation::slug($item[1]->language03)
            	            );
            	            if (!DB::statement($query))
            	                throw new Exception('Error inserting uri');
        	                $result['trans']++;
                        }

                        #gr / content
                        if ($item[2]->language03) {
            	            $query = sprintf("
        	                    INSERT INTO document_attributes
        	                    SET
                                    document_type_id=%d,
            	                    document_entity_id=%d,
        	                        l='gr',
        	                        `key`='content',
        	                        value=%s
        	                    ",
        	                    $catId,
        	                    $entry_id,
        	                    DB::getPdo()->quote(nl2br($item[2]->language03))
            	            );
            	            if (!DB::statement($query))
            	                throw new Exception('Error inserting content');
        	                $result['trans']++;
                        }

                    } catch (Exception $e) {
                        $result['error']++;
        	            if (!$silent)
                        echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';
                        $this->error("Inserting ID {$entry_id} failed: ".$e->getMessage()); die;
                    }
                break;
	            case 'GRAVESTONES':
	            case 'GRAVESTONES_AAF':
                case 'GRAVES_LIVATHI':
	            case 'GRAVESTONES_POTAMOS':
	            	case 'GRAVESTONES2_KARAVAS':
	            	case 'GRAVESTONES_AGDESPINA':
	            	case 'GRAVESTONES_DRYMONAS':
	            	case 'GRAVESTONES_FRILIGIANIKA':
	            	case 'GRAVESTONES_HORA':
	            	case 'GRAVESTONES_KAPSALI':
	            	case 'GRAVESTONES_KARAVAS':
	            	case 'GRAVESTONES_MITATA':
	            	case 'GRAVES_AGANASTASIA':
	            	case 'GRAVES_AGTHEO':
	            	case 'GRAVES_ALEXANDRATHES':
	            	case 'GRAVES_ALEXANDRATHES2':
	            	case 'GRAVES_ARONIATHIKA':
	            	case 'GRAVES_AUSTRALIA':
	            	case 'GRAVES_GERAKARI':
	            	case 'GRAVES_GOUTHIANIKA':
	            	case 'GRAVES_KERAMOTO':
	            	case 'GRAVES_LOGOTHETIANIKA':
	            	case 'GRAVES_PITSINIANIKA':
	            	case 'GRAVES_TRYFILL':
	            	case 'GRAVES_USA':
	            	case 'GRAVES_USA2':

	            #Culture
	            case 'ENVIRONMENT':
                    case 'FOOD_AND_RECIPES':
                    case 'SONGS_AND_POEMS':
                    case 'BOOKS_ABOUT_US':
                    case 'STORIES':


           		#History
           		case 'HISTORY_ARTEFACTS':
            		case 'DOCUMENTS':
            		case 'OLD_LETTERS':
            		case 'OLD_PHOTOS':
            		case 'OLD_MAPS':


            	#PHOTOGRAPHY ISLAND
            	case 'PHOTOGRAPHY_VINTAGE_LANDSCAPES':
            		case 'PHOTOGRAPHY_SIGNS_AND_STATUES':
            		case 'PHOTOGRAPHY_SCHOOL_PHOTOS':
            		case 'PHOTOGRAPHY_NATURE':
            		case 'PHOTOGRAPHY_MODERN_PORTRAITS':
            		case 'PHOTOGRAPHY_MODERN_LANDSCAPES':
            		case 'PHOTOGRAPHY_ISLAND_WORKING_LIFE':
            		case 'PHOTOGRAPHY_ISLAND_WEDDINGS_AND_PROXENIA':
            		case 'PHOTOGRAPHY_ISLAND_VINTAGE_PEOPLE':
            		case 'PHOTOGRAPHY_ISLAND_SOCIAL_LIFE':
            		case 'PHOTOGRAPHY_ISLAND_Miscellaneous':
            		case 'PHOTOGRAPHY_CHURCHES':
            		case 'PHOTOGRAPHY_ARCHITECTURE':
					case 'GreatWalls2':

	           	#PHOTOGRAPHY_DIASPORA
	           	case 'PHOTOGRAPHY_CAFES_AND_SHOPS':
	            	case 'churchesDiaspora':
	            	case 'PHOTOGRAPHY_DIASPORA_KYTHERIAN_ART':
	            	case 'PHOTOGRAPHY_DIASPORA_Miscellaneous':
	            	case 'PHOTOGRAPHY_DIASPORA_SOCIAL_LIFE':
	            	case 'PHOTOGRAPHY_DIASPORA_SPORTING_LIFE':
	            	case 'PHOTOGRAPHY_DIASPORA_VINTAGE_PEOPLE':
	            	case 'PHOTOGRAPHY_DIASPORA_WEDDINGS_AND_PROXENIA':
	            	case 'PHOTOGRAPHY_DIASPORA_WORKING_LIFE':

               	#Natural History Museum
               	case 'BIRD_IMAGES':
                	case 'FISH_IMAGES':
                	case 'FLOWER_IMAGES':
                	case 'FOSSIL_IMAGES':
                	case 'INSECT_ AND_KIN_IMAGES':
                	case 'MAMMAL_IMAGES':
                	case 'REPTILE_AND_AMPHIBIAN_IMAGES':
                	case 'ROCKS_IMAGES':
                	case 'SEASHELL_IMAGES_2':
                	case 'SEASHELL_IMAGES':
                	case 'SEASHELL_IMAGES_3':
                	case 'MARINE_MISCELLANY_IMAGES':

                #Kythera Cultural Assoc.
                case 'ASSOCIATION_DOCUMENTS':
                	case 'KYTHERA_PHOTOGRAPHIC_ARCHIVE':
                	case 'KCA_PHOTO_CATEGORY_ONE':

               	case 'FAMILY_TREE_PERSON':
                	#3
	                if (!$silent)
	                $this->info(sprintf('Importing record %d - %d: %d', ++$i, $c, $entry_id));

	                //FAMILY_TREE_PERSON fix missing items
	                if ($cat == 'FAMILY_TREE_PERSON')
	                	$item = $this->fixFamilyTreePerson($entry_id, $item, true);

	                //OLD_MAPS fix missing english values
	                if ($cat == 'OLD_MAPS')
	                	$item = $this->fixOldMaps($entry_id, $item, true);

	                if ($cat == 'PHOTOGRAPHY_NATURE')
	                	$item = $this->fixThirdAdd($item);
	                if ($cat == 'PHOTOGRAPHY_MODERN_LANDSCAPES')
	                	$item = $this->fixThirdAdd($item);
	                if ($cat == 'PHOTOGRAPHY_ISLAND_SOCIAL_LIFE')
	                	$item = $this->fixThirdAdd($item);
	                if ($cat == 'PHOTOGRAPHY_ISLAND_Miscellaneous')
	                	$item = $this->fixThirdAdd($item);
	                if ($cat == 'PHOTOGRAPHY_CHURCHES')
	                	$item = $this->fixThirdAdd($item);
	                if ($cat == 'PHOTOGRAPHY_CAFES_AND_SHOPS')
	                	$item = $this->fixThirdAdd($item);
	                if ($cat == 'PHOTOGRAPHY_DIASPORA_Miscellaneous')
	                	$item = $this->fixThirdAdd($item);
	                if ($cat == 'PHOTOGRAPHY_DIASPORA_VINTAGE_PEOPLE')
	                	$item = $this->fixThirdAdd($item);
	                if ($cat == 'PHOTOGRAPHY_ARCHITECTURE')
	                	$item = $this->fixThirdAdd($item);
	                if ($cat == 'PHOTOGRAPHY_MODERN_PORTRAITS')
	                	$item = $this->fixThirdAdd($item);
	                if ($cat == 'PHOTOGRAPHY_ISLAND_VINTAGE_PEOPLE')
	                	$item = $this->fixThirdAdd($item);
	                if ($cat == 'GRAVESTONES')
	                	$item = $this->fixThirdAdd($item);

	                if ($this->skips($entry_id)) {
	                	$this->error("    Skip/Ignoring record {$i}: {$entry_id}");
	                	continue;
	                }

        	        //verify format
        	        if (count($item)!=3) {
        	        	if (!$this->hasFix($item)) {
	        	            $this->error("    Ignoring record {$i}: {$entry_id}");
	        	            $result['ignore']++;
	        	            $result['error']++;
	        	            if (!$silent)
	        	            echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';
	        	            continue;
        	        	} else
        	        		$result['fix']++;
        	        }

        	        //duplicate
        	        $item = $this->hasDuplicateUriEN($entry_id, $item, $result, true);
        	        //$item = $this->hasDuplicateUriEN_Cached($entry_id, $item, $result, true);

        	        //images
        	        //$item = Import::extractImages($item, true);
        	        //links
        	        $item = Import::extractAnchors2($item);
                    //download
                    //http://www.kythera family.net/download/Visit Kythera article on the restoration of the House with the Sundial at Avlemonas.pdf
                    //obfuscate email
                    //<a href="mailto:transoz@bigpond.net.au">George Poulos, email</a>

        	        //downloads
        	        $item = Import::extractDownloads($item);

                    try {
        	            $query = sprintf("
    	                    INSERT INTO document_entities
    	                    SET
    	                        id=%d,
    	                        document_type_id=%d,
    	                        enabled=%d,
    	                        persons_id=%d,
    	                        created_at='%s',
    	                        updated_at='%s',
    	                        top_article=%d,
        	            		related_village_id=%d
    	                    ",
    	                    $entry_id,
    	                    $item[1]->docTypeId,
    	                    $item[1]->documentEnabled,
    	                    $item[1]->persons_id,
    	                    $item[1]->org_created,
    	                    $item[1]->org_lastchange,
    	                    $item[1]->isTopArticle,
    	                    $item[1]->relatedVillageId
        	            );
        	            if (!DB::statement($query))
        	                throw new Exception('Error inserting entry');
        	            if ($delete) {
        	            	$originals[] = $entry_id;
        	            }

                        $attributes = array(
                            //'uri'=>Translation::slug($item[1]->language01),
                            'uri'=>Translation::slug($item[1]->uri),
                       		//'uri'=>$this->getURI($item, $cat),
                            //'title'=>$item[1]->language01,
                       		'title'=>strip_tags($item[1]->language01),
                            'content'=>nl2br($item[2]->language01),
                            //'image_name'=>$item[1]->import_image_name,
                            //'image_path' =>$item[1]->import_image_path,
                            //'image_original' =>$item[1]->import_image_original,
                            //'image_taken' =>$item[1]->import_image_taken,
                        );
                        if ($cat == 'FAMILY_TREE_PERSON') {
                        	$attributes = array(
                       			'name'=>$item[1]->language01,
                       			'data'=>$item[3]->language01,
                       			'content'=>nl2br($item[2]->language01)
                        	);
                        }

                        if ($cat == 'OLD_LETTERS') {
                        	$attributes = array(
                        		'uri'=>Translation::slug($item[1]->uri),
                       			//'uri'=>$this->getURI($item, $cat),
                        		//'title'=>$item[1]->language01,
                       			'title'=>strip_tags($item[1]->language01),
                       			//'image'=>$item[3]->language01,
                       			'content'=>nl2br($item[2]->language01)
                        	);
                        	if (!empty($item[3]->language01))
                        	$letter_images[$entry_id] = $item[3]->language01;
                        }
                        if (in_array($cat, array('FOOD_AND_RECIPES', 'BOOKS_ABOUT_US', 'SONGS_AND_POEMS', 'STORIES'))) {
                            $attributes = array(
                                'uri'=>Translation::slug($item[1]->uri),
                           		'title'=>strip_tags(trim($item[1]->language01)),
                                'content'=>nl2br($item[2]->language01),
                                'source'=>trim($item[3]->language01)
                        	);
                        }

                        if (in_array($cat, array(
                            #Gravestones
                            'GRAVESTONES',
                            'GRAVESTONES_AAF',
                            'GRAVES_LIVATHI',
                            'GRAVESTONES_POTAMOS',
                            'GRAVES_GERAKARI',
                            'GRAVESTONES_AGDESPINA',
                            'GRAVESTONES_DRYMONAS',
                            'GRAVESTONES_FRILIGIANIKA',
                            'GRAVESTONES_HORA',
                            'GRAVESTONES_KAPSALI',
                            'GRAVESTONES_KARAVAS',
                            'GRAVESTONES_MITATA',
                            'GRAVES_AGANASTASIA',
                            'GRAVES_AGTHEO',
                            'GRAVES_ALEXANDRATHES',
                            'GRAVES_ALEXANDRATHES2',
                            'GRAVES_ARONIATHIKA',
                            'GRAVES_AUSTRALIA',
                            'GRAVES_GERAKARI',
                            'GRAVES_GOUTHIANIKA',
                            'GRAVES_KERAMOTO',
                            'GRAVES_LOGOTHETIANIKA',
                            'GRAVES_PITSINIANIKA',
                            'GRAVES_TRYFILL',
                            #'GRAVES_USA',
                            'GRAVES_USA2',

                            #Photography Island
                            'PHOTOGRAPHY_VINTAGE_LANDSCAPES',
                            'PHOTOGRAPHY_SIGNS_AND_STATUES',
                            'PHOTOGRAPHY_SCHOOL_PHOTOS',
                            'PHOTOGRAPHY_NATURE',
                            'PHOTOGRAPHY_MODERN_PORTRAITS',
                            'PHOTOGRAPHY_MODERN_LANDSCAPES',
                            'PHOTOGRAPHY_ISLAND_WORKING_LIFE',
                            'PHOTOGRAPHY_ISLAND_WEDDINGS_AND_PROXENIA',
                            'PHOTOGRAPHY_ISLAND_VINTAGE_PEOPLE',
                            'PHOTOGRAPHY_ISLAND_SOCIAL_LIFE',
                            'PHOTOGRAPHY_ISLAND_Miscellaneous',
                            'PHOTOGRAPHY_CHURCHES',
                            'PHOTOGRAPHY_ARCHITECTURE',

                            #PHOTOGRAPHY_DIASPORA
                            'PHOTOGRAPHY_CAFES_AND_SHOPS',
                            'churchesDiaspora',
                            'PHOTOGRAPHY_DIASPORA_KYTHERIAN_ART',
                            'PHOTOGRAPHY_DIASPORA_Miscellaneous',
                            'PHOTOGRAPHY_DIASPORA_SOCIAL_LIFE',
                            'PHOTOGRAPHY_DIASPORA_SPORTING_LIFE',
                            'PHOTOGRAPHY_DIASPORA_VINTAGE_PEOPLE',
                            'PHOTOGRAPHY_DIASPORA_WEDDINGS_AND_PROXENIA',
                            'PHOTOGRAPHY_DIASPORA_WORKING_LIFE',

                            #Natural History Museum
                            'BIRD_IMAGES',
                            'FISH_IMAGES',
                            'FLOWER_IMAGES',
                            'FOSSIL_IMAGES',
                            'INSECT_ AND_KIN_IMAGES',
                            'MAMMAL_IMAGES',
                            'REPTILE_AND_AMPHIBIAN_IMAGES',
                            'ROCKS_IMAGES',
                            'SEASHELL_IMAGES_2',
                            'SEASHELL_IMAGES',
                            'SEASHELL_IMAGES_3',
                            'MARINE_MISCELLANY_IMAGES',
                        ))) {
                        	$attributes = array(
                       			'uri'=>Translation::slug($item[1]->uri),
                       			'title'=>strip_tags(trim($item[1]->language01)),
                       			'content'=>nl2br($item[2]->language01),
                       			'copyright'=>$item[3]->language01,
                        	);
                        }

        	            foreach($attributes as $k=>$v) {
            	            $query = sprintf("
        	                    INSERT INTO document_attributes
        	                    SET
                                    document_type_id=%d,
            	                    document_entity_id=%d,
        	                        `key`='%s',
        	                        value=%s;
        	                    ",
        	                    $catId,
        	                    $entry_id,
        	                    $k,
        	                    DB::getPdo()->quote($v)
            	            );
            	            //echo __FILE__.__LINE__.'<pre>$query='.htmlentities(print_r($query,1)).'</pre>';
            	            if (!DB::statement($query))
            	                throw new Exception('Error inserting '.$key);
        	            }
                        $result['ok']++;

                        #gr / title
                        if ($item[1]->language03) {
            	            $query = sprintf("
        	                    INSERT INTO document_attributes
        	                    SET
                                    document_type_id=%d,
            	                    document_entity_id=%d,
        	                        l='gr',
        	                        `key`='title',
        	                        value=%s
        	                    ",
        	                    $catId,
        	                    $entry_id,
        	                    DB::getPdo()->quote($item[1]->language03)
            	            );
            	            if (!DB::statement($query))
            	                throw new Exception('Error inserting title');
        	                $result['trans']++;

            	            #slug
            	            $query = sprintf("
        	                    INSERT INTO document_attributes
        	                    SET
                                    document_type_id=%d,
            	                    document_entity_id=%d,
        	                        l='gr',
        	                        `key`='uri',
        	                        value='%s'
        	                    ",
        	                    $catId,
        	                    $entry_id,
        	                    Translation::slug($item[1]->language03)
            	            );
            	            if (!DB::statement($query))
            	                throw new Exception('Error inserting uri');
        	                $result['trans']++;
                        }

                        #gr / content
                        if ($item[2]->language03) {
            	            $query = sprintf("
        	                    INSERT INTO document_attributes
        	                    SET
                                    document_type_id=%d,
            	                    document_entity_id=%d,
        	                        l='gr',
        	                        `key`='content',
        	                        value=%s
        	                    ",
        	                    $catId,
        	                    $entry_id,
        	                    DB::getPdo()->quote(nl2br($item[2]->language03))
            	            );
            	            if (!DB::statement($query))
            	                throw new Exception('Error inserting content');
        	                $result['trans']++;
                        }

                    } catch (Exception $e) {
                        $result['error']++;
                        echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';
                        $this->error("Inserting ID {$entry_id} failed: ".$e->getMessage()); die;
                    }
                break;


            #AUDIO
            case 'AUDIO_INTERVIEWDIASPORA':
                case 'AUDIO_INTERVIEW_ISLAND':
                case 'AUDIO_KYTHERIANMUSIC':
                case 'AUDIO_SOUNDSOFNATURE':
                case 'VINTAGE_FILMS':
	                if (!$silent)
	                $this->info(sprintf('Importing audio record %d - %d: %d', ++$i, $c, $entry_id));

        	        //verify format
        	        if (count($item)!=3) {
        	            $this->error("    Ignoring record {$i}: {$entry_id}");
        	            $result['ignore']++;
        	            $result['error']++;
        	            if (!$silent)
        	            echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';die;
        	            continue;
        	        }

        	        //duplicate
        	        //$item = $this->hasDuplicateUri($entry_id, $item, $result, true);
        	        $item = $this->hasDuplicateUriEN($entry_id, $item, $result, true);

        	        //images
        	        //$item = Import::extractImages($item, true);
        	        //links
        	        $item = Import::extractAnchors2($item);
                    //download
                    //http://www.kythera family.net/download/Visit Kythera article on the restoration of the House with the Sundial at Avlemonas.pdf
                    //obfuscate email
                    //<a href="mailto:transoz@bigpond.net.au">George Poulos, email</a>

        	        //downloads
        	        $item = Import::extractDownloads($item);

                    try {
        	            $query = sprintf("
    	                    INSERT INTO document_entities
    	                    SET
    	                        id=%d,
    	                        document_type_id=%d,
    	                        enabled=%d,
    	                        persons_id=%d,
    	                        created_at='%s',
    	                        updated_at='%s',
    	                        top_article=%d,
        	            		related_village_id=%d
    	                    ",
    	                    $entry_id,
    	                    $item[1]->docTypeId,
    	                    $item[1]->documentEnabled,
    	                    $item[1]->persons_id,
    	                    $item[1]->org_created,
    	                    $item[1]->org_lastchange,
    	                    $item[1]->isTopArticle,
    	                    $item[1]->relatedVillageId
        	            );
        	            if (!DB::statement($query))
        	                throw new Exception('Error inserting entry');
        	            if ($delete) {
        	            	$originals[] = $entry_id;
        	            }

                        $attributes = array(
                            //'uri'=>Translation::slug($item[1]->language01),
                            'uri'=>Translation::slug($item[1]->uri),
                       		//'uri'=>$this->getURI($item, $cat),
                            //'title'=>$item[1]->language01,
                       		'title'=>strip_tags($item[1]->language01),
                            'content'=>nl2br($item[2]->language01),
                            //'audio_name'=>$item[1]->import_audio_name,
                            //'audio_path' =>$item[1]->import_audio_path,
                            //'audio_taken' =>$item[1]->import_audio_taken != '0000-00-00' ?$item[1]->import_audio_taken: null,
                        );
        	            foreach($attributes as $k=>$v) {
            	            $query = sprintf("
        	                    INSERT INTO document_attributes
        	                    SET
                                    document_type_id=%d,
            	                    document_entity_id=%d,
        	                        `key`='%s',
        	                        value=%s;
        	                    ",
        	                    $catId,
        	                    $entry_id,
        	                    $k,
        	                    !is_null($v)?DB::getPdo()->quote($v):'null'
            	            );
            	            //echo __FILE__.__LINE__.'<pre>$query='.htmlentities(print_r($query,1)).'</pre>';
            	            if (!DB::statement($query))
            	                throw new Exception('Error inserting '.$key);
        	            }
                        $result['ok']++;

                        #gr / title
                        if ($item[1]->language03) {
            	            $query = sprintf("
        	                    INSERT INTO document_attributes
        	                    SET
                                    document_type_id=%d,
            	                    document_entity_id=%d,
        	                        l='gr',
        	                        `key`='title',
        	                        value=%s
        	                    ",
        	                    $catId,
        	                    $entry_id,
        	                    DB::getPdo()->quote($item[1]->language03)
            	            );
            	            if (!DB::statement($query))
            	                throw new Exception('Error inserting title');
        	                $result['trans']++;

            	            #slug
            	            $query = sprintf("
        	                    INSERT INTO document_attributes
        	                    SET
                                    document_type_id=%d,
            	                    document_entity_id=%d,
        	                        l='gr',
        	                        `key`='uri',
        	                        value='%s'
        	                    ",
        	                    $catId,
        	                    $entry_id,
        	                    Translation::slug($item[1]->language03)
            	            );
            	            if (!DB::statement($query))
            	                throw new Exception('Error inserting uri');
        	                $result['trans']++;
                        }

                        #gr / content
                        if ($item[2]->language03) {
            	            $query = sprintf("
        	                    INSERT INTO document_attributes
        	                    SET
                                    document_type_id=%d,
            	                    document_entity_id=%d,
        	                        l='gr',
        	                        `key`='content',
        	                        value=%s
        	                    ",
        	                    $catId,
        	                    $entry_id,
        	                    DB::getPdo()->quote(nl2br($item[2]->language03))
            	            );
            	            if (!DB::statement($query))
            	                throw new Exception('Error inserting content');
        	                $result['trans']++;
                        }

                    } catch (Exception $e) {
                        $result['error']++;
                        echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';
                        $this->error("Inserting ID {$entry_id} failed: ".$e->getMessage()); die;
                    }
                break;

                case 'FAMOUS_PEOPLE':
                    if (!$silent)
                        $this->info(sprintf('Importing famous people record %d - %d: %d', ++$i, $c, $entry_id));

                    $item = $this->fixFamousPerson($item);

                    //verify format
                    if (count($item)!=4) {
                        $this->error("    Ignoring record {$i}: {$entry_id}");
                        $result['ignore']++;
                        $result['error']++;
                        if (!$silent)
                            echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';die;
                        continue;
                    }

                    //duplicate
                    //$item = $this->hasDuplicateUri($entry_id, $item, $result, true);
                    $item = $this->hasDuplicateUriEN($entry_id, $item, $result, true);

                    //images
                    //$item = Import::extractImages($item, true);
                    //links
                    $item = Import::extractAnchors2($item);
                    //download
                    //http://www.kythera family.net/download/Visit Kythera article on the restoration of the House with the Sundial at Avlemonas.pdf
                    //obfuscate email
                    //<a href="mailto:transoz@bigpond.net.au">George Poulos, email</a>

                    //downloads
                    $item = Import::extractDownloads($item);

                    try {
                        $query = sprintf("
    	                    INSERT INTO document_entities
    	                    SET
    	                        id=%d,
    	                        document_type_id=%d,
    	                        enabled=%d,
    	                        persons_id=%d,
    	                        created_at='%s',
    	                        updated_at='%s',
    	                        top_article=%d,
        	            		related_village_id=%d
    	                    ",
                            $entry_id,
                            $item[1]->docTypeId,
                            $item[1]->documentEnabled,
                            $item[1]->persons_id,
                            $item[1]->org_created,
                            $item[1]->org_lastchange,
                            $item[1]->isTopArticle,
                            $item[1]->relatedVillageId
                        );
                        if (!DB::statement($query))
                            throw new Exception('Error inserting entry');
                        if ($delete) {
                            $originals[] = $entry_id;
                        }

                        $attributes = array(
                            'uri'=>Translation::slug($item[1]->uri),
                            'title'=>strip_tags($item[1]->language01),
                            'content'=>nl2br($item[2]->language01),
                            'city'=>$item[3]->language01,
                            'town'=>$item[4]->language01
                        );

                        foreach($attributes as $k=>$v) {
                            $query = sprintf("
        	                    INSERT INTO document_attributes
        	                    SET
                                    document_type_id=%d,
            	                    document_entity_id=%d,
        	                        `key`='%s',
        	                        value=%s;
        	                    ",
                                $catId,
                                $entry_id,
                                $k,
                                !is_null($v)?DB::getPdo()->quote($v):'null'
                            );
                            //echo __FILE__.__LINE__.'<pre>$query='.htmlentities(print_r($query,1)).'</pre>';
                            if (!DB::statement($query))
                                throw new Exception('Error inserting '.$key);
                        }
                        $result['ok']++;

                        #gr / title
                        if ($item[1]->language03) {
                            $query = sprintf("
        	                    INSERT INTO document_attributes
        	                    SET
                                    document_type_id=%d,
            	                    document_entity_id=%d,
        	                        l='gr',
        	                        `key`='title',
        	                        value=%s
        	                    ",
                                $catId,
                                $entry_id,
                                DB::getPdo()->quote($item[1]->language03)
                            );
                            if (!DB::statement($query))
                                throw new Exception('Error inserting title');
                            $result['trans']++;

                            #slug
                            $query = sprintf("
        	                    INSERT INTO document_attributes
        	                    SET
                                    document_type_id=%d,
            	                    document_entity_id=%d,
        	                        l='gr',
        	                        `key`='uri',
        	                        value='%s'
        	                    ",
        	                    $catId,
        	                    $entry_id,
        	                    Translation::slug($item[1]->language03)
                            );
                            if (!DB::statement($query))
                                throw new Exception('Error inserting uri');
                            $result['trans']++;
                        }

                        #gr / content
                        if ($item[2]->language03) {
                        $query = sprintf("
                        INSERT INTO document_attributes
                            SET
                                document_type_id=%d,
            	                    document_entity_id=%d,
        	                        l='gr',
        	                        `key`='content',
        	                        value=%s
        	                    ",
        	                    $catId,
        	                    $entry_id,
        	                    DB::getPdo()->quote(nl2br($item[2]->language03))
    	                        );
    	                        if (!DB::statement($query))
    	                            throw new Exception('Error inserting content');
    	                        $result['trans']++;
                        }

                        #gr / city
                        if ($item[3]->language03) {
                        $query = sprintf("
                        INSERT INTO document_attributes
                            SET
                                document_type_id=%d,
            	                    document_entity_id=%d,
        	                        l='gr',
        	                        `key`='city',
        	                        value=%s
        	                    ",
        	                    $catId,
        	                    $entry_id,
        	                    DB::getPdo()->quote(nl2br($item[3]->language03))
    	                        );
    	                        if (!DB::statement($query))
    	                            throw new Exception('Error inserting content');
    	                        $result['trans']++;
                        }

                        #gr / town
                        if ($item[4]->language03) {
                        $query = sprintf("
                        INSERT INTO document_attributes
                            SET
                                document_type_id=%d,
            	                    document_entity_id=%d,
        	                        l='gr',
        	                        `key`='town',
        	                        value=%s
        	                    ",
        	                    $catId,
        	                    $entry_id,
        	                    DB::getPdo()->quote(nl2br($item[4]->language03))
    	                        );
    	                        if (!DB::statement($query))
    	                            throw new Exception('Error inserting content');
    	                        $result['trans']++;
                        }

                    } catch (Exception $e) {
                        $result['error']++;
                        echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';
                        $this->error("Inserting ID {$entry_id} failed: ".$e->getMessage()); die;
                    }


                break;

	            default:
	                $this->info(sprintf('    Not implemented'));
	                $result['error']++;
	                break;
	        }

	    }

	    #clean up invalid villageId's
	    DB::table('document_entities')->where('related_village_id', 0)->update(array('related_village_id' => null));

	    #all content imported, delete originals 21674  7254
	    Import::deleteOriginals($originals);

	    $this->importLetterImages($letter_images);
// 	    if ($result['error']==0)
// 	    	Import::deleteOriginals($originals);
// 	    else
// 	    	$this->info(sprintf('Cannot delete originals because of errors(%d).', $result['error']));

	    $this->info(sprintf('DONE (errors:%d, ok:%d, ignored:%d, total:%d, dups:%d)', $result['error'], $result['ok'], $result['ignore'], $result['total'], $result['dup'] ));
	    $this->info('');

	}

}
