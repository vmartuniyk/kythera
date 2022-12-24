<?php

namespace Kythera\Support;

class ViewEntity
{
	const VIEW_MODE_VIEW        = 1;
	const VIEW_MODE_LIST        = 2;
	const VIEW_MODE_SIDEBAR     = 3;
	const VIEW_MODE_HOME        = 4;
	const VIEW_MODE_HOMEROW     = 5;
	const VIEW_MODE_KEYSMALL    = 6;
	const VIEW_MODE_KEYS        = 7;
	const VIEW_MODE_FOOTER      = 8;
	const VIEW_MODE_GUESTBOOK   = 9;
	const VIEW_MODE_MESSAGE     = 10;

	const IMAGE_FULL_WIDTH		= 420;
	const SIDEBAR_WIDTH			= 342;
	const KEYS_WIDTH			= 255;
	const KEYS_HEIGHT   		= 164;
	const BIG_KEYS_WIDTH		= 527;
	const BIG_KEYS_HEIGHT   	= 354;
	const HOMEROW_KEYS_WIDTH	= 165;
	const HOMEROW_KEYS_HEIGHT   = 105;
	const VIEW_MODE_VIEW_WIDTH  = 673;

	const VIEW_MODE_LIST_WIDTH  = 194;
	const VIEW_MODE_LIST_HEIGHT = 145;

	const VIEW_MODE_FOOTER_WIDTH = 75;
	const VIEW_MODE_FOOTER_HEIGHT = 50;


    public static function build($entity, $mode = ViewEntity::VIEW_MODE_VIEW)
    {
    	if (is_array($entity))
    	{
    		$result = array();
    		foreach ($entity as $item)
    		{
    			$result[] = static::build($item, $mode);
    		}
    		return $result;
    	}

        $class = str_replace('Kythera\Models\\', '', get_class($entity));
        $class = 'Kythera\Support\View'. $class;
        if (class_exists($class))
        {
            return new $class($entity, $mode);
        }
        else
        {
            throw new ViewEntityException('Invalid controller type given: '.$class);
        }
    }



    public static function getImage($entry, $width = 165, $height = 105)
    {
        $result = route('image.placeholder', ['width' => $width, 'height'=> $height, 'name' => $entry->title]);
        $result = route('image.view', ['width' => $width, 'height'=> $height, 'path'=>preg_replace("/[^0-9]/","",$entry->image_path), 'name' =>'originalSize_'.$entry->image_name]);
        return $result;
    }

}
