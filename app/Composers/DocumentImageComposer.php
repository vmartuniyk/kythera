<?php

namespace App\Composers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentImage;
use Kythera\Models\Village;
use Kythera\Support\ViewEntity;

/**
 * @author virgilm
 *
 */
class DocumentImageComposer extends PageComposer
{

    public function compose($view)
    {
        parent::compose($view);

        switch ($view->getName()) {
            case 'site.document.image.index':
                //reset pagination options on every page request
                if (0&&Session::get('paginate_page') != $this->getCurrentPage()->id) {
                    Session::set('paginate_page', $this->getCurrentPage()->id);
                    Session::set('paginate_size', 20);
                    Session::set('paginate_order', 1);
                }

                //handle pagination size
                if ($paginate_size = Input::get('ps')) {
                    Session::set('paginate_size', max(20, min(100, $paginate_size)));
                }
                $paginate_size   = Session::get('paginate_size', 20);
                $paginate_sizes  = [
                    20 => trans('locale.filter.perpage', ['number'=>20]),
                    50 => trans('locale.filter.perpage', ['number'=>50]),
                    100 => trans('locale.filter.perpage', ['number'=>100]),
                ];

                //handle ordering
                if ($paginate_order = Input::get('po')) {
                    Session::set('paginate_order', max(1, min(5, $paginate_order)));
                }
                $paginate_order  = Session::get('paginate_order', 1);
                $paginate_orders = [
                        1 => trans('locale.filter.newestfirst'),
                        2 => trans('locale.filter.newestlast'),
                        3 => trans('locale.filter.alphabetically'),
                        4 => trans('locale.filter.submitterfirst'),
                        5 => trans('locale.filter.submitterlast'),
                ];

                $orders = [
                    1 => 'document_entities.created_at DESC',
                    2 => 'document_entities.created_at ASC',
                    3 => 'document_attributes.value ASC',
                    4 => 'users.lastname ASC',
                    5 => 'users.lastname DESC'
                ];
                $stats = DB::select('SELECT distinct(enabled), count(*) FROM `documents` group by enabled');

                //handle filters
                if ($paginate_filter = Input::get('v')) {
                    Session::set('paginate_filter', $paginate_filter);
                }
                $paginate_filter  = Session::pull('paginate_filter', null);

//                 $s = DocumentImage::find(22853);                
//                 $items = ViewEntity::build(array($s), ViewEntity::VIEW_MODE_LIST);
//                 echo __FILE__.__LINE__.'<pre>$items='.htmlentities(print_r($items,1)).'</pre>';die;
                
                $pages = DocumentImage::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname')
                                   ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
                                   ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
                                   ->where('document_entities.document_type_id', $this->getCurrentPage()->controller_id)
                                   ->where('document_entities.enabled', 1)
                                   ->where('document_attributes.l', App::getLocale())
                                   ->where('document_attributes.key', 'title');

                $paginate_filter_village = null;
                if ($paginate_filter) {
                    $paginate_filter_village = Village::find($paginate_filter);

                    $pages = $pages
                                        ->where('related_village_id', $paginate_filter);
                }

                $pages = $pages
                                   ->orderByRaw($orders[$paginate_order])
                                   ->paginate($paginate_size);
                $items = ViewEntity::build($pages->items(), ViewEntity::VIEW_MODE_LIST);

                $view->with('stats', $stats);
                $view->with('pages', $pages);
                $view->with('items', $items);
                $view->with('paginate_sizes', $paginate_sizes);
                $view->with('paginate_orders', $paginate_orders);
                $view->with('paginate_filter_village', $paginate_filter_village);

                break;
            case 'site.document.image.view':
            default:
        }
    }
}
