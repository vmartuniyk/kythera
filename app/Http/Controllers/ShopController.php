<?php

namespace App\Http\Controllers;

use App\Plugins\Home\HomePlugin;

/**
 * @author virgilm
 *
 */

class ShopController extends Controller
{

    public function getIndex()
    {
        $page = new stdClass();
        $page->title = 'SHOP';
        $page->content = 'xxxxxxxxxxxxx';
        
        $p = new HomePlugin();
        $page->content = $p->content();
        
        
        return view('site.index.dynamic')
               ->with('page', $page);
    }
}
