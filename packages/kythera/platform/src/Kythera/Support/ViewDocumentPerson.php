<?php

namespace Kythera\Support;

use Config;

class ViewDocumentPerson extends ViewDocumentImage
{

	protected function getImageData()
	{
		if (Config::get('app.disable_images', false)) {
			return false;
		}

		//get image id from individuum
		$imageId = 0;
		if ($query = \DB::selectOne(\DB::raw(sprintf('select image_id from individuum where entry_id=%d limit 1;', $this->id)))) {
			$imageId = $query->image_id;
		}

		return \DB::table('document_images')
			->where('entry_id', $imageId)
			->first();
	}


	public function getImage($width = 165, $height = 105, $original = false)
	{
		$result = route('image.placeholder', ['width' => $width, 'height'=> $height, 'name' => $this->title]);
		$result = '/assets/img/avatar.png';
		if ($this->image_data)  {
			if ($original) {
				$result = route('image.view', ['width' => ViewEntity::IMAGE_FULL_WIDTH, 'height'=> 0, 'path'=>preg_replace("/[^0-9]/","",$this->image_data->image_path), 'name' =>'originalSize_'.$this->image_data->image_name]);
			} else {
				$result = route('image.view', ['width' => $width, 'height'=> $height, 'path'=>preg_replace("/[^0-9]/","",$this->image_data->image_path), 'name' =>$this->image_data->image_name]);
			}
		}
		return $result;
	}

}
