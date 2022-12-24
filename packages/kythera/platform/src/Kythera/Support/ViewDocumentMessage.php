<?php

namespace Kythera\Support;

use Illuminate\Support\Str;

use Kythera\Router\Facades\Router;
use Kythera\Html\Facades\Html;
use Kythera\Models\DocumentMessage;


class ViewDocumentMessage extends ViewDocumentEntity
{
    public function __construct($item, $mode = ViewEntity::VIEW_MODE_VIEW)
    {
        parent::__construct($item, $mode);

        switch($mode)
        {
    		case ViewEntity::VIEW_MODE_HOME:
            case ViewEntity::VIEW_MODE_SIDEBAR:
            	$this->parentId = $item->parent_id;
            	if ($this->parentId) {
            		if ($parent = DocumentMessage::find($this->parentId)) {
            			$this->parentUri = sprintf('%s#%d', Router::getEntityUri($parent), $this->id);
            		}
            	}

                $this->content  = Str::words(e(strip_tags($item->content)), 17);
                $this->date     = $item->updated_at->format('d.m.Y');
                $this->author   = \xhtml::fullname($item, false);

                $data           = Router::getEntityPage($item);
                //$this->crumbs  = $this->crumbs($data->page, ', ');
                if (isset($data->page))
                $this->crumbs   = Html::crumbs($data->page, ', ', true);
            break;
            case ViewEntity::VIEW_MODE_KEYS:
            	$this->parentId = $item->parent_id;
            	if ($this->parentId) {
            		if ($parent = DocumentMessage::find($this->parentId)) {
            			$this->parentUri = sprintf('%s#%d', Router::getEntityUri($parent), $this->id);
            		}
            	}

                $this->title   = Str::limit($this->title, 40, '..');
                $data = $this->getImageFromContent(255, 164, $item->content);
                if (isset($data->page))
                $this->image   = $data->image;
                $this->content = $this->getImages($this->content);
            break;
            case ViewEntity::VIEW_MODE_VIEW:
            default:
            	$this->parentId = $item->parent_id;
            	if ($this->parentId) {
            		if ($parent = DocumentMessage::find($this->parentId)) {
            			$this->parentUri = sprintf('%s#%d', Router::getEntityUri($parent), $this->id);
            		}
            	}

            	/*
                $data = $this->getImageFromContent(165, 105, $item->content);
                if ($data->page)
                $this->image   = $data->image;
                $this->content = $this->getImages($this->content);
                */
                $this->date = $item->updated_at;
                $this->content = $this->getImages($this->content);
        }
    }
}
