<?php
namespace Kythera\Models;

use Kythera\Entity\Entity;
use Illuminate\Database\Eloquent\Builder;
use URL;
use Illuminate\Support\Facades\Input;
use Mockery\CountValidator\Exception;
use Intervention\Image\Facades\Image;

/**
 * @author virgilm
 *
 */
class DocumentFamousPerson extends DocumentPerson
{

	public function getPersonsId()
	{
	}


	private function setPersonsId($id)
	{
	}


	/**
	 * @param unknown $data
	 * @param string $depricated
	 * @return Ambigous <NULL, \Illuminate\Database\Eloquent\static>
	 */
	public static function add($data, $depricated = null)
	{
		/*
		 * 1. create document person entity
		 * 2. create person entry
		 * N/A 3. create individuum entry
		 * 4. create names entry
		 * 5. update related names
         * TABLE `persons` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `hide` int(1) NOT NULL DEFAULT '0',
              `gender` enum('U','M','F') DEFAULT 'U',
              `date_of_birth` datetime DEFAULT NULL,
              `date_of_death` datetime DEFAULT NULL,
              `place_of_birth` int(11) DEFAULT NULL,
         * TABLE `names` (
              `persons_id` int(11) NOT NULL DEFAULT '0',
              `character_set_id` varchar(50) NOT NULL DEFAULT '',
              `firstname` varchar(255) DEFAULT NULL,
              `middlename` varchar(255) DEFAULT NULL,
              `lastname` varchar(255) DEFAULT NULL,
              `nickname` varchar(255) DEFAULT NULL,
              `maidenname` varchar(255) DEFAULT NULL,
         */

		$entity = null;
		if ($entity = static::create( array(
			'title'             => $data['title'],
			'content'           => $data['content'],
			'uri'               => '',
			'enabled'           => 1,
			'persons_id'        => \Auth::user()->id, //owner
			'document_type_id'  => 63 //FAMILY_TREE_PERSON
		))) {
			//echo __FILE__.__LINE__.'<pre>$entity='.htmlentities(print_r($entity,1)).'</pre>';

			$personsId = Person::personsInsert($data);
			//$inserted = Person::individuumInsert($personsId, $entity->id, null, 'xml');
			$inserted = Person::namesInsert($personsId, $data);

			$entity->setPersonsId($personsId);

			//make uri unique
			static::uniqueUri($entity);
		}
		return $entity;
	}


	public function set($data, $depricated = null)
	{
		//echo __FILE__.__LINE__.'<pre>$data='.htmlentities(print_r($data,1)).'</pre>';die;
		$entry = null;
		$this->title	= $data['title'];
		$this->content	= $data['content'];
		$this->updateTimestamps();
		if ($entry = $this->save()) {

			/*
			FamilyPerson::personsUpdate($data);
			FamilyPerson::individuumUpdate($data['personsId'], $this->id, null, 'xml');
			FamilyPerson::namesUpdate($data['personsId'], $data);
			*/

			Person::personsUpdate($data);
			//Person::individuumUpdate($data['personsId'], $this->id, null, 'xml');
			Person::namesUpdate($data['personsId'], $data);

			//make uri unique
			static::uniqueUri($entry);
		}
		return $entry;
	}


	public static function findByPersonsId($personsId)
	{
	}




}

