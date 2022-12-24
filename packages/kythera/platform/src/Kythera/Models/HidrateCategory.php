<?php
namespace Kythera\Models;

use Kythera\Models\DocumentCategory;

class HidrateCategory extends DocumentCategory
{
  	/**
  	 * Hidden fields for hidration
  	 * @var array
  	 */
  	protected $hidden = array('table_name', 'controller', 'label', 'group_label', 'pivot');

  	/**
  	 * The accessors to append to the model's array form.
  	 *
  	 * @var array
  	*/
  	protected $appends = array('title', 'uri', 'url');


  	private $page = null;

  	/**
  	 * Get category/page title
  	 *
  	 * @return string
  	 */
  	public function getTitleAttribute()
  	{
  		$result = 'UNDEFINED_PAGE_TITLE';
  		if ($this->getPage()) {
  			$result = $this->page->title->getValue();
  		}
  		return $result;
  	}

  	/**
  	 * Get category/page uri
  	 *
  	 * @return string
  	 */
  	public function getUriAttribute()
  	{
  		$result = 'UNDEFINED_PAGE_URI';
  		if ($this->getPage()) {
  			$result = $this->page->uri->getValue();
  		}
  		return $result;
  	}

  	/**
  	 * Get category/page full url
  	 *
  	 * @return string
  	 */
  	public function getUrlAttribute()
  	{
  		$result = 'UNDEFINED_PAGE_URL';
  		if ($this->getPage()) {
  			if ($page = \Route::find(null, array('key'=>'controller_id', 'val'=>$this->page->controller_id))) {
  				$result = route($page->name);
  			}
  		}
  		return $result;
  	}

  	/**
  	 * Getter for page attribute
  	 *
  	 * @return PageEntity
  	 */
  	protected function getPage()
  	{
  		if (!$this->page) {
  			$this->page = PageEntity::where('controller_id', $this->id)->first();
  		}
  		return $this->page;
  	}
}
