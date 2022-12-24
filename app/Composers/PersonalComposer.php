<?php

namespace App\Composers;

use Kythera\Models\DocumentEntity;

//use Kythera\Models\DocumentText;
//use Illuminate\Pagination\Paginator;

//use Kythera\Support\ViewEntity;
/**
 * @author virgilm
 *
 */
class PersonalComposer extends PageComposer
{

    /*
     * Depricated and moved to PageComposer
     */
    /*
    public function compose($view)
    {
    	throw new Exception('Depricated');

        parent::compose($view);

        switch ($view->getName()) {
            case 'site.page.personal.index':
                $view->with('page', Session::get('page'));
                $view->with('router.page', Session::get('router.page'));

                $items = DocumentEntity::getUserEntries(Auth::user(), 10000);
                //$items = ViewEntity::build($items);
                $view->with('items', $items);
            break;
            default:
        }
    }
    */
}
