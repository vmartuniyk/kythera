<?php

namespace App\Models;

use Illuminate\Support\Facades\App;
use Kythera\Models\TranslateModel;

/**
 * @author virgilm
 *
 */
class Document extends TranslateModel
{


    protected $table = "documents";


    protected $fillable = ['persons_id', 'created', 'uri', 'title', 'content'];


    public $translatable = [
            'uri' => Translation::TRANSLATE_STRING,
            'title' => Translation::TRANSLATE_STRING,
            'content' => Translation::TRANSLATE_TEXT
            ];

    public static $rules = [
        'title'       =>'required|min:3|max:255|unique:pages',
        'content'     =>'required'
    ];


    /**
     * Find and translate record by id
     *
     * @param int $id
     * @return TranslateModel
     */
    public static function get($id)
    {
        //fixme: add documents.id + index on documents.id + documents.uri
        if ($model = static::select('documents.*', 'users.id as uid', 'users.firstname', 'users.middlename', 'users.lastname')
            ->leftJoin('users', 'documents.persons_id', '=', 'users.id')
            ->leftJoin('translations', 'documents.id', '=', 'translations.id')
            ->where('documents.id', '=', $id)
            ->where('documents.enabled', '=', 1)
            ->first()) {
            return Translation::model($model, App::getLocale());
        }
        return false;
    }

    /**
     * Find record and transnlate by uri on both original (en) and translated value (gr)
     *
     * @param string $uri
     * @return TranslateModel
     */
    public static function entry($uri)
    {
        //fixme: add documents.id + index on documents.id + documents.uri
        if ($model = static::select('documents.*', 'users.id as uid', 'users.firstname', 'users.middlename', 'users.lastname')
            ->leftJoin('users', 'documents.persons_id', '=', 'users.id')
            ->leftJoin('translations', 'documents.id', '=', 'translations.id')
            ->where('documents.enabled', '=', 1)
            ->where('t', '=', 'documents')
            ->where('l', '=', 'gr')
            ->where('k', '=', 'uri')
            ->where('v', '=', $uri)
            ->orWhere('documents.uri', '=', $uri)
            ->first()) {
            return Translation::model($model, App::getLocale());
        }
        return false;
    }
}
