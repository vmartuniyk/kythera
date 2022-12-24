<?php

namespace Kythera\Support;

use Config;
use Illuminate\Support\Str;

class ViewDocumentImage extends ViewDocumentEntity
{

	protected $image_data;


	public function __construct($item, $mode = ViewEntity::VIEW_MODE_VIEW)
	{
		parent::__construct($item, $mode);

		$this->image_data = $this->getImageData();

		switch($mode) {
    		case ViewEntity::VIEW_MODE_HOME:
    		    //342x217, 108x69
    		    /*
						$data = $this->getImageFromContent(342, 217, $item->content);
		        $this->content = Str::words(e(strip_tags($data->content)), 20);
		        $this->image   = $this->getImage(342, 217);
		        */
		        //$this->content = Str::words(e(strip_tags($item->content)), 20);
		        $this->image     = $this->getImage(0, 0);
		        $this->cache     = $this->getImageCache();
		        $this->copyright = $this->getCopyright();
		    break;
    		case ViewEntity::VIEW_MODE_HOMEROW:
    		    //165*105
		        $this->image        = $this->getImage(0, 0);
		        $this->cache        = $this->getImageCache(ViewEntity::VIEW_MODE_HOMEROW);
		        $this->copyright    = $this->getCopyright();
            $this->content      = Str::words(e(strip_tags($item->content)), 20);
		    break;
		    case ViewEntity::VIEW_MODE_KEYS:
				$this->title		= Str::limit($this->title, 40, '..');
		        $this->image		= $this->getImage(255, 0);
		        $this->cache		= $this->getImageCache(ViewEntity::KEYS_WIDTH);
		        $this->copyright	= $this->getCopyright();
	        break;
		    case ViewEntity::VIEW_MODE_KEYSMALL:
				$this->title   = Str::limit($this->title, 40, '..');
		        $this->image   = $this->getImage(165, 111);
		        $this->cache     = $this->getImageCache();
		        $this->copyright = $this->getCopyright();
	        break;
		    case ViewEntity::VIEW_MODE_SIDEBAR:
		        //342x217, ?
		        /*
				$data = $this->getImageFromContent(165, 105, $item->content);
		        $this->content = Str::words(e(strip_tags($data->content)), 20);
		        $this->image   = $this->getImage(0, 0);
		        */
		        $this->content = Str::words(e(strip_tags($item->content)), 20);
		        //$this->image   = $this->getImage(342, 217);
		        //$this->image   = $this->getImage(0, 0);
		        $this->image   = $this->getImage(165, 105);
		        $this->cache     = $this->getImageCache();
		        $this->copyright = $this->getCopyright();
			break;
			case ViewEntity::VIEW_MODE_LIST:
			    //194x145
			    $this->image     = $this->getImage(194, 145);
		        $this->cache     = $this->getImageCache();
			    $this->copyright = $this->getCopyright();
		    break;
			case ViewEntity::VIEW_MODE_FOOTER:
			    //75x0
			    $this->image     = $this->getImage(75);
		      $this->cache     = $this->getImageCache(ViewEntity::VIEW_MODE_FOOTER);
			    $this->copyright = $this->getCopyright();
			    $this->lightbox  = $this->getImage(0, 0, true);
		    break;
			case ViewEntity::VIEW_MODE_VIEW:
				$this->image     = $this->getImage(673, 0);
		        $this->cache     = $this->getImageCache(ViewEntity::VIEW_MODE_VIEW);
		        //$this->image     = $this->cache;
				$this->lightbox  = $this->getImage(0, 0, true);
				$this->copyright = $this->getCopyright();
				if ($data = $this->getImageFromContent(0, 0, $this->content)) {
				    $this->content = $data->content;
				}
		    break;
		    default:
				$this->image     = $this->getImage(0,0);
		        $this->cache     = $this->getImageCache();
				$this->lightbox  = $this->getImage(0, 0, true);
				$this->copyright = $this->getCopyright();
				if ($data = $this->getImageFromContent(0, 0, $this->content)) {
				    $this->content = $data->content;
				}
		}
	}


	protected function getImageData()
	{
		if (Config::get('app.disable_images', false)) {
			return false;
		}

		return \DB::table('document_images')
					->where('entry_id', $this->id)
					->first();
	}


	protected function getCopyright()
	{
		$result = '';
		if ($this->image_data) {
			if ($this->item->copyright && $this->image_data->taken) {
				$year = substr($this->image_data->taken, 0, 4);
				$result = sprintf('Copyright (%s) %s', $year, ucwords($this->item->copyright));
			} else
			if ($this->item->copyright) {
				$result = sprintf('Copyright %s', ucwords($this->item->copyright));
			} else
			if ($this->image_data->taken) {
				$year = substr($this->image_data->taken, 0, 4);
				$result = sprintf('Copyright (%s)', $year);
			} else {
				$result = sprintf('CopyrighT');
			}
		}
		return $result;
	}


	protected function getImage($width = 165, $height = 105, $original = false)
	{
		$result = route('image.placeholder', ['width' => $width, 'height'=> $height, 'name' => $this->title]);

		if ($this->image_data) {
		    if ($original) {
		    	//verify if original exists
		    	$filename = sprintf('%s/%soriginalSize_%s', public_path(), $this->image_data->image_path, $this->image_data->image_name);
		    	if (file_exists($filename)) {
		    		$result = route('image.view', ['width' => $width, 'height'=> $height, 'path'=>preg_replace("/[^0-9]/","",$this->image_data->image_path), 'name' =>'originalSize_'.$this->image_data->image_name]);
		    	} else {
		    		$result = route('image.view', ['width' => $width, 'height'=> $height, 'path'=>preg_replace("/[^0-9]/","",$this->image_data->image_path), 'name' =>$this->image_data->image_name]);
		    	}
		    } else {
		    	$result = route('image.view', ['width' => $width, 'height'=> $height, 'path'=>preg_replace("/[^0-9]/","",$this->image_data->image_path), 'name' =>$this->image_data->image_name]);
		    }
		} else {
		    $result = '';
		}

		return $result;
	}


	protected function getImageCache($width = 165, $height = 105, $original = false)
	{
		$result = route('image.placeholder', ['width' => $width, 'height'=> $height, 'name' => $this->title]);

		#image intervention package
	    if ($this->image_data) {
            switch($width) {
                case ViewEntity::VIEW_MODE_HOMEROW:
                    $path	 = str_replace("photos/","",$this->image_data->image_path);
                    $result = route('imagecache', ['template' => 'homerow', 'filename' => $path.$this->image_data->image_name]);
                break;
                case ViewEntity::BIG_KEYS_WIDTH:
                    $path	 = str_replace("photos/","",$this->image_data->image_path);
                    $result = route('imagecache', ['template' => 'bigkey', 'filename' => $path.$this->image_data->image_name]);
                break;
                case ViewEntity::KEYS_WIDTH:
                    $path	 = str_replace("photos/","",$this->image_data->image_path);
                    $result = route('imagecache', ['template' => 'key', 'filename' => $path.$this->image_data->image_name]);
                break;
                case ViewEntity::VIEW_MODE_VIEW:
                    $path	 = str_replace("photos/","",$this->image_data->image_path);
                    $result = route('imagecache', ['template' => 'view', 'filename' => $path.$this->image_data->image_name]);
                break;
                case ViewEntity::VIEW_MODE_FOOTER:
                    $path	 = str_replace("photos/","",$this->image_data->image_path);
                    $result = route('imagecache', ['template' => 'footer', 'filename' => $path.$this->image_data->image_name]);
                break;
                default:
                    $path	 = str_replace("photos/","",$this->image_data->image_path);
                    $result = route('imagecache', ['template' => 'medium', 'filename' => $path.$this->image_data->image_name]);
            }
	    }

	    return $result;
	}

}
