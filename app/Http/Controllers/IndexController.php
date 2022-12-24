<?php

namespace App\Http\Controllers;

/**
 * @author virgilm
 *
 */

class IndexController extends Controller
{

    protected $pages;

    public function __construct(Page $pages)
    {
        $this->pages = $pages;
    }

    /**
     * Show index page
     */
    public function index()
    {
        $pages = $this->pages->all();
        return view('site.index.index')
               ->with('pages', $pages);
    }

    /**
     * Show about page
     */
    public function about()
    {
        $pages = $this->pages->all();
        return view('site.index.about')
               ->with('pages', $pages);
    }
}
