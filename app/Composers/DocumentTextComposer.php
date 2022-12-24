<?php

namespace App\Composers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentGuestbook;
use Kythera\Models\DocumentMessage;
use Kythera\Models\DocumentText;
use Kythera\Models\Village;
use Kythera\Support\ViewEntity;

/**
 * @author virgilm
 *
 */
class DocumentTextComposer extends PageComposer
{

    public function compose($view)
    {
        parent::compose($view);

        switch ($view->getName()) {
            case 'site.document.text.index':
            case 'site.document.quoted.index':
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

                if ($paginate_name_filter = Input::get('n')) {
                    Session::set('paginate_name_filter', $paginate_name_filter);
                }
                $paginate_name_filter  = Session::pull('paginate_name_filter', null);

                //todo:move query to model
                $pages = DocumentText::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
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

                $items = ViewEntity::build($pages->items());

                $view->with('stats', $stats);
                $view->with('pages', $pages);
                $view->with('items', $items);
                $view->with('paginate_sizes', $paginate_sizes);
                $view->with('paginate_orders', $paginate_orders);
                $view->with('paginate_filter_village', $paginate_filter_village);

                break;
            case 'site.document.guestbook.index':
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
                    4 => 'xlastname ASC',
                    5 => 'xlastname DESC'
                ];

                //handle filters
                if ($paginate_filter = Input::get('v')) {
                    Session::set('paginate_filter', $paginate_filter);
                }
                $paginate_filter  = Session::pull('paginate_filter', null);

                $pages = DocumentGuestbook::select(
                    'document_entities.*',
                    //'document_entities.id as did',
                            //'document_entities.persons_id',
                            DB::raw('IF (users.firstname IS NULL, users_guestbook.firstname, users.firstname) AS xfirstname'),
                    DB::raw('IF (users.lastname IS NULL, users_guestbook.surname, users.lastname) AS xlastname'),
                    DB::raw('IF (users.email IS NULL, users_guestbook.email, users.email) AS xemail')
                )
                        ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
                        ->leftJoin('users_guestbook', 'document_entities.id', '=', 'users_guestbook.documents_id')
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
                        //->orderByRaw('document_entities.updated_at DESC')
                        ->paginate($paginate_size);
                $items = ViewEntity::build($pages->items(), ViewEntity::VIEW_MODE_GUESTBOOK);

                $view->with('pages', $pages);
                $view->with('items', $items);
                $view->with('villages', $this->getVillages());
                $view->with('paginate_sizes', $paginate_sizes);
                $view->with('paginate_orders', $paginate_orders);
                $view->with('paginate_filter_village', $paginate_filter_village);

                break;
            case 'site.document.guestbook.edit':
                //$view->with('page', $this->getCurrentPage());
                $view->with('villages', $this->getVillages());
                break;
            case 'site.document.message.index':
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
                        4 => 'lastname ASC',
                        5 => 'lastname DESC'
                ];

                //handle filters
                if ($paginate_filter = Input::get('v')) {
                    Session::set('paginate_filter', $paginate_filter);
                }
                $paginate_filter  = Session::pull('paginate_filter', null);


                $pages = DocumentMessage::select(
                    'document_entities.*',
                    //DB::raw('IF (messageboard2.parent_id IS NOT NULL, messageboard2.parent_id, messageboard.parent_id) as messageBoardParentId'),
                        DB::raw('messageboard.parent_id as messageBoardParentId'),
                    'users.firstname',
                    'users.middlename',
                    'users.lastname',
                    'users.id as user_id'
                    //'document_entities.id as did',
                    //'document_entities.persons_id',
                )
                        ->withUser()
                        ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')

                        ->leftJoin('messageboard', 'document_entities.id', '=', 'messageboard.documents_id')
                        //->leftJoin('messageboard2', 'document_entities.id', '=', 'messageboard2.documents_id')

                        ->where('document_entities.document_type_id', $this->getCurrentPage()->controller_id)
                        ->where('document_entities.enabled', 1)
                        ->where('document_attributes.l', App::getLocale())
                        ->where('document_attributes.key', 'title')

                        ->where('messageboard.parent_id', 0);
                        //->whereOr('messageboard2.parent_id', 0);

                $paginate_filter_village = null;
                if ($paginate_filter) {
                    $paginate_filter_village = Village::find($paginate_filter);

                    $pages = $pages
                    ->where('related_village_id', $paginate_filter);
                }

                $pages = $pages
                        ->orderByRaw($orders[$paginate_order])
                        //->orderByRaw('document_entities.updated_at DESC')
                        ->paginate($paginate_size);
                //$p = $pages[0];echo __FILE__.__LINE__.'<pre>$p='.htmlentities(print_r($p,1)).'</pre>';die;

                $items = ViewEntity::build($pages->items(), ViewEntity::VIEW_MODE_MESSAGE);
                //$p = $items[0];echo __FILE__.__LINE__.'<pre>$p='.htmlentities(print_r($p,1)).'</pre>';die;

                $view->with('pages', $pages);
                $view->with('items', $items);
                //$view->with('villages', $this->getVillages());
                $view->with('paginate_sizes', $paginate_sizes);
                $view->with('paginate_orders', $paginate_orders);
                $view->with('paginate_filter_village', $paginate_filter_village);

                break;
            case 'site.document.text.view':
            default:
        }
    }


    /**
     * Helper to fetch villages
     */
    private function getVillages()
    {
        return DB::table('villages')
            ->where('character_set_id', 'latin')
            ->where('visible', 1)
            ->where('village_name', '!=', '')
            ->orderBy('village_name')
            ->get();
    }
}
