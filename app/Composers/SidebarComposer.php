<?php

namespace App\Composers;

use Illuminate\Support\Facades\URL;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentImage;
use Kythera\Models\DocumentMedia;
use Kythera\Models\DocumentMessage;
use Kythera\Models\DocumentText;
use Kythera\Router\Router;
use Kythera\Support\ViewEntity;

/**
 * @author virgilm
 *
 */
class SidebarComposer extends PageComposer
{

    public function compose($view)
    {
        parent::compose($view);

        $name = $view->getName();
        switch ($name) {
            case 'site.document.text.blocks.sidebar.posts':
                $items = DocumentEntity::getRecent(4);
                $items = ViewEntity::build($items, ViewEntity::VIEW_MODE_SIDEBAR);
                //echo __FILE__.__LINE__.'<pre>$items='.htmlentities(print_r($items,1)).'</pre>';die;

                $view->with('items', $items);
                break;
            case 'site.document.text.blocks.sidebar.media':
                $items = DocumentMedia::getRecent($this->getCurrentPage()->id == Router::PAGE_HOME ? 3 : 4);
                //fixme:: adjust metrics of first item in home view!
                $items = ViewEntity::build($items, $this->getCurrentPage()->id == Router::PAGE_HOME ? ViewEntity::VIEW_MODE_HOME : ViewEntity::VIEW_MODE_SIDEBAR);
                
                //echo __FILE__.__LINE__.'<pre>$items='.htmlentities(print_r($items,1)).'</pre>';die;
                $category = URL::route('site.page.audiovideo');
                $view->with('category', $category)
                     ->with('items', $items);
                break;
            case 'site.document.text.blocks.sidebar.tourist':
                $items = DocumentImage::getRecent(3, [49]);
                $items = ViewEntity::build($items, ViewEntity::VIEW_MODE_SIDEBAR);

                $view->with('title', 'Modern Portraits');
                $view->with('items', $items);
                break;
            case 'site.document.text.blocks.sidebar.message':
                $items = DocumentMessage::getRecent(4);
                $items = ViewEntity::build($items, $this->getCurrentPage()->id == Router::PAGE_HOME ? ViewEntity::VIEW_MODE_HOME : ViewEntity::VIEW_MODE_SIDEBAR);

                //$category = URL::route('site.page.message.board');
                $category = action('DocumentMessageController@getIndex');
                $view->with('category', $category)
                     ->with('items', $items);
                break;
            case 'site.document.text.blocks.sidebar.natural':
                $items = DocumentImage::getRecent($this->getCurrentPage()->id == Router::PAGE_HOME ? 4 : 3, [34, 35, 36]);
                $items = ViewEntity::build($items, $this->getCurrentPage()->id == Router::PAGE_HOME ? ViewEntity::VIEW_MODE_HOME : ViewEntity::VIEW_MODE_SIDEBAR);

                $view->with('items', $items);
                break;
        }
    }
}
