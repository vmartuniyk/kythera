<?php
namespace Kythera\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use \Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FamilyTreeException extends \Exception {}

/*
 * #vm
 * The field persons.hide (integer) can be 1 or 0 in all queries.
 * Since it's not clear what it's meaning is we ignore it for now (to mimic old site as much as possible).
 *
 */
class FamilyTree extends Eloquent
{

    public $timestamps = false;

    protected $table = "namebox_entries";

    protected $fillable = array();

    public static $rules =  array();

    public function slug()
    {
    	return Str::slug($this->lastname);
    }


    public static function getAlphabet()
    {
        $result = array();
        if ($items = DB::select(DB::raw(sprintf('
			SELECT SQL_CACHE
			    DISTINCT(UCASE(SUBSTRING(lastname,1,1))) as a,
			    names.character_set_id FROM languages,
			    names USE INDEX(PRIMARY)
			RIGHT JOIN
				individuum USE INDEX(PRIMARY) ON names.persons_id = individuum.persons_id
			LEFT JOIN
				persons ON individuum.persons_id = persons.id
			LEFT JOIN
				document_entities ON individuum.entry_id = document_entities.id
			WHERE
				document_entities.enabled = 1
			    AND (names.character_set_id = "latin" OR names.character_set_id = "")
			    AND %s
			    AND `lastname` <> ""
			    AND languages.id = %d
			ORDER BY
			    lastname;
   	        ',  Auth::check() ? 'true' : 'persons.hide = 0',
        		App::getLocale() == 'en' ? 1 : 3 )))) {
        	        foreach ($items as $item) {
        	        	//if (empty(trim($item->a)))
        	            $result[] = $item->a;
        	        }
        }
        return $result;
    }


    public static function getFamilyName($familyName)
    {
    	$result = array();
    	if ($items = DB::select(DB::raw(sprintf('
			SELECT
			    DISTINCT CONCAT(IFNULL(names.firstname,""), " ",
    			IFNULL(names.middlename,"")) AS fullname,
    			persons.id, /*VM*/
			    entry_id AS did,
			    /*categories.id AS cid,*/
			    /*navigation_tree.id AS nav,*/
			    persons.date_of_birth,
			    persons.date_of_death
			FROM
			    individuum
			LEFT JOIN
			    names ON individuum.persons_id = names.persons_id
			LEFT JOIN
			    persons ON individuum.persons_id = persons.id
			LEFT JOIN
			    document_entities ON individuum.entry_id = document_entities.id
			    /*
			LEFT JOIN
			    document_types ON documents.document_type_id = document_types.id
			    */
			    /*
			LEFT JOIN
			    categories ON documents.document_type_id = categories.document_type_id AND document_types.label = categories.label
			    */
			    /*
			LEFT JOIN
			    navigation_tree ON document_types.label = navigation_tree.label
			    */
			WHERE
			    document_entities.enabled = 1
			    AND names.lastname = "%s"
			    AND %s
			ORDER BY
			    names.firstname
   	        ', $familyName->name, Auth::check() ? 'true' : 'persons.hide = 0')))) {
   	        	$result = $items;
    	}
    	return $result;
    }


    public static function getFamilyNames($letter, $familyNameId)
    {
    	$result = array();
    	if ($items = DB::select(DB::raw(sprintf('
			SELECT
			    SQL_CACHE names.lastname AS name,
			    COUNT(DISTINCT(individuum.persons_id)) AS count,
			    MIN(names.persons_id) as persons_id
			FROM
			    individuum
			LEFT JOIN
			    names ON individuum.persons_id = names.persons_id
			LEFT JOIN
			    persons ON individuum.persons_id = persons.id
			LEFT JOIN
			    document_entities ON individuum.entry_id = document_entities.id
			LEFT JOIN
			    namebox_entries ON names.lastname = namebox_entries.name
			LEFT JOIN
			    name_compounds ON namebox_entries.id=name_compounds.name1_id
			WHERE
			    document_entities.enabled = 1
			    AND names.lastname <> ""
			    AND %s
			GROUP BY
			    names.lastname
			ORDER BY
			    names.lastname;
   	        ', Auth::check() ? 'true' : 'persons.hide = 0')))) {
   	        	$alphabet = self::getAlphabet();
        		foreach($items as $item) {
        			//if ($letter != NULL && preg_match("/^".$alphabet[$letter]."/i", (string)$item->name)) {
        			if ($letter > -1 && Str::startsWith(Str::lower($item->name), Str::lower($alphabet[$letter]))) {
        				$result[] = $item;
        			}
        		}

   	        	if ($familyNameId) {
   	        		$result = new \stdClass();
   	        		foreach($items as $item) {
   	        			if ($item->persons_id == $familyNameId) {
   	        				$result->families = $item;
   	        				$result->members  = self::getFamilyName($item);
   	        			}
   	        		}
   	        	}
    	}

    	return $result;
    }

}
