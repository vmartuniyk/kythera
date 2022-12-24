<?php

namespace App\Models;

use Kythera\Models\TranslateModel;
use Kythera\Models\TranslateModelException;

/**
 * @author virgilm
 *
 */
class Folder extends TranslateModel
{

    
    public $timestamps = false;
    
    
    protected $table = "folders";

    
    protected $fillable = ['title'];
    
    
    public $translatable = [
            'title' => Translation::TRANSLATE_STRING
            ];
    
    
    public static $rules = [
        'title' =>'required|min:3|max:255',
    ];
    
    
    /**
     *  Disable deletion.
     */
    public function delete()
    {
        throw new TranslateModelException('Error: class '.get_class($this). ' is read only.');
    }
}
