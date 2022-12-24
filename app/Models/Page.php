<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Kythera\Models\TranslateModel;

/**
 * @author virgilm
 *
 * Depricated. In favour of PageEntity class.
 */
class Page extends TranslateModel
{


    const PAGE_HOME = 11;


    use SoftDeletes;


    protected $table = "pages";


    public $name;


    protected $fillable = ['parent_id', 'folder_id', 'controller_id', 'active', 'title', 'uri', 'content', 'meta_title', 'meta_keywords', 'meta_description', 'plugin'];


    /**
     * @var Change translation technique to document style
     */
    public $translatable = [
            'title' => Translation::TRANSLATE_STRING,
            'uri' => Translation::TRANSLATE_STRING,
            'content' => Translation::TRANSLATE_TEXT
            ];


    /**
     * Holds the page crumbs
     *
     * @var array
     */
    public $crumbs = [];


    /**
     * Holds any child pages
     *
     * @var Illuminate\Database\Eloquent\Collection
     */
    public $children = [];


    /**
     * Page is currently requested
     *
     * @var boolean
     */
    public $selected = false;


    /**
     * Controller for this page
     *
     * @var array
     */
    //public $controller = array();


    public static $rules = [
        'folder_id'   =>'required',
        'title'       =>'required|min:5|max:255',
        'uri'         =>'min:5|max:255',
    ];


    /**
     * Determine I can have children
     *
     * @param Page $page
     * @return boolean
     */
    public static function canHaveChildren($page)
    {
        return (in_array($page->folder_id, [2,3]) && !$page->parent_id);
    }


    /**
     * Getter for the content attribute
     *
     * @param string $value
     * @return string
     */
    /*
	public function getContentAttribute($value) {
	    //fixme:
	    //is should be here but sinds were consuming a page model loaded from the router the content field is missing
	    //and potentially more!
	    if (!$this->getAttributeFromArray('content')) {
	        if ($data = DB::table('pages')->select('content')
	            ->where('id', '=', $this->id)
	            ->first()) {
	            $value = $data->content;
	        }
	    }
	    return $value;
	}
	*/


    public function full()
    {
        //get missing values
        $data = Page::find($this->id);
        foreach ($data->getAttributes() as $key => $value) {
            if (!array_key_exists($key, $this->attributes)) {
                $this->attributes[$key] = $value;
            }
        }
        return $this;
    }


    /**
     * Toggle visibility of a page by changing the active attribute
     *
     * @param int $id
     * @return stdClass
     */
    protected function cmdToggleActive($id)
    {
        $result = new stdClass();
        $result->result  = false;

        if ($page = Page::find($id)) {
            $page->active = (int)!$page->active;
            if ($result->result = $page->save()) {
            }
        }

        return $result;
    }
}
