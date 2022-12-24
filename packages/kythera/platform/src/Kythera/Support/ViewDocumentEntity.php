<?php

namespace Kythera\Support;

use Config;
use Illuminate\Support\Str;

use Kythera\Router\Facades\Router;
use Kythera\Html\Facades\Html;


class ViewDocumentEntity
{

	/**
	 * Original document
	 *
	 * @var DocumentEntity
	 */
		public $item;

    public $title;
    public $uri;
    public $content;
    public $crumbs;
    public $image;


    public function __construct($item, $mode = ViewEntity::VIEW_MODE_VIEW)
    {
    	$this->item = $item;

    	switch($mode) {
    		case ViewEntity::VIEW_MODE_HOME:
    		case ViewEntity::VIEW_MODE_SIDEBAR:
    			$this->id         = $item->id;
		        $this->title      = e($item->title);
		        $this->content    = Str::words(e(strip_tags($item->content)), 20);
		        $data             = Router::getEntityPage($item);
		        if (isset($data->page)) {
		        //$this->crumbs     = $this->crumbs($data->page, ', ', true);
		        $this->crumbs     = Html::crumbs($data->page, ', ', true);
		        $this->uri        = $data->uri;
		        }
		        $this->user_id    = $item->user_id;
    		break;
    		case ViewEntity::VIEW_MODE_KEYS:
    			$this->id         = $item->id;
		        $this->title      = e($item->title);
		        $data             = Router::getEntityPage($item);
		        if (isset($data->page)) {
		        //$this->crumbs     = $this->crumbs($data->page, ', ', true);
		        $this->crumbs     = Html::crumbsKeys($data->page, ', ');
		        $this->uri        = $data->uri;
		        }
		        $this->user_id    = $item->user_id;
	        break;
    		case ViewEntity::VIEW_MODE_GUESTBOOK:
    		break;
    		case ViewEntity::VIEW_MODE_VIEW:
    		case ViewEntity::VIEW_MODE_LIST:
  		  case ViewEntity::VIEW_MODE_FOOTER:
    		default:
    		    //todo:obfuscate emails
    			$this->id         = $item->id;
		        $this->title      = e($item->title.' ');
		        $this->content    = (string)$item->content;
		        $this->firstname  = $item->firstname;
		        $this->middlename = $item->middlename;
		        $this->lastname   = $item->lastname;
		        $this->updated_at = $item->updated_at;
		        $this->created_at = $item->created_at;

		        $this->uri        = '';
		        $data             = Router::getEntityPage($item);
		        if (isset($data->page)) {
		        //$this->crumbs     = $this->crumbs($data->page, ', ', true);
		        if (isset($data->page->crumbs))
		        $this->crumbs     = Html::crumbs($data->page, ', ', true);
		        if (isset($data->uri))
		        $this->uri        = $data->uri;
		        }
		        $this->content    = \App\Classes\Helpers::obfuscateEmails($this->content);
		        $this->user_id    = $item->user_id;
    	}
    }


  protected function getImages($content = '', $replace = false, $mode = ViewEntity::VIEW_MODE_VIEW)
	{
		//ALERT: document_images.entry_id do NOT correspond with document_entities.id!!
		$result = (string)$content;

		if (preg_match_all('#\[\[picture:"([^"]+)" ID:([0-9]+)]]#', $content, $matches))
		{
			$replaces = array();
		    if ($items = \DB::table('document_images')
					->whereIn('entry_id', $matches[2])
					->get()) {
						foreach ($items as $item) {
							switch ($mode) {
								case ViewEntity::VIEW_MODE_VIEW:
									$title	= sprintf('%s - %s', trim($this->title), trim(pathinfo($item->original_image_name, PATHINFO_FILENAME)));
									$img	= sprintf('
								    <a href="http://%s/%s0/0/%s" data-lightbox="%s" data-title="%s">
								    <img src="http://%s/%s673/0/%s" alt="%s"/>
								    </a>',
									$_SERVER['HTTP_HOST'], $item->image_path, $item->image_name, $title, $title,
									$_SERVER['HTTP_HOST'], $item->image_path, $item->image_name, $title
									);
									$replaces[] = $img;
								break;
								default:
									//$replaces[] = sprintf('<img src="http://www.kythera-family.net/%s%s" alt="%s" />', $item->image_path, $item->image_name, pathinfo($item->original_image_name, PATHINFO_FILENAME));
									//$img	= sprintf('<img src="/%s0/0/%s" alt="%s" style="width:100%%"/>', $item->image_path, $item->image_name, pathinfo($item->original_image_name, PATHINFO_FILENAME));
									$title	= sprintf('%s - %s', trim($this->title), trim(pathinfo($item->original_image_name, PATHINFO_FILENAME)));
									$img	= sprintf('
								    <a href="/%s0/0/%s" data-lightbox="%s" data-title="%s">
								    <img src="/%s0/0/%s" alt="%s" style="width:100%%"/>
								    </a>',
									$item->image_path, $item->image_name, $title, $title,
									$item->image_path, $item->image_name, $title
									);
									$replaces[] = $img;
							}
						}
			}

			#replace tags with images, by reference!
			if ($replace)
			$result = str_ireplace($matches[0], $replaces, $content);
	    }

	    return $result;
	}


	protected function getImageFromContent($width = 165, $height = 105, $content)
	{
	    //ImageDocument:http://kfn.laravel.debian.mirror.virtec.org/en/history/documents/ionian-union-of-greece-citation-for-professor-minas-coroneo
		$result = new \stdClass();
		$result->image = route('image.placeholder', ['width' => $width, 'height'=> $height, 'name' => $this->title]);
		$result->images  = [];
		$result->content = $content;

		if (preg_match_all('#\[\[picture:"([^"]+)" ID:([0-9]+)]]#', $content, $matches))
		{
		    //echo __FILE__.__LINE__.'<pre>$matches='.htmlentities(print_r($matches,1)).'</pre>';
		    sort($matches[2]);
			if ($items = \DB::table('document_images')
					->whereIn('entry_id', $matches[2])
			        ->orderBy('entry_id', 'ASC')
					->get()) {
					    //echo __FILE__.__LINE__.'<pre>$items='.htmlentities(print_r($items,1)).'</pre>';
					    $images  = [];
					    $sources = [];
					    foreach ($items as $item) {
					        $src       = route('image.view', ['width' => $width, 'height'=> $height, 'path'=>preg_replace("/[^0-9]/","",$item->image_path), 'name' => $item->image_name]);
					        $sources[] = $src;
					        $title     = sprintf('%s - %s', trim($this->title), trim(pathinfo($item->original_image_name, PATHINFO_FILENAME)));
					        $images[]  = sprintf('<img src="%s" alt="%s"/>', $src, $title);
					    }
				//	    echo __FILE__.__LINE__.'<pre>$images='.htmlentities(print_r($images,1)).'</pre>';
				    #replace tags
				    $result->content = str_ireplace($matches[0], $images, $content);
				    $result->images  = $images;
				    if (count($sources)) {
				    	$result->image = current($sources);
				    }
			}

		}

		if (Config::get('app.disable_images', false)) {
			$result->image = '';
		}
		return $result;
	}


	protected function getImageFromContent_old($width = 165, $height = 105, $content)
	{
		$result = new \stdClass();
		$result->image = route('image.placeholder', ['width' => $width, 'height'=> $height, 'name' => $this->title]);
		$result->content = $content;

		if (preg_match_all('#\[\[picture:"([^"]+)" ID:([0-9]+)]]#', $content, $matches))
		{
		    echo __FILE__.__LINE__.'<pre>$matches='.htmlentities(print_r($matches,1)).'</pre>';

			if ($item = \DB::table('document_images')
					->whereIn('entry_id', $matches[2])
					->first()) {
						$result->image = route('image.view', ['width' => $width, 'height'=> $height, 'path'=>preg_replace("/[^0-9]/","",$item->image_path), 'name' => $item->image_name]);
					}

			#remove tags
			$result->content = str_ireplace($matches[0], '', $content);
		}

		if (Config::get('app.disable_images', false)) {
			$result->image = '';
		}
		return $result;
	}


    /**
     * Depricated. Use xhtml::crumbs() instead.
     */
	/*
    protected function crumbs($page, $seperator = ' &gt; ', $all = false)
    {
        //<a href="" title="">Home</a> > <span>Contact</span>
        $h = '';
        foreach ($page->crumbs as $crumb) {
            if ($crumb != end($page->crumbs) || $all)
                $h[] = sprintf('<a href="%s" title="%s">%s</a>', $crumb['uri'], $crumb['title'], $crumb['title']);
            else
                $h[] = sprintf('<span>%s</span>', $crumb['title']);
        }
        return implode($seperator, $h);
    }
    */
}
