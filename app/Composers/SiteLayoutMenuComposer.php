<?php

namespace App\Composers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

/**
 * @author virgilm
 *
 */
class SiteLayoutMenuComposer extends Composer
{

    
    protected $menu;
    
    
    protected $folders;
    
    
    public function __construct()
    {
        $this->menu = $this->menu();
        //$this->folders = $this->folders();
    }
    
    
    public function compose($view)
    {
        $view->with('menus', implode('<br class="clear">', $this->menu));
        //$view->with('folders', implode('<br class="clear">', $this->folders));
    }
    
    
    /**
     * Build menu's
     *
     * @return html
     */
    private function menu()
    {
        $result = false;
        foreach (Route::folders() as $folder) {
            $h = sprintf('<h2>%s</h2>', $folder->title);
            $h = '';
            $h.= $this->htmlMenu2(Route::getFolder($folder->id));
            $result[$folder->id] = $h;
        }
        return $result;
    }

    
    private function menu2()
    {
        return $this->htmlMenu2(Route::getPages());
    }
    
    
    /**
     * Build menu
     *
     * @param string $items
     * @param number $level
     * @return string
     */
    private function htmlMenu($items = null, $level = 0)
    {
        $level++;
        $h = '';
        $h.= '<ul class="navigation l'.($level).'">';
        foreach ($items as $item) {
            $h.= sprintf('<li>');
            $h.= sprintf('<a href="/%s%s">%s</a> (%d:%s)', App::getLocale(), $item->path, $item->title, $item->id, $item->title);
            if (isset($item->children)) {
                $h.= $this->htmlMenu($item->children, $level);
            }
            $h.= sprintf('</li>');
        }
        $h.= '</ul>';
        return $h;
    }
    
    
    /**
     * Build bootstrap menu
     *
     * @param string $items
     * @param number $level
     * @return string
     */
    private function htmlMenu2($items = null, $level = 0)
    {
        $level++;
        $h = '';
        
        $level == 1 ? $h.= '<ul class="nav navbar-nav ">'
                    : $h.= '<ul class="dropdown-menu" role="menu">';
        
        foreach ($items as $item) {
            !isset($item->children) ? $h.= sprintf('<li>')
                                    : $h.= sprintf('<li class="dropdown">');
            
            !isset($item->children) ? $h.= sprintf('<a href="/%s%s">%s</a>', App::getLocale(), $item->path, $item->title)
                                    : $h.= sprintf('<a class="dropdown-toggle" data-toggle="dropdown" href="/%s%s">%s</a>', App::getLocale(), $item->path, $item->title);
            
            if (isset($item->children)) {
                $h.= $this->htmlMenu2($item->children, $level);
            }
            $h.= sprintf('</li>');
        }
        
        $h.= '</ul>';
        return $h;
    }
}
