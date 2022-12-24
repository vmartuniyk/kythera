<?php namespace Kythera\Html;

use Kythera\Models\DocumentEntity;

use Kythera\Router\Router;
use Illuminate\Database\Eloquent\Collection, App, Config;
use Input, Lang, Auth;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Session;
use Kythera\Models\PageEntity;
use App\Models\Folder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/**
 * @author virgilm
 *
 */
class HtmlMenu extends Html {


    /**
     * @var Kythera\Router\Router
     */
    protected $router;


    /**
     * @param Router $router
     */
    public function __construct(Router $router) {
        $this->router = $router;
    }

    /**
     * Build html for site controls (language / login/register)
     *
     * @return string
     */
    public function controls()
    {
        $h = '
        <div id="site-controls" class="container">
        ';

        $h .= $this->login();

        // foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        // {
        //     $h .= '
        //           <span><a type="button" class="btn btn-default btn-xs" href="'.LaravelLocalization::getLocalizedURL($localeCode, null, [], true).'" class="language" rel="alternate" hreflang="'.$localeCode.'">'.$properties['native'].'</a></span> ';
        // }

        $curLang = LaravelLocalization::getCurrentLocale() === 'gr' ? 'el' : 'en';
        // $greekUrl = LaravelLocalization::getLocalizedURL('gr', null, [], true);
        // $englishUrl = LaravelLocalization::getLocalizedURL('en', null, [], true);

        $h .= '
            <div class="pull-right">&nbsp;&nbsp;</div>
            <div class="btn-group pull-right">
              <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                <span class="lang-xs lang-lbl" lang="'.$curLang.'"></span> <span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li><a href="/en"><span class="lang-xs lang-lbl" lang="en"></span></a></li>
                <li><a href="/gr"><span class="lang-xs lang-lbl" lang="el"></span></a></li>
              </ul>
            </div>
           ';

        $h .= '
        </div>
        ';

        return $h;
    }

    /**
     * Build html for menu
     *
     * @return string
     */
    public function header() {

        //Just a one level filter
        $folder = Folder::find(1);
        $pages  = $this->router->getNavigation()->filter(function($page) use ($folder) {
            return ($page->folder_id == $folder->id);
        });
        $endpoint = $this->router->getPageByID(PageEntity::PAGE_ADVANCED_SEARCH)->path;


        $h = sprintf('
        <nav class="header-menu">
          <ul>
             <li class="first">Visitors: <span>%d</span></li>
             <li>Articles: <span>%s</span></li>
        ', Session::get('page.visitors', 9999), DocumentEntity::getCount());


        $pages->each(function($page) use(&$h) {
            $h .=sprintf('
                <li class="%s"><a href="%s" title="%s">%s</a></li>',
                    ($page->selected ? 'active':''),$page->path, e($page->title), e($page->title));
        });

        $h.= sprintf('
                <li class="search last">
    			        <form id="remote" class="search" method="GET" action="%s">
    	        			<i class="fa fa-search"></i>
            				<span>SEARCH</span>
    			            <input type="text" class="typeahead" placeholder="search keywords..." name="q" />
            				<input type="submit" style="visibility:hidden;position:fixed"/>
    			        </form>
                        <div class="spinner" style="background-image:url(/assets/img/ajax-loader-small.gif)">
            		</li>', $endpoint);

        $h .= sprintf('
                <li style="padding: 0px 0px; float: right;">
                  <a href="%s"><img src="/assets/img/entry.png" alt="Add entry" style="height: 28px;" /></a>
                </li>', action('EntryController@create'));


        $h .= '
          </ul>
        </nav><!-- /.header-menu -->
        ';

        return $h;
    }


    /**
     * Build html for bootstrap menu
     *
     * @return string
     */
    public function main() {

        //Just a one level filter
        $folder = Folder::find(2);
        $pages  = $this->router->getNavigation()->filter(function($page) use ($folder) {
            return ($page->folder_id == $folder->id);
        });

        return $this->_renderMain($pages);
    }


    /**
     * Helper to build bootstrap menu
     *
     * @param Collection $collection
     * @param number $level
     * @return string
     */
    protected function _renderMain(Collection $collection, $level = 0) {
        ++$level;
        $h = '';
        $h.= sprintf('
                <ul class="%s">', ($level==1?'main-menu nav navbar-nav':'dropdown-menu'));
        foreach ($collection as $c) {
            $hasChildren = count($c->children);
            $first = ($c == $collection->first());
            $last  = ($c == $collection->last());

            if ($level==1)
            {
                $h.=sprintf('
                       <li class="menu-item%s%s%s%s"><a href="%s" title="%s" %s>%s %s</a>', ($hasChildren?' dropdown':''), ($first?' first':''), ($last?' last':''), ($c->selected ? ' active':''), $c->path, e($c->title), ($hasChildren?'class="dropdown-toggle" data-toggle="dropdown"':''), e($c->title), ($hasChildren?'<b class="caret"></b>':''));
            } else {
                $h.=sprintf('
                       <li class="menu-item %s"><a href="%s" title="%s">%s</a>', ($c->selected ? 'active':''), $c->path, e($c->title), e($c->title));
            }

            //get your kids from school :)
            if ($hasChildren)
                $h.= $this->_renderMain($c->children, $level);

            $h.= sprintf('</li>');
        }
        $h.= '
                </ul>';
        return $h;
    }

    /**
     * Build html for bootstrap menu
     *
     * @return string
     */
    public function sub() {

        //Just a one level filter
        $folder = Folder::find(3);
        $pages  = $this->router->getNavigation()->filter(function($page) use ($folder) {
            return ($page->folder_id == $folder->id);
        });

        return $this->_renderSub($pages);
    }


    /**
     * Helper to build bootstrap menu
     *
     * @param Collection $collection
     * @param number $level
     * @return string
     */
    protected function _renderSub(Collection $collection, $level = 0) {
        ++$level;
        $h = '';
        $h.= sprintf('
                <ul class="%s">', ($level==1?'sub-menu nav navbar-nav':'dropdown-menu'));
        foreach ($collection as $c) {
            $hasChildren = count($c->children);
            $last = ($c == $collection->last());

            if ($level==1)
            {
                $h.=sprintf('
                       <li class="menu-item%s%s%s"><a href="%s" title="%s" %s>%s %s</a>', ($hasChildren?' dropdown':''), ($last?' last':''), ($c->selected ? ' active':''), $c->path, e($c->title), ($hasChildren?'class="dropdown-toggle" data-toggle="dropdown"':''), e($c->title), ($hasChildren?'<b class="caret"></b>':''));
            } else {
                $h.=sprintf('
                       <li class="menu-item%s"><a href="%s" title="%s">%s</a>',($c->selected ? ' active':''), $c->path, e($c->title), e($c->title));
            }

            //get your kids from school :)
            if ($hasChildren)
                $h.= $this->_renderSub($c->children, $level);

            $h.= sprintf('</li>');
        }
        $h.= '
                </ul>';
        return $h;
    }


    /**
     * Build html for menu
     *
     * @return string
     */
    public function footer()
    {
        //Just a one level filter
        $folder = Folder::find(4);
        $pages  = $this->router->getNavigation()->filter(function($page) use ($folder) {
            return ($page->folder_id == $folder->id);
        });

        $c = '';

        $h = '';
        $h.= '<h5>Information</h5>';
        $h.= '<nav class="footer-menu">';
        $h.= '<ul>';
        $pages->each(function($page) use(&$h, &$c) {
        	if ($page->id == PageEntity::PAGE_COPYRIGHT) {

	            $c.=sprintf('
	                <li class="menu-item%s"><a href="%s" title="%s">%s</a></li>',
	                    ($page->selected ? ' active':''),$page->path, e($page->title), e($page->title));

        	} else {

	            $h.=sprintf('
	                <li class="menu-item%s"><a href="%s" title="%s">%s</a></li>',
	                    ($page->selected ? ' active':''),$page->path, e($page->title), e($page->title));
        	}
        });
        $h.= '</ul>';
        $h.= '</nav>';

        $c = '<div class="footer-menu bottom"><nav class="footer-menu"><ul>' .$c. '</ul></nav></div>';

        return $h.$c;
    }


    /**
     * Build login/profile menu option
     *
     */
    public function login()
    {
        $h = '';

        if(Auth::check()) {
            $h.= '
                <ul id="signInDropdown" class="nav navbar-nav navbar-right">
                    <li class="dropdown last">
                        <button type="button" id="dropdownMenu1" data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle"><span class="login">'.Auth::user()->firstname.'</span> <i class="glyphicon glyphicon-user color-black"></i><span class="caret"></span></button>
                        <ul class="dropdown-menu">
                        <li style="width: 300px;">
                <div style="padding:10px">
                    <span class="blue">'. Auth::user()->full_name .'</span>
                    <br/>
                    <span class="gray">'. Auth::user()->email .'</span>
                    <br/>';

            if (Auth::user()->isAdmin()) {
                $h.= '<br/>&gt; <a target="cms" class="black" href="'. action('Admin\AdminDashboardController@getIndex') .'">CMS Admin</a>';
            }

            // if ($this->router->has('site.page.your.personal.page')) {
                $h.= '<br/>&gt; <a class="black" href="'. action('PersonalPageController@getIndex') .'">Your personal page</a>';
                $h.= '<br/>&gt; <a class="black" href="'. action('PersonalPageController@edit') .'">Edit your details</a>';
            // }

            $h.= '
                </div>

                <div class="thin-line"></div>
                <div class="form-group">
                    <a class="btn btn-black pull-right" href="'.action('UsersController@getLogout').'">Logout</a>
                </div>';

            $h.='
                        </li>
                        </ul>
                    </li>
                </ul>
            ';
        } else {
            $h.= '
                <ul id="signInDropdown" class="nav navbar-nav navbar-right">
                    <li class="dropdown last">
                        <button type="button" id="dropdownMenu1" data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle"><span class="login">Login/Register</span> <i class="glyphicon glyphicon-user color-black"></i><span class="caret"></span></button>
                        <ul class="dropdown-menu">
                          <li style="width: 250px;">
                                <form class="navbar-form form" method="POST" action="'. action('UsersController@postLogin') .'" accept-charset="UTF-8" role="form">
                                    '. csrf_field() .'
                                    <div class="form-group">
                                      <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-black"></i></span>
                                            <input name="email" type="text" value="'. Input::old('email') .'" id="emailInput" placeholder="Username or Email" class="form-control" oninvalid="setCustomValidity(\'Please enter a valid user or email address!\')" onchange="try{setCustomValidity(\'\')}catch(e){}" required="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-black"></i></span>
                                            <input name="password" type="password" placeholder="Password" id="passwordInput" class="form-control" oninvalid="setCustomValidity(\'Please enter a password!\')" onchange="try{setCustomValidity(\'\')}catch(e){}" required="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                    		                <div class="checkbox">
                    		                    <label for="remember">
                    		                        <input type="checkbox" name="remember" id="remember"> Remember Me
                    		                    </label>
                    		                </div>
                    		            </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-black form-control pull-left">Login</button>
                                        <span class="pull-right">
                                            <a class="btn btn-default" href="'. action('UsersController@getAccess') .'">Register</a>
                                        </span>
                                    </div>';

            $h.='
                                    <hr/>
                                    <span class="pull-right"><a href="'. action('Auth\PasswordController@showResetForm') .'">Forgot Password?</a></span>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
                ';
        }
        return $h;
    }


    /**
     * Build edit entry toolbar considering permissions
     *
     */
    public function entry_edit_error3237($user_id, $entry, $classname = 'pull-right')
    {
        if (isset($entry->item))
            $entry = $entry->item;

        $h = '';
        if(Auth::check() && $entry) {
            $h .= '<div class="toolbar">';

            $cats = $entry->categories()->first();

            //add to category
            if ($cats) {
                switch ($cats->id) {
                    default:
                        //$h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'. route('entry.create.cat', $cats->id) .'" title="Submit an entry here!"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> Submit an entry here!</a>';
                        $h .= '<a class="blue ' . $classname . '" href="' . route('entry.create.cat', $cats->id) . '" title="Submit an entry here!"> Submit an entry here!</a>';
                }
            }

            //edit entry
            if ((Auth::user()->id === $user_id) || (Auth::user()->isAdmin())) {
                //switch($entry->item->document_type_id) {
                switch($cats->id) {
                    case 23:
                        //guestbook
                        $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'. action('DocumentGuestbookController@edit', $entry->id) .'" title="Edit guestbook entry"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>';
                        break;
                    default:
                        //$h.= '<a class="personal '.$classname.'" href="'. action('EntryController@edit', $entry->id) .'">&raquo; Edit entry</a>';
                        $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'. action('EntryController@edit', $entry->id) .'" title="Edit entry"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>';
                }

                //enable/disable entry
                if ($entry->enabled) {
                    $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'.action('EntryController@enable', array($entry->id, 0)).'" title="Disable entry"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> Disable</a>';
                } else {
                    $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'.action('EntryController@enable', array($entry->id, 1)).'" title="Enable entry"><span class="glyphicon glyphicon-eye-open alert-warning" aria-hidden="true"></span> Enable</a>';
                }
            }

            //edit top article
            //switch($entry->item->document_type_id) {
            switch($cats->id) {
                case 18:
                    break;
                default:
                    if (Auth::user()->isAdmin() && isset($entry->top_article)) {
                        if ($entry->top_article) {
                            //$h.= '<br><a class="personal '.$classname.'" href="'. action('EntryController@action', array($entry->id, 'degrade')) .'">&raquo; Top article off</a>';
                            $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'. action('EntryController@promote', array($entry->id, 'degrade')) .'" title="Disable entry as top article"><span class="glyphicon glyphicon-star blue" aria-hidden="true"></span> Top article</a>';
                        } else {
                            //$h.= '<br><a class="personal '.$classname.'" href="'. action('EntryController@action', array($entry->id, 'promote')) .'">&raquo; Top article on</a>';
                            $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'. action('EntryController@promote', array($entry->id, 'promote')) .'" title="Set entry as top article"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> Top article</a>';
                        }
                    }
            }

            $h .= '</div>';
        } else {

            if ($catId = Session::get('selected.catId', 0)) {
                $h .= '<div class="toolbar">';
                $h.= '<a class="blue '.$classname.'" href="'. route('entry.create.cat', $catId) .'" title="Submit an entry here!"> Submit an entry here!</a>';
                $h .= '</div>';
            }

        }
        return $h;
    }


    /**
     * Build edit entry toolbar considering permissions
     *
     */
    public function entry_edit($user_id, $entry, $classname = 'pull-right')
    {
        $h = '';
        if(Auth::check() && $entry) {
            $h .= '<div class="toolbar">';

            if (isset($entry->item))
                $cats = $entry->item->categories()->first();
            else
                $cats = $entry->categories()->first();

            //add to category
            if ($cats) {
                switch ($cats->id) {
                    default:
                        //$h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'. route('entry.create.cat', $cats->id) .'" title="Submit an entry here!"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> Submit an entry here!</a>';
                        $h .= '<a class="blue ' . $classname . '" href="' . route('entry.create.cat', $cats->id) . '" title="Submit an entry here!"> Submit an entry here!</a>';
                }
            } else {
                //messageboard:18
                $cats = new \stdClass();
                if (isset($entry->item))
                    $cats->id = $entry->item->document_type_id;
                else
                    $cats->id = $entry->document_type_id;
            }

            //edit entry
            if ((Auth::user()->id === $user_id) || (Auth::user()->isAdmin())) {
                //switch($entry->item->document_type_id) {
                switch($cats->id) {
                    case 23:
                        //guestbook
                        $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'. action('DocumentGuestbookController@edit', $entry->id) .'" title="Edit guestbook entry"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>';
                        $h.= '<a class="btn btn-default btn-xs '.$classname.'" onclick="return confirm(\'Are you sure you want to delete?\')" href="'. route('guestbook.delete', ['id' => $entry->id]) .'" title="Delete guestbook entry"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</a>';
                        break;
                    default:
                        //$h.= '<a class="personal '.$classname.'" href="'. action('EntryController@edit', $entry->id) .'">&raquo; Edit entry</a>';
                        $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'. action('EntryController@edit', $entry->id) .'" title="Edit entry"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>';
                }

                //enable/disable entry
                if ($entry->item->enabled) {
                    $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'.action('EntryController@enable', array($entry->id, 0)).'" title="Disable entry"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> Disable</a>';
                } else {
                    $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'.action('EntryController@enable', array($entry->id, 1)).'" title="Enable entry"><span class="glyphicon glyphicon-eye-open alert-warning" aria-hidden="true"></span> Enable</a>';
                }
            }

            //edit top article
            //switch($entry->item->document_type_id) {
            switch($cats->id) {
                case 18:
                    break;
                default:
                    if (Auth::user()->isAdmin() && isset($entry->item->top_article)) {
                        if ($entry->item->top_article) {
                            //$h.= '<br><a class="personal '.$classname.'" href="'. action('EntryController@action', array($entry->id, 'degrade')) .'">&raquo; Top article off</a>';
                            $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'. action('EntryController@promote', array($entry->id, 'degrade')) .'" title="Disable entry as top article"><span class="glyphicon glyphicon-star blue" aria-hidden="true"></span> Top article</a>';
                        } else {
                            //$h.= '<br><a class="personal '.$classname.'" href="'. action('EntryController@action', array($entry->id, 'promote')) .'">&raquo; Top article on</a>';
                            $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'. action('EntryController@promote', array($entry->id, 'promote')) .'" title="Set entry as top article"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> Top article</a>';
                        }
                    }
            }

            $h .= '</div>';
        } else {

            if ($catId = Session::get('selected.catId', 0)) {
            	switch ($catId) {
            		/*
            		case PageEntity::PAGE_GUESTBOOK:
            		break;
            		*/
            		case 23: #guestbook cat
           			break;
            		default:
            			$h.= '<div class="toolbar">';
            			$h.= '<a class="blue '.$classname.'" href="'. route('entry.create.cat', $catId) .'" title="Submit an entry here!"> Submit an entry here!</a>';
            			$h.= '</div>';
            	}
            }

        }
        return $h;
    }


    public function personal()
    {
    	$h = '';
    	$h.= '<ul>';
    	foreach($items = DocumentEntity::getUserEntries(Auth::user()) as $item) {
    		if ($page = $this->router->getPageByID($item->page_id)) {
    			$h.=sprintf('<li><a href="%s">%s (%d)</a></li>', $page->path, $item->cat, $item->n);
    		} else {
    			$h.=sprintf('<li>%s (%d)</li>', $item->cat, $item->n);
    		}
    	}
    	$h.= '</ul>';
    	return $h;
    }


    public function categories($items = array(), $selected = 0, $empty = false, $level = 0)
    {
    	$level++;
    	$h = '';
    	if ($level==1 && is_numeric($empty)) {
    		$h.= sprintf('<option value="%d">Please choose</option>', $empty);
    	}
    	foreach ($items as $item) {
    	    //echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';

    	    $features = [];
    	    switch($item['controller']) {
    	        case 'DocumentQuotedTextController':
    	        case 'DocumentLetterController':
    	        default:
    	            $features = array('controller' => $item['controller']);
    	    }
    	    $json = json_encode($features, JSON_FORCE_OBJECT | JSON_HEX_QUOT);

    		$h.= (($level==1) && (isset($item['children']) && ($count = count($item['children'])))) ? sprintf('
    				<optgroup label="%s">', $item['title']) :
    				sprintf('
    				<option value="%d"%s data-features=\'%s\'>%s', $item['controller_id'], $selected==$item['controller_id'] ? ' selected="selected"':'', $json, $item['title']);

    		if (isset($item['children']) && count($item['children']))
    			$h.= $this->categories($item['children'], $selected, $empty, $level);

    		$h.= (($level==1) && (isset($item['children']) && ($count = count($item['children'])))) ? sprintf('
    				</optgroup>') :
    				sprintf('</option>');
    	}
    	return $h;
    }


    public function categories2($items = array(), $selected = 0, $empty = false, $level = 0)
    {
    	$level++;
    	$h = '';
    	if ($level==1 && is_numeric($empty)) {
    		$h.= sprintf('<option value="%d">Please choose</option>', $empty);
    	}
    	foreach ($items as $item) {
    		$h.= (($level==1) && (isset($item['children']) && ($count = count($item['children'])))) ? sprintf('
    				<optgroup label="%s">', $item['title']) :

    				($item['controller_id']==0?'':
    				sprintf('
    				<option value="%d"%s>%s', $item['controller_id'], $selected==$item['controller_id'] ? ' selected="selected"':'', $item['title'])
    		        );

    		if (isset($item['children']) && count($item['children']))
    			$h.= $this->categories($item['children'], $selected, $empty, $level);

    		$h.= (($level==1) && (isset($item['children']) && ($count = count($item['children'])))) ? sprintf('
    				</optgroup>') :

    				($item['controller_id']==0?'':
    				sprintf('</option>')
    				);
    	}
    	return $h;
    }


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



    public function author($item)
    {
    	return '<p class="author">'. trans('locale.submitted.href', array(
    			'fullname'=>$this->fullname($item, false),
    			'date'=>$item->created_at->format('d.m.Y'),
    			'href'=>action('UsersController@getContact', $item->id)
    			)).'</p>';

    }













}
