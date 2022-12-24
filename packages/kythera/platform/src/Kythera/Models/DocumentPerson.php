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
class DocumentPerson extends DocumentEntity
{

	/**
	 * @int: Foreign key to persons table.
	 */
	protected $_personsId = null;


	public function getPersonsId()
	{
		if (is_null($this->_personsId))
			$this->_personsId = \DB::table('individuum')->where('entry_id', $this->id)->pluck('persons_id');

		return $this->_personsId;
	}

	private function setPersonsId($id)
	{
		$this->_personsId = $id;
	}

	
	/**
	 * @param array $data
	 * @return \Illuminate\Database\Eloquent\static
	 */
	public static function add($data, $depricated = null)
	{
		/*
         * 1. create document person entity
         * 2. create person entry
         * TABLE `persons` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `hide` int(1) NOT NULL DEFAULT '0',
              `gender` enum('U','M','F') DEFAULT 'U',
              `date_of_birth` datetime DEFAULT NULL,
              `date_of_death` datetime DEFAULT NULL,
              `place_of_birth` int(11) DEFAULT NULL,
         * 3. create individuum entry
         * TABLE `individuum` (
              `persons_id` int(11) NOT NULL DEFAULT '0',
              `entry_id` int(11) NOT NULL DEFAULT '0',
              `image_id` int(11) DEFAULT NULL,
              `data` mediumtext, XML?! <still_living></still_living><year_of_birth>1964</year_of_birth><profession>Manager</profession><country_of_birth>Australia</country_of_birth><state_of_birth>NSW</state_of_birth><city_of_birth>Sydney</city_of_birth><country_of_death></country_of_death><state_of_death></state_of_death><city_of_death></city_of_death><religion></religion><education></education>
         * 4. create names entry
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
			'uri'               => \Translation::slug($data['lastname'] .' '. $data['firstname']),
			'enabled'           => 1,
			'persons_id'        => \Auth::user()->id, //owner
			'document_type_id'  => 63 //FAMILY_TREE_PERSON
		))) {
			$imageId = static::setImage($entity, $data);

			$personsId = Person::personsInsert($data);
			$inserted = Person::individuumInsert($personsId, $entity->id, $imageId, $data);
			$inserted = Person::namesInsert($personsId, $data);

			$entity->setPersonsId($personsId);

			//make uri unique
			static::uniqueUri($entity);
		}
		return $entity;
	}


	public function set($data, $depricated = null)
	{
		$this->title	= $data['title'];
		$this->content	= $data['content'];
		$this->updateTimestamps();
		if ($this->save()) {
			$imageId = static::setImage($this, $data);
			
			Person::personsUpdate($data);
			Person::individuumUpdate($data['personsId'], $this->id, $imageId, json_encode($data));
			Person::namesUpdate($data['personsId'], $data);

			//make uri unique
			static::uniqueUri($this);
		}
		
		return $this;
	}


	public static function findByPersonsId($personsId)
	{
		$entry = null;
		if ($data = self::query()
				->join('individuum', 'id', '=', 'individuum.entry_id')
				->where('individuum.persons_id', $personsId)
				->first()) {
			$entry = $data;
		}
		return $entry;
	}


	public static function setImage($entity, $data) 
	{
		$result = null;
		$mode   = null;
		
		if (!isset($data['file'])) {
			return null;
		}
		
		$file   = $data['file'];
		$data['title'] = $data['title'] . '-' . $entity->id;
		
		//check if already exists
		$documentImage = null;
		if ( ($query = \DB::selectOne(\DB::raw(sprintf('select image_id from individuum where entry_id=%d limit 1;', $entity->id)))) && $query->image_id ) {
			//update
			$documentImage = DocumentImage::find($query->image_id);
			$mode = 'update';
		} else {
			//insert
			$documentImage = DocumentImage::create( array(
				'title'              => $data['title'],
				'content'            => null,
				'uri'                => \Translation::slug($data['title']),
				'enabled'            => 1,
				'persons_id'         => \Auth::user()->id,
				'document_type_id'   => $entity->document_type_id
			));
			$mode = 'insert';
		}
		
		//do we have a file?
 		if ($file && ($file['error']['photo'] == UPLOAD_ERR_OK)) {
			//set image
			$src  = basename($file['name']['photo']);
			$ext  = strtolower(pathinfo($src, PATHINFO_EXTENSION));
			$name = DocumentEntity::getRandomFilename($ext);
			$dst  = DocumentEntity::getRandomDirectory(\Config::get('files.images'));
				
			if (move_uploaded_file($file['tmp_name']['photo'], $dst.$name)) {
				//remove current image
				$documentImage->deleteImage();
				
				$id = \DB::table('document_images')->insertGetId(array(
					'entry_id' => $documentImage->id,
					'original_image_name' => $src,
					'image_name' => $name,
					'image_path' => $dst
				));
			}
		}
		
		return $documentImage->id;
		
		/*
		//set image
		$src  = basename($_FILES['person']['name']['photo']);
		$ext  = strtolower(pathinfo($src, PATHINFO_EXTENSION));
		$name = DocumentEntity::getRandomFilename($ext);
		$dst  = DocumentEntity::getRandomDirectory(\Config::get('files.images'));
		
		if (move_uploaded_file($_FILES['person']['tmp_name']['photo'], $dst.$name)) {
		
			//remove current image
			if ($image = \DB::table('document_images')->where('entry_id', $entity->id)->first()) {
					
				//delete from disk
				$filename = public_path().'/'.$image->image_path.$image->image_name;
				if (file_exists($filename))
					@unlink($filename);
						
					//delete db entry
					\DB::table('document_images')
					->where('id', $image->id)
					->delete();
			}
		
			$id = \DB::table('document_images')->insertGetId(array(
					'entry_id' => $entry->id,
					'original_image_name' => $src,
					'image_name' => $name,
					'image_path' => $dst
			));
		
			$result = $entry->id;
		}
		
		
		
		if ($entry = DocumentImage::create( array(
			'title'              => $data['title'],
			'content'            => null,
			'uri'                => \Translation::slug($data['title']),
			'enabled'            => 1,
			'persons_id'         => \Auth::user()->id,
			'document_type_id'   => $entity->document_type_id
		))) {
			
			//$entry->save();
			
			$src  = basename($_FILES['person']['name']['photo']);
			$ext  = strtolower(pathinfo($src, PATHINFO_EXTENSION));
			$name = DocumentEntity::getRandomFilename($ext);
			$dst  = DocumentEntity::getRandomDirectory(\Config::get('files.images'));
			
			if (move_uploaded_file($_FILES['person']['tmp_name']['photo'], $dst.$name)) {
				
				//remove current image
				if ($image = \DB::table('document_images')->where('entry_id', $entity->id)->first()) {
					
					//delete from disk
					$filename = public_path().'/'.$image->image_path.$image->image_name;
					if (file_exists($filename))
						@unlink($filename);
					
					//delete db entry
					\DB::table('document_images')
						->where('id', $image->id)
						->delete();
				}

				$id = \DB::table('document_images')->insertGetId(array(
					'entry_id' => $entry->id,
					'original_image_name' => $src,
					'image_name' => $name,
					'image_path' => $dst
				));

				$result = $entry->id; 
			}
		}
		
		return $result;
		*/
	}

	
	
}

