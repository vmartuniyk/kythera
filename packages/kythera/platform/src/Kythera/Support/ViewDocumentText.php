<?php

namespace Kythera\Support;

use Illuminate\Support\Str;

class ViewDocumentText extends ViewDocumentEntity
{

	public function __construct($item, $mode = ViewEntity::VIEW_MODE_VIEW)
	{
		parent::__construct($item, $mode);

		switch($mode)
		{
    		case ViewEntity::VIEW_MODE_HOME:
				$data = $this->getImageFromContent(342, 217, $item->content);
				//$data = $this->getImageFromContent(0, 0, $item->content);
				$this->image   = $data->image;
		        $this->content = Str::words(e(strip_tags($data->content)), 20);
			break;
		    case ViewEntity::VIEW_MODE_HOMEROW:
		    case ViewEntity::VIEW_MODE_SIDEBAR:
				$data = $this->getImageFromContent(165, 105, $item->content);
				$this->image   = $data->image;
		        $this->content = Str::words(e(strip_tags($data->content)), 20);
			break;
		    case ViewEntity::VIEW_MODE_KEYS:
				$data = $this->getImageFromContent(255, 164, $item->content);
				$this->title   = Str::limit($this->title, 40, '..');
				$this->image   = $data->image;
			break;
		    case ViewEntity::VIEW_MODE_KEYSMALL:
				$this->title   = Str::limit($this->title, 40, '..');
				$data = $this->getImageFromContent(165, 111, $item->content);
				$this->image   = $data->image;
		        $this->content = Str::words(e(strip_tags($data->content)), 20);
			break;
		    case ViewEntity::VIEW_MODE_FOOTER:
				$this->title   = Str::limit($this->title, 40, '..');
				$data = $this->getImageFromContent(75, 0, $item->content);
				$this->image   = $data->image;
			break;
			case ViewEntity::VIEW_MODE_VIEW:
				$data = $this->getImageFromContent(673, 0, $item->content);
				$this->image    = $data->image;
				$this->content  = $this->getImages($this->content, true, ViewEntity::VIEW_MODE_VIEW);
			break;
			default:
				$data = $this->getImageFromContent(165, 105, $item->content);
				$this->image    = $data->image;
			    $this->content  = $this->getImages($this->content);
		}
	}

}
