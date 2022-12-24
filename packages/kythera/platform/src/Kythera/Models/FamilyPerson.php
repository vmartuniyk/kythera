<?php
namespace Kythera\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use \Carbon\Carbon;
use Kythera\Models;

class FamilyPersonException extends \Exception {}

class FamilyPerson extends Eloquent
{

    /*
     * TESTS:
     * 2 child from other partner & child with partner
     * http://kfn-tree.laravel.debian.mirror.virtec.org/en/family-trees/7742
     *
     * child with partner
     * http://kfn-tree.laravel.debian.mirror.virtec.org/en/family-trees/1692
     * */

	//Update Route::get('family-trees/{persons_id}/add/{member}')
	const MEMBER_PERSON = 1;
	const MEMBER_PARENT = 2;
	const MEMBER_PARTNER = 3; const MEMBER_SPOUSE = 3;
	const MEMBER_CHILD  = 4;


    public $timestamps = false;

    protected $table = "individuum";

    protected $primaryKey = 'persons_id';

    protected $fillable = array();

    public static $rules =  array();

    public static function findByEntryId($entry_id)
    {
    	return self::where('entry_id', $entry_id)->first();
    }

    public static function findById($personsId)
    {
    	return self::find($personsId);
    }

    public static function getPersonDate($date)
    {
    	$result = false;
    	if (preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})/', $date, $m)) {
    		$result[0] = ($v = intval($m[1])) != 0 ? $v : 0;
    		$result[1] = ($v = intval($m[2])) != 0 ? $v : 1;
    		$result[2] = ($v = intval($m[3])) != 0 ? $v : 1;
    		if ($result[0]==0) return;
    		return Carbon::createFromDate($result[0], $result[1], $result[2]);
    	}
    	return $result;
    }

    public static function getPersonYearDate($date)
    {
    	$result = false;
    	if (preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})/', $date, $m)) {
    		$result = ($v = intval($m[1])) !== 0 ? $v : false;
    	}
    	return $result;
    }

    public static function formatPersonDates($name)
    {
    	$result = '';
    	if ($y = self::getPersonYearDate($name->date_of_birth)) {
    		$result = sprintf('*%d', $y);
    	}
    	if ($y = self::getPersonYearDate($name->date_of_death)) {
    		$result = sprintf('%s †%d', $result, $y);
    	}
    	if ($result) {
    		$result = sprintf('(%s)', $result);
    	}
    	return $result;
    }

    public static function getInfo($personsId)
    {
    	throw new \Exception(__FILE__.__LINE__.' depricated');
    	$result = array();
    	if (\Config::get('app.debug')) {
    		if ($item = DB::select(DB::raw(sprintf('
			SELECT
			    DISTINCT CONCAT(IFNULL(names.firstname,""), " ",
    			IFNULL(names.middlename,"")) AS fullname,
    			persons.id, /*VM*/
    			/*documents.persons_id as authorId, *//*VM*/
    			document_entities.persons_id as authorId,
			    entry_id AS did,
			    /*categories.id AS cid,*/
			    /*navigation_tree.id AS nav,*/
			    names.*,
			    persons.*
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
			    documents ON individuum.entry_id = documents.id
    			*/
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
			    /*documents.enabled = 1*/
			    /*document_entities.enabled = 1
			    AND*/ persons.id = %d;
   	        ', $personsId)))) {
    		   	        $result = $item[0];
    		}
    	} else {
    		if ($item = DB::select(DB::raw(sprintf('
			SELECT
			    DISTINCT CONCAT(IFNULL(names.firstname,""), " ",
    			IFNULL(names.middlename,"")) AS fullname,
    			persons.id, /*VM*/
    			/*documents.persons_id as authorId, *//*VM*/
    			document_entities.persons_id as authorId,
			    entry_id AS did,
			    /*categories.id AS cid,*/
			    /*navigation_tree.id AS nav,*/
			    names.*,
			    persons.*
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
			    documents ON individuum.entry_id = documents.id
    			*/
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
			    /*documents.enabled = 1*/
			    document_entities.enabled = 1
			    AND persons.id = %d;
   	        ', $personsId)))) {
    		   	        $result = $item[0];
    		}
    	}

    	return $result;
    }


	public static function namesInsert($personsId, $data)
	{
		throw new \Exception(__FILE__.__LINE__.' depricated');
		/* TABLE `names` (
              `persons_id` int(11) NOT NULL DEFAULT '0',
              `character_set_id` varchar(50) NOT NULL DEFAULT '',
              `firstname` varchar(255) DEFAULT NULL,
              `middlename` varchar(255) DEFAULT NULL,
              `lastname` varchar(255) DEFAULT NULL,
              `nickname` varchar(255) DEFAULT NULL,
              `maidenname` varchar(255) DEFAULT NULL,
		*/
		//echo __FILE__.__LINE__.'<pre>$data='.htmlentities(print_r($data,1)).'</pre>';die;
		$result = false;
		if (DB::table('names')->insert(array(
			'persons_id'		=> $personsId,
			'character_set_id'	=> 'latin',
			'firstname'			=> $data['firstname'],
			//'middlename'		=> $data['middlename'],
			'lastname'			=> $data['lastname'],
			//'nickname'			=> $data['nickname'],
			//'maidenname'		=> $data['maidenname']
		))) {
			$result = true;
		};
		//echo __FILE__.__LINE__.'<pre>$queryId='.htmlentities(print_r($queryId,1)).'</pre>';die;
		return $result;

	}


	public static function namesUpdate($personsId, $data)
	{
		throw new \Exception(__FILE__.__LINE__.' depricated');
		/* TABLE `names` (
              `persons_id` int(11) NOT NULL DEFAULT '0',
              `character_set_id` varchar(50) NOT NULL DEFAULT '',
              `firstname` varchar(255) DEFAULT NULL,
              `middlename` varchar(255) DEFAULT NULL,
              `lastname` varchar(255) DEFAULT NULL,
              `nickname` varchar(255) DEFAULT NULL,
              `maidenname` varchar(255) DEFAULT NULL,
		*/
		//echo __FILE__.__LINE__.'<pre>$data='.htmlentities(print_r($data,1)).'</pre>';die;
		$result = false;
		if (DB::table('names')
            ->where('persons_id', $personsId)
            ->update(array(
			//'persons_id'		=> $personsId,
			'character_set_id'	=> 'latin',
			'firstname'			=> $data['firstname'],
			//'middlename'		=> $data['middlename'],
			'lastname'			=> $data['lastname'],
			//'nickname'			=> $data['nickname'],
			//'maidenname'		=> $data['maidenname']
		))) {
			$result = true;
		};
		//echo __FILE__.__LINE__.'<pre>$queryId='.htmlentities(print_r($queryId,1)).'</pre>';die;
		return $result;

	}


	public static function personsInsert($data)
	{
		throw new \Exception(__FILE__.__LINE__.' depricated');
		/* TABLE `persons` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `hide` int(1) NOT NULL DEFAULT '0',
              `gender` enum('U','M','F') DEFAULT 'U',
              `date_of_birth` datetime DEFAULT NULL,
              `date_of_death` datetime DEFAULT NULL,
              `place_of_birth` int(11) DEFAULT NULL,
		*/
		//echo __FILE__.__LINE__.'<pre>$data='.htmlentities(print_r($data,1)).'</pre>';
		$result = 0;
		if ($queryId = DB::table('persons')->insertGetId(array(
			'hide'			=> 0,
			'gender'		=> $data['gender'],
			'date_of_birth'	=> Carbon::createFromDate($data['year_of_birth'], 1, 1)
		))) {
			$result = $queryId;
		};
		//echo __FILE__.__LINE__.'<pre>$queryId='.htmlentities(print_r($queryId,1)).'</pre>';die;
		return $result;

	}


	public static function personsUpdate($data)
	{
		throw new \Exception(__FILE__.__LINE__.' depricated');
		/* TABLE `persons` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `hide` int(1) NOT NULL DEFAULT '0',
              `gender` enum('U','M','F') DEFAULT 'U',
              `date_of_birth` datetime DEFAULT NULL,
              `date_of_death` datetime DEFAULT NULL,
              `place_of_birth` int(11) DEFAULT NULL,
		*/
		//echo __FILE__.__LINE__.'<pre>$data='.htmlentities(print_r($data,1)).'</pre>';die;
		$result = false;
		if (DB::table('persons')
            ->where('id', $data['personsId'])
            ->update(array(
			'hide'			=> 0,
			'gender'		=> $data['gender'],
			'date_of_birth'	=> Carbon::createFromDate($data['year_of_birth'], 1, 1)
		))) {
			$result = true;
		};
		return $result;

	}


	public static function individuumInsert($personsId, $entry_id, $image_id, $data = '')
	{
		throw new \Exception(__FILE__.__LINE__.' depricated');
		/* TABLE `individuum` (
              `persons_id` int(11) NOT NULL DEFAULT '0',
              `entry_id` int(11) NOT NULL DEFAULT '0',
              `image_id` int(11) DEFAULT NULL,
              `data` mediumtext, XML?! <still_living></still_living><year_of_birth>1964</year_of_birth><profession>Manager</profession><country_of_birth>Australia</country_of_birth><state_of_birth>NSW</state_of_birth><city_of_birth>Sydney</city_of_birth><country_of_death></country_of_death><state_of_death></state_of_death><city_of_death></city_of_death><religion></religion><education></education>
		 */

		$result = false;
		if (DB::table('individuum')->insert(array(
			'persons_id'	=> $personsId,
			'entry_id'		=> $entry_id,
			'image_id'		=> $image_id,
			'data'			=> $data
		))) {
			$result = true;
		};

		return $result;

/*
		$person = new FamilyPerson();
		$person->persons_id = $personsId;
		$person->entry_id = $entry_id;
		$person->image_id = $image_id;
		$person->data     = $data;
		$result = $person->save();
		echo __FILE__.__LINE__.'<pre>$result='.htmlentities(print_r($result,1)).'</pre>';die;
*/
	}


	public static function individuumUpdate($personsId, $entry_id, $image_id, $data = '')
	{
		throw new \Exception(__FILE__.__LINE__.' depricated');
		/* TABLE `individuum` (
              `persons_id` int(11) NOT NULL DEFAULT '0',
              `entry_id` int(11) NOT NULL DEFAULT '0',
              `image_id` int(11) DEFAULT NULL,
              `data` mediumtext, XML?! <still_living></still_living><year_of_birth>1964</year_of_birth><profession>Manager</profession><country_of_birth>Australia</country_of_birth><state_of_birth>NSW</state_of_birth><city_of_birth>Sydney</city_of_birth><country_of_death></country_of_death><state_of_death></state_of_death><city_of_death></city_of_death><religion></religion><education></education>
		 */

		$result = false;
		if (DB::table('individuum')
            ->where('persons_id', $personsId)
            ->update(array(
			'image_id'		=> $image_id,
			'data'			=> $data
		))) {
			$result = true;
		};

		return $result;
	}


    public static function getPersons($authorId)
    {
    	throw new \Exception(__FILE__.__LINE__.' depricated');
    	$result = array();
    	//if ($item = DB::connection('import')->select(DB::raw(sprintf('
    	if ($items = DB::select(DB::raw(sprintf('
			SELECT
			    DISTINCT CONCAT(IFNULL(names.firstname,""), " ",
    			IFNULL(names.middlename,"")) AS fullname,
    			persons.id, /*VM*/
    			/*documents.persons_id as authorId, *//*VM*/
    			document_entities.persons_id as authorId,
			    entry_id AS did,
			    /*categories.id AS cid,*/
			    /*navigation_tree.id AS nav,*/
			    names.*,
			    persons.*
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
			    documents ON individuum.entry_id = documents.id
    			*/
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
			    /*documents.enabled = 1*/
			    document_entities.enabled = 1
    			AND names.firstname != ""
			    AND document_entities.persons_id = %d
    		ORDER BY
    			lastname, firstname, id;
   	        ', $authorId)))) {
       	        $result = $items;
    	}
    	return $result;
    }


    public static function getParents($personsId, $full = false)
    {
    	throw new \Exception(__FILE__.__LINE__.' depricated');
    	$result = array();
    	//first find parents id
    	//if ($parentId = DB::connection('import')->select(DB::raw(sprintf('
    	if ($parentId = DB::select(DB::raw(sprintf('
			SELECT
    			*
			FROM
			    family
			WHERE
			    kind = %d;
   	        ', $personsId)))) {

   	        //if ($parents = DB::connection('import')->select(DB::raw(sprintf('
   	        if ($parents = DB::select(DB::raw(sprintf('
				SELECT
	    			*
				FROM
				    parents
				WHERE
				    id = %d;
	   	        ', $parentId[0]->parents_id)))) {
	   	        	//echo __FILE__.__LINE__.'<pre>$parents='.htmlentities(print_r($parents,1)).'</pre>';die;

	   	        	$result[] = $parents[0]->partner1;
	   	        	$result[] = $parents[0]->partner2;

	   	        	if ($full) {
	   	        		$result = array();
	   	        		if (!is_null($parents[0]->partner1))
        				$result[] = self::getInfo($parents[0]->partner1);
	   	        		if (!is_null($parents[0]->partner2))
        				$result[] = self::getInfo($parents[0]->partner2);
	   	        	}
   	        }
    	}
    	return $result;
    }


	/**
	 * Get partners with children
	 *
	 * @param $personsId
	 * @param bool|false $full
	 * @return array
	 */
    public static function getPartnersWithChildren($personsId, $full = false)
    {
    	throw new \Exception(__FILE__.__LINE__.' depricated');
    	$result = array();
    	//if ($spouseIds = DB::connection('import')->select(DB::raw(sprintf('
    	if ($spouseIds = DB::select(DB::raw(sprintf('
	    	SELECT
		    	DISTINCT  `parents`.`id`,
		    	`parents`.`partner1`,
		    	`parents`.`partner2`
	    	FROM
    			`family`
    			LEFT JOIN `parents`  ON `parents`.`id` = `family`.`parents_id`
	    	WHERE
    			partner1 is not null and
    			partner2 is not null and
    			(`partner1` = %d  OR `partner2` = %d);
   	        ', $personsId, $personsId)))) {
   	        	foreach ($spouseIds as $spouse) {
   	        		if ($personsId != $spouse->partner1)
   	        		$result[] = $spouse->partner1;
   	        		if ($personsId != $spouse->partner2)
   	        		$result[] = $spouse->partner2;
   	        	}

   	        	if ($full) {
   	        		$result = array();
   	        		foreach ($spouseIds as $spouse) {
   	        			if ($personsId != $spouse->partner1)
   	        			$result[] = self::getInfo($spouse->partner1);
   	        			if ($personsId != $spouse->partner2)
   	        			$result[] = self::getInfo($spouse->partner2);
   	        		}
   	        	}
    	}
    	return $result;
    }


	/**
	 * Get partners (id)
	 *
	 * @param $personsId
	 * @param bool|false $full
	 * @return array
	 */
	public static function getPartners($personsId, $full = false)
	{
		throw new \Exception(__FILE__.__LINE__.' depricated');
		$result = array();
		if ($items = DB::select(DB::raw(sprintf('
	    	SELECT
		    	DISTINCT id,
		    	partner1,
		    	partner2
	    	FROM
    			parents
	    	WHERE
    			partner1 = %d
    			OR partner2 = %d;
   	        ', $personsId, $personsId)))) {
			//echo __FILE__.__LINE__.'<pre>$items='.htmlentities(print_r($items,1)).'</pre>';die;

			foreach ($items as $item) {
				if (!is_null($item->partner1))
				if ($personsId != $item->partner1)
					$result[$item->id] = $item->partner1;
				if (!is_null($item->partner2))
				if ($personsId != $item->partner2)
					$result[$item->id] = $item->partner2;
			}
			//echo __FILE__.__LINE__.'<pre>$result='.htmlentities(print_r($result,1)).'</pre>';die;

			if ($full) {
				$result = array();
				foreach ($items as $item) {
					if (!is_null($item->partner1))
					if ($personsId != $item->partner1)
						$result[$item->id] = self::getInfo($item->partner1);
					if (!is_null($item->partner2))
					if ($personsId != $item->partner2)
						$result[$item->id] = self::getInfo($item->partner2);
				}
				//echo __FILE__.__LINE__.'<pre>$result='.htmlentities(print_r($result,1)).'</pre>';die;
			}
		}
		return $result;
	}

    public static function getChildren($personsId, $full = false)
    {
    	throw new \Exception(__FILE__.__LINE__.' depricated');
    	$result = array();
    	//if ($items = DB::connection('import')->select(DB::raw(sprintf('
    	if ($items = DB::select(DB::raw(sprintf('
	    	SELECT DISTINCT
	    	  `family`.`kind`,
	    	  family.id
	    	FROM
	    	  `family`
	    	  LEFT JOIN `parents` ON `family`.`parents_id` = `parents`.`id`
	    	WHERE
	    	  (`parents`.`partner1` = %d OR `parents`.`partner2` = %d);
   	        ', $personsId, $personsId)))) {
   	        	//echo __FILE__.__LINE__.'<pre>items='.htmlentities(print_r($items,1)).'</pre>';die;
   	        	foreach ($items as $child) {
                    //echo __FILE__.__LINE__.'<pre>$child='.htmlentities(print_r($child,1)).'</pre>';
   	        		$p = self::getParents($child->kind);
                    //echo __FILE__.__LINE__.'<pre>$p='.htmlentities(print_r($p,1)).'</pre>';
   	        		$result[$child->kind]['familyId'] = $child->id;
   	        		$result[$child->kind]['parents'] = $p;
   	        		//$result[] = $p;
   	        	}

   	        	if ($full) {
   	        		$result = array();
   	        	   	foreach ($items as $child) {
   	        	   		$result[] = self::getInfo($child->kind);
   	        	   	}
   	        	}
    	}
    	//echo __FILE__.__LINE__.'<pre>$result='.htmlentities(print_r($result,1)).'</pre>';die;
    	return $result;
    }


    public static function buildDescription($person)
    {
        //fixme
    	if (!is_object($person)) {return 'xxxxxxx';}

    	$maidenname = empty($person->maidenname) ? '' : '('.e($person->maidenname).') ';
    	if ($person->middlename) {
    		$result = sprintf('%s%s %s %s %s', $maidenname, e($person->firstname), e($person->middlename), e($person->lastname), self::formatPersonDates($person));
    	} else {
    		$result = sprintf('%s%s %s %s', $maidenname, e($person->firstname), e($person->lastname), self::formatPersonDates($person));
    	}
    	return $result;
    }


    public static function getTree($entryId, $personsId)
    {
        /*
         * TODO:
         * add partners of children
         * */

        //echo __FILE__.__LINE__.'<pre>$entryId='.htmlentities(print_r($entryId,1)).'</pre>';
        //echo __FILE__.__LINE__.'<pre>$personsId='.htmlentities(print_r($personsId,1)).'</pre>';

    	$result = array();
    	$parents = [];
    	$partners = [];

    	//$person = self::getInfo($personsId, \Config::get('app.debug'));
		//echo __FILE__.__LINE__.'<pre>$person='.htmlentities(print_r($person,1)).'</pre>';die;
    	$person = new Person($personsId);

		//set subject
		$result[] = array(
			'id'=>$person->id,
			'entry_id'=>$entryId,
			'parents'=>$parents,
			'spouses'=>$partners,
			'title'=>'',
			'label'=>'',
			'description'=> sprintf('%s %s %s %s', $person->firstname, $person->middlename, $person->lastname, self::formatPersonDates($person)),
			'description'=> self::buildDescription($person),
			'image'=> "/assets/vendors/primitives/images/photos/avatar1.png"
		);
		$subject = &$result[0];

		/*
    	//add parents
    	if ($parentIds = self::getParents($personsId)) {
    		foreach ($parentIds as $parentId) {
    			$parent = self::getInfo($parentId);
                if ($parent)
    			$result[] = array(
    					'id'=>$parent->id,
    					'parents'=>$parents,
    					'spouses'=>$partners,
    					'title'=>'',
    					'label'=>'',
    					'description'=> sprintf('%s %s %s %s', $parent->firstname, $parent->middlename, $parent->lastname, self::formatPersonDates($parent)),
    					'description'=> self::buildDescription($parent),
    					'image'=> "/assets/vendors/primitives/images/photos/avatar1.png"
    			);
    		}

    		foreach ($parentIds as $parentId) {
    			$parents[] = $parentId;
    		}
    	}
    	//echo __FILE__.__LINE__.'<pre>$parents='.htmlentities(print_r($parentIds,1)).'</pre>';
		*/

    	//add partners
    	if ($items = self::getPartners($personsId)) {
    		foreach ($items as $itemId) {
    			if ($itemId == $personsId) continue;
    			$item = self::getInfo($itemId);
                if ($item)
    			$result[] = array(
    					'id'=>$item->id,
                        'entry_id'=>$item->did,
    					'parents'=>$parents,
    					'spouses'=>[$result[0]['id']],
    					'title'=>'',
    					'label'=>'',
    					'description'=> sprintf('%s %s %s %s', $item->firstname, $item->middlename, $item->lastname, self::formatPersonDates($item)),
    					'description'=> self::buildDescription($item),
    					'image'=> "/assets/vendors/primitives/images/photos/avatar1.png"
    			);
    		}
    		foreach ($items as $itemId) {
				if ($itemId == $personsId) continue;
				$partners[] = $itemId;
    		}
    	}
    	//echo __FILE__.__LINE__.'<pre>$partners='.htmlentities(print_r($partners,1)).'</pre>';die;
		$subject['spouses'] = $partners;

    	//add children
    	if ($items = self::getChildren($personsId)) {
            //echo __FILE__.__LINE__.'<pre>$items='.htmlentities(print_r($items,1)).'</pre>';
    		foreach ($items as $childId => $parents) {
    			$child = self::getInfo($childId);
                //echo __FILE__.__LINE__.'<pre>$child='.htmlentities(print_r($child,1)).'</pre>';
                if ($child) {
                	//http://kfn-tree.laravel.debian.mirror.virtec.org/en/family-trees/1692
                	$child_spouses = array();
                	if ($items2 = self::getPartners($childId)) {
                		foreach ($items2 as $itemId2) {
                			if ($itemId2 == $childId) continue;
                			$item2 = self::getInfo($itemId2);
                			if ($item2) {
                				$result[] = array(
                						'id'=>$item2->id,
                						'entry_id'=>$item2->did,
                						'parents'=>[],
                						'spouses'=>[$child->id],
                						'title'=>'',
                						'label'=>'',
                						'description'=> sprintf('%s %s %s %s', $item2->firstname, $item2->middlename, $item2->lastname, self::formatPersonDates($item2)),
                						'description'=> self::buildDescription($item2),
                						'image'=> "/assets/vendors/primitives/images/photos/avatar1.png"
                				);
                				$child_spouses[] = $item2->id;
                			}
                		}
                	}

	    			$result[] = array(
	    					'id'=>$child->id,
	                        'entry_id'=>$child->did,
	    					'parents'=>$parents['parents'],
	    					'spouses'=>$child_spouses,
	    					'title'=>'',
	    					'label'=>'',
	    					'description'=> sprintf('%s %s %s %s', $child->firstname, $child->middlename, $child->lastname, self::formatPersonDates($child)),
	    					'description'=> self::buildDescription($child),
	    					'image'=> "/assets/vendors/primitives/images/photos/avatar1.png"
	    			);
                }
    		}
    	}

		//echo __FILE__.__LINE__.'<pre>$result='.htmlentities(print_r($result,1)).'</pre>';die;
    	return $result;
    }


    public static function AjaxGetPersonInfo($persons_id)
    {
		//echo __FILE__.__LINE__.'<pre>$persons_id='.htmlentities(print_r($persons_id,1)).'</pre>';

    	$result = new \stdClass();
    	if ($subject = self::getInfo($persons_id)) {
    		foreach ($subject as $k=>$v) {
    			$result->$k = $v;
    		}

			//echo __FILE__.__LINE__.'<pre>$subject='.htmlentities(print_r($subject,1)).'</pre>';

    		if ($entry = DocumentEntity::find($subject->did)) {

				//echo __FILE__.__LINE__.'<pre>$entry='.htmlentities(print_r($entry,1)).'</pre>';die;

    			$result->name = $entry->title->getValue();
    			$result->title = $entry->title->getValue();
    			$result->content = $entry->content->getValue();

    			$simple = '<root>'.$entry->content->getValue().'</root>';
    			$p = xml_parser_create();
    			xml_parse_into_struct($p, $simple, $vals, $index);
    			xml_parser_free($p);
    			foreach ($vals as $v) {
    				$key = strtolower($v['tag']);
    				$value = isset($v['value']) ? $v['value'] : '';
    				$result->$key = $value;
    			}
    		}
    	}

    	$h = '';
    	$h.= '<style>
    			table.i {width:50%}
    			table.i tr {vertical-align:top;border-top:1px solid #ccc}
    			td.k {font-weight:700;width:50%}
    			</style>';
    	$h.= sprintf('<h3>Details of %s</h3>', self::buildDescription($subject));
    	$h.= '<table class="i">';
    	foreach ($result as $k=>$v) {
    		$h.=sprintf('<tr><td class="k">%s</td><td>%s</td></tr>', $k, $v);
    	}
    	$h.= '</table>';
    	return $h;
    }

/*

    public static function setPerson($data)
    {
    	throw new \Exception(__FILE__.__LINE__.' depricated');
        //echo __FILE__.__LINE__.'<pre>$data='.htmlentities(print_r($data,1)).'</pre>';die;
		$entry = null;
        $entryId = isset($data['entryId']) ? $data['entryId'] : 0;
		if ($entryId) {
            //convert
            $data['title']   = sprintf('%s %s', $data['firstname'], $data['lastname']);
            $data['content'] = sprintf('%s', $data['life_story']);

			//update
            if ($entry = DocumentPerson::find($entryId)) {
                $entry->set($data);
            }
		} else {
			//convert
			$data['title']   = sprintf('%s %s', $data['firstname'], $data['lastname']);
			$data['content'] = sprintf('%s', $data['life_story']);

			//create
			if ($entry = DocumentPerson::add($data)) {
			}
		}

		return $entry;
    }

    public static function addMother($data)
    {
    	throw new \Exception(__FILE__.__LINE__.' depricated');
    	echo __FILE__.__LINE__.'<pre>$data='.htmlentities(print_r($data,1)).'</pre>';
    }

    public static function addFather($data)
    {
    	throw new \Exception(__FILE__.__LINE__.' depricated');
    	echo __FILE__.__LINE__.'<pre>$data='.htmlentities(print_r($data,1)).'</pre>';
    }

    public static function addPartner($data)
    {
    	throw new \Exception(__FILE__.__LINE__.' depricated');
        //echo __FILE__.__LINE__.'<pre>$data='.htmlentities(print_r($data,1)).'</pre>';die;

        if ($data['existingId']) {
            $partner1 = $data['personsId'];
            $partner2 = $data['existingId'];
        } else {
            $entry = self::setPerson($data);
            $partner1 = $data['personsId'];
            $partner2 = $entry->getPersonsId();
        }
        //echo __FILE__.__LINE__.'<pre>$partner1='.htmlentities(print_r($partner1,1)).'</pre>';
        //echo __FILE__.__LINE__.'<pre>$partner2='.htmlentities(print_r($partner2,1)).'</pre>';die;

        $result = 0;
        //fixme: replace into??!
        if ($queryId = DB::table('parents')->insertGetId(array(
            'partner1' => $partner1,
            'partner2' => $partner2
        ))) {
            $result = $queryId;
        };
        //echo __FILE__.__LINE__.'<pre>$queryId='.htmlentities(print_r($queryId,1)).'</pre>';die;
        return $result;
    }


    public static function getFamilyId($partner1, $partner2)
    {
    	throw new \Exception(__FILE__.__LINE__.' depricated');
        $result = null;
        if ($items = DB::select(DB::raw(sprintf('
            getFamilyId()
            SELECT
                family.id AS familyId,
                parents.id AS parentsId
            FROM
                family
                LEFT JOIN parents ON family.parents_id = parents.id
            WHERE
                (partner1 = %d AND partner2 = %d)
                OR (partner2 = %d AND partner1 = %d)
            ;', $partner1, $partner2, $partner2, $partner1)))) {
            echo __FILE__.__LINE__.'<pre>$items='.htmlentities(print_r($items,1)).'</pre>';die;
        }
        return $result;
    }


    public static function getParentsId($partner1, $partner2)
    {
    	throw new \Exception(__FILE__.__LINE__.' depricated');
        $result = null;

        if ($item = DB::select(DB::raw(sprintf('
            getParentsId()
            SELECT
                id
            FROM
                parents
            WHERE
                (partner1 = %d AND partner2 = %d)
                OR (partner2 = %d AND partner1 = %d)
            LIMIT 1
            ;', $partner1, $partner2, $partner2, $partner1)))) {
            //echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';die;
            $result = current($item)->id;
        }

        return $result;
    }


    public static function addChild($data)
    {
    	throw new \Exception(__FILE__.__LINE__.' depricated');
        $result = 0;


        //update partner
        //self::deletePartner($data['personsId'], $data['parentId']);

        //$parentsId = self::addPartner(array(
        //    'personsId' => $data['personsId'],
        //    'existingId' => $data['parentId']
        //));

        //get parents id
        $parentsId = ($parentsId = self::getParentsId($data['personsId'], $data['parentId'])) ? $parentsId :
            self::addPartner(array(
                'personsId' => $data['personsId'],
                'existingId' => $data['parentId']
            ));
        //echo __FILE__.__LINE__.'<pre>$parentsId='.htmlentities(print_r($parentsId,1)).'</pre>';die;

        if ($data['existingId']) {
            $kindId = $data['existingId'];
        } else {
            //echo __FILE__.__LINE__.'<pre>$data='.htmlentities(print_r($data,1)).'</pre>';
            $kind = self::setPerson($data);
            //echo __FILE__.__LINE__.'<pre>$kind='.htmlentities(print_r($kind,1)).'</pre>';die;
            $kindId = $kind->getPersonsId();
        }

        if ($familyId = DB::table('family')->insertGetId(array(
            'parents_id' => $parentsId,
            'kind' => $kindId
        ))) {
            $result = $familyId;
        };
        //echo __FILE__.__LINE__.'<pre>$result='.htmlentities(print_r($result,1)).'</pre>';die;
        return $result;
    }



    public static function deletePartner($personsId, $relativeId)
	{
        $result = true;
        //check if partner exists
        if (in_array($relativeId, $partners = self::getPartners($personsId))) {
            //delete
            if ($id = array_search($relativeId, $partners)) {
                if (DB::table('parents')->where('id', $id)->delete()) {
                    $result = true;
                }
            }
        }
        //echo __FILE__.__LINE__.'<pre>$partners='.htmlentities(print_r($partners,1)).'</pre>';die;
        //echo __FILE__.__LINE__.'<pre>$result='.htmlentities(print_r($result,1)).'</pre>';die;
        return $result;
	}


    public static function deleteChild($personsId, $relativeId)
	{
        //echo __FILE__.__LINE__.'<pre>$relativeId='.htmlentities(print_r($relativeId,1)).'</pre>';
        $result = false;
        //check if child exists
        if (array_key_exists($relativeId, $children = self::getChildren($personsId))) {
            //echo __FILE__.__LINE__.'<pre>$children='.htmlentities(print_r($children,1)).'</pre>';die;
            //delete
            if (($child = $children[$relativeId]) && DB::table('family')->where('id', $child['familyId'])->delete()) {
                $result = true;
            }
        }
        //echo __FILE__.__LINE__.'<pre>$result='.htmlentities(print_r($result,1)).'</pre>';die;
        return $result;
	}


	public static function deleteRelative($personsId, $member, $relativeId)
	{
		switch($member) {
			case FamilyPerson::MEMBER_PARENT:
				break;
			case FamilyPerson::MEMBER_PARTNER:
				return self::deletePartner($personsId, $relativeId);
			case FamilyPerson::MEMBER_SPOUSE:
				break;
			case FamilyPerson::MEMBER_PERSON:
				break;
			case FamilyPerson::MEMBER_CHILD:
                return self::deleteChild($personsId, $relativeId);
				break;
			default:
				throw new Exception('Undefined member: '.$member);
		}
	}
}
>>>>>>> familytree
*/
}