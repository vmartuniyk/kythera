<?php namespace Kythera\Router;

use Closure, DB, Log, Route, Cache, Request, App, Config, Session;
use App\Models\Folder;
use App\Models\Page;
use Illuminate\Support\Str;
use Symfony\Component\Routing\RouteCollection;
use Illuminate\Routing\Router as IlluminateRouter;
//use Skapator\Greeklish\Greeklish;
use Symfony\Component\Finder\Iterator\PathFilterIterator;
use Illuminate\Database\Eloquent\Collection;
use Kythera\Models\PageEntity;
//use App\Models\Translation;

/**
 * @author virgilm
 *
 */

class RouterException extends \Exception {}

class Router extends IlluminateRouter
{


    const PAGE_HOME = 11;

    /**
     * Holds complete tree of folders and pages
     * @var Illuminate\Database\Eloquent\Collection
     */
    protected $navigation = array();


    /**
     * Holds the current page selected by the router.
     *
     * @var array
     */
    protected $selected = null;


    /**
     * List of named routes coupled to pages
     *
     * @var array
     */
    protected $controllers = array();


    /**
     * Register routes of all user pages
     *
     * @param string $items
     * @param unknown $options: method(GET|RESOURCE|CONTROLLER))
     *
     * todo: profile this!
     */
    public function register($options = array('method'=>'GET', 'as' => 'site.page.show', 'uses' => 'PageController@index'))
    {
        //Config::set('cache.cacheable.navigation', false);

	    //get complete navigation tree
	    if (Config::get('cache.cacheable.navigation', false)) {
    	    $this->navigation = Cache::remember('router.'.App::getLocale(), 60, function()
    	    {
    	        return $this->all2(null, null, '/');
            });
	    } else {
	        $this->navigation = $this->all2(null, null, '/');
	    }

        $this->_register2($this->navigation, $options);
    }


    private function _register2($items = null, $options = array())
    {
	    foreach ($items as $item) {
            //path to register route/controller on
	        $path = $item->path == '/' ? $item->path
	                                   : substr($item->path, 1);
            $path = Str::lower($path);

            if ($item->controller_id) {
                //register custom controller
                $item = $this->registerCustomController($item, $path, $options);
            } else {
                //register default controller
                $item = $this->registerController($item, $path, $options);
            }

	        #add language prefix
	        $item->path = $path == '/' ? sprintf('/%s', App::getLocale())
	                                   : sprintf('/%s/%s', App::getLocale(), $path);

	        /*
            #and set selected
            $p=Request::decodedPath();
            $q=$item->path;
            if ($item->path == '/'.Request::decodedPath()) {
            //if (str_contains('/'.Request::decodedPath(), $item->path)) {
                $item->selected = true;
                $this->selected = $item;

                //save in session
                Session::put('router.page', $item);
            }
            */

            if (count($item->children))
                $this->_register2($item->children, $options);
	    }
    }


    /**
     * Register normal route / normal controller
     *
     * @param Page $page
     * @param string $path
     * @param array $options
     */
    protected function registerController(PageEntity $page, $path, $options = array() )
    {
        //register route
        $options['as'] = $as = $this->generateRouteName($page);
        Route::get($path, $options);

        $this->addController($as, $page);

        $page->name = $as;

        return $page;
    }


    /**
     * Register custom controller
     *
     * @param Page $page
     * @param string $path
     * @param array $options
     */
    protected function registerCustomController(PageEntity $page, $path, $options = array() )
    {
        $as = $this->generateRouteName($page);

        //register controller specific routes
        //fixme: move to db or use reflection on controller to extract routes
        switch($page->string_id) {
        	case 'KFN_PERSONAL_PAGE':
        		Route::get($path.'/category/{id?}', array('as'=>$as.'.category', 'uses'=>$page->controller.'@getCategory'));
        		$this->addController($as.'.category', $page);

        		Route::get($path.'/comment/{id?}', array('as'=>$as.'.comment', 'uses'=>$page->controller.'@getComment'));
        		$this->addController($as.'.comment', $page);
        	break;
        	case 'xKFN_SEARCH':
        		Route::get($path, array('as'=>$as.'.get', 'uses'=>$page->controller.'@getSearch'));
        		$this->addController($as.'.post', $page);
        	break;
        	case 'KFN_NEWS_LETTER':
        		Route::post($path.'/subscribe', array('as'=>$as.'.subscribe', 'uses'=>$page->controller.'@postSubscribe'));
        		$this->addController($as.'.subscribe', $page);
        		Route::get($path.'/subscribe/{email?}', array('as'=>$as.'.subscribe', 'uses'=>$page->controller.'@getSubscribe'));
        		$this->addController($as.'.subscribe', $page);

        		Route::get($path.'/unsubscribe/{email?}/{token?}', array('as'=>$as.'.unsubscribe', 'uses'=>$page->controller.'@getUnsubscribe'));
        		$this->addController($as.'.unsubscribe', $page);

        		Route::get($path.'/confirm/{email?}/{token?}', array('as'=>$as.'.confirm', 'uses'=>$page->controller.'@getConfirm'));
        		$this->addController($as.'.confirm', $page);
        	break;
        	case 'xGUEST_BOOK':
        		//has no subpages / has no  {entry} routes
        		/*
        		Route::get($path.'/contact/{id}', array('as'=>$as.'.contact', 'uses'=>$page->controller.'@contact'));
        		$this->addController($as.'.contact', $page);
        		*/

        		//add fixed routes manually
        		$this->addController('guestbook.edit', $page);
        		$this->addController('guestbook.contact', $page);
        	break;
        	case 'xKFN_FAMILY_TREE':
        		Route::get($path.'/tree/{id?}', array('as'=>$as.'.tree', 'uses'=>$page->controller.'@getTree'));
        		$this->addController($as.'.tree', $page);
        	break;
        	case 'KFN_LATEST_POSTS':
        	    Route::get($path.'/{type?}', array('as'=>$as.'.type', 'uses'=>$page->controller.'@getIndex'))
                ->where(array (
                    'type' => 'text|comment|image|tree'
                ));
        	    $this->addController($as.'.type', $page);
       	    break;
        	default:
        		//register {entry} route
        		Route::get($path.'/{entry}', array('as'=>$as.'.entry', 'uses'=>$page->controller.'@getEntry'));
        		$this->addController($as.'.entry', $page);
        }


        //fix:map /index to just /
        Route::get($path, $page->controller.'@getIndex');

        //register controller
        Route::controller($path, $page->controller, array(
                'getIndex'=>$as,
                'getId'=>$as.'.id',
            ));
        $this->addController($as, $page);
        $this->addController($as.'.id', $page);

        $page->name = $as;

        return $page;
    }


    protected function addController($name, $page)
    {
        if (!array_key_exists($name, $this->controllers)) {
            $this->controllers[$name] = $page->id;
        }/* else
            throw new RouterException('Controller already exists: '.$name);*/
    }

    /**
     * Helper to generate named routes
     *
     * @param \Page $page
     * @return string
     */
    private function generateRouteName(PageEntity $page) {
        $name = str_replace('/', '.', $page->name);
        $name = $page->path == '/' ? '.home' : $name;
        $name = 'site.page'.$name;
        $name = str_replace('-', '.', $name);
        return $name;


        $name = str_replace('/', '.', $page->path);
        $name = $page->path == '/' ? '.home' : $name;
        $name = 'site.page'.$name;
        $name = str_replace('-', '.', $name);
        return $name;
    }


    /**
     * Fetch all pages
     *
     * @param string $parent
     * @param string $folder
     * @param string $path
     * @param array $crumbs
     * @return array
     *
     * fixme: catch this!
     */
    public function all2($parent = null, $folder = null, $path = '/', $crumbs = array())
	{
	    /*
	    #cache this
	    $this->_pages = Cache::remember('_pages', 60, function() use ($parent, $folder, $path, $crumbs) {
            return $this->_all($parent, $folder, $path, $crumbs);
        });
	    return $this;
	    */
	    return $this->_all2($parent, $folder, $path, $crumbs);
	}


	/*
    private function _all2($parent = null, $folder = null, $path = '/', $crumbs = array(array('title'=>'home', 'uri'=>'/')), $active = 1) {
        $items = Page::select(array('pages.id', 'parent_id', 'folder_id', 'controller_id', 'active', 'uri', 'title', 'document_types.string_id', 'document_types.controller'))
                    ->whereRaw(sprintf("
                            %s AND
                            %s AND
                            %s
                            ",
                            $active ? "active = 1 " : " 1=1 ",
                            $parent ? "parent_id = {$parent->id} " : "parent_id IS NULL ",
                            $folder ? "folder_id = {$folder}" : " 1=1 "
                            ))
                    ->leftJoin('document_types', 'pages.controller_id', '=', 'document_types.id')
                    ->orderBy('folder_id')
                    ->orderBy('sort')
                    ->get();

        foreach ($items as $item) {
            //for now only translate string values on the model, when fetched translate all
            $item           = Translation::model($item, App::getLocale(), Translation::TRANSLATE_STRING);
            $item->path     = $path.Str::lower($item->uri);
            #fix for home
            $item->path     = str_replace('//', '/', $item->path);
            $item->crumbs   = $crumbs;
            $item->crumbs[] = array('title'=>$item->title, 'uri'=>'/'.App::getLocale().$item->path);
            if ($children = $this->_all2($item, $folder, $item->path.'/', $item->crumbs, $active)) {
                $item->children = $children;
            }
        }
        return $items;
    }
    */

	private function _all2($parent = null, $folder = null, $path = '/', $crumbs = array(array('title'=>'home', 'uri'=>'/')), $active = 1, $name = '/') {
		$items = PageEntity::select(array('pages.id', 'parent_id', 'folder_id', 'controller_id', 'active', 'document_types.string_id', 'document_types.controller'))
					->whereRaw(sprintf("
                            %s AND
                            %s AND
                            %s
                            ",
				$active ? "active = 1 " : " 1=1 ",
				$parent ? "parent_id = {$parent->id} " : "parent_id IS NULL ",
				$folder ? "folder_id = {$folder}" : " 1=1 "
						))
						->leftJoin('document_types', 'pages.controller_id', '=', 'document_types.id')
						->orderBy('folder_id')
						->orderBy('sort')
						->get();

		foreach ($items as $item) {
		    $item->name     = $name.Str::lower($item->router_name[0]);
		    $item->name     = str_replace('//', '/', $item->name);

			$item->path     = $path.Str::lower($item->uri);
			#fix for home
			$item->path     = str_replace('//', '/', $item->path);
			$item->crumbs   = $crumbs;
			$item->crumbs[] = array('title'=>$item->title, 'uri'=>'/'.App::getLocale().$item->path);
			if ($children = $this->_all2($item, $folder, $item->path.'/', $item->crumbs, $active, $item->name.'/')) {
				$item->children = $children;
			}
		}
		return $items;
	}


    /**
     * Get page folders using Folder model
     *
     * @param boolean $compact
     * @return array
     */
    public function folders($compact = false) {
        $result = Folder::all();
        if ($compact) {
            foreach ($result as $folder) {
                echo __FILE__.__LINE__.'<pre>$folder='.htmlentities(print_r($folder,1)).'</pre>';die;
                $tmp[$folder->id] = $folder->title;
            }
            $result = $tmp;
        }
        return $result;
    }


    /**
     * Get all pages in a given folder.
     *
     * @param \Folder $folder
     * @return \Illuminate\Database\Eloquent\Collection
     */
    //public function folder(\Folder $folder, \Illuminate\Database\Eloquent\Collection $collection = null) {
    public function folder(\Folder $folder) {
        $tmp = new Collection();
        foreach ($this->navigation as $item) {
            if ($item->folder_id == $folder->id) {
                $tmp->add($item);
            }
        }
        return $tmp;
    }


    /**
     * Find page by attribute
     *
     * @param array $items
     * @param string $path
     * @return array
     */
    public function find($items = null, $search = array('key'=>'path', 'val'=>''))
    {
        $items  = $items ? $items : $this->navigation;
        $result = false;
	    foreach ($items as $item) {
            if ($item->{$search['key']} == $search['val']) {
                return $item;
	        }
            if (count($item->children)) {
                $result = $this->find($item->children, $search);
                if ($result) return $result;
            }
	    }
	    return $result;
    }


    /**
     * Find page by route name
     *
     * @param string $route: route name
     * @throws RouterException
     * @return TranslateModel
     */
    /*
    public function getPage($route) {
	    if (array_key_exists($route, $this->controllers)) {
	        if ($page = $this->find(null, array('key'=>'id', 'val'=>$this->controllers[$route])))
	            $page = $page->full();
                return Translation::model($page, App::getLocale());
	    }

	    throw new RouterException('Named route not found: ' .$route);
    }
    */
    public function getPage($route)
    {
	    if (array_key_exists($route, $this->controllers))
	    {
	        return $this->find(null, array('key'=>'id', 'val'=>$this->controllers[$route]));
	    }
    }


    public function getPageByID($id)
    {
        return $this->find(null, array('key'=>'id', 'val'=>$id));
    }


	public function getPageByController($id)
	{
		return $this->find(null, array('key'=>'controller_id', 'val'=>$id));
	}


    public function getRequestedPage()
    {
        return $this->getPage($this->current()->getName());
    }
    public function getCurrentPage()
    {
    	$page = $this->current();
    	return $this->getPage($this->current()->getName());
    }

    public function getControllerUrl($type = 'index') {
        $types = array('entry'=>'entry', 'id'=>'id');
        //$base  = Router::current()->getName();
        $base  = $this->getRequestedPage()->name;
        $name  = '';
        if (array_key_exists($type, $types)) {
            $name = '.'.$types[$type];
        }
        return $base.$name;
    }


    public function getNavigation() {
        return $this->navigation;
    }


	/**
	 * Depricated
	 * @return \Kythera\Router\TranslateModel|multitype:
	 */
	public function getSelected()
	{
		return $this->getRequestedPage();

		if ($this->selected) {
			return $this->selected;
		} else {
			return $this->getRequestedPage();
		}
	    return $this->selected;
	}


	public function getControllers() {
	    return $this->controllers;
	}


	/**
	 * Deprecated
	 *
	 * @param unknown $id
	 * @param number $active
	 */
	public function getFolder($id, $active = 1) {
	    //We already got the complete structure loaded once, so why not load it from there instead of querying the db again?
	    return $this->_all2(null, $id, '/', $crumbs = array(array('title'=>'home', 'uri'=>'/')), $active);
	    return $this->_all(null, $id, '/', $crumbs = array(array('title'=>'home', 'uri'=>'/')), $active);
	}


    /**
     * Just dump all routes
     *
     * @param unknown $items
     * @param number $level
     * @return string
     */
    public function dump($items = null, $level = 0) {
    	$items  = $items ? $items : $this->navigation;
	    $level++;
	    $h = '';
	    $h.= '<meta charset="utf-8">';
	    $h.= '<ul class="navigation l'.($level).'">';
	    foreach ($items as $item) {
	        $h.= sprintf('<li>');
	        $u = sprintf('/%s%s', App::getLocale(), $item->path);
	        $u = $item->path;
            $h.= sprintf('<a style="%s" href="%s">%d: %s - %s %s</a>', $item->selected ? 'background-color:silver;':'background:none;', $u, $item->id, $item->title, $u, @print_r($item->controller,1));
            if (isset($item->children))
                //$h.= static::dump($item->children, $level);
                $h.= $this->dump($item->children, $level);
	        $h.= sprintf('</li>');
	    }
	    $h.= '</ul>';
	    return $h;
    }


    public function getItemUrl($item) {
        //route(site.page.people.life.stories.entry,  $item->id)
        //route(site.page.people.nicknames.entry,  $item->id)
        if ($page = $this->find(null, array('key'=>'controller_id', 'val'=>$item->document_type_id))) {
            return route($page->name.'.entry', (string)$item->uri);
        }
    }


    public function getControllerPage($controller_id)
    {
        if ($controller = $this->find(null, array('key'=>'controller_id', 'val'=>$controller_id))) {
            return $controller;
        }
    }


    /**
     * Get entity page object
     * @param unknown $item
     * @return \stdClass
     */
    public function getEntityPage($item)
    {
        $result = new \stdClass();
        if ($result->page = $this->find(null, array('key'=>'controller_id', 'val'=>$item->document_type_id))) {
            $result->uri = route($result->page->name.'.entry', (string)$item->uri);
        }
        return $result;
    }


    public function getEntityController($entity)
    {
    	$result = false;
    	if ($item = PageEntity::select('document_types.controller')
    	 		->leftJoin('document_types', 'controller_id', '=', 'document_types.id')
    	 		->where('document_types.id', $entity->document_type_id)
    	 		->first()) {
    	 			$result = str_replace('Controller', '', $item->controller);
    	}
    	return $result;
    }

    /**
     * Get entity URI
     * @param unknown $item
     * @return string
     */
    public function getEntityUri($item)
    {
    	return $this->getItemUrl($item);
    }


    /**
     * Get all categories, so only return pages with a category controller assigned.
     * @param string $items
     * @param number $level
     * @return string
     */
    public function categories($items = null, $level = 0)
    {
    	$result = array();
    	$items  = $items ? $items : $this->navigation;
    	$level++;
    	foreach ($items as $item) {
    	    if (!$item->controller_id && !count($item->children)) {
    	        //echo "<br>SKIP :$item->title";
    	        continue;
    	    }
     	    if (!in_array($item->controller, array(
     	        '',
    	        'DocumentTextController',
    	        'DocumentQuotedTextController',
    	        'DocumentImageController',
    	        'DocumentAudioController',
    	        'DocumentVideoController',
    	        'DocumentGuestbookController',
    	        'DocumentMessageController',
    	        'DocumentLetterController',
     	        'PeopleNameController'
     	    ))) {
    	        //echo "<br>SKIPX :$item->title";
                continue;
     	    }
    		//fixme: disable 'Names' for now.
    		if (in_array($item->controller_id, array(146))) continue;

    		$result[$item->id] = array('controller_id'=>$item->controller_id, 'controller'=>$item->controller, 'title'=>$item->title->getValue(), 'path'=>$item->path);
    		if (count($item->children)) {
    			$result[$item->id]['children'] = $this->categories($item->children, $level);
    		}
    	}
    	return $result;
    }


    public function getTermsOfUseURI()
    {
    	return route('site.page.terms.of.use');
    }



}
