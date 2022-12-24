<?php

namespace App\Http\Controllers\Admin;

use Cache;
use App\Models\Comment;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentGuestbook;
use Kythera\Models\PageEntity;
use Kythera\Models\PeopleName;
use Kythera\Router\Facades\Router;
use Kythera\Support\ViewEntity;
use Illuminate\Http\Request;

/**
 * @author virgilm
 *
 */
class AdminEntryController extends AdminController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($type, Request $request)
    {
        //$i= Router::getPageByID(PageEntity::PAGE_ADVANCED_SEARCH)->path;

        switch ($type) {
            case 'guestbook':
				
                $title = 'Guestbook Entries';
                
                $itemsToShow = $request->showItems ?? 20;
                // dd($request->showItems);
                $pages = DocumentGuestbook::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                       ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
                       ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
                       ->where('document_entities.document_type_id', 23) //guest book
                       ->where('document_attributes.l', App::getLocale())
                       ->where('document_attributes.key', 'title')
                       //->orderBy('document_type_id', 'ASC')
                       ->orderBy('created_at', 'DESC')
                       //->remember(60)
                       ->paginate($itemsToShow);
				// dd($pages);
                $items = ViewEntity::build($pages->items());
                break;

            case 'comment':
                $title = 'Comments';

                //get comments
                $pages = Comment::select('comments.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                        ->join('users', 'comments.persons_id', '=', 'users.id')
                        ->join('document_entities', 'comments.document_id', '=', 'document_entities.id')
                        ->orderBy('document_id', 'DESC')
                        ->orderBy('comments.created_at', 'DESC')
                        //->remember(60)
                        ->paginate(20);
                $items = $pages->items();

                //get entires
                foreach ($items as $item) {
                    $entry = DocumentEntity::find($item->document_id);
                    $item->entry = ViewEntity::build($entry);

                //    echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';die;
                }
                break;

            case 'xtree':
                /*
	            $title = 'Family trees';

	            $pages = DocumentEntity::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
    	            ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
    	            ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
    	            ->where('document_entities.document_type_id', '=', 63)
    	            ->where('document_attributes.l', App::getLocale())
    	            ->where('document_attributes.key', 'title')
    	            ->orderBy('created_at', 'DESC')
    	            ->paginate(50);

	            $items = ViewEntity::build($pages->items());
	            */
                break;

            case 'top':
                $title = 'Top articles';

                $pages = DocumentEntity::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                   ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
                   ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
                   ->where('document_entities.document_type_id', '<>', 23) //guest book
                   //->where('document_entities.document_type_id', '<>', 18) //message board
                   ->where('document_entities.document_type_id', '<>', 63) //family tree
                   ->where('document_entities.top_article', 1)
                   ->where('document_attributes.l', App::getLocale())
                   ->where('document_attributes.key', 'title')
                   //->orderBy('document_type_id', 'ASC')
                   ->orderBy('created_at', 'DESC')
                   //->remember(60)
                   ->paginate(20);

                $items = ViewEntity::build($pages->items());
                break;

            case 'document':
            default:
                $title = 'Documents';

                $pages = DocumentEntity::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                   ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
                   ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
                   ->where('document_entities.document_type_id', '<>', 23) //guest book
                   ->where('document_entities.document_type_id', '<>', 63) //family tree
                   ->where('document_attributes.l', App::getLocale())
                   ->where('document_attributes.key', 'title')
                   //->orderBy('document_type_id', 'ASC')
                   ->orderBy('created_at', 'DESC')
                   //->remember(60)
                   ->paginate(20);

                $items = ViewEntity::build($pages->items());
                //$item = $items[0];echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';die;
        }

        return view('admin.page.entry.index')
                      ->with('type', $type)
                      ->with('title', $title)
                      ->with('items', $items)
                      ->with('pages', $pages)
                      ->with('itemsToShow', !empty($itemsToShow) ? $itemsToShow : '');
    }


    public function search($type)
    {
        $query = str_ireplace([' '], '%', trim(Input::get('q')));
        if (empty($query)) {
            return Redirect::action('AdminEntryController@index', ['document']);
        }

        switch ($type) {
            case 'document':
                $title = 'Search documents';

                $pages = Cache::remember('documents-search_'.$query, 60, function() use ($query) {
                    return DocumentEntity::select(
                          'document_entities.*',
                          'document_attributes.key as qkey',
                          'document_attributes.value as qvalue',
                          'users.firstname',
                          'users.middlename',
                          'users.lastname',
                          'users.id as user_id'
                          )
                          ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
                          ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
                          ->where('document_entities.document_type_id', '<>', 23) //guest book
                          ->where('document_entities.document_type_id', '<>', 63) //family tree
                          ->where('document_attributes.l', '=', \App::getLocale())
                          ->whereIn('document_attributes.key', ['title', 'content'])
                          ->where('document_attributes.value', 'LIKE', '%'.$query.'%')
                          ->groupBy('document_entities.id')
                          ->orderBy('document_entities.created_at', 'DESC')
                          ->paginate(100);
                  });

                $items = ViewEntity::build($pages->items());
                //$item = $items[0];echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';die;
                break;
            case 'guestbook':
                $title = 'Search guestbook entries';

                $pages = Cache::remember('guestbook-search_'.$query, 60, function() use ($query) {
                     return DocumentGuestbook::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                            ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
                            ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
                            ->where('document_entities.document_type_id', 23) //guest book
                            ->where('document_attributes.l', App::getLocale())
                            ->whereIn('document_attributes.key', ['title', 'content'])
                            ->where('document_attributes.value', 'LIKE', '%'.$query.'%')
                            ->groupBy('document_entities.id')
                            ->orderBy('document_entities.created_at', 'DESC')
                            ->paginate(100);
                });
                // $pages = DocumentGuestbook::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                //        ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
                //        ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
                //        ->where('document_entities.document_type_id', 23) //guest book
                //        ->where('document_attributes.l', App::getLocale())
                //        ->whereIn('document_attributes.key', ['title', 'content'])
                //        ->where('document_attributes.value', 'LIKE', '%'.$query.'%')
                //        ->groupBy('document_entities.id')
                //        ->orderBy('document_entities.created_at', 'DESC')
                //        ->remember(60)
                //        ->paginate(100);

                $items = ViewEntity::build($pages->items());
                //$item = $items[0];echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';die;
                break;

            case 'comment':
                $title = 'Search comments';

                $pages = Cache::remember('comments-search_'.$query, 60, function() use ($query) {
                    return Comment::select('comments.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                        ->join('users', 'comments.persons_id', '=', 'users.id')
                        ->join('document_entities', 'comments.document_id', '=', 'document_entities.id')
                        ->where('comments.comment', 'LIKE', '%'.$query.'%')
                        ->orderBy('document_id', 'DESC')
                        ->orderBy('comments.created_at', 'DESC')
                        ->paginate(100);
                });
                $items = $pages->items();
                //echo __FILE__.__LINE__.'<pre>$items='.htmlentities(print_r($items,1)).'</pre>';

                //get entires
                foreach ($items as $item) {
                    $entry = DocumentEntity::find($item->document_id);
                    //echo __FILE__.__LINE__.'<pre>$entry='.htmlentities(print_r($entry,1)).'</pre>';die;
                    //$r=get_class($entry);echo __FILE__.__LINE__.'<pre>$r='.htmlentities(print_r($r,1)).'</pre>';
                    $item->entry = ViewEntity::build($entry);
                }
                break;
        }

        return view('admin.page.entry.index')
            ->with('q', Input::get('q'))
            ->with('type', $type)
            ->with('title', $title)
            ->with('items', $items)
            ->with('pages', $pages)
			->with('itemsToShow', '20');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.people.names.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update($id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function multiDelete($ids)
    {
        $delete = DocumentGuestbook::whereIn('id',explode(",",$ids))->delete();
        
        if($delete){
            return response()->json(['status'=>true,'message'=>"Guestbook deleted successfully."]);
        }
        else{
            return response()->json(['status'=>false,'message'=>"Something went wrong."]);   
        }
        
    }


    /**
     * Enable actions
     *
     * @param Entity $id
     * @param string $action
     *
     * promote|degrade Update admin permissions
     */
    public function action($id, $action)
    {
    }
}
