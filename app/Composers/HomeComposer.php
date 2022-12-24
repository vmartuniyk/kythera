<?php

namespace App\Composers;

use Kythera\Router\Facades\Router;
use Illuminate\Support\Facades\DB;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentImage;
use Kythera\Models\DocumentMedia;
use Kythera\Models\DocumentMessage;
use Kythera\Models\DocumentText;
use Kythera\Support\ViewEntity;

/**
 * @author virgilm
 *
 */
class HomeComposer extends PageComposer
{

    public function compose($view)
    {
        parent::compose($view);

        switch ($name = $view->getName()) {
            #home
            case 'site.page.home.index':
                break;
            case 'site.page.home.blocks.keys':
                
                $items = DocumentEntity::getEntriesTopArticlesRandom(4);
                $items = ViewEntity::build($items, ViewEntity::VIEW_MODE_KEYS);
                
                $view->with('items', $items);
                break;
            case 'site.page.home.blocks.keys-small':
                $items = DocumentEntity::getEntriesSmallKeys(6);
                $items = ViewEntity::build($items, ViewEntity::VIEW_MODE_KEYSMALL);

                $view->with('items', $items);
                break;
            case 'site.page.home.blocks.posts':
                $items = DocumentImage::getRecent(6);
                $items = ViewEntity::build($items, ViewEntity::VIEW_MODE_HOMEROW);
                $link  = Router::getPageByID(202); //Latest posts
                //echo __FILE__.__LINE__.'<pre>$items='.htmlentities(print_r($items,1)).'</pre>';die;

                $view->with('link', $link);
                $view->with('items', $items);
                break;

            #footer
            case 'site.page.footer.photos':
                $items = DocumentImage::getEntriesPhotos(config('view.footer_count_photos'), [], 'DESC');
                $items = ViewEntity::build($items, ViewEntity::VIEW_MODE_FOOTER);
                //echo __FILE__.__LINE__.'<pre>$items='.htmlentities(print_r($items,1)).'</pre>';die;

                $view->with('title', 'photos');
                $view->with('items', $items);
                break;
            case 'site.page.footer.photos-2':
                $items = DocumentImage::getEntriesPhotos(config('view.footer_count_photos'), [], 'ASC');
                $items = ViewEntity::build($items, ViewEntity::VIEW_MODE_FOOTER);
                //echo __FILE__.__LINE__.'<pre>$items='.htmlentities(print_r($items,1)).'</pre>';die;

                $view->with('title', 'photos');
                $view->with('items', $items);
                break;
            case 'site.page.footer.posts':
                $items = DocumentEntity::getEntries(config('view.footer_count_posts'));
                $items = ViewEntity::build($items, ViewEntity::VIEW_MODE_SIDEBAR);

                $view->with('title', 'Latest posts');
                $view->with('items', $items);
                break;
            case 'site.page.footer.social':
                break;

            default:
                throw new Exception('Invalid composer called on '.$name);
        }
    }
}
