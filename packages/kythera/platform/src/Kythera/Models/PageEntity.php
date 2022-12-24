<?php
namespace Kythera\Models;

use Kythera\Entity\Entity;
use Kythera\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Contracts\ArrayableInterface;
use Kythera\Router\Facades\Router;
use Illuminate\Support\Facades\DB;

class PageEntityException extends \Exception {}

/**
 * @author virgilm
 *
 */
class PageEntity extends Entity
{
	//use SoftDeletingTrait;


	const PAGE_HOME = 11;
	const PAGE_ADVANCED_SEARCH = 113;
	const PAGE_COPYRIGHT = 64;
	const PAGE_GUESTBOOK = 87;


	/**
	 * Table where to store translatables
	 *
	 * @var string
	 */
	public $attributes_table = 'pages_attributes';


	/**
	 * Default attribute type
	 *
	 * @var integer
	 */
	public $document_type_id = 10000;


	/**
	 * URI
	 *
	 * @var string
	 */
	public $path;


	/**
	 * Unique name for route
	 *
	 * @var string
	 */
	public $name;


	/**
	 * Holds the page crumbs
	 *
	 * @var array
	 */
	public $crumbs = array();


	/**
	 * Holds any child pages
	 *
	 * @var Illuminate\Database\Eloquent\Collection
	*/
	public $children = array();


	/**
	 * Page is currently requested
	 *
	 * @var boolean
	*/
	public $selected = false;


	/**
	 * Load primary language when second language is not available
	 *
	 * @var string
	 */
	public $original_language = 'en';


    /**
     * Models table name.
     *
     * @var string
     */
    protected $table = "pages";


    /**
     * Fillable class properties.
     *
     * @var array
     */
    protected $fillable = array('parent_id', 'folder_id', 'active', 'sort', 'controller_id');


    //protected $appends = array('router_name');


    protected $default_attributes = array(
    	'id', 'parent_id', 'folder_id', 'active', 'sort', 'controller_id', 'created_at', 'updated_at', 'router_name'
    );


    /**
     * Translatable class properties.
     *
     * @var array
     */
    protected $entity_attributes  = array (
        'uri' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
        'title' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
        'content' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
        'image' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
        'colorbox' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
        'colorboxurl' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
        'colorboxtitle' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
        'colorboximage' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING)
    );


    /**
     * Validate rules.
     *
     * @var array
     */
    public static $rules = array(
        //todo: |unique:document_attributes,value dependend on category
    	'folder_id'   =>'required',
   		'title'       =>'required|min:2|max:255',
   		'uri'         =>'min:1|max:255',
    );


    /**
     * Register to model's events
     */
    public static function boot()
    {
    	parent::boot();

    	static::saved( function($model)
    	{
    		$model->clearCache();
    	});
    }


    /**
     * Determine if I can have children.
     *
     * @param Page $page
     * @return boolean
     */
    public static function canHaveChildren($page) {
    	return (in_array($page->folder_id, array(2,3)) && !$page->parent_id);
    }


    /**
     * Ajax command: toggle visibility of a page by changing the active attribute
     *
     * @param int $id
     * @return stdClass
     */
    protected function cmdToggleActive($id) {
    	$result = new \stdClass();
    	$result->result  = false;

    	if ($page = PageEntity::find($id)) {
    		$page->active = (int)!$page->active;
    		if ($result->result = $page->save()) {
    		}
    	}

    	return $result;
    }


    /**
     * Ajax command: Update folder/page order from ajax
     *
     * @param string $f
     * @param array $pages
     * @return \stdClass
     */
    protected function cmdOrderPages($f, array $pages) {
    	$result = new \stdClass();
    	$result->result  = false;

    	if ($f && $pages) {
    		\Log::info('cmdOrderPages', array($f, $pages));
    		$folder = str_replace('f', '', $f);
    		foreach($pages as $i => $page) {
    			$page = str_replace('p', '', $page);
    			//$query = sprintf ( 'UPDATE pages SET folder_id=%d, sort=%d WHERE id=%d LIMIT 1;', $folder, ($i + 1) * 10, $page );
    			//Log::info ( $query );
    			if ($page = PageEntity::find($page)) {
    				$page->parent_id = ($folder == $page->folder_id) ? $page->parent_id : null;
    				$page->folder_id = $folder;
    				$page->sort = ($i + 1) * 10;
    				$page->save();
    			}
    		}
    		$result->result = true;
    	}

    	return $result;
    }


    /**
     * Content attribute mutator
     *
     * @param string $value
     * @return string
     */
    public function getContentAttribute($value)
    {
    	return \App\Classes\Helpers::obfuscateEmails((string)$value);
    }


    public function getContentWithLine() {
        $result = $this->content;
        if (preg_match('#<h1>(.*)<\/h1>#', $result, $m)) {
            $result = str_replace($m[0], $m[0].'<div class="line"></div>', $result);
        }
        return $result;
    }


    public function getRouterNameAttribute($value)
    {
        $value = DB::table($this->attributes_table)
            ->where('document_entity_id', $this->id)
            ->where('key', 'uri')
            ->where('l', 'en')
            ->pluck('value');

        return $value;
    }


    private function clearCache()
    {
    	$key = 'router_'.App::getLocale();
    	if (Cache::has($key))
    	{
    		Cache::forget($key);
    	}
    }


    public function getRouter()
    {
    	$result = \Kythera\Router\Facades\Router::find(null, array('key'=>'controller_id', 'val'=>$this->controller_id));
    	echo __FILE__.__LINE__.'<pre>$result='.htmlentities(print_r($result,1)).'</pre>';
    	return $result;
    }


    /**
     * @return string
     *
     * Ej. /en/people/life-stories
     */
    public function getUri()
    {
    	if ($page = $this->getRouter()) {
    		return $page->path;
    	}
    	return false;
    }


    public function getImageUri()
    {
        //<img src="/en/photos/1/527/354/key.jpg" width="527" height="354">
        if ($this->image) {
            return '/en/photos/1/527/354/'. $this->image;
        }
        return false;
    }


    public function getColorBoxImageUri()
    {
        if ($this->colorboximage) {
            return '/en/media/key/1/'. $this->colorboximage;
        }
        return false;
    }


    public function getColorBox()
    {
        $h = "";
        $title = $this->colorboxtitle->getValue();
        if (!empty($this->colorbox) && !empty($this->colorboxurl) && !empty($this->colorboximage) && !empty($title)) {
            $h.= sprintf('
            <div class="col-md-6 key2">
                <a href="%s" title="%s">
                    <div>
                        <img src="%s" alt="%s" />
                    </div>
                    <div class="text color3">
                        <h2>%s</h2>
                        <div class="line"></div>
                        <p>%s</p>
                    </div>
                </a>
            </div>',
                $this->colorboxurl, $this->colorboxtitle,
                $this->getColorBoxImageUri(), $this->colorboxtitle,
                $this->colorboxtitle,
                $this->colorbox
                );
        }
        //echo __FILE__.__LINE__.'<pre>$h='.htmlentities(print_r($h,1)).'</pre>';die;
        return $h;
    }


    /**
     * @return array
     */
    public function getPath()
    {
    	$result = array();
    	if ($page = $this->getRouter()) {
    		$result = $page->crumbs;
    	}
    	return $result;
    }


	/**
	 * Try to fix old url structure to new
	 *
	 * @param $uri
     */
	static public function convert($uri, $redirect = false)
	{
        /*
        /index.php?nav=104-111&cid=201-41&did=7788&pageflip=2&hits=20&enlarge=1
        /index.php?nav=135-139&cid=277&did=7224
        /index.php?nav=3&selLet=0&selVil=17
        /index.php?nav=162&selLet=0&selVil=17
        /index.php?nav=3-162&selLet=0&selVil=17
        /index.php?nav=3-162&cid=342&did=23336&pageflip=1&hits=20&act=338&did=23337 Selects two did's!

        /index.php?nav=9&cid=246&did=4527&view=DEF&author=149&start=30
        */
        $url = '';
        if ( (stripos($uri, 'index.php')!==FALSE) || (stripos($uri, '/redirect?')!==FALSE) ) {
            $queries = parse_url($uri, PHP_URL_QUERY);
            $queries = \GuzzleHttp\Psr7\parse_query($queries);

            //try redirecting based on document id (view pages)
            $did = isset($queries['did']) ? $queries['did'] : 0;

            //fix: #8603
            //Can contain multiple entries so only select first element
            $did = (count($did)>1) ? current($did) : $did;

            if ($did) {
                //echo __FILE__ . __LINE__ . '<pre>$did=' . htmlentities(print_r($did, 1)) . '</pre>';
                if ($entry = DocumentEntity::find($did)) {
                    //echo __FILE__ . __LINE__ . '<pre>$entry=' . htmlentities(print_r($entry, 1)) . '</pre>';die;
                    if ($entry_page = Router::getEntityPage($entry)) {
                        //echo __FILE__ . __LINE__ . '<pre>$entry_page=' . htmlentities(print_r($entry_page, 1)) . '</pre>';die;
                        if (!empty($entry_page->page)) {
                            $url = sprintf('http://%s%s/%s', $_SERVER['HTTP_HOST'], $entry_page->page->path, $entry->uri);
                        }
                    } else {
                        //page does not exist
                    }
                } else {
                    //document does not exist
                    //echo __FILE__.__LINE__.'<pre>$entry='.htmlentities(print_r($entry,1)).'</pre>';die;
                }
            }

            //try redirecting based on document controller (list pages)
            $nav = isset($queries['nav']) ? $queries['nav'] : 0;
            //Can contain multiple entries so only select first element
            $nav = (count($nav)>1) ? current($nav) : $nav;
            //echo __FILE__.__LINE__.'<pre>$nav='.htmlentities(print_r($nav,1)).'</pre>';die;
            if ($nav && !$url) {
                if ($pid = self::extractPageId($nav)) {
                    if ($page = \App\Classes\Import::find(null, $pid)) {
                        //echo __FILE__ . __LINE__ . '<pre>$page=' . htmlentities(print_r($page, 1)) . '</pre>';
                        if ($page->document_type_id) {
                            if ($entry_page = Router::getPageByController($page->document_type_id)) {
                                $url = sprintf('http://%s%s', $_SERVER['HTTP_HOST'], $entry_page->path);
                            } else {
                                //page does not exist
                            }
                        }
                    }
                }
            }

            if ($url && $redirect) {
                \Log::alert(sprintf('URL-REDIRECT: %s > %s', $uri, $url));
                header('Location: '.$url, 301);
                exit;
            } else {
                \Log::alert(sprintf('URL-SKIPPED1: %s', $uri));
            }
        }

        return $url;
	}


    static private function extractPageId($nav)
    {
        $r=explode('-', $nav);
        return end($r);
    }


    public function isHomepage()
    {
    	return $this->id == self::PAGE_HOME;
    }

}
