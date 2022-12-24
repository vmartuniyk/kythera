<?php
namespace Kythera\Models;

use Kythera\Entity\Entity;
use URL;

/**
 * @author virgilm
 *
 */
class DocumentAudio extends DocumentMedia
{
    
    protected $entity_attributes = array(
        'uri' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
        'title' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
        'content' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
        'copyright' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
    );
    

	public static function getRecent($limit = 3, $document_type_id = array())
	{
	    return parent::getDocuments('DocumentAudioController', $limit, $document_type_id);
	}


	public function setMedia($parent_entity, $data)
	{
		$id   = false;
		$src  = $data['path'].$data['name'];
		//$ext  = strtolower(pathinfo($src, PATHINFO_EXTENSION));
		$ext  = DocumentEntity::getExtension($src);
		$name = time().'.'.$ext;
		$dst  = DocumentEntity::getRandomDirectory(\Config::get('files.audio'));
		//$date = !empty($data['d']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['d']))) : null;
        $date = DocumentEntity::convertFromGreek($data['d']);

		//store in db
		if (DocumentEntity::isMedia($src)) {
			//remove current image
			\DB::table('document_audio')->where('entry_id', $this->id)->delete();

			if ($id = \DB::table('document_audio')->insertGetId(array(
					'entry_id' => $this->id,
					'original_audio_name' => $data['name'],
					'audio_name' => $name,
					'audio_path' => $dst,
					'recorded' => $date
					//village = $data['v]
					//copyright = $data['c]
			))) {
				//store in media folder
				$dst1  = public_path().'/'.$dst.$name;
				rename($src, $dst1);

				//attach media
				//\DB::table('document_media')->insert(array('entry_id'=>$parent_entity->id, 'audio_id'=>$this->id));
			}
		}
		return $id;
	}


	public function getFiles()
	{
		/*
			//version 2
			$query = \DB::table('document_media')
			->select('image_id as kfnid', 'document_images.*')
			->leftJoin('document_images', 'document_media.image_id', '=', 'document_images.entry_id')
			->leftJoin('document_audio', 'document_media.audio_id', '=', 'document_audio.entry_id')
			->where('document_media.entry_id', $this->id)
			->get();
			*/

		//version 1
		$query = \DB::table('document_audio')
			->select('id as kfnid', 'document_audio.*')
			->where('document_audio.entry_id', $this->id)
			->get();
		return $query;
	}


	/**
	 * @param DocumentEntity $entity -- depricated
	 * @param array $data
	 * @return integer $id
	 */
	public function updateMedia($entity, $data)
	{
		if (!$data['kfnid']) {
			//detach current media
			\DB::table('document_audio')->where(array('entry_id'=>$this->id))->delete();

			//insert in db
			$id = $this->setMedia($this, $data);
		} else {
			//update in db
			//$date = !empty($data['d']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['d']))) : null;
            $date = DocumentEntity::convertFromGreek($data['d']);
			\DB::table('document_audio')
				->where('id', $data['kfnid'])
				->update(array(
						//'entry_id' => $this->id,
						//'original_image_name' => $data['name'],
						//'image_name' => $name,
						//'image_path' => $dst,
						'recorded' => $date
						//village = $data['v]
						//copyright = $data['c]
				));
			$id = $data['kfnid'];
		}
		return $id;
	}



}
