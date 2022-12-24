<?php

namespace App\Composers;

use Illuminate\Support\Facades\Route;

/**
 * @author virgilm
 *
 */
class SiteInfoIndexComposer extends Composer
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
        if (Route::getSelected()) {
            return $this->htmlCrumbs(Route::getSelected());
        }
    }
    
    private function htmlCrumbs($item)
    {
        $result = false;
        foreach ($item->crumbs as $crumb) {
            $result[] = sprintf('<a href="/en%s">%s</a>', $crumb['uri'], $crumb['title']);
        }
        $result = implode(' > ', $result);
        return $result;
    }
}
