<?php
namespace Kythera\Models;

use App\Models\Translation;
use Kythera\Entity\Entity;
use URL;
use Illuminate\Support\Facades\DB;

/**
 * @author virgilm
 *
 */
class DocumentMessage extends DocumentEntity
{

	const CONTROLLER = 18;


	public static $rules_reply = array(
		'content' => 'required|min:3'
	);

	public static $rules_reply_update = array(
		'content' => 'required|min:3',
		'terms' => 'required'
	);


	public static $rules_create = array(
		'title' => 'required|min:3',
		'content' => 'required|min:3',
		'terms' => 'required'
	);


	public static $rules_update = array(
		'title' => 'required|min:3',
		'content' => 'required|min:3',
		'terms' => 'required'
	);


	public static $messages = array(
		'terms.required' => 'The terms of use must be accepted.'
	);


	public static function getRecent($limit = 4, $document_type_id = array())
	{
	    return self::getDocuments('DocumentMessageController', $limit, $document_type_id);
	}


	public static function build($data)
	{
	    $class = 'Kythera\Models\\'. str_replace('Controller', '', $data->controller);
	    if (class_exists($class))
	    {
	        return $class::select('document_entities.*', 'messageboard.parent_id', 'users.firstname', 'users.middlename', 'users.lastname')
    	        ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
    	        ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
    	        ->leftJoin('messageboard', 'document_entities.id', '=', 'messageboard.documents_id')
    	        ->where('document_entities.enabled', 1)
    	        ->where('document_attributes.l', \App::getLocale())
    	        ->where('document_attributes.key', 'title')
    	        ->where('document_entities.id', '=', $data->id)
    	        ->first();
	    }
	    else
	    {
	        throw new DocumentEntityException('Invalid controller type given: '.$class);
	    }
	}


	public static function getReplies($entity)
	{
		$result = null;

		if ($items = DocumentMessage::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
				->withUser()
				->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
				->leftJoin('messageboard', 'document_entities.id', '=', 'messageboard.documents_id')

				->where('document_entities.enabled', 1)
				->where('document_attributes.l', \App::getLocale())
				->where('document_attributes.key', 'content')
				->where('messageboard.parent_id', '=', $entity->id)
				->orderBy('document_entities.created_at')
				->get()) {

					$result = $items;

		}
		return $result;
	}


	public function isParent()
	{
		$result = DB::table('messageboard')
						->select('*')
						->where('documents_id', $this->id)
						->where('parent_id', 0)
						->orderBy('documents_id')
						->orderBy('parent_id')
						->first();
		return $result;
	}


	public function getParent($object = false)
	{
		$result = DB::table('messageboard')
			->select('parent_id')
			->where('documents_id', $this->id)
			->orderBy('documents_id')
			->orderBy('parent_id')
			->pluck('parent_id');

		if ($result && $object) {
			$result = DocumentMessage::find($result);
		}

		return $result;
	}

	/**
	 * TODO: controller_id will be depricated in favor of category ID
	 * @param unknown $controller_id
	 * @return \Illuminate\Database\Eloquent\static
	 */
	public static function add($data, $controller_id = null)
	{
		$data['v'] = null;

		if ($result = static::create( array(
			'title'              => $data['title'],
			'content'            => $data['content'],
			'uri'                => Translation::slug($data['title']),
			'enabled'            => 1,
			'persons_id'         => \Auth::user()->id,
			'document_type_id'   => self::CONTROLLER,
			'related_village_id' => $data['v']
		))) {
			//update categories
			//we dont apply categories for message board items
			//$result->categories()->sync($cats);

			//make uri unique
			static::uniqueUri($result);

			//update relations
			DB::insert(sprintf('INSERT INTO messageboard (documents_id, parent_id) VALUES (%d, %d)', $result->id, 0));
		}
		return $result;
	}


	public function set($data, $controller_id = null)
	{
		$data['v'] = null;

		$this->title			  = $data['title'];
		$this->content			  = $data['content'];
		$this->uri 				  = Translation::slug($data['title']);
		$this->document_type_id   = self::CONTROLLER;
		$this->related_village_id = $data['v'];
		//$this->updateTimestamps();
		if ($result = $this->save()) {
			//update categories
			//we dont apply categories for message board items
			//$this->categories()->sync($cats);

			//make uri unique
			static::uniqueUri($this);
		}
		return $result;
	}

}
