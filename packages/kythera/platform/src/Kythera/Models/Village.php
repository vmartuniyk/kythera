<?php
namespace Kythera\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use \Carbon\Carbon;
use Kythera\Models;
use Illuminate\Support\Facades\Config;

class VillageException extends \Exception {}

class Village extends Eloquent
{

    public $timestamps = false;


    protected $table = "villages";


    protected $primaryKey = 'id';


    protected $fillable = array(
   		'village_name',
   		'character_set_id',
   		'visible',
   		'lost',
   		'latitude',
   		'longitude'
    );


    public static $rules =  array(
   		'village_name' => 'required|unique:villages,village_name'
    );

    public static $rulesUpdate =  array(
   		'village_name' => 'required'
    );


    public static $messages =  array(
   		'village_name.unique' => 'Village name already exists.'
    );


    /**
     * Get all used first letters for a given language
     * @return array of letters used
     */
    public static function getAlphabet($all = false)
    {
    	$result = array();
    	if ($items = DB::select(DB::raw(sprintf('
            SELECT
    	        DISTINCT(SUBSTRING(village_name,1,1)) letter
	        FROM
    	        villages
            WHERE
    	        character_set_id = "%s" AND
                village_name != "" AND
    			%s
	        ORDER BY
    	        ORD(UCASE(SUBSTRING(village_name, 1, 4)));
  			', App::getLocale() == 'en' ? 'latin' : 'greek', $all ? '1=1' : 'visible=1' )))) {
        	        foreach ($items as $item) {
        	        	$result[] = $item->letter;
        	        }
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
    	        villages
            WHERE
                village_name != ""
	        ORDER BY
    			character_set_id DESC,
    	        village_name;
    	    ')))) {
    	}
    	return $result;
    }


    /**
     * Build query for Google Maps
     * @return string
     */
    public function getAddress()
    {
    	return sprintf('%s, Kythera, Greece', $this->village_name);
    }


    public function getCompounds()
    {
    	$result = array();
    	if ($result = DB::select(DB::raw(sprintf('
			/*get compounds */
			SELECT
			    *
			FROM
			    villages V
			LEFT JOIN
			    village_compounds C on V.id = C.village1_id
			INNER JOIN
			    villages VC on C.village2_id = VC.id
			WHERE
			    V.id = %d AND
                V.village_name != "" AND
    			C.village1_id IS NOT NULL
    		ORDER BY
    			V.character_set_id,
    			VC.village_name
    			;
    	    ', $this->id)))) {
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
    			village_compounds
			SET
			    village1_id=%d,
			    village2_id=%d
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
    			village_compounds
			WHERE
			    (village1_id=%d AND village2_id=%d) OR
			    (village2_id=%d AND village1_id=%d)
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
    			village_compounds
			WHERE
			    village1_id=%d OR
			    village2_id=%d
    			;
    	    ', $this->id, $this->id)))) {
    	}
    	return $result;
    }



}