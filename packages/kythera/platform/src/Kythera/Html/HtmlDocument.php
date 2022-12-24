<?php namespace Kythera\Html;

use Kythera\Html\Html;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentImage;
use Kythera\Support\ViewEntity;
use Kythera\Models\DocumentText;
use Illuminate\Support\Facades\URL;

/**
 * @author virgilm
 *
 */
class HtmlDocument extends Html
{
    
    public function recent($controller = 'DocumentImageController', $limit = 4, $title = '', $category = '', $document_type_id = array())
    {
        switch ($controller)
        {
            case 'DocumentTextController':
                //echo __FILE__.__LINE__.'<pre>$document_type_id='.htmlentities(print_r($document_type_id,1)).'</pre>';
                $items = DocumentText::getRecent($limit, $document_type_id);
                $items = ViewEntity::build($items, ViewEntity::VIEW_MODE_HOME);
//                echo __FILE__.__LINE__.'<pre>$items='.htmlentities(print_r($items,1)).'</pre>';die;
                return \View::make('site.page.home.blocks.text')
                        ->with('category', $category)
                        ->with('title', $title)
                        ->with('items', $items)
                        ->render();
            break;
            case 'DocumentImageController':
                $items = DocumentImage::getRecent($limit, $document_type_id);
                $items = ViewEntity::build($items, ViewEntity::VIEW_MODE_HOME);
                return \View::make('site.page.home.blocks.image')
                        ->with('category', $category)
                        ->with('title', $title)
                        ->with('items', $items)
                        ->render();
            break;
        }
    }

}
