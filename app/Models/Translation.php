<?php

namespace App\Models;

use App\Classes\Helpers;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Kythera\Models\BaseModel;
use Kythera\Models\TranslateModel;

/**
 * @author virgilm
 *
 */

/**
 * Define custom exception class
 */
class TranslationException extends \Exception
{
}


/**
 * Handle translations stored in database.
 *
 * As opose to Illuminate\Translation\Translator which is file based.
 *
 * fixme:
 * Can we derive laravels class to support database storage?
 * Or do we just introduce a CustomTranslator class?
 *
 */
class Translation extends BaseModel
{

    
    /**
     * @const translation types
     */
    const TRANSLATE_STRING = 1;
    const TRANSLATE_TEXT   = 2;

    
    public $timestamps = false;
    
    
    protected $cache_expire = 60;

    
    protected $table = "translations";

    
    protected $fillable = ['id','t','l','k','v','m'];
    
    
    public static $rules = [
        'id' =>'required',
        't' =>'required',
        'l' =>'required',
        'k' =>'required'
    ];

    
    public static function slug($title, $separator = '-')
    {
        // Convert all dashes/underscores into separator
        $flip = $separator == '-' ? '_' : '-';

        $title = preg_replace('!['.preg_quote($flip).']+!u', $separator, $title);

        // Remove all characters that are not the separator, letters, numbers, or whitespace.
        $title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($title));

        // Replace all separator characters and whitespace by a single separator
        $title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);

        return trim($title, $separator);
    }
    
    
    public function scopeCompositeKey($query, $id, $table, $language, $key)
    {
        //Translation::compositeKey();
        return $query->where('id', '=', $id)
                     ->where('t', '=', $table)
                     ->where('l', '=', $language)
                     ->where('k', '=', $key);
    }
    
    
    /**
     * Verify if translation is needed by comparing current language with default language
     *
     * @return boolean
     */
    public static function isDefault()
    {
        return App::getLocale() == config('app.default_locale');
    }
    
    
    /**
     * Translate a single value by key
     *
     * @param unknown $id
     * @param unknown $table
     * @param unknown $language
     * @param unknown $key
     * @param string $default
     * @return string
     */
    public static function string($id, $table, $language, $key, $default = '')
    {
        if (!static::isDefault() &&
           ($result = static::query()
                    ->select('v')
                    ->where('id', '=', $id)
                    ->where('t', '=', $table)
                    ->where('l', '=', $language)
                    ->where('k', '=', $key)
                    ->first())) {
            //Log::info(array('STRING', $id, $table, $language, $key, $default, $result));
            return $result->v;
        } else {
            return $default;
        }
    }

    
    /**
     * Translate a text value by key
     *
     * @param unknown $id
     * @param unknown $table
     * @param unknown $language
     * @param unknown $key
     * @param string $default
     * @return string
     */
    public static function text($id, $table, $language, $key, $default = '')
    {
        if (!static::isDefault() &&
           ($result = static::query()
                    ->select('m')
                    ->where('id', '=', $id)
                    ->where('t', '=', $table)
                    ->where('l', '=', $language)
                    ->where('k', '=', $key)
                    ->first())) {
            //Log::info(array('TEXT', $id, $table, $language, $key, $default));
            return $result->m;
        } else {
            return $default;
        }
    }
   
    
    /**
     * Translate a model.
     * We access the model attributes directly avoiding the attribute mutators.
     *
     * @param TranslateModel $model
     * @param string $language
     * @return TranslateModel
     *
     * fixme: cache these values
     */
    public static function model(TranslateModel $model, $language, $type = 0)
    {
        //echo __FILE__.__LINE__.'<pre>$model='.htmlentities(print_r($model,1)).'</pre>';die;
        
        if (!$model->translated) {
            //only translate when request language is not the default language
            if (!static::isDefault()) {
                //get translations
                $translations = static::query()
                                    ->select('k', 'v', 'm')
                                    ->where('id', '=', $model->id)
                                    ->where('t', '=', $model->getTable())
                                    ->where('l', '=', $language)
                                    ->whereIn('k', $model->getTranslatable($type))
                                    ->get();
                $q = Helpers::lastQuery();
                
                
                $translations = static::toTransArray($translations);
                //Log::info(array('MODEL', $translations));
                
                //fixme: find default array function to do this
                foreach ($model->getAttributes() as $key => $value) {
                    if (array_key_exists($key, $model->translatable)) {
                        if (array_key_exists($key, $translations)) {
                            //set translation
                            $model->translations[$key] = $translations[$key];
                        } else {
                            if ($type==0) {
                            //translation not found in database so set a default value..
                                $model->translations[$key] = 'default';
                            //like from default language?
                                $model->translations[$key] = $model->getAttributeFromArray($key);
                            }
                        }
                    }
                }
                
                //Mark model as translated
                $model->translated = ($type == 0);
            }
        }
        
        return $model;
    }

    
    public static function store($id, $table, $language, $key, $value, $type)
    {
        switch ($type) {
            case Translation::TRANSLATE_TEXT:
                static::storeText($id, $table, $language, $key, $value);
                break;
            case Translation::TRANSLATE_STRING:
                static::storeString($id, $table, $language, $key, $value);
            default:
                throw new TranslationException('Invalid translate type: ' . $type);
        }
    }

    
    /**
     * Save translation type string
     *
     * @param int $id
     * @param string $table
     * @param string $language
     * @param string $key
     * @param string $value
     */
    public static function storeString($id, $table, $language, $key, $value)
    {
        return DB::statement($query = sprintf(
            "
            REPLACE INTO translations
            SET
                id=%d,
                t='%s',
                l='%s',
                k='%s',
                v=%s
            ",
            $id,
            $table,
            $language,
            $key,
            DB::getPdo()->quote($value)
        ));
    }
    
    
    /**
     * Save translation type text
     *
     * @param int $id
     * @param string $table
     * @param string $language
     * @param string $key
     * @param string $value
     */
    public static function storeText($id, $table, $language, $key, $value)
    {
        return DB::statement($query = sprintf(
            "
            REPLACE INTO translations
            SET
                id=%d,
                t='%s',
                l='%s',
                k='%s',
                m=%s
            ",
            $id,
            $table,
            $language,
            $key,
            DB::getPdo()->quote($value)
        ));
    }
    
    
    /**
     * Delete translations
     *
     * @param int $id
     * @param string $table
     */
    public static function remove($id, $table)
    {
        static::where('id', '=', $id)
              ->where('t', '=', $table)
              ->delete();
    }
    
    
    
    /**
     * Replace or insert translation
     *
     * @param integer $id
     * @param string $table
     * @param string $language
     * @param string $key
     * @param string $value
     * @return integer
     */
    /*
	public static function replaceInto($id, $table, $language, $key, $value)
	{
        return DB::statement($query = sprintf("
            REPLACE INTO translations
            SET
                id=%d,
                t='%s',
                l='%s',
                k='%s',
                v=%s
            ",
            $id,
            $table,
            $language,
            $key,
            DB::getPdo()->quote($value)));
	}
	*/
    
    
    /**
     * Transform query results to convenient format
     *
     * @param Collection $items
     * @return multitype:NULL
     */
    public static function toTransArray(Collection $items)
    {
        $result = [];
        foreach ($items as $item) {
            $result[$item->k] = isset($item->m) ? $item->m
                                                : $item->v;
        }
        return $result;
    }
}
