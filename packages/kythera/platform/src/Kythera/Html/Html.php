<?php namespace Kythera\Html;

use Collective\Html\HtmlBuilder;
use Carbon\Carbon;
use Kythera\Router\Facades\Router;
use Illuminate\Support\Facades\Input;
/**
 * @author virgilm
 *
 */


/**
 * Derived from Illuminate\Html\HtmlBuilder for customizacion
 *
 */
class Html extends HtmlBuilder {


	/**
	 * Generate a link to a JavaScript file.
	 *
	 * @param  string  $url
	 * @param  array   $attributes
	 * @param  bool    $secure
	 * @return string
	 */
	public function script($url, $attributes = array(), $secure = null)
	{
		$attributes['src'] = $this->url->asset($url, $secure);
        //$attributes['type'] = 'text/javascript';

		return '<script'.$this->attributes($attributes).'></script>';
	}


	/**
	 * Generate a link to a CSS file.
	 *
	 * @param  string  $url
	 * @param  array   $attributes
	 * @param  bool    $secure
	 * @return string
	 */
	public function style($url, $attributes = array(), $secure = null)
	{
		$defaults = array('media' => 'all', 'type' => 'text/css', 'rel' => 'stylesheet');

		$attributes = $attributes + $defaults;

		$attributes['href'] = $this->url->asset($url, $secure);

		return '<link'.$this->attributes($attributes).'>';
	}


    public function fullname($user, $link = false) {
        if ($user->middlename) {
            $result = sprintf('%s %s %s', ($user->firstname), ($user->middlename), e($user->lastname));
        } else {
            $result = sprintf('%s %s', ($user->firstname), ($user->lastname));
        }
        $result = strip_tags($result, '<i>');
        if ($link) {
            $result = sprintf('<a href="#" title="%s">%s</a>', $result, $result);
        }
        return $result;
    }


    public function date($date)
    {
        if ($date instanceof Carbon) {
            //return date('d.m.Y', strtotime($date->date));
            return $date->format('d.m.Y');
        } else {
            return date('d.m.Y', $date);
        }
    }


    public function crumbs($page, $seperator = ' &gt; ', $all = false, $item = null, $filters = array())
    {
    	$crumbs = $page->crumbs;

    	if ($item && isset($item->title)) {
    		$crumbs[] = array('title' => $item->title);
    		$all = false;
    	}

    	$filters = ($filter = implode('&', $filters)) ? '?'.$filter : '';

        $h = array();
        foreach ($crumbs as $crumb) {
            if ($crumb != end($crumbs) || $all)
                $h[] = sprintf('<a href="http://%s%s%s" title="%s">%s</a>', $_SERVER['HTTP_HOST'], $crumb['uri'], $filters, $crumb['title'], $crumb['title']);
            else
                $h[] = sprintf('<span>%s</span>', $crumb['title']);
        }
        return implode($seperator, $h);
    }


    public function crumbsKeys($page, $seperator = ' &gt; ')
    {
    	$crumbs = $page->crumbs;

        $h = array();
        foreach ($crumbs as $crumb) {
            $h[] = sprintf('<span>%s</span>', $crumb['title']);
        }
        return implode($seperator, $h);
    }

}
