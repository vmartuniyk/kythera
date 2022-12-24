<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Import;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

/**
 *
 * @author virgilm
 *
 */
class AdminImportController extends AdminController
{
    
    /**
     * Admin import
     *
     * Fetch data from old database for database structure analysis
     * See /info/database.ods for database layout
     */
    public function getIndex()
    {
        return view('admin.import.index')->with('title', 'Import')->with('i', 0)->with('items', []);
    }
    
    /**
     * NAVIGATION
     */
    public function getNavigation()
    {
        // $navigation = $this->navigation();
        $navigation = Import::navigation();
        // echo __FILE__.__LINE__.'<pre>$navigation='.htmlentities(print_r($navigation,1)).'</pre>';die;
        $content = $this->tree($navigation);
        
        return view('admin.import.navigation')->with('title', 'Navigation')->with('content', $content);
    }
    private function tree($items = null, $level = 0)
    {
        $level ++;
        $h = '';
        $h .= '<ul class="xnavigation l' . ($level) . '">';
        foreach ($items as $item) {
            $h .= sprintf('<li class="%s">', $item->controller);
            $h .= sprintf('%d: %s (%s:%d:%s)', $item->id, $item->language01, $item->controller, $item->document_type_id, $item->string_id);
            if (isset($item->children)) {
                $h .= $this->tree($item->children, $level);
            }
            $h .= sprintf('</li>');
        }
        $h .= '</ul>';
        return $h;
    }
    
    /**
     * Depricated.
     *
     * Use Import::navigation instead.
     */
    /*
	 * private function navigation($parent = null) {
	 * $result = array();
	 *
	 * if (is_null($parent)) {
	 * $items = DB::connection('import')->select(DB::raw('
	 * select N.*, language01 from navigation_tree N, document_type_id
	 * left join
	 * system_dictionary S on N.label=S.id
	 * left join
	 * categories C on N.id=C.parent_id
	 * where
	 * parent=0 and enabled=1 and permission=1
	 * order by
	 * sort;'));
	 * } else {
	 * $items = DB::connection('import')->select(DB::raw('
	 * select N.*, language01 from navigation_tree N, document_type_id
	 * left join
	 * system_dictionary S on N.label=S.id
	 * left join
	 * categories C on N.id=C.parent_id
	 * where
	 * parent='.$parent->id.' and enabled=1 and permission=1
	 * order by
	 * sort;'));
	 * }
	 *
	 * foreach ($items as $item) {
	 * $result[] = $item;
	 * $children = DB::connection('import')->select(DB::raw('select count(*) n from navigation_tree where enabled=1 and permission=1 and parent='.$item->id));
	 * $children = is_array($children) ? current($children)->n : 0;
	 * if ($children) {
	 * $item->children = $this->navigation($item);
	 * }
	 * }
	 *
	 * return $result;
	 * }
	 */
    
    /**
     * DOCUMENTS
     */
    public function getCategory()
    {
        if ($c = Input::get('c')) {
            return $this->showDocuments(trim($c));
        } else {
            return $this->showDocuments('nicknames');
        }
    }
    public function getNames()
    {
        
        // find all used letters
        $names =  [];
        foreach (range('A', 'Z') as $letter) {
            if ($items = DB::connection('import')->table('namebox_entries')->where('name', 'like', $letter . '%')->groupBy('name')->get()) {
                $names [$letter] = $items;
            }
        }
        
        // show all
        return view('admin.import.names')->with('title', 'NAMES')->with('names', $names);
    }
    public function getNicknames()
    {
        return $this->showDocuments('nicknames');
    }
    public function getSurnamebook()
    {
        return $this->showDocuments('PEOPLE_SURNAMEBOOK');
    }
    public function getLifestories()
    {
        return $this->showDocuments('PEOPLE_LIFESTORIES');
    }
    public function getFamous_people()
    {
        return $this->showDocuments('FAMOUS_PEOPLE');
    }
    public function getObituaries()
    {
        return $this->showDocuments('PEOPLE_OBITUARIES');
    }
    public function getGravestones()
    {
        return $this->showDocuments('GRAVESTONES');
    }
    public function getGravestones_karavas()
    {
        return $this->showDocuments('GRAVESTONES2_KARAVAS');
    }
    public function getGravestones_potamos()
    {
        return $this->showDocuments('GRAVESTONES_POTAMOS');
    }
    public function getOral_history()
    {
        return $this->showDocuments('ORAL_HISTORY');
    }
    public function getReal_estate_how()
    {
        return $this->showDocuments('REAL_ESTATE_HOWTOPOST');
    }
    public function getReal_estate_houses()
    {
        return $this->showDocuments('REAL_ESTATE_HOUSES');
    }
    public function getArchitecture()
    {
        return $this->showDocuments('PHOTOGRAPHY_ARCHITECTURE');
    }
    public function getSocial_life()
    {
        return $this->showDocuments('PHOTOGRAPHY_ISLAND_SOCIAL_LIFE');
    }
    public function getKytherian_music()
    {
        return $this->showDocuments('AUDIO_KYTHERIANMUSIC');
    }
    public function getSounds_of_nature()
    {
        return $this->showDocuments('AUDIO_SOUNDSOFNATURE');
    }
    public function getGuestbook()
    {
        return $this->showDocuments('GUEST_BOOK');
    }
    public function getBoard()
    {
        
        /*
		 * //DB::enableQueryLog();
		 * Debugbar::info($_GET);
		 * Debugbar::error("Error!");
		 * Debugbar::warning('Watch out..');
		 * Debugbar::addMessage('Another message', 'mylabel');
		 *
		 *
		 * Debugbar::startMeasure('render','Time for rendering');
		 * sleep(4);
		 * Debugbar::stopMeasure('render');
		 * Debugbar::addMeasure('now', LARAVEL_START, microtime(true));
		 * Debugbar::measure('My long operation', function() {
		 * sleep(7);
		 * });
		 */
        return $this->showDocuments('MESSAGE_BOARD');
    }
    public function getNews_archive()
    {
        return $this->showDocuments('NEWS-ARCHIVE');
    }
    
    /* HELPER functions */
    private function showDocuments($category = 'PEOPLE_LIFESTORIES')
    {
        $entry_id = Input::get('entry_id');
        $items = $this->getDocuments($category, $entry_id);
        
        // show single
        if ($entry_id) {
            if ($item = $items [$entry_id]) {
                // echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';
                
                // info
                if ($item [1]->persons_id > 0) {
                    $person = $this->_getPerson($item [1]->persons_id);
                }
                    // echo __FILE__.__LINE__.'<pre>$person='.htmlentities(print_r($person,1)).'</pre>';
                
                if ($item [1]->relatedVillageId > 0) {
                    $village = $this->_getVillage($item [1]->relatedVillageId);
                }
                    // echo __FILE__.__LINE__.'<pre>$village='.htmlentities(print_r($village,1)).'</pre>';
                
                $images = $this->_getImage($item [2]->language01, true);
                // echo __FILE__.__LINE__.'<pre>$images='.htmlentities(print_r($images,1)).'</pre>';
                $gallery = $this->_getImage2($item);
                $audio = $this->_getMedia($item);
                // echo __FILE__.__LINE__.'<pre>$audio='.htmlentities(print_r($audio,1)).'</pre>';
                // /echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';die;
                
                return view('admin.import.view')->with('title', strtoupper($category))->with('entry_id', $entry_id)->with('person', @$person)->with('village', @$village)->with('gallery', @$gallery)->with('audio', @$audio)->with('item', $item);
            }
        }
        
        // show all
        return view('admin.import.index')->with('title', $category)->with('i', 0)->with('items', $items);
    }
    private function _getMedia($items)
    {
        $result = false;
        foreach ($items as $i => $item) {
            if ($item->audioPath) {
                $result [] = sprintf('<a href="http://www.kythera-family.net/%s" alt="%s">%s</a>', $item->audioPath, @$items [1]->language01, @$items [1]->language01);
            }
        }
        if (is_array($result)) {
            $result = array_unique($result);
        }
        return $result;
    }
    private function _getImage2($items)
    {
        $result = false;
        foreach ($items as $i => $item) {
            if ($item->imagePath) {
                $result [] = sprintf('<img src="http://www.kythera-family.net/%s" alt="%s" />', $item->imagePath, @$items [1]->language01);
            }
        }
        if (is_array($result)) {
            $result = array_unique($result);
        }
        return $result;
    }
    private function _getImage(&$text, $replace = false)
    {
        // [[picture:"1.png" ID:18793]]
        $result = false;
        // if (preg_match_all('#\[\[picture:"([^"]+)" ID:([0-9]+)]]#', $s, $matches, PREG_SET_ORDER)) {
        if (preg_match_all('#\[\[picture:"([^"]+)" ID:([0-9]+)]]#', $text, $matches)) {
            if ($items = DB::connection('import')->table('imagedocuments')->whereIn('entry_id', $matches [2])->get()) {
                $replaces =  [];
                foreach ($items as $item) {
                    $result [$item->entry_id] = $item;
                    $replaces [] = sprintf('<img src="http://www.kythera-family.net/%s%s" alt="%s" />', $item->image_path, $item->image_name, pathinfo($item->original_image_name, PATHINFO_FILENAME));
                }
            }
            
            // replace tags with images, by reference!
            if ($replace) {
                $text = str_ireplace($matches [0], $replaces, $text);
            }
        }
        return $result;
    }
    private function _getVillage($relatedVillageId)
    {
        $result = new stdClass();
        
        // info
        $result->info = DB::connection('import')->table('villages')->leftJoin('village_properties', 'villages.id', '=', 'village_properties.village_id')->where('villages.id', $relatedVillageId)->first();
        
        // compounds
        // select * from villages V left join village_compounds C on V.id=C.village1_id left join villages V2 on V2.id=C.village2_id where V.id=166;
        $items = DB::connection('import')->table('villages as V')->join('village_compounds as C', 'V.id', '=', 'C.village1_id')->join('villages as V2', 'V2.id', '=', 'C.village2_id')->where('V.id', $relatedVillageId)->get();
        // aggregate
        foreach ($items as $item) {
            $result->compounds [$item->character_set_id] [] = $item;
        }
        
        return $result;
    }
    private function _getPerson($persons_id)
    {
        $result = DB::connection('import')->table('users')->leftJoin('names', 'users.persons_id', '=', 'names.persons_id')->where('users.persons_id', $persons_id)->first();
        return $result;
    }
    private function getDocuments($category = 'PEOPLE_LIFESTORIES', $id = false)
    {
        $result =  [];
        if ($items = $this->_getDocuments($category)) {
            foreach ($items as $item) {
                if (! $id) {
                    $item->language01 = Str::words($item->language01, 100);
                }
                    // 22.06.14
                if (preg_match('/([0-9]+).([0-9]+).([0-9]+)/', $item->lastchange, $m)) {
                    $item->date = Carbon::createFromDate(( int ) '20' . $m [3], $m [2], $m [1]);
                }
                $result [$item->entry_id] [$item->id] = $item;
            }
            unset($items);
        }
        return $result;
    }
    private function _getDocuments($category = 'people_lifestories', $limit = 10000)
    {
        /*
		 * ACADEMICS_EIGHT
		 * ACADEMICS_FIVE
		 * ACADEMICS_FOUR
		 * ACADEMICS_NINE
		 * ACADEMICS_ONE
		 * ACADEMICS_SEVEN
		 * ACADEMICS_SIX
		 * ACADEMICS_TEN
		 * ACADEMICS_THREE
		 * ACADEMICS_TWO
		 * ADDRESSES
		 * ARCHEOLOGY
		 * ARCHITECTURE
		 * ARCHIVE
		 * ASSOCIATIONS
		 * ASSOCIATION_DOCUMENTS
		 * AUDIO_INTERVIEWDIASPORA
		 * AUDIO_INTERVIEW_ISLAND
		 * AUDIO_KYTHERIANMUSIC
		 * AUDIO_SOUNDSOFNATURE
		 * BIBLIOGRAPHY
		 * BIRD_IMAGES
		 * BLOG
		 * BOOKS_ABOUT_US
		 * CALENDAR OF EVENTS2
		 * churchesDiaspora
		 * COMMUNITY_ISLANDINFOS
		 * CULTURAL_KNOWLEDGE
		 * CULTUREKYTHERIANIDENTITY
		 * DOCUMENTS
		 * ENVIRONMENT
		 * FAMILY_TREE_PERSON
		 * FAMOUS_PEOPLE
		 * FISH_IMAGES
		 * FLOWER_IMAGES
		 * FOOD_AND_RECIPES
		 * FOSSIL_IMAGES
		 * GENEALOGICAL_RESEARCH
		 * GENERAL_HISTORY
		 * GRAVESTONES
		 * GRAVESTONES2_KARAVAS
		 * GRAVESTONES_AAF
		 * GRAVESTONES_AGDESPINA
		 * GRAVESTONES_DRYMONAS
		 * GRAVESTONES_FRILIGIANIKA
		 * GRAVESTONES_HORA
		 * GRAVESTONES_KAPSALI
		 * GRAVESTONES_KARAVAS
		 * GRAVESTONES_MITATA
		 * GRAVESTONES_POTAMOS
		 * GRAVES_AGANASTASIA
		 * GRAVES_AGTHEO
		 * GRAVES_ALEXANDRATHES
		 * GRAVES_ALEXANDRATHES2
		 * GRAVES_ARONIATHIKA
		 * GRAVES_AUSTRALIA
		 * GRAVES_GERAKARI
		 * GRAVES_GOUTHIANIKA
		 * GRAVES_KERAMOTO
		 * GRAVES_LIVATHI
		 * GRAVES_LOGOTHETIANIKA
		 * GRAVES_PITSINIANIKA
		 * GRAVES_TRYFILL
		 * GRAVES_USA
		 * GRAVES_USA2
		 * GreatWalls2
		 * GUEST_BOOK
		 * HISTORY_ARTEFACTS
		 * HOME_REMEDIES
		 * INSECT_ AND_KIN_IMAGES
		 * ISLAND_NEWS
		 * KCA:WAR
		 * KCA_Activities
		 * KCA_NEWSACTIVITIES
		 * KCA_PHOTO_CATEGORY_ONE
		 * KCA_PHOTO_CATEGORY_THREE
		 * KCA_PHOTO_CATEGORY_TWO
		 * KYTHERA_PHOTOGRAPHIC_ARCHIVE
		 * KYTHERIAN BUSINESS GUIDE2
		 * KYTHERIAN_ARTISTS
		 * MAMMAL_IMAGES
		 * MARINE_MISCELLANY_IMAGES
		 * MESSAGE_BOARD
		 * MESSAGE_BOARD2
		 * MODERN_MAPS
		 * museumsgalleries
		 * MYTHS_AND_LEGENDS
		 * NEWS-ARCHIVE
		 * NICKNAMES
		 * OLD_LETTERS
		 * OLD_MAPS
		 * OLD_PHOTOS
		 * ORAL_HISTORY
		 * PEOPLE_LIFESTORIES
		 * PEOPLE_OBITUARIES
		 * PEOPLE_SURNAMEBOOK
		 * PHOTOGRAPHY_ARCHITECTURE
		 * PHOTOGRAPHY_CAFES_AND_SHOPS
		 * PHOTOGRAPHY_CHURCHES
		 * PHOTOGRAPHY_DIASPORA_KYTHERIAN_ART
		 * PHOTOGRAPHY_DIASPORA_Miscellaneous
		 * PHOTOGRAPHY_DIASPORA_SOCIAL_LIFE
		 * PHOTOGRAPHY_DIASPORA_SPORTING_LIFE
		 * PHOTOGRAPHY_DIASPORA_VINTAGE_PEOPLE
		 * PHOTOGRAPHY_DIASPORA_WEDDINGS_AND_PROXENIA
		 * PHOTOGRAPHY_DIASPORA_WORKING_LIFE
		 * PHOTOGRAPHY_ISLAND_Miscellaneous
		 * PHOTOGRAPHY_ISLAND_SOCIAL_LIFE
		 * PHOTOGRAPHY_ISLAND_VINTAGE_PEOPLE
		 * PHOTOGRAPHY_ISLAND_WEDDINGS_AND_PROXENIA
		 * PHOTOGRAPHY_ISLAND_WORKING_LIFE
		 * PHOTOGRAPHY_MODERN_LANDSCAPES
		 * PHOTOGRAPHY_MODERN_PORTRAITS
		 * PHOTOGRAPHY_NATURE
		 * PHOTOGRAPHY_SCHOOL_PHOTOS
		 * PHOTOGRAPHY_SIGNS_AND_STATUES
		 * PHOTOGRAPHY_VINTAGE_LANDSCAPES
		 * REAL_ESTATE_HOLIDAY
		 * REAL_ESTATE_HOUSES
		 * REAL_ESTATE_HOWTOPOST
		 * REAL_ESTATE_LAND
		 * REAL_ESTATE_SEARCHproperty
		 * REAL_ESTATE_search_holiday
		 * RELIGION
		 * REPTILE_AND_AMPHIBIAN_IMAGES
		 * ROCKS_IMAGES
		 * SEASHELL_IMAGES
		 * SEASHELL_IMAGES_2
		 * SEASHELL_IMAGES_3
		 * SIGHTSEEING
		 * SONGS_AND_POEMS
		 * SPARE
		 * STORIES
		 * THE_FATSEAS_ARCHIVE
		 * TOURIST_ONLINE
		 * VINTAGE_FILMS
		 * WHERE_TO_EAT
		 * WHERE_TO_STAY
		 * WINDFARM
		 * WINDFARM_DOCS
		 * WINDFARM_INTERVIEW
		 * WINDFARM_PHOTOS
		 */
        
        // $result = Cache::remember('documents.'.$category, 1, function() use ($category, $limit) {
        return DB::connection('import')->select('
                SELECT documents.id AS entry_id,
                       documents.persons_id AS persons_id,
                       documents.enabled AS documentEnabled,
                       documents.top_article AS isTopArticle,
                       Date_format(documents.created, "%d.%m.%y") AS created,
                       Date_format(documents.lastchange, "%d.%m.%y") AS lastchange,
                       documents.related_village_id AS relatedVillageId,
                       document_types.string_id AS docType,
                       document_types.id AS docTypeId,
                       document_types.label AS docTypeLabelId,
                       document_types.controller AS controller,
                       Concat(imagedocuments.image_path, "thumb_", imagedocuments.image_name) AS imageThumb,
                       Concat(imagedocuments.image_path, "mediumsize_",
                       imagedocuments.image_name) AS imageMedium,
                       Concat(imagedocuments.image_path, imagedocuments.image_name) AS imagePath,
                       Concat(audiodocuments.audio_path, audiodocuments.audio_name) AS audioPath,
                       Date_format(imagedocuments.taken, "%d.%m.%y") AS photoTaken,
                       Date_format(audiodocuments.recorded, "%d.%m.%y") AS audioRecorded,
                       IF (messageboard2.parent_id IS NOT NULL, messageboard2.parent_id, messageboard.parent_id) AS messageBoardParentId,
                       famous_people.persons_id AS famousPersonId,
                        
                       IF (family_tree_persons.id IS NOT NULL, family_tree_persons.hide, 0) AS family_tree_personsHide,
                       IF (family_tree_persons.id IS NOT NULL, family_tree_persons.id, 0) AS family_tree_personsId,

                       /*should we want to include this*/
                       /*P.hide AS personHidden,*/
                        
                       IF (dicTitle.language01 IS NOT NULL, dicTitle.language01, "no title") AS Title,
                       IF (dicTitle.language03 IS NOT NULL, dicTitle.language03, "no title") AS Title03,
                        
                       IF (documents.id <= 5000, document_dictionary1.id,
                       IF (documents.id <= 10000, document_dictionary2.id,
                       IF (documents.id <= 15000, document_dictionary3.id,
                       IF (documents.id <= 20000, document_dictionary4.id, document_dictionary5.id)))) AS id,
                        
                       IF (documents.id <= 5000, document_dictionary1.language01,
                       IF (documents.id <= 10000, document_dictionary2.language01,
                       IF (documents.id <= 15000, document_dictionary3.language01,
                       IF (documents.id <= 20000, document_dictionary4.language01, document_dictionary5.language01)))) AS language01,
                        
                       IF (documents.id <= 5000, document_dictionary1.language03,
                       IF (documents.id <= 10000, document_dictionary2.language03,
                       IF (documents.id <= 15000, document_dictionary3.language03,
                       IF (documents.id <= 20000, document_dictionary4.language03, document_dictionary5.language03)))) AS language03
                FROM   document_types
                       LEFT JOIN documents
                              ON document_types.id = documents.document_type_id
                       LEFT JOIN imagedocuments
                              ON documents.id = imagedocuments.entry_id
                       LEFT JOIN audiodocuments
                              ON documents.id = audiodocuments.entry_id
                       LEFT JOIN messageboard
                              ON documents.id = messageboard.documents_id
                       LEFT JOIN messageboard2
                              ON documents.id = messageboard2.documents_id
                       LEFT JOIN famous_people
                              ON documents.id = famous_people.documents_id
                       LEFT JOIN letters
                              ON documents.id = letters.document_id
                       LEFT JOIN individuum
                              ON documents.id = individuum.entry_id
                       LEFT JOIN persons AS family_tree_persons
                              ON individuum.persons_id = family_tree_persons.id

                       /*inserted for exclude hidden persons*/
                       /*
                       LEFT JOIN persons AS P
                              ON documents.persons_id = P.id*/
                        
                       LEFT JOIN document_dictionary1 AS dicTitle
                              ON imagedocuments.entry_id = dicTitle.entry_id
                                 AND dicTitle.id = 1
                       LEFT JOIN document_dictionary1
                              ON documents.id = document_dictionary1.entry_id
                       LEFT JOIN document_dictionary2
                              ON documents.id = document_dictionary2.entry_id
                       LEFT JOIN document_dictionary3
                              ON documents.id = document_dictionary3.entry_id
                       LEFT JOIN document_dictionary4
                              ON documents.id = document_dictionary4.entry_id
                       LEFT JOIN document_dictionary5
                              ON documents.id = document_dictionary5.entry_id
                WHERE  ( document_types.string_id = "' . $category . '" )
                        
                        /*allow disabled docs too*/
                        /*AND documents.enabled > 0*/
                                               
                /* HAVING */
                        /*disabled to include only greek versions too*/
                        /*( language01 IS NOT NULL ) AND*/
                        
                        /*disabled to include hidden persons too (NOT working anyway)*/
                        /*( family_tree_personshide = 0 )*/
                
                        /*HAVING personHidden=1*/
                        
                ORDER  BY
                        /*change default order*/
                        documents.created DESC, documents.id DESC /*documents.created DESC*/
                        
                LIMIT ' . $limit);
        // });
        
        return $result;
    }
}
