<?php
/**
 * @author virgilm
 *
 */
namespace Kythera\Models;


use App, Log;
use App\Models\Translation;
use Kythera\Models\Behaviours\ITranslatable;
use Illuminate\Database\Eloquent\Builder;


/*
 * Define custom exception class
 */
class TranslateModelException extends \Exception {}


/*
 * todo: delete translations on delete model, keep softdeletes in mind!
 * fixme: Implement as trait?
 */
abstract class TranslateModel extends BaseModel implements ITranslatable {

    /**
     * Translatable model attributes
     *
     * @var array
     */
    public $translatable = array();


    /**
     * Flag model as translated to prevent double translation
     *
     * @var boolean
     */
    public $translated = false;


    /**
     * Cache translated values
     *
     * @var array
     */
    public $translations = array();


    /**
     * Cache expiry time in minutes
     * @var int
     */
    protected $cache_expire = 60;


	public function __construct(array $attributes = array())
	{
	    parent::__construct($attributes);

	    //register deleted event when SoftDelete trait is implemented
	    if ($this->hasSoftDeleting()) {

    	    static::deleted(function($model){

    	        if ($model->forceDeleting)
    	        {
        	        //delete translations
        	        Translation::remove($model->id, $model->table);
    	        }

    	    });

	    }

	}


    /**
     * Make translatable attributes optional (to save bandwidth on large TEXT fields)
     * @param number $type
     * @return array
     */
    public function getTranslatable($type = 0) {
        $result = array();
        foreach ($this->translatable as $key => $_type) {
            if (!$type || ($_type == $type)) {
                $result[] = $key;
            }
        }
        return $result;
    }


	/**
	 * Get a plain attribute (not a relationship).
	 *
	 * @param  string  $key
	 * @return string
	 */
    public function getAttributeValue($key)
    {
        //Get default value and honor mutators
        $value = parent::getAttributeValue($key);

        //Get translation
        return $this->getTranslateValue($key, $value);
    }


    /**
     * Fetch translated value first from database and subsequent requests from local cache
     *
     * @param string $key
     * @param string $default
     * @return string
     *
     */
    public function getTranslateValue($key, $default)
    {
        //Check if is translatable
        if (array_key_exists($key, $this->translatable))
        {
            //Check if we need translation
            if (!Translation::isDefault())
            {
                //Check if we already have it, if not get translation and cache it (to save extra database requests)
                if (!array_key_exists($key, $this->translations) && !$this->translated)
                {

                    //By requesting the $this->id (and possibly $this->table) we go through the getAttributeValue once again because of the magic __get function.
                    //By using getAttributeFromArray we circumvent this.
                    $id = $this->getAttributeFromArray('id');

                    //Determine translation type
                    switch($this->translatable[$key])
                    {
                        case Translation::TRANSLATE_TEXT:
                            $this->translations[$key] = Translation::text($id, $this->table, App::getLocale(), $key, $default);
                        break;
                        //Set this as default so we don't have to add the types in the translatable array if we only have string values :)
                        case Translation::TRANSLATE_STRING:
                        default:
                            $this->translations[$key] = Translation::string($id, $this->table, App::getLocale(), $key, $default);
                    }
                    //Log::info(array('FETCHED', $key));
                }
                //else Log::info(array('CACHED', $key));

                return $this->translations[$key];
            }
        }

        return $default;

    }


	/**
	 * Set a given attribute on the model. Overidden to add translations
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
    public function setAttribute($key, $value)
    {
        //Set common attributes between languages
        if (!array_key_exists($key, $this->translatable))
        {
            parent::setAttribute($key, $value);
        }

        //Set default language attributes
        if (Translation::isDefault())
        {
            parent::setAttribute($key, $value);
        }

        //Set the translated attributes
        if (!Translation::isDefault())
        {
            $this->setTranslate($key, $value);
        }

        //Set defaults for default language when creating in foreign language
        if (!$this->getAttributeFromArray('id') &&
            !Translation::isDefault() &&
            array_key_exists($key, $this->translatable))
        {
            parent::setAttribute($key, $value);
        }
    }


    /**
     * Set translations
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function setTranslate($key, $value)
    {
        if (array_key_exists($key, $this->translatable))
        {
            $this->translations[$key] = $value;
        }
    }


	/**
	 * Perform a model update operation. Overidden to add translations
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @return bool|null
	 */
    protected function performUpdate(Builder $query, array $options = [])
	{
	    if ($result = parent::performUpdate($query, $options))
	    {
	        $this->storeTranslations($query, $options);
	    }
	    return $result;
	}


	/**
	 * Perform a model insert operation. Overidden to add translations
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @return bool
	 */
	protected function performInsert(Builder $query, array $options = [])
	{
        if ($result = parent::performInsert($query, $options))
        {
            $this->storeTranslations($query, $options);
        }
        return $result;
	}


	/**
	 * Save translations in database
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param array $options
	 * @return boolean|true
	 */
	protected function storeTranslations($query, $options)
	{
	    if (!Translation::isDefault())
	    {
    	    foreach ($this->translatable as $key => $type) {
    	        if (array_key_exists($key, $this->translations))
    	        {
    	            $id = $this->getAttributeFromArray('id');

    	            //Translation::store($id, $this->table, App::getLocale(), $key, $this->translations[$key], $type);
    	            switch($type) {
    	                case Translation::TRANSLATE_TEXT:
    	                    Translation::storeText($id, $this->table, App::getLocale(), $key, $this->translations[$key]);
	                    break;
	                    case Translation::TRANSLATE_STRING:
	                    default:
	                        Translation::storeString($id, $this->table, App::getLocale(), $key, $this->translations[$key]);
    	            }
    	        }
    	    }
	    }
	    return true;
	}


	public function isTranslated() {
	    foreach (array_keys($this->translatable) as $key) {
	        $r = array_key_exists($key, $this->translations);
	        echo __FILE__.__LINE__.'<pre>$r='.htmlentities(print_r($r,1)).'</pre>';
	    }

	    return false;
	}

	/**
	 * Test if SoftDelete trait is applied.
	 *
	 *  @return bool
	 */
	public function hasSoftDeleting()
	{
	    return isset($this->forceDeleting);
	}


	/* (non-PHPdoc)
	 * @see \Illuminate\Database\Eloquent\Model::performDeleteOnModel()
	 *
	 * This function is only overridden when the softdelete trait is NOT applied!
	 * (If applied the translations are deleted through the delete event.)
	 */
	protected function performDeleteOnModel()
	{
	    //delete
	    parent::performDeleteOnModel();

        //delete translations
        Translation::remove($this->id, $this->table);
	}


	/**
	 * Translate model wrapper
	 *
	 * @return TranslateModel
	 */
	public function translate()
	{
        return Translation::model($this, App::getLocale());
	}

}
