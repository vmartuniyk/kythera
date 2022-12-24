<?php
namespace Kythera\Models;

use Kythera\Models\DocumentEntity;
use URL;

/**
 * @author virgilm
 *
 */
class DocumentText extends DocumentEntity
{
	public static function getRecent($limit = 4, $document_type_id = array())
	{
	    return parent::getDocuments('DocumentTextController', $limit, $document_type_id);
	}

	public function getImage($width = 165, $height = 105)
	{
		return URL::route('image.placeholder', ['width' => $width, 'height'=> $height, 'name' => $this->title]);
	}
}
