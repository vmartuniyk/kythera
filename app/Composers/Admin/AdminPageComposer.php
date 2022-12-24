<?php

namespace App\Composers\Admin;

use App\Http\Controllers\Admin\AdminCommandController;
use App\Models\DocumentType;
use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Kythera\Models\PageEntity;
use Kythera\Router\Facades\Router;

/**
 * @author virgilm
 *
 */
class AdminPageComposer extends \App\Composers\Composer
{


    public function compose($view)
    {
        #get data for this view
        $data = $view->getData();

        #get selected page
        $page = isset($data['page']) ? $data['page'] : null;

        $view->with('folders', $this->folders());
        $view->with('parent_select', $this->parents($page));
        $view->with('controllers', $this->controllers());
    }


    /**
     * Build available controllers select box
     *
     * @param Page $page
     * @return string
     */
    private function controllers()
    {
        $controllers = DocumentType::select('id', DB::raw('concat(string_id, " :", id, " :", controller) as title'))
            //->where('controller','=','DocumentTextController')
            ->orderBy('string_id')
            ->lists('title', 'id');
        //$controllers = DocumentType::orderBy('string_id')->lists('string_id','id');

        //add default option, beacause field is not required
        $controllers->prepend( '-' );

        return $controllers;
    }


    /**
     * Build parent select box
     *
     * @param Page $page
     * @return string
     */
    private function parents(PageEntity $page = null)
    {
        $result = '';
        $result.= '<select id="parent" name="parent" class="parent form-control">';
        foreach (Router::folders() as $folder) {
            $result .= $this->htmlParents(Router::getFolder($folder->id), $folder, $page);
        }
        $result.= '</select>';
        return $result;
    }


    /**
     * @param collection of Page $items
     * @param number $folder
     * @param Page $page
     * @param number $level
     * @return string
     */
    private function htmlParents($items, $folder, $page, $level = 0)
    {
        $h = '';
        if (++$level==1) {
            $h.= sprintf('
                <option %s class="l%d" value=\'{"f":%d,"p":0}\'>%s</option>', ($page && ($page->folder_id==$folder->id)) ? 'selected="selected"' : '', $level, $folder->id, $folder->title);
        }
        foreach ($items as $item) {
            if (!PageEntity::canHaveChildren($item)) {
                continue;
            }

            #&nbsp;, &thinsp;, &ensp;,and &emsp;
            $h.= sprintf('
                    <option %s %s class="l%d" value=\'{"f":%d,"p":%d}\'>%s%s</option>', ($page && ($page->parent_id==$item->id)) ? 'selected="selected"' : '', ($page && (($page->id==$item->id) || !Page::canHaveChildren($item))) ? 'disabled="disabled"' : '', $level+1, $folder->id, $item->id, str_repeat('. . ', $level), $item->title);
            if (count($item->children)) {
                $h.=$this->htmlParents($item->children, $folder, $page, $level);
            }
        }
        return $h;
    }


    /**
     * Build menus with containg pages
     *
     * @return string
     */
    private function folders()
    {
        $result = '';
        foreach (Route::folders() as $folder) {
            //$result .= sprintf('<h2><i class="glyphicon glyphicon-collapse-down tree" data-folder="f%d"></i>%s</h2>', $folder->id, $folder->title);
            $result .= sprintf('<h2>%s</h2>', $folder->title);
            $result .= $this->htmlPagesSortable(Route::getFolder($folder->id, 0), $folder);
        }
        return $result;
    }


    /**
     * Build pages list for a given menu with dragging
     *
     * @param string $items
     * @param string $folder
     * @param number $level
     * @return string
     */
    private function htmlPagesSortable($items = null, $folder = null, $level = 0)
    {
        $selected = Session::get('admin.page.edit', 0);

        $level++;
        $h = '';
        $h.= sprintf('
	            <ul id="f%d" class="navigation l%d connectedSortable sortable">', $folder->id, $level);
        foreach ($items as $item) {
            $h.= sprintf('
	                <li class="%s ui-state-default %s" id="p%d">
	                <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>', isset($item->children) ? 'children':'', ($selected == $item->id ? 'selected' : ''), $item->id);

            $h.= sprintf('<a class="%s" title="%s" href="%s">%s<i class="glyphicon glyphicon-edit"></i></a>', $item->active ? 'active' : 'inactive', trans('locale.item.edit'), URL::route('admin.page.edit', $item->id), $item->title);
            $h.= sprintf(
                '<a href="#" title="%s"
                    data-toggle="modal"
                    data-target="#confirm-delete"
                    data-id=%d
                    data-title="%s"
                    data-message="%s"
                    data-action="%s"><i class="glyphicon glyphicon-trash"></i></a>',
                trans('locale.item.delete'),
                $item->id,
                trans('locale.delete.confirm'),
                trans('locale.delete.confirm.question', ['value'=>$item->title]),
                URL::route('admin.page.destroy', $item->id)
            );
            $h.= sprintf('
                    <a class="command" href="#" title="%s" onclick=%s><i class="glyphicon %s"></i></a>', trans('locale.item.hide'), AdminCommandController::command("ToggleActive", ['id'=>$item->id], true), $item->active ? 'glyphicon-eye-open' : 'glyphicon-eye-close');

            if (count($item->children)) {
                $h.= $this->htmlPagesSortable($item->children, $folder, $level);
            }
            $h.= sprintf('
	                </li>');
        }

        //$h.= '<li><br></li>';

        $h.= '
	            </ul>';
        return $h;
    }


    /**
     * Build pages list for a given menu
     *
     * @param string $items
     * @param number $level
     * @return string
     */
    private function htmlPages($items = null, $level = 0)
    {
        $level++;
        $h = '';
        $h.= '<ul class="navigation l'.($level).'">';
        foreach ($items as $item) {
            $h.= sprintf('<li>');
            $h.= sprintf('<a href="%s">%s (%d)<i class="glyphicon glyphicon-edit"></i></a>', URL::route('admin.page.edit', $item->id), $item->title, $item->id);
            $h.= sprintf(
                '<a href="#"
                    data-toggle="modal"
                    data-target="#confirm-delete"
                    data-id=%d
                    data-title="Confirm"
                    data-message="%s"
                    data-action="%s"><i class="glyphicon glyphicon-trash"></i></a>',
                $item->id,
                trans('admin.page.delete.confirm', ['title'=>$item->title]),
                URL::route('admin.page.destroy', $item->id)
            );
            if (isset($item->children)) {
                $h.= $this->htmlPages($item->children, $level);
            }
            $h.= sprintf('</li>');
        }
        $h.= '</ul>';
        return $h;
    }
}
