<?php

namespace App\Classes;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
+--------------------------------------------+-----+
| string_id                                  | id  |
+--------------------------------------------+-----+
| ACADEMICS_EIGHT                            |  77 |
| ACADEMICS_FIVE                             |  74 |
| ACADEMICS_FOUR                             |  73 |
| ACADEMICS_NINE                             |  78 |
| ACADEMICS_ONE                              |  70 |
| ACADEMICS_SEVEN                            |  76 |
| ACADEMICS_SIX                              |  75 |
| ACADEMICS_TEN                              |  79 |
| ACADEMICS_THREE                            |  72 |
| ACADEMICS_TWO                              |  71 |
| ADDRESSES                                  |  27 |
| ARCHEOLOGY                                 |  64 |
| ARCHITECTURE                               |  13 |
| ARCHIVE                                    |  19 |
| ASSOCIATIONS                               |  15 |
| ASSOCIATION_DOCUMENTS                      |  29 |
| AUDIO_INTERVIEWDIASPORA                    |  93 |
| AUDIO_INTERVIEW_ISLAND                     |  94 |
| AUDIO_KYTHERIANMUSIC                       |  92 |
| AUDIO_SOUNDSOFNATURE                       |  91 |
| BIBLIOGRAPHY                               |  33 |
| BIRD_IMAGES                                |  34 |
| BLOG                                       | 127 |
| BOOKS_ABOUT_US                             |  10 |
| CALENDAR OF EVENTS2                        |  86 |
| churchesDiaspora                           | 145 |
| COMMUNITY_ISLANDINFOS                      |  98 |
| CULTURAL_KNOWLEDGE                         |   6 |
| CULTUREKYTHERIANIDENTITY                   |  89 |
| DOCUMENTS                                  |  20 |
| ENVIRONMENT                                |  14 |
| FAMILY_TREE_PERSON                         |  63 |
| FAMOUS_PEOPLE                              |  21 |
| FISH_IMAGES                                |  35 |
| FLOWER_IMAGES                              |  36 |
| FOOD_AND_RECIPES                           |  12 |
| FOSSIL_IMAGES                              |  37 |
| GENEALOGICAL_RESEARCH                      |  22 |
| GENERAL_HISTORY                            |   3 |
| GRAVESTONES                                |  43 |
| GRAVESTONES2_KARAVAS                       | 105 |
| GRAVESTONES_AAF                            | 142 |
| GRAVESTONES_AGDESPINA                      | 112 |
| GRAVESTONES_DRYMONAS                       | 109 |
| GRAVESTONES_FRILIGIANIKA                   | 107 |
| GRAVESTONES_HORA                           | 110 |
| GRAVESTONES_KAPSALI                        | 111 |
| GRAVESTONES_KARAVAS                        | 104 |
| GRAVESTONES_MITATA                         | 106 |
| GRAVESTONES_POTAMOS                        | 108 |
| GRAVES_AGANASTASIA                         | 125 |
| GRAVES_AGTHEO                              | 118 |
| GRAVES_ALEXANDRATHES                       | 121 |
| GRAVES_ALEXANDRATHES2                      | 122 |
| GRAVES_ARONIATHIKA                         | 119 |
| GRAVES_AUSTRALIA                           | 116 |
| GRAVES_GERAKARI                            | 123 |
| GRAVES_GOUTHIANIKA                         | 113 |
| GRAVES_KERAMOTO                            | 124 |
| GRAVES_LIVATHI                             | 120 |
| GRAVES_LOGOTHETIANIKA                      | 103 |
| GRAVES_PITSINIANIKA                        | 126 |
| GRAVES_TRYFILL                             | 117 |
| GRAVES_USA                                 | 114 |
| GRAVES_USA2                                | 115 |
| GreatWalls2                                | 141 |
| GUEST_BOOK                                 |  23 |
| HISTORY_ARTEFACTS                          | 101 |
| HOME_REMEDIES                              |   5 |
| INSECT_ AND_KIN_IMAGES                     |  38 |
| ISLAND_NEWS                                | 100 |
| KCA:WAR                                    |  95 |
| KCA_Activities                             |  99 |
| KCA_NEWSACTIVITIES                         |  97 |
| KCA_PHOTO_CATEGORY_ONE                     |  80 |
| KCA_PHOTO_CATEGORY_THREE                   |  82 |
| KCA_PHOTO_CATEGORY_TWO                     |  81 |
| KYTHERA_PHOTOGRAPHIC_ARCHIVE               |  30 |
| KYTHERIAN BUSINESS GUIDE2                  |  85 |
| KYTHERIAN_ARTISTS                          |  65 |
| MAMMAL_IMAGES                              |  39 |
| MARINE_MISCELLANY_IMAGES                   |  41 |
| MESSAGE_BOARD                              |  18 |
| MESSAGE_BOARD2                             |  84 |
| MODERN_MAPS                                |  28 |
| museumsgalleries                           | 143 |
| MYTHS_AND_LEGENDS                          |   4 |
| NEWS-ARCHIVE                               | 128 |
| NICKNAMES                                  |  32 |
| OLD_LETTERS                                |   7 |
| OLD_MAPS                                   |  17 |
| OLD_PHOTOS                                 |  16 |
| ORAL_HISTORY                               |   2 |
| PEOPLE_LIFESTORIES                         |  87 |
| PEOPLE_OBITUARIES                          |  88 |
| PEOPLE_SURNAMEBOOK                         |  90 |
| PHOTOGRAPHY_ARCHITECTURE                   |  46 |
| PHOTOGRAPHY_CAFES_AND_SHOPS                |  59 |
| PHOTOGRAPHY_CHURCHES                       |  47 |
| PHOTOGRAPHY_DIASPORA_KYTHERIAN_ART         |  66 |
| PHOTOGRAPHY_DIASPORA_Miscellaneous         |  62 |
| PHOTOGRAPHY_DIASPORA_SOCIAL_LIFE           |  60 |
| PHOTOGRAPHY_DIASPORA_SPORTING_LIFE         |  69 |
| PHOTOGRAPHY_DIASPORA_VINTAGE_PEOPLE        |  58 |
| PHOTOGRAPHY_DIASPORA_WEDDINGS_AND_PROXENIA |  68 |
| PHOTOGRAPHY_DIASPORA_WORKING_LIFE          |  61 |
| PHOTOGRAPHY_ISLAND_Miscellaneous           |  57 |
| PHOTOGRAPHY_ISLAND_SOCIAL_LIFE             |  54 |
| PHOTOGRAPHY_ISLAND_VINTAGE_PEOPLE          |  52 |
| PHOTOGRAPHY_ISLAND_WEDDINGS_AND_PROXENIA   |  67 |
| PHOTOGRAPHY_ISLAND_WORKING_LIFE            |  56 |
| PHOTOGRAPHY_MODERN_LANDSCAPES              |  48 |
| PHOTOGRAPHY_MODERN_PORTRAITS               |  49 |
| PHOTOGRAPHY_NATURE                         |  50 |
| PHOTOGRAPHY_SCHOOL_PHOTOS                  |  55 |
| PHOTOGRAPHY_SIGNS_AND_STATUES              |  53 |
| PHOTOGRAPHY_VINTAGE_LANDSCAPES             |  51 |
| REAL_ESTATE_HOLIDAY                        | 136 |
| REAL_ESTATE_HOUSES                         | 134 |
| REAL_ESTATE_HOWTOPOST                      | 133 |
| REAL_ESTATE_LAND                           | 135 |
| REAL_ESTATE_SEARCHproperty                 | 137 |
| REAL_ESTATE_search_holiday                 | 138 |
| RELIGION                                   |  11 |
| REPTILE_AND_AMPHIBIAN_IMAGES               |  40 |
| ROCKS_IMAGES                               |  83 |
| SEASHELL_IMAGES                            |  42 |
| SEASHELL_IMAGES_2                          |  44 |
| SEASHELL_IMAGES_3                          |  45 |
| SIGHTSEEING                                |  26 |
| SONGS_AND_POEMS                            |   8 |
| SPARE                                      |  96 |
| STORIES                                    |   9 |
| THE_FATSEAS_ARCHIVE                        |  31 |
| TOURIST_ONLINE                             | 129 |
| VINTAGE_FILMS                              | 102 |
| WHERE_TO_EAT                               |  25 |
| WHERE_TO_STAY                              |  24 |
| WINDFARM                                   | 130 |
| WINDFARM_DOCS                              | 132 |
| WINDFARM_INTERVIEW                         | 139 |
| WINDFARM_PHOTOS                            | 131 |
+--------------------------------------------+-----+
*/


class Import
{

    public static function getDocuments($category = 'PEOPLE_LIFESTORIES', $limit = 10000, $dump = false)
    {
        $result = [];
        if ($items = static::_getDocuments($category, $limit, $dump)) {
            foreach ($items as $item) {
                //if (!$id)
                //$item->language01 = Str::words($item->language01, 100);
                //22.06.14
                if (preg_match('/([0-9]+).([0-9]+).([0-9]+)/', $item->lastchange, $m)) {
                    //$item->date = Carbon::createFromDate((int)'20'.$m[3], $m[2], $m[1]);
                    $item->date = Carbon::create((int)'20'.$m[3], $m[2], $m[1], 0, 0, 0);
                }
                $result[$item->entry_id][$item->id] = $item;
            }
            unset($items);
        }
        return $result;
    }


    public static function extractAnchors2($item)
    {
        $item[2]->language01 = static::extractAnchor($item[2]->language01);
        $item[2]->language03 = static::extractAnchor($item[2]->language03);
        return $item;
    }

    public static function extractAnchor($text)
    {
        /*
	    update
	    document_attributes
	    set
	    value=replace(value, 'http://kythera.laravel.debian.mirror.virtec.org/', 'http://dev.kythera-family.net/')
	    where
	    `key`='content'
        and value like '%http://kythera.laravel.debian.mirror.virtec.org/%'
        */

        if ($text) {
            $parsed = [];
            if (preg_match_all('#<a\s+(?:[^>]*?\s+)?href="([^"]*)"#', $text, $m1)) {
                foreach ($m1[1] as $i => $href) {
                    $href = html_entity_decode($href);
                    $vars = parse_url($href, PHP_URL_QUERY);

                    if (($vars) && ($vars = explode('&', $vars))) {
                        //with GET
                        foreach ($vars as $var) {
                            if ($values = explode('=', $var)) {
                                $get[$values[0]] = $values[1];
                            }
                        }
                        $parsed[] = $get;
                    } else {
                        //no GET
                        $parsed[] = '';
                    }
                }
                foreach ($parsed as $i => $values) {
                    if (isset($values['nav'])) {
                        switch ($values['nav']) {
                            case 9:
                                $replaces[$i] = action('RedirectController@index', $values);
                                break;
                            default:
                                $replaces[$i] = action('RedirectController@index', $values);
                        }
                    } else {
                        //external links
                        $replaces[$i] = $m1[1][$i];
                    }
                }
                $text = str_replace($m1[1], $replaces, $text);
            }
        }
        return $text;
    }

    public static function extractDownloads($item)
    {

        if (isset($item[2]->language01)) {
            $item[2]->language01 = str_ireplace(['http://www.kythera-family.net/download/'], ['/download/'], $item[2]->language01);
        }
        if (isset($item[2]->language03)) {
            $item[2]->language03 = str_ireplace(['http://www.kythera-family.net/download/'], ['/download/'], $item[2]->language03);
        }
        return $item;
    }

    public static function extractImages($document, $replace = false)
    {
        //[[picture:"1.png" ID:18793]]
        $result = false;

        //en
        $text = $document[2]->language01;
        if (preg_match_all('#\[\[picture:"([^"]+)" ID:([0-9]+)]]#', $text, $matches)) {
            if ($items = DB::connection('import')
                        ->table('imagedocuments')
                        ->whereIn('entry_id', $matches[2])
                        ->get()) {
                $replaces = [];
                foreach ($items as $item) {
                    $result[$item->entry_id] = $item;
                    //$replaces[] = sprintf('<img src="http://www.kythera-family.net/%s%s" alt="%s" />', $item->image_path, $item->image_name, pathinfo($item->original_image_name, PATHINFO_FILENAME));
                    $replaces[] = sprintf('<img src="/%s%s" alt="%s" />', $item->image_path, $item->image_name, pathinfo($item->original_image_name, PATHINFO_FILENAME));
                }
            }

            #replace tags with images, by reference!
            if ($replace) {
                $text = str_ireplace($matches[0], $replaces, $text);
                $document[2]->language01 = $text;
                print(sprintf('    Replacing %d images in EN.
                ', count($replaces)).chr(13).chr(10));
            }
        }

        //gr
        $text = $document[2]->language03;
        if (preg_match_all('#\[\[picture:"([^"]+)" ID:([0-9]+)]]#', $text, $matches)) {
            if ($items = DB::connection('import')
                        ->table('imagedocuments')
                        ->whereIn('entry_id', $matches[2])
                        ->get()) {
                $replaces = [];
                foreach ($items as $item) {
                    $result[$item->entry_id] = $item;
                    //$replaces[] = sprintf('<img src="http://www.kythera-family.net/%s%s" alt="%s" />', $item->image_path, $item->image_name, pathinfo($item->original_image_name, PATHINFO_FILENAME));
                    $replaces[] = sprintf('<img src="/%s%s" alt="%s" />', $item->image_path, $item->image_name, pathinfo($item->original_image_name, PATHINFO_FILENAME));
                }
            }

            #replace tags with images, by reference!
            if ($replace) {
                $text = str_ireplace($matches[0], $replaces, $text);
                $document[2]->language03 = $text;
                print(sprintf('    Replacing %d images in GR.
                ', count($replaces)).chr(13).chr(10));
            }
        }

        if ($replace) {
            return $document;
        } else {
            return $result;
        }
    }



    public static function _getDocuments($category = 'people_lifestories', $limit = 10000, $dump = false)
    {
        $query =
                '
                SELECT documents.id AS entry_id,
                       documents.persons_id AS persons_id,
                       documents.enabled AS documentEnabled,
                       documents.top_article AS isTopArticle,
                       Date_format(documents.created, "%d.%m.%y") AS created,
                       Date_format(documents.lastchange, "%d.%m.%y") AS lastchange,

                       /*original datetime*/
                       documents.created AS org_created,
                       documents.lastchange AS org_lastchange,

                       documents.related_village_id AS relatedVillageId,
                       document_types.string_id AS docType,
                       document_types.id AS docTypeId,
                       document_types.label AS docTypeLabelId,
                       document_types.controller AS controller,

                       /*images*/
                       Concat(imagedocuments.image_path, "thumb_", imagedocuments.image_name) AS imageThumb,
                       Concat(imagedocuments.image_path, "mediumsize_",
                       imagedocuments.image_name) AS imageMedium,
                       Concat(imagedocuments.image_path, imagedocuments.image_name) AS imagePath,
                       Date_format(imagedocuments.taken, "%d.%m.%y") AS photoTaken,

                       imagedocuments.image_name as import_image_name,
                       imagedocuments.image_path as import_image_path,
                       imagedocuments.original_image_name as import_image_original,
                       imagedocuments.taken as import_image_taken,

                       Concat(audiodocuments.audio_path, audiodocuments.audio_name) AS audioPath,
                       Date_format(audiodocuments.recorded, "%d.%m.%y") AS audioRecorded,

                       audiodocuments.audio_name as import_audio_name,
                       audiodocuments.audio_path as import_audio_path,
                       audiodocuments.recorded as import_audio_taken,

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
                       IF (documents.id <= 20000, document_dictionary4.id, document_dictionary5.id))))
                          AS id,

                       IF (documents.id <= 5000, document_dictionary1.language01,
                       IF (documents.id <= 10000, document_dictionary2.language01,
                       IF (documents.id <= 15000, document_dictionary3.language01,
                       IF (documents.id <= 20000, document_dictionary4.language01, document_dictionary5.language01))))
                          AS language01,

                       IF (documents.id <= 5000, document_dictionary1.language02,
                       IF (documents.id <= 10000, document_dictionary2.language02,
                       IF (documents.id <= 15000, document_dictionary3.language02,
                       IF (documents.id <= 20000, document_dictionary4.language02, document_dictionary5.language02))))
                          AS language02,

                       IF (documents.id <= 5000, document_dictionary1.language03,
                       IF (documents.id <= 10000, document_dictionary2.language03,
                       IF (documents.id <= 15000, document_dictionary3.language03,
                       IF (documents.id <= 20000, document_dictionary4.language03, document_dictionary5.language03))))
                          AS language03
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
                WHERE  ( document_types.string_id = "'.$category.'" )

                    /*allow disabled docs too*/
                    /*AND documents.enabled > 0*/

                	AND documents.id IS NOT NULL
                /* HAVING */
                        /*disabled to include only greek versions too*/
                        /*( language01 IS NOT NULL ) AND*/

                        /*disabled to include hidden persons too (NOT working anyway)*/
                        /*( family_tree_personshide = 0 )*/

                        /*HAVING personHidden=1*/

                ORDER  BY
                    /*change default order*/
                    documents.created DESC
                LIMIT ' . $limit . ';
                        ';

        if ($dump) {
            echo $query;
        }

        $items = DB::connection('import')
                ->select($query);

        return $items;
    }


    public static function navigation($parent = null)
    {
        $result = [];

        if (is_null($parent)) {
            $items = DB::connection('import')->select(DB::raw('
    	            select distinct(N.id), T.controller, language01, document_type_id, T.string_id  from navigation_tree N
    	            left join
    	                system_dictionary S on N.label=S.id
    	            left join
    	                categories C on N.id=C.parent_id
    	            left join
    	                document_types T on C.document_type_id=T.id
    	            where
    	                parent=0 and N.enabled>0 and permission=1
    	            order by
    	                N.sort;'));
        } else {
            $items = DB::connection('import')->select(DB::raw('
    	            select distinct(N.id), T.controller, language01, document_type_id, T.string_id  from navigation_tree N
	                left join
    	                system_dictionary S on N.label=S.id
    	            left join
    	                categories C on N.id=C.parent_id
    	            left join
    	                document_types T on C.document_type_id=T.id
    	            where
    	                parent='.$parent->id.' and N.enabled>0 and permission=1
    	            order by
    	                N.sort;'));
        }

        foreach ($items as $item) {
            $result[] = $item;
            $children = DB::connection('import')->select(DB::raw('select count(*) n from navigation_tree where enabled=1 and permission=1 and parent='.$item->id));
            $children = is_array($children) ? current($children)->n : 0;
            if ($children) {
                $item->children = static::navigation($item);
            }
        }

        return $result;
    }


    public static function find($items = null, $id)
    {
        $result = false;
        $items = $items ? $items : static::navigation();
        foreach ($items as $item) {
            if ($item->id == $id) {
                return $item;
            }

            if (isset($item->children)) {
                $result = static::find($item->children, $id);
                if ($result) {
                    return $result;
                }
            }
        }
        return $result;
    }


    public static function deleteOriginals($entry_ids)
    {
        if (count($entry_ids)) {
            echo "    Deleting original records..".chr(13).chr(10);
            foreach ($entry_ids as $id) {
                DB::connection('import')->table('documents')->delete($id);
                //print(sprintf('        Deleting original entry: %d', $id)).chr(13).chr(10);
            }
            echo "    Done.".chr(13).chr(10);
        }
    }

    /*

	public static function copy($src, $dst, $drop = false) {
		if ($drop) {
			DB::statement('drop table if exists ?;', array($dst));
		} else {
			DB::table($dst)->truncate();
		}

		switch($src) {
			case 'import_kythera.villages':
				$q = "
				CREATE TABLE IF NOT EXISTS `villages` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `village_name` varchar(255) NOT NULL DEFAULT '',
				  `character_set_id` varchar(50) NOT NULL DEFAULT '',
				  `visible` int(1) DEFAULT '1',
				  `lost` int(1) DEFAULT '0',
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `id` (`id`),
				  UNIQUE KEY `name` (`village_name`,`character_set_id`)
				) ENGINE=Innodb DEFAULT CHARSET=utf8;";
				DB::statement($q);
				$q = "
				INSERT IGNORE INTO ".$dst." SELECT * FROM $src";
				DB::statement($q);
			break;
		}


	}
	*/
}
