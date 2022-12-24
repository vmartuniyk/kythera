<?php
namespace Kythera\Models\Behaviours;

/**
 * @author virgilm
 *
 * Interface for models with translation possibilities.
 */
interface ITranslatable {
    
    
    /**
     * Override to provide hook for automatic translations if/when needed
     *
     * @param string $key
     * @return string
     */
    public function getAttributeValue($key);
    
    
    /**
     * Fetch translated value first from database and subsequent requests from local cache
     *
     * @param string $key
     * @param string $default
     * @return string
     *
     */
    public function getTranslateValue($key, $default);
    
    
	/**
	 * Set a given attribute on the model. Overidden to add translations
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
    public function setAttribute($key, $value);
    
    
    /**
     * Set translations
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function setTranslate($key, $value);
	
	
    /**
     * Make translatable attributes optional (to save bandwidth on large TEXT fields)
     * @param number $type
     * @return array
     */
    public function getTranslatable();
    
}