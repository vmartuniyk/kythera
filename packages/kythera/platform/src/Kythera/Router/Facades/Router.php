<?php namespace Kythera\Router\Facades;
 
use Illuminate\Support\Facades\Facade;
 
class Router extends Facade {
 
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() { return 'router'; }
 
}