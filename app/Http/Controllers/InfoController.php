<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

/**
 * @author virgilm
 *
 */

class InfoController extends Controller
{

    /*
    protected $page;
    
    public function __construct(Page $page) {
        $this->page = $page;
        echo __FILE__.__LINE__.'<pre>$page='.htmlentities(print_r($page,1)).'</pre>';die;
    }
    */
    
    
    
    
    /**
     * Default controller action
     */
    public function getIndex()
    {
        return view('site.info.index')
                     ->with('title', 'Default controller action');
    }
    
    /**
     * Default controller action missing
     */
    public function missingMethod($parameters = [])
    {
        return view('site.info.index')
                     ->with('title', 'Missing action on controller!')
                     ->with('parameters', $parameters);
    }
    
    
    public function index()
    {
        $version = config('shop::version');
        $locale  = Session::get('locale');
        $locale  = App::getLocale();
        $locale  = config('app.locale');
        return view('site.info.index')
                     ->with('title', 'Default route');
    }
}
