<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * @author virgilm
 *
 */


class Comment extends \Kythera\Models\BaseModel
{

    protected $table = "comments";


    protected $fillable = ['persons_id', 'document_id', 'l', 'comment'];


    public static $rules = [
        'comment' => 'required|min:3'
    ];


    /**
     * Register to model's events
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->blacklist();
        });
    }

    public function scopeUser($query)
    {
        return $query
                ->leftJoin('users', 'comments.persons_id', '=', 'users.id');
    }


    public static function whereEntityCount($entity, $enabled = 1)
    {
        return static::whereRaw(sprintf(
            "
                            %s AND
                            document_id = %d
                            ",
            $enabled ? "enabled = 1 " : " 1=1 ",
            $entity->id
        ))
            ->count();
    }


    public static function whereEntity($entity, $parent = null, $enabled = 1)
    {
        $items = static::select('comments.*', 'users.firstname', 'users.middlename', 'users.lastname')
            ->leftJoin('users', 'comments.persons_id', '=', 'users.id')
            ->whereRaw(sprintf(
                "
                            %s AND
                            %s AND
                            document_id = %d
                            ",
                $enabled ? "enabled = 1 " : " 1=1 ",
                $parent ? "parent_id = {$parent->id} " : "parent_id IS NULL ",
                $entity->id
            ))
            ->orderBy('parent_id')
            ->orderBy('created_at')
            ->get();

        foreach ($items as &$item) {
            $item->children = static::whereEntity($entity, $item, $enabled);
        }
        return $items;
    }


    public static function getUserComments(User $user, $document_type_id = [])
    {
        $group = count($document_type_id)==0;
        $query = static::query();
        if ($group) {
            $query
            ->select(
                'comments.*',
                'document_entities.document_type_id',
                'users.firstname',
                'users.middlename',
                'users.lastname',
                'pages.id as page_id',
                'pages_attributes.value as cat',
                \DB::raw('count(*) as n')
            );
        } else {
            $query
            ->select(
                'comments.*',
                'document_entities.document_type_id',
                'users.firstname',
                'users.middlename',
                'users.lastname',
                'pages.id as page_id',
                'pages_attributes.value as cat'
            );
        }
        $query
            ->leftJoin('users', 'comments.persons_id', '=', 'users.id')
            ->join('document_entities', 'comments.document_id', '=', 'document_entities.id')
            ->leftJoin('document_types', 'document_entities.document_type_id', '=', 'document_types.id')
            ->leftJoin('pages', 'document_entities.document_type_id', '=', 'pages.controller_id')
            ->leftJoin('pages_attributes', 'pages.id', '=', 'pages_attributes.document_entity_id');

        $query
            ->where('comments.persons_id', $user->id);
        $query
            ->where('pages_attributes.l', \App::getLocale());
        $query
            ->where('pages_attributes.key', 'title');

        if ($group) {
            $query
            ->groupBy('document_type_id')
            ->orderByRaw('cat');
        } else {
            $query
            ->whereIn('document_entities.document_type_id', $document_type_id)
            ->orderByRaw('comments.created_at DESC');
        }

        return $query->get();
    }


    public function blacklist()
    {
        $blacklist = [];
        if ($words = \DB::table('blacklist')->select('word')->get()) {
            foreach ($words as $word) {
                $blacklist[] = $word->word;
            }
        }

        switch (get_class($this)) {
            default:
                $this->comment = str_ireplace($blacklist, '', $this->comment);
        }
    }
}
