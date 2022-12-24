<?php

namespace App\Composers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

/**
 * @author virgilm
 *
 */
class SiteIndexDynamicComposer extends Composer
{
    
    protected $menu;
    protected $crumbs;
    
    public function __construct()
    {
        parent::__construct();
        $this->crumbs = $this->crumbs();
    }
    
    public function compose($view)
    {
        parent::compose($view);
        $view->with('crumbs', $this->crumbs);
    }
    
    private function crumbs()
    {
        return $this->htmlCrumbs(Route::getSelected());
    }
    
    private function htmlCrumbs($item)
    {
        $result = false;
        foreach ($item->crumbs as $crumb) {
            $result[] = sprintf('<a href="/%s%s">%s</a>', App::getLocale(), $crumb['uri'], $crumb['title']);
        }
        $result = implode(' > ', $result);
        return $result;
    }
}
