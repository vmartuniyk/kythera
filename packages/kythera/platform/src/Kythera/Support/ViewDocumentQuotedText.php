<?php

namespace Kythera\Support;

use Config;

class ViewDocumentQuotedText extends ViewDocumentEntity
{


    public $content;

    /**
     * Array of related images
     *
     * @var array
     */
    protected $images;


	public function __construct($item, $mode = ViewEntity::VIEW_MODE_VIEW)
	{
		parent::__construct($item, $mode);

		$this->getImages();

		switch($mode)
		{
    		case ViewEntity::VIEW_MODE_HOME:
		    case ViewEntity::VIEW_MODE_HOMEROW:
		    case ViewEntity::VIEW_MODE_SIDEBAR:
		    case ViewEntity::VIEW_MODE_KEYS:
		    case ViewEntity::VIEW_MODE_KEYSMALL:
		    case ViewEntity::VIEW_MODE_FOOTER:
			case ViewEntity::VIEW_MODE_VIEW:
			default:
			    $this->getContent(ViewEntity::VIEW_MODE_VIEW);
			    $this->source = $item->source;
		}

		//fixme: We can not unset this, to save memoery, because the admin buttons need it. Grrrr
		//unset($this->item);
	}


	protected function getContent($mode = ViewEntity::VIEW_MODE_VIEW)
	{
	    $this->content = $this->item->content;
        foreach($this->images as $i=>$image) {
            $image = $this->getImage($mode, $i);
            switch($mode) {
                case ViewEntity::VIEW_MODE_VIEW:
                default:
                    $this->content = str_ireplace($image->replace, $image->lightbox, $this->content);
                break;
            }
        }
        return $this;
	}


	protected function getImage($mode = ViewEntity::VIEW_MODE_VIEW, $index = 0)
	{
	    $result = new \stdClass();
	    if (isset($this->images[$index])) {
	        $image = $this->images[$index];
	        switch($mode) {
                case ViewEntity::VIEW_MODE_VIEW:
                default:
                    //$file             = sprintf($image['cached'], 'sidebar');
                    //$file             = sprintf($image['cached'], 'small');
                    $file             = $image['path'].$image['name'];
                    $result->tag      = sprintf('<img src="%s" alt="%s"/>', $file, $image['title']);
                    $result->lightbox = sprintf($image['lightbox'], $result->tag);
                    $result->replace  = $image['replace'];
                break;
            }
	    }
	    return $result;
	}


	protected function getImages($content = '', $replace = false, $mode = ViewEntity::VIEW_MODE_VIEW)
	{
	    $this->images = [];

		if (preg_match_all('#\[\[picture:"([^"]+)" ID:([0-9]+)]]#', $this->item->content, $matches))
		{
		    //echo __FILE__.__LINE__.'<pre>$matches='.htmlentities(print_r($matches,1)).'</pre>';
		    sort($matches[2]);
			if ($items = \DB::table('document_images')
					->whereIn('entry_id', $matches[2])
			        ->orderBy('entry_id', 'ASC')
					->get()) {
					    //echo __FILE__.__LINE__.'<pre>$items='.htmlentities(print_r($items,1)).'</pre>';
					    $images = [];
					    $template = 'small';
					    foreach ($items as $i=>$item) {
					        $path     = '/photos/'.preg_replace("/[^0-9]/","",$item->image_path);
					        $path     = '/'.$item->image_path;
					        $name     = $item->image_name;
					        $title    = sprintf('%s - %s', trim($this->item->title), trim(pathinfo($item->original_image_name, PATHINFO_FILENAME)));
					        $tag      = sprintf('<img src="%s" alt="%s"/>', $path.$name, $title);
					        //http://kfn.laravel.debian.mirror.virtec.org/en/media/normal/photos/9/1331786919.jpg
					        $cached   = sprintf('/%s/%s%s%s', Config::get('imagecache::config.route'), '%s', $path, $name);
					        $replace  = $matches[0][$i];
					        $lightbox = sprintf('
							    <a href="%s" data-lightbox="%s" data-title="%s" title="%s">
							        %s
							    </a>
					            ',
					            $path.$name, $title, $title, $title,
					            '%s');

					        $this->images[] = array('path'=>$path, 'name'=>$name, 'title'=>$title, 'tag'=>$tag, 'lightbox'=>$lightbox, 'replace'=>$replace, 'cached'=>$cached);
					    }

			}
		}

		return $this;
	}

}
