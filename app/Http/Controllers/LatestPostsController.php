<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentImage;
use Kythera\Models\DocumentText;
use Kythera\Support\ViewEntity;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author virgilm
 *
 */
//INSERT INTO `laravel_kfn_live`.`document_types` (`id`, `string_id`, `table_name`, `controller`, `label`, `group_label`) VALUES ('0', 'KFN_LATEST_POSTS', NULL, 'LatestPostsController', NULL, NULL);
class LatestPostsController extends PageController
{

    /**
     * Show list of entries
     */
    public function getIndex($type = null)
    {

        $all = Input::all();
        $limit = 10;
        $page  = Input::get('page', 1);
        if (isset($all['image'])) {
            $type = 'image';
        }elseif(isset($all['comment'])){
            $type = 'comment';
        }else{
            $type = 'text';
        }
        $limit = 10;
        $page  = Input::get('page', 1);

        switch ($type) {
            case 'comment':
                $items = Comment::select('comments.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                    ->join('users', 'comments.persons_id', '=', 'users.id')
                    ->join('document_entities', 'comments.document_id', '=', 'document_entities.id')
                    ->where('comments.enabled', 1)
                    ->orderBy('comments.created_at', 'DESC')
                    ->orderBy('document_id', 'DESC')
                    //->remember(60)
                    ->limit(11)
                    ->get();
                $count = count($items);

                $pages = Comment::select('comments.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                    ->join('users', 'comments.persons_id', '=', 'users.id')
                    ->join('document_entities', 'comments.document_id', '=', 'document_entities.id')
                    ->where('comments.enabled', 1)
                    ->orderBy('comments.created_at', 'DESC')
                    ->orderBy('document_id', 'DESC')
                    ->skip($limit * ($page - 1))
                    ->take($limit)
                    //->remember(60)
                    ->get();
                //$pages = Paginator::make($pages->all(), $count, $limit);

                $pages = new \Illuminate\Pagination\LengthAwarePaginator(
                    $pages->all(),
                    $count,
                    $limit,
                    \Illuminate\Pagination\Paginator::resolveCurrentPage(), //resolve the path
                    ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
                );

                //get entires
                $items = $pages->items();
                foreach ($items as $item) {
                    if ($entry = DocumentEntity::find($item->document_id)) {
                        $item->entry = ViewEntity::build($entry);
                    }
                }
                break;
            case 'image':
                $items = DocumentImage::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                    ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
                    ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
                    ->leftJoin('document_types', 'document_entities.document_type_id', '=', 'document_types.id')
                    ->where('enabled', 1)
                    ->where('document_entities.document_type_id', '<>', 23) //guest book
                    ->where('document_entities.document_type_id', '<>', 63) //family tree
                    ->where('document_attributes.l', App::getLocale())
                    ->where('document_attributes.key', 'title')
                    ->where('document_types.controller', 'DocumentImageController')
                    ->orderBy('document_entities.created_at', 'DESC')
                    ->limit(50)
                    //->remember(60)
                    ->get();
                $count = count($items);

                $pages = DocumentImage::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                    ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
                    ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
                    ->leftJoin('document_types', 'document_entities.document_type_id', '=', 'document_types.id')
                    ->where('enabled', 1)
                    ->where('document_entities.document_type_id', '<>', 23) //guest book
                    ->where('document_entities.document_type_id', '<>', 63) //family tree
                    ->where('document_attributes.l', App::getLocale())
                    ->where('document_attributes.key', 'title')
                    ->where('document_types.controller', 'DocumentImageController')
                    ->orderBy('document_entities.created_at', 'DESC')
                    ->skip($limit * ($page - 1))
                    ->take($limit)
                    //->remember(60)
                    ->get();
                //$pages = Paginator::make($pages->all(), $count, $limit);

                $pages = new \Illuminate\Pagination\LengthAwarePaginator(
                    $pages->all(),
                    $count,
                    $limit,
                    \Illuminate\Pagination\Paginator::resolveCurrentPage(), //resolve the path
                    ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
                );

                $items = ViewEntity::build($pages->items(), ViewEntity::VIEW_MODE_LIST);
                //$item = $items[0];echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';die;

                break;
            case 'text':
            default:
                //$items = DocumentEntity::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                $items = DocumentImage::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                    ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
                    ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
                    ->leftJoin('document_types', 'document_entities.document_type_id', '=', 'document_types.id')
                    ->where('enabled', 1)
                    ->where('document_entities.document_type_id', '<>', 23) //guest book
                    ->where('document_entities.document_type_id', '<>', 63) //family tree
                    ->where('document_attributes.l', App::getLocale())
                    ->where('document_attributes.key', 'title')
                    ->where('document_types.controller', '<>', 'DocumentImageController')
                    ->orderBy('document_entities.created_at', 'DESC')
                    ->limit(50)
                    //->remember(60)
                    ->get();
                $count = count($items);

              // $pages = DocumentEntity::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
               $pages = DocumentImage::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                    ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
                    ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
                    ->leftJoin('document_types', 'document_entities.document_type_id', '=', 'document_types.id')
                    ->where('enabled', 1)
                    ->where('document_entities.document_type_id', '<>', 23) //guest book
                    ->where('document_entities.document_type_id', '<>', 63) //family tree
                    ->where('document_attributes.l', App::getLocale())
                    ->where('document_attributes.key', 'title')
                   // ->where('document_types.controller', '<>', 'DocumentImageController')
                    ->orderBy('document_entities.created_at', 'DESC')
                    ->skip($limit * ($page - 1))
                    ->take($limit)
                    //->remember(60)
                    ->get();

                //$pages = Paginator::make($pages->all(), $count, $limit);

                $pages = new \Illuminate\Pagination\LengthAwarePaginator(
                    $pages->all(),
                    $count,
                    $limit,
                    \Illuminate\Pagination\Paginator::resolveCurrentPage(), //resolve the path
                    ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
                );

                $items = ViewEntity::build($pages->items());
            //echo __FILE__ . __LINE__ . '<pre>$items=' . htmlentities(print_r($items, 1)) . '</pre>';die;
            //$item = $items[0];echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';die;
        }

        
        return $this->view('index')
                ->with('type', $type)
                ->with('items', $items)
                ->with('pages', $pages);
    }
}
