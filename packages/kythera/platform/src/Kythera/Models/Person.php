<?php
namespace Kythera\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use \Carbon\Carbon;
use Kythera\Models;
use Kythera\Support\ViewEntity;
use Kythera\Support\ViewDocumentImage;


class PersonException extends \Exception {}


class Person
{

	const MEMBER_PERSON = 1;
	const MEMBER_PARENT = 2;
	const MEMBER_PARTNER = 3; const MEMBER_SPOUSE = 3;
	const MEMBER_CHILD  = 4;

	const TABLE_INIDIVIDUUM	= 'individuum';
	const TABLE_PERSONS		= 'persons';
	const TABLE_PARENTS		= 'parents';
	const TABLE_FAMILY		= 'family';
	const TABLE_NAMES		= 'names';

	public $personsId = null;


	public function __construct($personsId = null)
	{
		$this->personsId = $personsId;
		if ($personsId) {
			$this->getInfo();
			$this->parseData();
		}
	}


	public static function findByEntryId($entry_id)
	{
	    $entry = null;

		if ($item = DB::table(self::TABLE_INIDIVIDUUM)
			->where('entry_id', $entry_id)
			->first()) {
				$entry = new Person($item->persons_id);
			}

        return $entry;

        /*
		return DB::table(self::TABLE_INIDIVIDUUM)
			->where('entry_id', $entry_id)
			->first();
			*/
	}


	public static function findById($personsId)
	{
		return DB::table(self::TABLE_INIDIVIDUUM)
			->find($personsId);
	}


	public function getInfo()
	{
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
				image_id,
				data,	
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
   	        ', $this->personsId)))) {
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
				image_id,
				data,	
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
   	        ', $this->personsId)))) {
	   	        $result = $item[0];
			}
		}

		foreach ($result as $k=>$v) {
			if ($v == '0000-00-00 00:00:00') $v = null;
			$this->{$k} = $v;
		}

		return $this;
	}
	
	
	protected function parseData() 
	{
		if (isset($this->data))
		if ($data = json_decode($this->data)) {
			foreach ($data as $k => $v) {
				if ($k == 'personsId') continue;
				$this->{$k} = $v;
			}
		}	
	}


	/*
	 * Refactor to getData()
	 * */
	public function getPersons()
	{
		$result = array();
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
   	        ', $this->authorId)))) {
	   	        $result = $items;
		}
		return $result;
	}


	/*
	 * image_id = DocumentImage class
	 */
	public static function setPerson($data)
	{
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


	public static function addPartner($data)
	{
		if ($data['existingId']) {
			$partner1 = $data['personsId'];
			$partner2 = $data['existingId'];
		} else {
			$entry = self::setPerson($data);
			$partner1 = $data['personsId'];
			$partner2 = $entry->getPersonsId();
		}

		/*
		$queryId = DB::statement( sprintf('REPLACE INTO %s SET partner1=%d, partner2=%d WHERE ', self::TABLE_PARENTS, $partner1, $partner2) );
		echo __FILE__.__LINE__.'<pre>$queryId='.htmlentities(print_r($queryId,1)).'</pre>';die;
		*/


		$result = 0;
		//fixme: replace into??!
		if ($queryId = DB::table('parents')->insertGetId(array(
			'partner1' => $partner1,
			'partner2' => $partner2
		))) {
			$result = $queryId;
		};
		return $result;
	}


	public static function addChild($data)
	{
		$result = 0;
		
		//from existing person
		//create new person

		//get parents id
		/*
		$parentsId = ($parentsId = self::getParentsId($data['personsId'], $data['parentId'])) ? $parentsId :
			self::addPartner(array(
				'personsId' => $data['personsId'],
				'existingId' => $data['parentId']
			));
        */
		
		//get parents id or create it if not already exists
		$parentsId = ($parentsId = self::getParentsId($data['personsId'], $data['parentId'])) ? $parentsId : self::setParentsId($data['personsId']);

		if (@$data['existingId']) {
			//add exting person as child
			$kindId = $data['existingId'];
		} else {
			//create a new person and add it as child
			$kind = self::setPerson($data);
			$kindId = $kind->getPersonsId();
		}

		//set child/parents relation
		if ($familyId = DB::table('family')->insertGetId(array(
			'parents_id' => $parentsId,
			'kind' => $kindId
		))) {
			$result = $familyId;
		};
		
		return $result;
	}


	public static function addParents($personsId, $motherId, $fatherId)
	{
		$parentsId = ($parentsId = self::getParentsId($motherId, $fatherId)) ? $parentsId : (
    		    is_null($motherId)
        		    ? self::addPartner(array(
        				'personsId' => $motherId,
        				'existingId' => $fatherId
                    ))
        		    :  self::addPartner(array(
        				'personsId' => $fatherId,
        				'existingId' => $motherId
                    ))
		    );
		/*
		$parentsId = ($parentsId = self::getParentsId($motherId, $fatherId)) ? $parentsId :
        		    self::addPartner(array(
        				'personsId' => $motherId ? $motherId : $fatherId,
        				'existingId' => $fatherId ? $fatherId : $motherId
                    ));
			*/
		if ($familyId = DB::table('family')->insertGetId(array(
			'parents_id' => $parentsId,
			'kind' => $personsId
		))) {
			$result = $familyId;
		};
		return $result;
	}


	public static function addMother($data)
	{
		//echo __FILE__.__LINE__.'<pre>$data='.htmlentities(print_r($data,1)).'</pre>';
	    $motherId = null;
		if ($data['existingId']) {
			$motherId = $data['existingId'];
		} else {
		    if (!empty($data['firstname']) && !empty($data['lastname'])) {
    			$mother = self::setPerson($data);
    		    $motherId = $mother->getPersonsId();
		    }
		}
		return $motherId;
	}


	public static function addFather($data)
	{
		//echo __FILE__.__LINE__.'<pre>$data='.htmlentities(print_r($data,1)).'</pre>';
	    $fatherId = null;
		if ($data['existingId']) {
			$fatherId = $data['existingId'];
		} else {
		    if (!empty($data['firstname']) && !empty($data['lastname'])) {
    			$father = self::setPerson($data);
    			$fatherId = $father->getPersonsId();
		    }
		}
		return $fatherId;
	}



	public static function getParentsId($partner1, $partner2)
	{
		$result = null;

		if ($item = DB::select(DB::raw(sprintf('
            /*getParentsId()*/
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


	public static function setParentsId($partner1)
	{
		$result = null;
		
		if ($parentsId = DB::table('parents')->insertGetId(array(
		    'partner1' => $partner1
		))) {
		    $result = $parentsId;
		};
		
		return $result;
	}


	public function getParents($full = false)
    {
    	$result = array();
    	//first find parents id
    	if ($parentId = DB::select(DB::raw(sprintf('
			SELECT
    			*
			FROM
			    family
			WHERE
			    kind = %d;
   	        ', $this->personsId)))) {

   	        if ($parents = DB::select(DB::raw(sprintf('
				SELECT
	    			*
				FROM
				    parents
				WHERE
				    id = %d;
	   	        ', $parentId[0]->parents_id)))) {
	   	        	//echo __FILE__.__LINE__.'<pre>$parents='.htmlentities(print_r($parents,1)).'</pre>';die;

   	                if (!is_null($parents[0]->partner1))
	   	        	$result[] = $parents[0]->partner1;
   	                if (!is_null($parents[0]->partner2))
	   	        	$result[] = $parents[0]->partner2;

	   	        	if ($full) {
	   	        		$result = array();
	   	        		if (!is_null($parents[0]->partner1))
        				//$result[] = self::getInfo($parents[0]->partner1);
        				$result[] = new Person($parents[0]->partner1);
	   	        		if (!is_null($parents[0]->partner2))
        				//$result[] = self::getInfo($parents[0]->partner2);
	   	        		$result[] = new Person($parents[0]->partner2);
	   	        	}
   	        }
    	}
    	return $result;
    }


	public function getPartners($full = false)
	{
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
   	        ', $this->personsId, $this->personsId)))) {
			//echo __FILE__.__LINE__.'<pre>$items='.htmlentities(print_r($items,1)).'</pre>';die;

			foreach ($items as $item) {
				if (!is_null($item->partner1))
				if ($this->personsId != $item->partner1)
					$result[$item->id] = $item->partner1;

				if (!is_null($item->partner2))
				if ($this->personsId != $item->partner2)
					$result[$item->id] = $item->partner2;
			}
			//echo __FILE__.__LINE__.'<pre>$result='.htmlentities(print_r($result,1)).'</pre>';die;

			if ($full) {
				$result = array();
				foreach ($items as $item) {
					if (!is_null($item->partner1))
					if ($this->personsId != $item->partner1)
						$result[$item->id] = new Person($item->partner1);

					if (!is_null($item->partner2))
					if ($this->personsId != $item->partner2)
						$result[$item->id] = new Person($item->partner2);
				}
				//echo __FILE__.__LINE__.'<pre>$result='.htmlentities(print_r($result,1)).'</pre>';die;
			}
		}
		return $result;
	}


    /*
     * Can be more than one in fe. in case of seperated parents
     * */
	public function getFamilies()
	{
	    $result = array();
	    if ($items = DB::select(DB::raw(sprintf('
			SELECT
                *
			FROM
			    family
	        LEFT JOIN
	            parents ON family.parents_id = parents.id
			WHERE
			    kind = %d;
   	        ', $this->personsId)))) {
            $result = $items;
	    }
	    return $result;
	}

	
	public static function getByUser($user)
	{
		#with invitations
		return DB::select(DB::raw(sprintf('
			SELECT
			    DISTINCT CONCAT(IFNULL(names.lastname ,""), ", ", IFNULL(names.firstname ,""), " ", IFNULL(names.middlename,"")) AS displayname,
			    A.id,
			    A.persons_id as authorId,
			    IF (A.persons_id = %d, 0, 1) as invited,
			    persons.id,
			    entry_id,
			    image_id,
			    data,
			    names.*,
			    persons.*
			FROM
			    individuum
			    LEFT JOIN names ON individuum.persons_id = names.persons_id
			    LEFT JOIN persons ON individuum.persons_id = persons.id
			    LEFT JOIN document_entities A ON individuum.entry_id = A.id
			WHERE
			    (A.document_type_id = 63 AND enabled=1 AND A.persons_id = %d)
			    OR (A.document_type_id = 63 AND enabled=1 AND A.id IN (SELECT document_entity FROM document_permissions WHERE user_id = %d))
				AND names.lastname <> ""
			ORDER BY
				names.lastname;
			', $user->id, $user->id, $user->id)));
		
		#without invitations
		return DB::select(DB::raw(sprintf('
	        SELECT
			    /*DISTINCT CONCAT(IFNULL(names.firstname,""), " ", IFNULL(names.middlename,""), " ", IFNULL(names.lastname  ,"")) AS fullname,*/
			    DISTINCT CONCAT(IFNULL(names.lastname ,""), ", ", IFNULL(names.firstname ,""), " ", IFNULL(names.middlename,"")) AS displayname,
			    persons.id,
			    document_entities.persons_id as authorId,
			    entry_id,
			    image_id,
			    data,
			    names.*,
			    persons.*
			FROM
			    individuum
			    LEFT JOIN names ON individuum.persons_id = names.persons_id
			    LEFT JOIN persons ON individuum.persons_id = persons.id
			    LEFT JOIN document_entities ON individuum.entry_id = document_entities.id
			WHERE
			    document_entities.persons_id = %d
				and names.lastname <> ""
			ORDER BY
				names.lastname;
			', $user->id)));
	}
	

	public function getChildren($full = false)
    {
    	$result = array();
    	if ($items = DB::select(DB::raw(sprintf('
	    	SELECT DISTINCT
	    	  `family`.`kind`,
	    	  family.id
	    	FROM
	    	  `family`
	    	  LEFT JOIN `parents` ON `family`.`parents_id` = `parents`.`id`
	    	WHERE
	    	  (`parents`.`partner1` = %d OR `parents`.`partner2` = %d);
   	        ', $this->personsId, $this->personsId)))) {
   	        	//echo __FILE__.__LINE__.'<pre>items='.htmlentities(print_r($items,1)).'</pre>';die;
   	        	foreach ($items as $child) {
                    $person = new Person($child->kind);
   	        		$parents = $person->getParents();
   	        		$result[$child->kind]['familyId'] = $child->id;
   	        		$result[$child->kind]['parents'] = $parents;
   	        	}

   	        	if ($full) {
   	        		$result = array();
   	        	   	foreach ($items as $child) {
   	        	   		//$result[] = self::getInfo($child->kind);
   	        	   		$result[] = new Person($child->kind);
   	        	   	}
   	        	}
    	}
    	//echo __FILE__.__LINE__.'<pre>$result='.htmlentities(print_r($result,1)).'</pre>';die;
    	return $result;
    }


	public static function namesInsert($personsId, $data)
	{
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
		if (DB::table(self::TABLE_NAMES)->insert(array(
			'persons_id'		=> $personsId,
			'character_set_id'	=> 'latin',
			'firstname'			=> $data['firstname'],
			'middlename'		=> $data['middlename'],
			'lastname'			=> $data['lastname'],
			'nickname'			=> isset($data['nickname']) ? $data['nickname'] : null,
			'maidenname'		=> isset($data['maidenname']) ? $data['maidenname'] : null
		))) {
			$result = true;
		};
		//echo __FILE__.__LINE__.'<pre>$queryId='.htmlentities(print_r($queryId,1)).'</pre>';die;
		return $result;

	}


	public static function namesUpdate($personsId, $data)
	{
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
		return DB::table(self::TABLE_NAMES)
    			->where('persons_id', $personsId)
    			->update(array(
    				//'persons_id'		=> $personsId,
    				'character_set_id'	=> 'latin',
    				'firstname'			=> $data['firstname'],
    				'middlename'		=> $data['middlename'],
    				'lastname'			=> $data['lastname'],
    				'nickname'			=> $data['nickname'],
    				'maidenname'		=> @$data['maidenname']
    			));
	}


	public static function personsInsert($data)
	{
		/* TABLE `persons` (
		 `id` int(11) NOT NULL AUTO_INCREMENT,
		 `hide` int(1) NOT NULL DEFAULT '0',
		 `gender` enum('U','M','F') DEFAULT 'U',
		 `date_of_birth` datetime DEFAULT NULL,
		 `date_of_death` datetime DEFAULT NULL,
		 `place_of_birth` int(11) DEFAULT NULL,
			*/
		//echo __FILE__.__LINE__.'<pre>$data='.htmlentities(print_r($data,1)).'</pre>';
		
		//2xxx
		/*		
		//from gedcom import
		if (isset($data['date_of_birth']) && strpos($data['date_of_birth'], '-') == false) {
			$dob = $data['date_of_birth'] = $data['date_of_birth'] . '-00-00 00:00:00';
		}
		if (isset($data['date_of_death']) && strpos($data['date_of_death'], '-') == false) {
			$dod = $data['date_of_death'] = $data['date_of_death'] . '-00-00 00:00:00';
		}
		
		//from add entry
		if (isset($data['year_of_birth'])) {
			$dob = @floatval($data['year_of_birth'])>1000 ? Carbon::createFromDate($data['year_of_birth'], 0, 0) : null;
		}
		if (isset($data['year_of_death'])) {
			$dod = @floatval($data['year_of_death'])>1000 ? Carbon::createFromDate($data['year_of_death'], 0, 0) : null;
		}
		*/
		$dob = null; $dod = null;
		
		//from gedcom import
		if (isset($data['date_of_birth'])) {
			$dob = self::formatPersonDate($data['date_of_birth']);
		}
		if (isset($data['date_of_death'])) {
			$dod = self::formatPersonDate($data['date_of_death']);
		}
		
		//from add entry
		if (isset($data['year_of_birth'])) {
			$dob = self::formatPersonDate($data['year_of_birth']);
		}
		if (isset($data['year_of_death'])) {
			$dod = self::formatPersonDate($data['year_of_death']);
		}
		
		$result = 0;
		if ($queryId = DB::table(self::TABLE_PERSONS)->insertGetId(array(
			'hide'			=> 0,
			'gender'		=> $data['gender'],
			'date_of_birth'	=> $dob,
			'date_of_death'	=> $dod,
			'gedcom_id'     => @$data['gedcom_id'] ? $data['gedcom_id'] : null, 
		))) {
			$result = $queryId;
		};
		return $result;

	}


	public static function personsUpdate($data)
	{
		/* TABLE `persons` (
		 `id` int(11) NOT NULL AUTO_INCREMENT,
		 `hide` int(1) NOT NULL DEFAULT '0',
		 `gender` enum('U','M','F') DEFAULT 'U',
		 `date_of_birth` datetime DEFAULT NULL,
		 `date_of_death` datetime DEFAULT NULL,
		 `place_of_birth` int(11) DEFAULT NULL,
			*/
		//echo __FILE__.__LINE__.'<pre>$data='.htmlentities(print_r($data,1)).'</pre>';die;
		
		/*
		//from add entry
		if (isset($data['year_of_birth'])) {
			$dob = @floatval($data['year_of_birth'])>1000 ? Carbon::createFromDate($data['year_of_birth'], 1, 1) : null;
		}
		if (isset($data['year_of_death'])) {
			$dod = @floatval($data['year_of_death'])>1000 ? Carbon::createFromDate($data['year_of_death'], 1, 1) : null;
		}
		*/
		$dob = null; $dod = null;
		
		//from add entry
		if (isset($data['year_of_birth'])) {
			$dob = self::formatPersonDate($data['year_of_birth']);
		}
		if (isset($data['year_of_death'])) {
			$dod = self::formatPersonDate($data['year_of_death']);
		}
		if (isset($data['date_of_birth'])) {
			$dob = self::formatPersonDate($data['date_of_birth']);
		}
		if (isset($data['date_of_death'])) {
			$dod = self::formatPersonDate($data['date_of_death']);
		}
		
		return DB::table(self::TABLE_PERSONS)
        		->where('id', $data['personsId'])
        		->update(array(
        			'hide'			=> 0,
        			'gender'		=> $data['gender'],
        			'date_of_birth'	=> $dob,
      				'date_of_death'	=> $dod
        		));
	}


	public static function individuumInsert($personsId, $entry_id, $image_id, $data = array())
	{
		/* TABLE `individuum` (
		 `persons_id` int(11) NOT NULL DEFAULT '0',
		 `entry_id` int(11) NOT NULL DEFAULT '0',
		 `image_id` int(11) DEFAULT NULL,
		 `data` mediumtext, XML?! <still_living></still_living><year_of_birth>1964</year_of_birth><profession>Manager</profession><country_of_birth>Australia</country_of_birth><state_of_birth>NSW</state_of_birth><city_of_birth>Sydney</city_of_birth><country_of_death></country_of_death><state_of_death></state_of_death><city_of_death></city_of_death><religion></religion><education></education>
		 */

		$result = false;
		if (DB::table(self::TABLE_INIDIVIDUUM)->insert(array(
			'persons_id'	=> $personsId,
			'entry_id'		=> $entry_id,
			'image_id'		=> $image_id,
			'data'			=> json_encode($data)
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
		/* TABLE `individuum` (
		 `persons_id` int(11) NOT NULL DEFAULT '0',
		 `entry_id` int(11) NOT NULL DEFAULT '0',
		 `image_id` int(11) DEFAULT NULL,
		 `data` mediumtext, XML?! <still_living></still_living><year_of_birth>1964</year_of_birth><profession>Manager</profession><country_of_birth>Australia</country_of_birth><state_of_birth>NSW</state_of_birth><city_of_birth>Sydney</city_of_birth><country_of_death></country_of_death><state_of_death></state_of_death><city_of_death></city_of_death><religion></religion><education></education>
		 */

		return DB::table(self::TABLE_INIDIVIDUUM)
    			->where('persons_id', $personsId)
    			->update(array(
    				'image_id'		=> $image_id,
    				'data'			=> $data
    			));
	}


	/**
	 * Hide names from living persons as not to infringe their privacy.
	 * @param unknown $person
	 */
	public static function buildDescription($person)
	{
		//fixme
		if (!is_object($person)) {return '?';}
		
		$maidenname = empty($person->maidenname) ? '' : '('.e($person->maidenname).') ';
		if ($person->middlename) {
			$result = sprintf('%s%s %s %s %s', $maidenname, e($person->firstname), e($person->middlename), e($person->lastname), self::formatPersonYearDates($person));
		} else {
			$result = sprintf('%s%s %s %s', $maidenname, e($person->firstname), e($person->lastname), self::formatPersonYearDates($person));
		}
		
		//anonimize
		if ($person instanceof Person)
		if ($person->isLiving()) {
			$result = 'Living';
		}
		
		
		if (\Config::get('app.debug')) {
			$result = sprintf('(%d) ', $person->id) . $result;
		}
		
		return $result;
	}
	
	
	public function isLiving()
	{
		return ($this->date_of_death == '' && @$this->year_of_death == '');
	}

	
	public static function formatPersonDate($date) {
		//$date = '2000';
		//$date = '2000-02';
		//$date = '2000-2';
		//$date = '2000-02-04';
		//$date = '2000-12-04';
		//$date = '2000-1-4';
		$result = null;

		//full date given
		if (preg_match('/^([0-9]{4})-([0-9]+)-([0-9]+)/', $date, $m)) {
			$result = sprintf('%d-%02d-%02d', $m[1], $m[2], $m[3]);
		} else
		//only year & month given
		if (preg_match('/^([0-9]{4})-([0-9]+)/', $date, $m)) {
			$result = sprintf('%d-%02d-00', $m[1], $m[2]);
		} else
		//only year given
		if (preg_match('/^([0-9]{4})/', $date, $m)) {
			$result = sprintf('%d-00-00', $m[1]);
		}
		return $result;
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


	public static function formatPersonYearDates($name)
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


	public static function formatPersonDate2($date = '0000-00-00 00:00:00', $yearOnly = false)
	{
		/*
		$date = '1931-00-00 00:00:00';
		$date = '1931-02-00 00:00:00';
		$date = '1931-02-04 00:00:00';

		$date = '1890-00-00 00:00:00';
		$date = '1890-01-02 00:00:00';

		http://kfn-tree.laravel.debian.mirror.virtec.org/en/family-trees/382/info
		http://kfn-tree.laravel.debian.mirror.virtec.org/en/family-trees/5855/info
		http://kfn-tree.laravel.debian.mirror.virtec.org/en/family-trees/501/info
		*/

		$result = '';
		if (preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})/', $date, $match)) {
			$has_y = intval($match[1]) ? 1 : 0; $y = intval($match[1]);
			$has_m = intval($match[2]) ? 1 : 0; $m = intval($match[2]);
			$has_d = intval($match[3]) ? 1 : 0; $d = intval($match[3]);
			if ($yearOnly) {
				$has_m = false;
				$has_d = false;
			}

			if ($has_y && $has_m && $has_d) {
				//$result = date('d.m.Y', mktime(0, 0, 0, $m, $d, $y));
				$result = Carbon::createFromDate($y, $m, $d)->format('d.m.Y');
			} else
			if ($has_y && $has_m) {
				//$result = date('m.Y', mktime(0, 0, 0, $m, 1, $y));
				$result = Carbon::createFromDate($y, $m, 1)->format('m.Y');
			} else
			if ($has_y) {
				//$result = date('Y', mktime(0, 0, 0, 1, 1, $y));
				$result = Carbon::createFromDate($y, 1, 1)->format('Y');
			} else {
				//$result = 'N/A';
			}
		}
		return $result;
	}


	public static function AjaxGetPersonInfo($persons_id)
	{
	    $fields = array();
	    /*
		$result=stdClass Object
		(
		    [personsId] => 4
		    [fullname] => child[firstname] child[middlename]
		    [id] => 4
		    [authorId] => 1
		    [did] => 23490
		    [image_id] => 
		    [data] => {"personsId":"1","parentId":"2","existingId":"0","firstname":"child[firstname]","middlename":"child[middlename]","lastname":"child[lastname]","nickname":"child[nickname]","maidenname":"child[maidenname]","gender":"F","year_of_birth":"2000","country_of_birth":"child[country_of_birth]","state_of_birth":"child[state_of_birth]","city_of_birth":"child[city_of_birth]","year_of_death":"","profession":"child[profession]","religion":"child[religion]","education":"child[education]","life_story":"child[life_story]","title":"child[firstname] child[lastname]","content":"child[life_story]"}
		    [persons_id] => 4
		    [character_set_id] => latin
		    [firstname] => child[firstname]
		    [middlename] => child[middlename]
		    [lastname] => child[lastname]
		    [nickname] => child[nickname]
		    [maidenname] => child[maidenname]
		    [hide] => 0
		    [gender] => F
		    [date_of_birth] => 2000-01-01 01:57:11
		    [date_of_death] => 
		    [place_of_birth] => 
		    [parentId] => 2
		    [existingId] => 0
		    [year_of_birth] => 2000
		    [country_of_birth] => child[country_of_birth]
		    [state_of_birth] => child[state_of_birth]
		    [city_of_birth] => child[city_of_birth]
		    [year_of_death] => 
		    [profession] => child[profession]
		    [religion] => child[religion]
		    [education] => child[education]
		    [life_story] => child[life_story]
		    [title] => child[firstname] child[lastname]
		    [content] => child[life_story]
		)
	    */

	    if (\Config::get('app.debug')) {
	    $fields['personsId'] = 'pid'; // 2949
	    $fields['did'] = 'did'; // 2949
	    }

	    $fields['thumb']         = 'Photo';
	    
	    $fields['fullname'] 	 = 'Fullname';
	    $fields['nickname'] 	 = 'Nickname';
	    $fields['maidenname'] 	 = 'Maidenname';
	    
	    $fields['date_of_birth'] = 'Year of birth';
	    $fields['city_of_birth'] = 'Place of birth';
	    
	    $fields['date_of_death'] = 'Year of death';
	    $fields['city_of_death'] = 'Died in';
	    
	    $fields['profession']    = 'Profession';
	    $fields['religion']      = 'Religion';
	    $fields['education']     = 'Education';
	    
	    $fields['life_story']    = 'Life story';


		$result = new \stdClass();
		if ($subject = new Person($persons_id)) {
			foreach ($subject as $k=>$v) {
				$result->$k = $v;
			}

			if ($entry = DocumentPerson::find($subject->did)) {
				$view = ViewEntity::build($entry);
				$result->thumb = $view->getImage();
				$result->image = $view->getImage(420, 0, true);

				$result->name = $entry->title->getValue();
				$result->title = $entry->title->getValue();
				$result->content = $entry->content->getValue();

				/*
                #convert xml data to json
				$data   = $entry->content->getValue() ? $entry->content->getValue() : $entry->data->getValue();
				$simple = '<root>'.$data.'</root>';
				$p = xml_parser_create();
				xml_parse_into_struct($p, $simple, $vals, $index);
				xml_parser_free($p);
				foreach ($vals as $v) {
					$key = strtolower($v['tag']);
					$value = isset($v['value']) ? $v['value'] : '';
					$result->$key = $value;
				}
				*/
			}
		}
		//echo __FILE__.__LINE__.'<pre>$result='.htmlentities(print_r($result,1)).'</pre>';

		$h = '';
		$h.= '<style>
    			table {border:none;}
    			table.i {xwidth:80%}
    			table.i tr {vertical-align:top;xborder-top:1px solid #ccc}
    			table.i td {padding:5px}
    			td.k {font-weight:700;width:130px}
    			</style>';
		$h.= sprintf('<div class="row">');

		$h.= sprintf('<div class="col-md-12">');
		$h.= sprintf('<h3>%s</h3>', self::buildDescription($subject));
		$h.= '</div>';

		$h.= sprintf('<div class="col-md-6">');
		$h.= '<table class="i" xborder=1>';
		
		foreach ($fields as $k=>$label) {
			if (!isset($result->{$k})) continue;
			$v = trim($result->{$k});
			$c = 1;
			switch($k) {
				case 'date_of_birth':
				case 'date_of_death':
					$v = self::formatPersonDate2($subject->$k, true);
				break;
				case 'thumb':
					$v = sprintf('<img src="%s" alt="%s"/>', $v, self::buildDescription($subject));
					$c = 2;
				break;
			}
			if (empty($v)) continue;
			if ($c==1)
				$h.=sprintf('<tr><td class="k">%s:</td><td>%s</td></tr>', $label, $v);
			else 
				$h.=sprintf('<tr><td class="k" colspan="2">%s</td></tr>', $v);
		}
		$h.= '</table>';
		$h.= '</div>';

		$h.= sprintf('</div>');

		return $h;
	}


	public static function getImage($entryId)
	{
		//$entryId = 23464;
		$result = '';
		if ($entry = DocumentPerson::find($entryId)) {
			$view = ViewEntity::build($entry);
			$result = $view->getImage();
		}
		//return 'http://kfn.laravel.debian.mirror.virtec.org/en/media/homerow/8/14737543255736.jpg';
		return $result;
	}


	public static function getTree($entryId, $personsId)
	{
		$result = array();
		$parents = [];
		$partners = [];

		$person = new Person($personsId);

		//set subject
		$result[] = array(
			'id'=>$person->id,
			'entry_id'=>$entryId,
			'parents'=>$parents,
			'spouses'=>$partners,
			//'title'=>'',
			//'label'=>'',
			//'description'=> sprintf('%s %s %s %s', $person->firstname, $person->middlename, $person->lastname, self::formatPersonDates($person)),
			'description'=> self::buildDescription($person),
			'image'=> self::getImage($entryId)
		);
		$subject = &$result[0];

		//add parents
		if ($parentIds = $person->getParents()) {
			$parent_partners = array();
			foreach ($parentIds as $parentId) {
				$parent_partners[] = $parentId;
			}

			foreach ($parentIds as $parentId) {
				$parent = new Person($parentId);
				if ($parent) {
					$result[] = array(
						'id'=>$parent->id,
						'entry_id'=>$parent->did,
						'parents'=>$parents,
						'spouses'=>array_diff($parent_partners, array($parent->id)),
						//'title'=>'',
						//'label'=>'',
						//'description'=> sprintf('%s %s %s %s', $parent->firstname, $parent->middlename, $parent->lastname, self::formatPersonDates($parent)),
						'description'=> self::buildDescription($parent),
						//'image'=> "/assets/vendors/primitives/images/photos/avatar1.png"
						'image'=> self::getImage($parent->did)
					);
				}
				$subject['parents'][] = $parentId;
			}
		}

		//add partners
		if ($items = $person->getPartners()) {
			foreach ($items as $itemId) {
				if ($itemId == $personsId) continue;
				//$item = self::getInfo($itemId);
				$item = new Person($itemId);
				if ($item)
					$result[] = array(
						'id'=>$item->id,
						'entry_id'=>$item->did,
						'parents'=>$parents,
						//'spouses'=>[$result[0]['id']],
						'spouses'=>[$subject['id']],
						//'title'=>'',
						//'label'=>'',
						//'description'=> sprintf('%s %s %s %s', $item->firstname, $item->middlename, $item->lastname, self::formatPersonDates($item)),
						'description'=> self::buildDescription($item),
						//'image'=> "/assets/vendors/primitives/images/photos/avatar1.png"
						'image'=> self::getImage($item->did)
					);
			}
			foreach ($items as $itemId) {
				if ($itemId == $personsId) continue;
				$partners[] = $itemId;
			}
		}
		$subject['spouses'] = $partners;

		//add children
		if ($items = $person->getChildren()) {
			foreach ($items as $childId => $parents) {
				$child = new Person($childId);
				if ($child) {
					//http://kfn-tree.laravel.debian.mirror.virtec.org/en/family-trees/1692
					$child_spouses = array();
					if ($items2 = $child->getPartners()) {
						foreach ($items2 as $itemId2) {
							if ($itemId2 == $childId) continue;
							//$item2 = self::getInfo($itemId2);
							$item2 = new Person($itemId2);
							if ($item2) {
								$result[] = array(
									'id'=>$item2->id,
									'entry_id'=>$item2->did,
									'parents'=>[],
									'spouses'=>[$child->id],
									//'title'=>'',
									//'label'=>'',
									//'description'=> sprintf('%s %s %s %s', $item2->firstname, $item2->middlename, $item2->lastname, self::formatPersonDates($item2)),
									'description'=> self::buildDescription($item2),
									//'image'=> "/assets/vendors/primitives/images/photos/avatar1.png"
									'image'=> self::getImage($item2->did)
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
						//'title'=>'',
						//'label'=>'',
						//'description'=> sprintf('%s %s %s %s', $child->firstname, $child->middlename, $child->lastname, self::formatPersonDates($child)),
						'description'=> self::buildDescription($child),
						//'image'=> "/assets/vendors/primitives/images/photos/avatar1.png"
						'image'=> self::getImage($child->did)
					);
				}
			}
		}

		return $result;
	}


	public static function deletePartner($personsId, $relativeId)
	{
		$result = true;
		$person = new Person($personsId);
		//check if partner exists
		if (in_array($relativeId, $partners = $person->getPartners())) {
			//delete
			if ($id = array_search($relativeId, $partners)) {
				if (DB::table(self::TABLE_PARENTS)->where('id', $id)->delete()) {
					$result = true;
				}
			}
		}
		return $result;
	}


	public static function deleteChild($personsId, $relativeId)
	{
		$result = false;
		$person = new Person($personsId);
		//check if child exists
		if (array_key_exists($relativeId, $children = $person->getChildren())) {
			//delete
			if (($child = $children[$relativeId]) && DB::table(self::TABLE_FAMILY)->where('id', $child['familyId'])->delete()) {
				$result = true;
			}
		}
		return $result;
	}


	public static function deleteParent($personsId, $relativeId)
	{
		$result = false;
		echo __FILE__.__LINE__.'<pre>$relativeId='.htmlentities(print_r($relativeId,1)).'</pre>';
		$person = new Person($personsId);
		echo __FILE__.__LINE__.'<pre>$person='.htmlentities(print_r($person,1)).'</pre>';
		//$parentIds = $person->getParents(true);echo __FILE__.__LINE__.'<pre>$parentIds='.htmlentities(print_r($parentIds,1)).'</pre>';
		$families = $person->getFamilies();
		echo __FILE__.__LINE__.'<pre>$families='.htmlentities(print_r($families,1)).'</pre>';

		foreach ($families as $family) {
		    if ($family->partner1 == $relativeId || $family->partner2 == $relativeId) {
		        $partner1 = $family->partner1;
		        $partner2 = $family->partner2;

		        if ($family->partner1 == $relativeId) {
		            $partner1 = null;
		        } else
		        if ($family->partner2 == $relativeId) {
		            $partner2 = null;
		        }

		        if (is_null($partner1) && is_null($partner2)) {
		            //delete partners
		            DB::table(self::TABLE_PARENTS)->where('id', $family->parents_id)->delete();

		            //delete family
		            DB::table(self::TABLE_FAMILY)->where('id', $family->id)->delete();
		        } else
	            if (is_null($partner1)) {
	                //update partner1 set null
	                DB::table(self::TABLE_PARENTS)->where('id', $family->parents_id)->update(array('partner1' => $partner1));
		        } else
	            if (is_null($partner2)) {
	                //update partner2 set null
	                DB::table(self::TABLE_PARENTS)->where('id', $family->parents_id)->update(array('partner2' => $partner2));
	            }

		        $result = true;
		    }
		}
		return $result;
	}


	public static function deleteRelative($personsId, $member, $relativeId)
	{
		switch($member) {
			case Person::MEMBER_PARENT:
			    return self::deleteParent($personsId, $relativeId);
			break;
			case Person::MEMBER_PARTNER:
				return self::deletePartner($personsId, $relativeId);
			case Person::MEMBER_SPOUSE:
			break;
			case Person::MEMBER_PERSON:
			break;
			case Person::MEMBER_CHILD:
				return self::deleteChild($personsId, $relativeId);
			break;
			default:
				throw new Exception('Undefined member: '.$member);
		}
	}


	/**
	 * @param \GedcomIndividual $individual
	 * @return unknown
	 * TODO: ALTER TABLE `persons` ADD `gedcom_id` INT NULL AFTER `id`;
	 */
	public static function setGedcomPerson(\GedcomIndividual $individual) 
	{
		//echo __FILE__.__LINE__.'<pre>$individual='.htmlentities(print_r($individual,1)).'</pre>';
		
		$data = [];
		#DocumentPerson fields
		$data['title']     = sprintf('%s %s', $individual->first_name, $individual->last_name);
		$data['content']   = $individual->getNotes();
		#Person fields
		$data['gedcom_id'] = $individual->gedcom_id;
		$data['date_of_birth'] = ($birth = $individual->birth()) ? $birth->date : null;
		$data['date_of_death'] = ($death = $individual->death()) ? $death->date : null;
		$data['gender']        = strtoupper($individual->sex);
		#Individuum fields
		$data['still_living']  = ($death && ($death->date || $death->valuestring == 'Y')) ? 'no' : 'yes';
		
		$data['profession'] = ($value = $individual->eventsByType('OCCU')->first()) ? $value->valuestring : null;
		$data['religion']   = ($value = $individual->eventsByType('RELI')->first()) ? $value->valuestring : null;
		$data['education']  = ($value = $individual->eventsByType('EDUC')->first()) ? $value->valuestring : null;
		$data['note']       = $individual->getNotes();

		$data['year_of_birth'] = ($birth = $individual->birth()) ? $birth->date . ' 00:00:00' : null;
		$place = $individual->getPlace('BIRT');
		$data['country_of_birth'] = $place['country'];
		$data['state_of_birth'] = $place['state'];
		$data['city_of_birth'] = $place['city'];

		$data['year_of_death'] = ($death && $death->date) ? $death->date . ' 00:00:00' : null;
		$place = $individual->getPlace('DEAT');
		$data['country_of_death'] = $place['country'];
		$data['state_of_death'] = $place['state'];
		$data['city_of_death'] = $place['city'];

		#Individuum fields
		//$data['firstname']  = $individual->first_name . ' ('.$individual->gedcom_key.')';
		$data['firstname']  = $individual->first_name;
		$data['lastname']   = $individual->last_name;
		$data['nickname']   = ($value = $individual->eventsByType('NICK')->first()) ? $value->valuestring : null;
		$data['middlename'] = null;
		$data['maidenname'] = null;
		$data['gedcom_key'] = $individual->gedcom_key;
		//echo __FILE__.__LINE__.'<pre>$data='.htmlentities(print_r($data,1)).'</pre>';
		
		return DocumentPerson::add($data);
	}
	
	/*
	public static function build($personsId)
	{
		static $result = array();

		$person = new Person($personsId);
		$person->parents  = $person->getParents();
		$person->partners = $person->getPartners();
		$person->children = $person->getChildren();
		$result[$personsId] = $person;

		foreach ($person->parents as $id) {}
		foreach ($person->partners as $id) {
			if (!array_key_exists($id, $result))
			$result = array_merge($result, self::build($id));
		}

		return $result;

	}
	*/

}