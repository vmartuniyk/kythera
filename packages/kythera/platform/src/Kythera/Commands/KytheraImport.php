<?php namespace Kythera\Commands;

/*
ACADEMICS_EIGHT
ACADEMICS_FIVE
ACADEMICS_FOUR
ACADEMICS_NINE
ACADEMICS_ONE
ACADEMICS_SEVEN
ACADEMICS_SIX
ACADEMICS_TEN
ACADEMICS_THREE
ACADEMICS_TWO
ADDRESSES
ARCHEOLOGY
ARCHITECTURE
ARCHIVE
ASSOCIATIONS
ASSOCIATION_DOCUMENTS
AUDIO_INTERVIEWDIASPORA
AUDIO_INTERVIEW_ISLAND
AUDIO_KYTHERIANMUSIC
AUDIO_SOUNDSOFNATURE
BIBLIOGRAPHY
BIRD_IMAGES
BLOG
BOOKS_ABOUT_US
CALENDAR OF EVENTS2
churchesDiaspora
COMMUNITY_ISLANDINFOS
CULTURAL_KNOWLEDGE
CULTUREKYTHERIANIDENTITY
DOCUMENTS
ENVIRONMENT
FAMILY_TREE_PERSON
FAMOUS_PEOPLE
FISH_IMAGES
FLOWER_IMAGES
FOOD_AND_RECIPES
FOSSIL_IMAGES
GENEALOGICAL_RESEARCH
GENERAL_HISTORY
GRAVESTONES
GRAVESTONES2_KARAVAS
GRAVESTONES_AAF
GRAVESTONES_AGDESPINA
GRAVESTONES_DRYMONAS
GRAVESTONES_FRILIGIANIKA
GRAVESTONES_HORA
GRAVESTONES_KAPSALI
GRAVESTONES_KARAVAS
GRAVESTONES_MITATA
GRAVESTONES_POTAMOS
GRAVES_AGANASTASIA
GRAVES_AGTHEO
GRAVES_ALEXANDRATHES
GRAVES_ALEXANDRATHES2
GRAVES_ARONIATHIKA
GRAVES_AUSTRALIA
GRAVES_GERAKARI
GRAVES_GOUTHIANIKA
GRAVES_KERAMOTO
GRAVES_LIVATHI
GRAVES_LOGOTHETIANIKA
GRAVES_PITSINIANIKA
GRAVES_TRYFILL
GRAVES_USA
GRAVES_USA2
GreatWalls2
GUEST_BOOK
HISTORY_ARTEFACTS
HOME_REMEDIES
INSECT_ AND_KIN_IMAGES
ISLAND_NEWS
KCA:WAR
KCA_Activities
KCA_NEWSACTIVITIES
KCA_PHOTO_CATEGORY_ONE
KCA_PHOTO_CATEGORY_THREE
KCA_PHOTO_CATEGORY_TWO
KYTHERA_PHOTOGRAPHIC_ARCHIVE
KYTHERIAN BUSINESS GUIDE2
KYTHERIAN_ARTISTS
MAMMAL_IMAGES
MARINE_MISCELLANY_IMAGES
MESSAGE_BOARD
MESSAGE_BOARD2
MODERN_MAPS
museumsgalleries
MYTHS_AND_LEGENDS
NEWS-ARCHIVE
NICKNAMES
OLD_LETTERS
OLD_MAPS
OLD_PHOTOS
ORAL_HISTORY
PEOPLE_LIFESTORIES
PEOPLE_OBITUARIES
PEOPLE_SURNAMEBOOK
PHOTOGRAPHY_ARCHITECTURE
PHOTOGRAPHY_CAFES_AND_SHOPS
PHOTOGRAPHY_CHURCHES
PHOTOGRAPHY_DIASPORA_KYTHERIAN_ART
PHOTOGRAPHY_DIASPORA_Miscellaneous
PHOTOGRAPHY_DIASPORA_SOCIAL_LIFE
PHOTOGRAPHY_DIASPORA_SPORTING_LIFE
PHOTOGRAPHY_DIASPORA_VINTAGE_PEOPLE
PHOTOGRAPHY_DIASPORA_WEDDINGS_AND_PROXENIA
PHOTOGRAPHY_DIASPORA_WORKING_LIFE
PHOTOGRAPHY_ISLAND_Miscellaneous
PHOTOGRAPHY_ISLAND_SOCIAL_LIFE
PHOTOGRAPHY_ISLAND_VINTAGE_PEOPLE
PHOTOGRAPHY_ISLAND_WEDDINGS_AND_PROXENIA
PHOTOGRAPHY_ISLAND_WORKING_LIFE
PHOTOGRAPHY_MODERN_LANDSCAPES
PHOTOGRAPHY_MODERN_PORTRAITS
PHOTOGRAPHY_NATURE
PHOTOGRAPHY_SCHOOL_PHOTOS
PHOTOGRAPHY_SIGNS_AND_STATUES
PHOTOGRAPHY_VINTAGE_LANDSCAPES
REAL_ESTATE_HOLIDAY
REAL_ESTATE_HOUSES
REAL_ESTATE_HOWTOPOST
REAL_ESTATE_LAND
REAL_ESTATE_SEARCHproperty
REAL_ESTATE_search_holiday
RELIGION
REPTILE_AND_AMPHIBIAN_IMAGES
ROCKS_IMAGES
SEASHELL_IMAGES
SEASHELL_IMAGES_2
SEASHELL_IMAGES_3
SIGHTSEEING
SONGS_AND_POEMS
SPARE
STORIES
THE_FATSEAS_ARCHIVE
TOURIST_ONLINE
VINTAGE_FILMS
WHERE_TO_EAT
WHERE_TO_STAY
WINDFARM
WINDFARM_DOCS
WINDFARM_INTERVIEW
WINDFARM_PHOTOS
 */


use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Input;

class KytheraImport extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'kythera:import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import kythera data';

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

        $this->info('');
        $this->info('Src db: '.DB::connection('import')->getDatabaseName());
        $this->info('Dst db: '. DB::connection()->getDatabaseName());
        $this->info('');
        if (!$this->confirm('Do you wish to continue? [y|N]')) {
            exit;
        }

        $time_start = microtime(true);

		$this->call('down');

	    #options
	    DB::disableQueryLog();

        #truncate
        DB::table("document_entities")->truncate();
        DB::table("document_attributes")->truncate();
        DB::table("document_categories")->truncate();

        #copy tables by truncating them first!
        // ./artisan kythera:import_table -d0 villages
        $this->call('kythera:import_table', array('source'=>'villages'));
        $this->call('kythera:import_table', array('source'=>'village_compounds'));
        $this->call('kythera:import_table', array('source'=>'page_visitors'));
        $this->call('kythera:import_table', array('source'=>'guestbook'));

	    $this->call('kythera:import_users');

        $this->call('kythera:import_table', array('source'=>'names'));
	    $this->call('kythera:import_table', array('source'=>'namebox_entries'));
	    $this->call('kythera:import_table', array('source'=>'related_names'));
	    $this->call('kythera:import_table', array('source'=>'name_compounds'));

	    $this->call('kythera:import_table', array('source'=>'letters'));

	    $this->call('kythera:import_table', array('source'=>'messageboard'));
	    $this->call('kythera:import_table', array('source'=>'messageboard2')); //merge

	    $this->call('kythera:import_table', array('source'=>'newsletter_subscriber'));

	    $entities = array(

	        #people
	        'NICKNAMES',
		        'PEOPLE_LIFESTORIES',
		        'PEOPLE_SURNAMEBOOK',
		        'PEOPLE_OBITUARIES',

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
	    		'GRAVES_USA',
	    		'GRAVES_USA2',

	    	#History
    		'GENERAL_HISTORY',
	    		'ORAL_HISTORY',
	    		'ARCHEOLOGY',
	    		'ARCHIVE',
	    		'MYTHS_AND_LEGENDS',
	    		'HISTORY_ARTEFACTS',
	    		'DOCUMENTS',
	    		#'OLD_LETTERS',
	    		'OLD_PHOTOS',
	    		'OLD_MAPS',

	    	#culture
    		'ARCHITECTURE',
	    		'ASSOCIATIONS',
	    		'BIBLIOGRAPHY',
	    		'BLOG',
	    		'CULTURAL_KNOWLEDGE',
	    		'CULTUREKYTHERIANIDENTITY',
	    		'HOME_REMEDIES',
	    		'ISLAND_NEWS',
	    		'KYTHERIAN BUSINESS GUIDE2',
	    		'KYTHERIAN_ARTISTS',
	    		'museumsgalleries',
	    		'RELIGION',
	    		'FOOD_AND_RECIPES',
	    		'BOOKS_ABOUT_US',
	    		'SONGS_AND_POEMS',
	    		'STORIES',
	    		'ENVIRONMENT',

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
	    		'GreatWalls2',

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

	        #audio
	        'AUDIO_INTERVIEWDIASPORA',
		        'AUDIO_INTERVIEW_ISLAND',
		        'AUDIO_KYTHERIANMUSIC',
		        'AUDIO_SOUNDSOFNATURE',
		        'VINTAGE_FILMS',

	        #REAL ESTATE
	        'REAL_ESTATE_HOWTOPOST',
		        'REAL_ESTATE_HOUSES',
		        'REAL_ESTATE_LAND',
		        'REAL_ESTATE_HOLIDAY',
		        'REAL_ESTATE_SEARCHproperty',
		        'REAL_ESTATE_search_holiday',

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

	        #tourist info
	        'COMMUNITY_ISLANDINFOS',
		        'CALENDAR OF EVENTS2',
		        'TOURIST_ONLINE',
		        'SIGHTSEEING',
		        'WHERE_TO_EAT',
		        'WHERE_TO_STAY',

	    	#Academic Research
    		'ACADEMICS_TWO',
	    		'ACADEMICS_ONE',
	    		'ACADEMICS_THREE',
	    		'ACADEMICS_FOUR',
	    		'ACADEMICS_FIVE',
	    		'ACADEMICS_SIX',
	    		'ACADEMICS_SEVEN',
	    		'ACADEMICS_EIGHT',
	    		'ACADEMICS_NINE',
	    		'ACADEMICS_TEN',

	        'MESSAGE_BOARD',
	        'GUEST_BOOK',
	        'NEWS-ARCHIVE',
	    );

	    foreach ($entities as $entity)
	    {
	       $this->call('kythera:import_entities', array('category' => $entity, 'silent' => true));
	    }

	    #copy tables by modifying table structure!
	    $this->call('kythera:import_audio');
	    $this->call('kythera:import_images');
	    $this->call('kythera:import_comments');
	    $this->call('kythera:import_categories');

	    $this->call('up');

	    $time_end = microtime(true);
	    $time = $time_end - $time_start;
	    $this->info("Elapsed $time seconds.");
	    $this->info(sprintf("Elapsed ~%f minutes.", $time/60));

	    $this->info("You should run ./artisan kythera:hydrate now.");
	}

}
