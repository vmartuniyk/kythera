<?php
namespace Kythera\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

/**
 * @author virgilm
 *
 */
class PeopleName extends Eloquent
{

    public $timestamps = false;


    protected $table = "namebox_entries";


    protected $fillable = array('name', 'character_set_id', 'visible');


    public static $rules = array (
    	'name' => 'required|unique:namebox_entries,name'
    );


    public static $rulesUpdate = array (
    	'name' => 'required'
    );


    public static $messages =  array(
   		'name.unique' => 'Name already exists.'
    );


    public function slug()
    {
    	return Str::slug($this->lastname);
    }

    /**
     * Get all used first letters for a given language
     * @return array of letters used
     */
    public static function getAlphabet($all = false)
    {
        $result = array();
    	if ($items = DB::select(DB::raw(sprintf('
            SELECT %s
    	        DISTINCT(SUBSTRING(name,1,1)) letter
	        FROM
    	        namebox_entries
            WHERE
    	        namebox_entries.character_set_id = "%s" AND
                name != "" AND
    			%s
	        ORDER BY
    	        ORD(UCASE(SUBSTRING(name, 1, 4)));
    	        ', Config::get('debug') ? 'SQL_CACHE' : '', App::getLocale() == 'en' ? 'latin' : 'greek', $all ? '1=1' : 'visible=1' )))) {
	        foreach ($items as $item) {
	            $result[] = $item->letter;
	        }
    	}
    	return $result;
    }


    public function getCompounds()
    {
    	$result = array();
    	if ($result = DB::select(DB::raw(sprintf('
			/*get compounds */
			SELECT
			    *
			FROM
			    namebox_entries V
			LEFT JOIN
			    name_compounds C on V.id = C.name1_id
			INNER JOIN
			    namebox_entries VC on C.name2_id = VC.id
			WHERE
			    V.id = %d AND
                V.name != "" AND
    			C.name1_id IS NOT NULL
    		ORDER BY
    			V.character_set_id,
    			VC.name
    			;
    	    ', $this->id)))) {
    	}
    	return $result;
    }


    public static function getAllCompounds()
    {
    	$result = array();
    	if ($result = DB::select(DB::raw(sprintf('
   			/*getAllCompounds()*/
            SELECT
    	        *
	        FROM
    	        namebox_entries
            WHERE
                name != ""
	        ORDER BY
    			character_set_id DESC,
    	        name;
    	    ')))) {
    	}
    	return $result;
    }


    public function addCompound($compoundId)
    {
    	$result = false;
    	if ($this->id != $compoundId)
    		if ($result = DB::statement(DB::raw(sprintf('
			/*add compounds */
			REPLACE INTO
    			name_compounds
			SET
			    name1_id=%d,
			    name2_id=%d
    			;
    	    ', $this->id, $compoundId)))) {
    	}
    	return $result;
    }


    public function deleteCompound($compoundId)
    {
    	$result = false;
    	if ($result = DB::statement(DB::raw(sprintf('
			/*delete compound */
			DELETE FROM
    			name_compounds
			WHERE
			    (name1_id=%d AND name2_id=%d) OR
			    (name2_id=%d AND name1_id=%d)
    			;
    	    ', $this->id, $compoundId, $compoundId, $this->id)))) {
    	}
    	return $result;
    }


    public function deleteCompounds()
    {
    	$result = false;
    	if ($result = DB::statement(DB::raw(sprintf('
			/*delete compounds */
			DELETE FROM
    			name_compounds
			WHERE
			    name1_id=%d OR
			    name2_id=%d
    			;
    	    ', $this->id, $this->id)))) {
    	}
    	return $result;
    }


    public static function getDocumentCount($name)
    {
    	///*OR documents.persons_id = '.$user->userId;*/
    	$result = false;
    	if ($result = DB::select(DB::raw(sprintf('
			/*get document count for name */
			SELECT %s
    			names.lastname AS name,
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
			    AND namebox_entries.visible = 1
			    AND names.lastname = "%s"
			    AND (persons.hide = 0)
			GROUP BY
			    names.lastname
			ORDER BY
			    names.lastname
    			;
    	    ', Config::get('debug') ? 'SQL_CACHE' : '', $name->name)))) {
    	    $result = $result[0]->count;
    	}
    	return $result;
    }


    public function getDocuments()
    {
    	//todo: review query when all document types are implemented.
    	$result = false;
    	/*	SELECT SQL_CACHE
    			document_types.id AS controllerID,
    			document_types.string_id AS docTypeID,
    			COUNT(DISTINCT(document_entities.id)) AS docCount
	    	FROM
    			related_names
	    	LEFT JOIN
    			document_entities ON document_id = document_entities.id
	    	LEFT JOIN
    			document_types ON document_type_id = document_types.id,
    			name_compounds
	    	WHERE
    			document_entities.enabled = 1
    			AND related_names.namebox_entry_id = %d
    			OR (related_names.namebox_entry_id = name_compounds.name2_id AND name_compounds.name1_id = %d)
	    	GROUP BY
    			document_types.id
	    	HAVING
    			docTypeID IS NOT NULL
			ORDER BY	
				document_types.id*/

    	    	
    	if ($result = DB::select(DB::raw(sprintf('
   			/*get documents for name */
	    	SELECT %s
    			document_types.id AS controllerID,
    			document_types.string_id AS docTypeID,
    			COUNT(DISTINCT(document_entities.id)) AS docCount
	    	FROM
    			related_names
	    	LEFT JOIN
    			document_entities ON document_id = document_entities.id /* AND document_entities.enabled = 1*/
	    	LEFT JOIN
    			document_types ON document_type_id = document_types.id
    		LEFT JOIN
    			pages ON document_types.id = pages.controller_id,
    			name_compounds
	    	WHERE
    			document_entities.enabled = 1
    			AND related_names.namebox_entry_id = %d
    			OR (related_names.namebox_entry_id = name_compounds.name2_id AND name_compounds.name1_id = %d)
	    	GROUP BY
    			document_types.id
	    	HAVING
    			docTypeID IS NOT NULL
			ORDER BY	
				pages.folder_id, parent_id
    			;
    	    ', Config::get('debug') ? 'SQL_CACHE' : '', $this->id, $this->id)))) {
    	    	    //$result = $result[0]->count;
    	}
    	return $result;
    }


}