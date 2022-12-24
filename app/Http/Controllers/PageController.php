<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Kythera\Models\PageEntity;
use Kythera\Router\Facades\Router;

/**
 * @author virgilm
 *
 */
class PageController extends Controller
{
    /**
     * Determine requested page.
     *
     * @param Router $router
     * @throws Exception
     */
    public function __construct()
    {
        $this->visitors();
    }


    /**
     * Register current online users.
     *
     */
    protected function visitors()
    {
        //first set users offline when time_lastaction older than 2 hours
        DB::statement('DELETE FROM page_visitors WHERE time_lastaction <= ?', [time()-7200]);

        //save IP und Host eintragen only when is valid user
        $bot = new \Jaybizzle\CrawlerDetect\CrawlerDetect();
        if (!$bot->isCrawler() && !empty($_SERVER["HTTP_USER_AGENT"])) {
            DB::statement('REPLACE INTO page_visitors (ip,time_lastaction,hostname) VALUES(?, ?, ?)', [$_SERVER["REMOTE_ADDR"], time(), $_SERVER["HTTP_USER_AGENT"]]);
        }

        //save count for easy display
       // Session::set('page.visitors', $this->visitors = DB::table('page_visitors')->count());
    }


    /**
     * VM:DEPRICATED 03-2016 in favor of $this->visitors()
     *
     * Register current online users.
     */
    protected function visitors_error6102()
    {
        //currently visitors online
        //first set users offline when time_lastaction older than 2 hours
        DB::statement('DELETE FROM page_visitors WHERE time_lastaction <= ?', [time()-7200]);

        //IP und Host eintragen
        $bot = (isset($_SERVER['HTTP_USER_AGENT']) && ($_SERVER['HTTP_USER_AGENT'] != "")) && (
                   preg_match('#bot#i', $_SERVER['HTTP_USER_AGENT'])
                   || preg_match('#spider#i', $_SERVER['HTTP_USER_AGENT'])
                   || preg_match('#Yahoo!#i', $_SERVER['HTTP_USER_AGENT'])
                   || preg_match('#Twiceler#i', $_SERVER['HTTP_USER_AGENT'])
                   || preg_match('#DotBot#i', $_SERVER['HTTP_USER_AGENT'])
                   || preg_match('#Google#i', $_SERVER['HTTP_USER_AGENT'])
                   || preg_match('#MSN#i', $_SERVER['HTTP_USER_AGENT'])
               );
        if (!$bot) {
            DB::statement('REPLACE INTO page_visitors (ip,time_lastaction,hostname) VALUES(?, ?, ?)', [$_SERVER["REMOTE_ADDR"], time(), $_SERVER["HTTP_USER_AGENT"]]);
        }

       // Session::set('page.visitors', $this->visitors = DB::table('page_visitors')->count());
    }

    /**
     * Show index page
     */
    public function index()
    {
        return $this->view();
    }


    /**
     * Make view wrapper to make the current request page available in all templates.
     * @param string $view
     * @return View
     */
    protected function view($view = 'site.page.default')
    {
        
        $name = $this->getCurrentPage()->controller;
       
        switch ($name) {
            case 'DocumentTextController':
                $view = 'site.document.text.'.$view;
                break;
            case 'DocumentQuotedTextController':
                $view = 'site.document.quoted.'.$view;
                break;
            case 'DocumentImageController':
            case 'DocumentLetterController':
                $view = 'site.document.image.'.$view;
                break;
            case 'DocumentAudioController':
                $view = 'site.document.audio.'.$view;
                break;
            case 'DocumentVideoController':
                $view = 'site.document.video.'.$view;
                break;
            case 'DocumentGuestbookController':
                $view = 'site.document.guestbook.'.$view;
                break;
            case 'DocumentMessageController':
                $view = 'site.document.message.'.$view;
                break;
            case 'DocumentFamilyController':
                $view = 'site.document.family.'.$view;
                break;
            case 'DocumentPersonController':
                $view = 'site.document.person.'.$view;
                break;
            case 'NewsLetterController':
                $view = 'site.page.newsletter.'.$view;
                break;
            case 'VillageController':
                $view = 'site.page.village.'.$view;
                break;
            case 'PeopleNameController':
                $view = 'site.page.people.names.'.$view;
                break;
            case 'LatestPostsController':
                $view = 'site.page.latest.'.$view;
                break;
        }

        //todo: move to database
        $image = null;
        $text = null;
        $box = null;
        if ($this->getCurrentPage()->id == PageEntity::PAGE_HOME) {
            $view  = $this->getCurrentPage()->name . '.index';
            $image = $this->getCurrentPage()->getImageUri();
            $text  = $this->getCurrentPage()->getContentWithLine();
            $box   = $this->getCurrentPage()->getColorBox();
        }

        return view($view)
               ->with('page', $this->getCurrentPage())
               ->with('image', $image)
               ->with('text', $text)
               ->with('box', $box);
    }


    /**
     * FIXME: Rename! A cool feature is that public getFoo() automatic generates a route!
     *
     * Getter for $page
     *
     * @return Page
     */
    /*
    public function postFoo() {}
    public function getFoo() {}
    public function putFoo() {}
    */
    protected function getCurrentPage()
    {
        // $router = $this->router;
        return Router::getCurrentPage();//selected;
    }
}
