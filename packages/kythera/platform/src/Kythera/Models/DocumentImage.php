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
class DocumentImage extends DocumentEntity
{

    /*
     * UNIQUE KEY `i` (`image_name`,`image_path`),
     * KEY `entry_id` (`entry_id`)
     */

	private $info;


	protected $entity_attributes = array(
	    'uri' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
	    'title' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
	    'content' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
	    'copyright' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
	);

	
	public static $rules = array(
		//todo: |unique:document_attributes,value dependend on category
		'title' => 'required|min:3|max:255',
		'content' => 'required',
		'uri' => 'unique:document_attributes,value'
	);


	public static function getRecent($limit = 3, $document_type_id = array())
	{
	    return parent::getDocuments('DocumentImageController', $limit, $document_type_id);
	}


    public function getImage($width = 165, $height = 105)
	{
		return URL::route('image.view', ['width' => $width, 'height'=> $height, 'path'=>preg_replace("/[^0-9]/","",$this->image_path), 'name' => $this->image_name]);
	}


	/**
	 * TODO: controller_id will be depricated in favor of category ID
	 * @param unknown $data
	 * @param unknown $controller_id
	 * @return \Illuminate\Database\Eloquent\static
	 */
	/*
	public static function add($data, $controller_id = null)
	{
		//determine controller
		$cats = array_unique(array_filter($data['cats']));

		if ($entity = static::create( array(
			'title'             => $data['title'],
			'content'           => $data['content'],
			'uri'               => \Translation::slug($data['title']),
			'enabled'           => 1,
			'persons_id'        => \Auth::user()->id,
			'document_type_id'  => $cats[0]
		))) {
			//handle categories
			$entity->categories()->sync($cats);
		}
		return $entity;
	}
	*/

	private function getUniqueName($ext)
	{
	    return time().rand(1000, 10000).'.'.$ext;
	}


	public function deleteImage()
	{
	    if ($image = \DB::table('document_images')
                ->where('entry_id', $this->id)
                ->first()) {

            //delete from disk
            $filename = public_path().'/'.$image->image_path.$image->image_name;
            if (file_exists($filename))
                unlink($filename);
                                
            //delete db entry
            \DB::table('document_images')
                ->where('entry_id', $this->id)
                ->delete();
        }
	}
	
	
	public function setImage($parent, $data)
	{
		$src  = $data['path'].$data['name'];
		$ext  = strtolower(pathinfo($src, PATHINFO_EXTENSION));
		#bug 5127
		//$name = time().'.'.$ext;
		$name = $this->getUniqueName($ext);
		$dst  = DocumentEntity::getRandomDirectory(\Config::get('files.images'));
		//$date = !empty($data['d']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['d']))) : null;
        $date = DocumentEntity::convertFromGreek($data['d']);


        //remove current image
		\DB::table('document_images')->where('entry_id', $this->id)->delete();

		//store in db
		if ($id = \DB::table('document_images')->insertGetId(array(
			'entry_id' => $this->id,
			'original_image_name' => $data['name'],
			'image_name' => $name,
			'image_path' => $dst,
			'taken' => $date
			//village = $data['v]
			//copyright = $data['c]
		))) {
			/*
			//store in image folder
			$dst1  = public_path().'/'.$dst.$name;
			rename($src, $dst1);
			*/
			/*
			//create copy
			$dst2  = public_path().'/'.$dst.'originalSize_'.$name;
			copy($dst1, $dst2);
			*/

			$this->createThumbs($name, $src, $dst);
		}
		return $id;
		//attach image
		//\DB::table('document_media')->insert(array('entry_id'=>$parent->id, 'image_id'=>$this->id));
	}


	private function createThumbs($name, $src, $dst)
	{
		/*
		 * HOME
		 * key big   : 527 x 354
		 * key medium: 255 x 164
		 * key small : 165 x 111
		 *
		 * LIST
		 * 194 x 145
		 * VIEW
		 * 673 x 0
		 *
		 * SIDEBAR
		 * 165 x 105
		 * */

		/*
		 * file
		 * originalSize_
		 * mediumSize_
		 * textInclude_
		 * thumb_
		 */

		//1. store in image folder
		$dst1  = public_path().'/'.$dst.$name;
		rename($src, $dst1);

		//3. create original
		$dst2 = public_path().'/'.$dst.'originalSize_'.$name;
		copy($dst1, $dst2);

		//2. create max 673px
		$image  = Image::make($dst1);
		$width  = 673;
		$height = $image->height();
		$image->resize($width, $height, function ($constraint) {
			$constraint->aspectRatio();
		} );
		$dst2 = public_path().'/'.$dst.$name;
		$image->save($dst2);

		//4. create medium 50%
		$image  = Image::make($dst1);
		$width  = $image->width()/2;
		$height = $image->height();
		$image->resize($width, $height, function ($constraint) {
			$constraint->aspectRatio();
		} );
		$dst3 = public_path().'/'.$dst.'mediumSize_'.$name;
		$image->save($dst3);

		//5. create text include
		$image = Image::make($dst1);
		$width  = $image->width()/2;
		$height = $image->height();
		$image->resize($width, $height, function ($constraint) {
			$constraint->aspectRatio();
		} );
		$dst4 = public_path().'/'.$dst.'textInclude_'.$name;
		$image->save($dst4);

		//6. create thumb
		$image = Image::make($dst1);
		$width  = $image->width()/2;
		$height = $image->height();
		$image->resize($width, $height, function ($constraint) {
			$constraint->aspectRatio();
		} );
		$dst5 = public_path().'/'.$dst.'thumb_'.$name;
		$image->save($dst5);
	}


	/**
	 * @param DocumentEntity $entity -- depricated
	 * @param array $data
	 * @return integer $id
	 */
	public function updateImage($entity, $data)
	{
		if (!$data['kfnid']) {
			//detach current image
			//\DB::table('document_images')->where(array('entry_id'=>$this->id))->delete();
			$this->deleteImage();

			//insert new in db
			$id = $this->setImage($this, $data);
		} else {
			//update in db
			//$date = !empty($data['d']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['d']))) : null;
			$date = DocumentEntity::convertFromGreek($data['d']);
			\DB::table('document_images')
				->where('id', $data['kfnid'])
				->update(array(
					//'entry_id' => $this->id,
					//'original_image_name' => $data['name'],
					//'image_name' => $name,
					//'image_path' => $dst,
					'taken' => $date
					//village = $data['v]
					//copyright = $data['c]
			));
			$id = $data['kfnid'];
		}
		return $id;
	}


	public function getInfo()
	{
		if (is_null($this->info)) {
			$this->info = \DB::table('document_images')->where('entry_id', $this->id)->first();
			$this->info->type = 'image';
			$this->info->cats = array(0,0,0);
			foreach ($this->categories as $cat) {
				$this->info->cats[] = $cat->pivot->category_id;
			}
			$this->info->taken = date('d/m/Y', strtotime($this->info->taken));
		}
		//$r=$this->info;echo __FILE__.__LINE__.'<pre>$r='.htmlentities(print_r($r,1)).'</pre>';die;
		return $this->info;
	}


	public function getURI()
	{
		return '/'.$this->getInfo()->image_path . $this->getInfo()->image_name;
	}

	/**
	 * Depricated in favor of setImage
	 * @param unknown $data
	 * @param unknown $files
	 */
	public function addImage($data, $files)
	{
	    throw new Exception('Depricated');
	    
		foreach ($files as $i=>$file) {
			$src  = public_path().$file;
			//$ext  = strtolower(pathinfo($src, PATHINFO_EXTENSION));
			$ext  = DocumentEntity::getExtension($src);
			$name = time().'.'.$ext;
			$date = isset($data['d']) ? date('Y-m-d', strtotime($data['d'])) : null;

			/*
			switch($ext) {
				case 'gif':
				case 'png':
				case 'jpg':
					$dst = DocumentEntity::getRandomDirectory(\Config::get('files.images'));
					$id = \DB::table('document_images')->insertGetId(array(
						'entry_id' => $this->id,
						'original_image_name' => basename($file),
						'image_name' => $name,
						'image_path' => $dst,
						'taken' => $date
						//village = $data['v]
						//copyright = $data['c]
					));
					$dst = public_path().'/'.$dst.$name;
				break;
			}
			*/
			if (DocumentEntity::isImage($src)) {
				$dst = DocumentEntity::getRandomDirectory(\Config::get('files.images'));
				$id = \DB::table('document_images')->insertGetId(array(
						'entry_id' => $this->id,
						'original_image_name' => basename($file),
						'image_name' => $name,
						'image_path' => $dst,
						'taken' => $date
						//village = $data['v]
						//copyright = $data['c]
				));
				$dst = public_path().'/'.$dst.$name;

				rename($src, $dst);
			} else
				throw new Exception('Invalid image extension: ' . $ext);
		}
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
		$query = \DB::table('document_images')
				->select('id as kfnid', 'document_images.*')
				->where('document_images.entry_id', $this->id)
				->get();
		return $query;
	}

}