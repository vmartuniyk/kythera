<?php

namespace Kythera\Support;

class ViewDocumentGuestbook extends ViewDocumentEntity
{
    public function __construct($item, $mode = ViewEntity::VIEW_MODE_VIEW)
    {
        parent::__construct($item, $mode);

        switch($mode)
        {
    		case ViewEntity::VIEW_MODE_HOME:
            case ViewEntity::VIEW_MODE_SIDEBAR:
            case ViewEntity::VIEW_MODE_KEYS:
            case ViewEntity::VIEW_MODE_VIEW:
            default:
            	$this->id      = $item->id;
            	$this->title   = e($item->title);
            	$this->date    = $item->updated_at->format('d.m.Y');
            	$this->content = (strip_tags($item->content, '<br>'));
            	$this->content = \App\Classes\Helpers::obfuscateEmails($this->content);
            	$this->entry   = $item;
        }
    }
}
