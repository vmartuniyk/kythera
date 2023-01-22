<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Kythera\Models\DocumentEntity;
use Kythera\Router\Facades\Router;


class PersonalCommentController extends PageController

{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }
    public function index(){

        $comments = Comment::getUserComments(Auth::user());
        $c = count($comments); $n = 0;
        $categoriesArraId =[];
        foreach ($comments as $comment) {
            //if no page is found please check if category already is imported
            if ($page = Router::getPageByID($comment->page_id)) {
                $comment->category = $page;
                array_push($categoriesArraId,$comment->document_type_id);
//                dd($comment->document_type_id);
            }
            $n += $comment->n;
        }
//        dd($categoriesArraId);
        $entries = [];
        $items = [];
        foreach ($categoriesArraId as $category){
//            $item = Comment::getUserComments(Auth::user(), [ $category ], true);
            $itemsAll = Comment::getUserComments(Auth::user(), [ $category ], true);
            foreach ($itemsAll as $item){
                array_push($items,$item );

            }
//            dd($entries);
            if (count($itemsAll)) {
                $item = $itemsAll[ 0 ] ;
                $page = Router::getPageByID($item->page_id);


                foreach ($itemsAll as $i => $item) {
//                    $entries[ $i ] = DocumentEntity::find($item->document_id);
                    array_push($entries,DocumentEntity::find($item->document_id) );
//                    dd($entries);
                }
//                dd($entries);
            }
        }
//        dd($items);

//        dd($entries);
            return view('site.page.personal.comments')
                ->with('list', 'comment')
                ->with('page', $page)
                ->with('items', $items)
                ->with('comments', $comments)
                ->with('com_stat', sprintf('%d comments in %d categories', $n, $c))
                ->with('entries', $entries);
    }

    public function getComment($id = null)
    {
//        dd('we here');
        if ($id) {
            $comments = Comment::getUserComments(Auth::user());
            $c = count($comments); $n = 0;
            $items = Comment::getUserComments(Auth::user(), [ $id ], true);

            if (count($items)) {
                $item = $items[ 0 ] ;
                $page = Router::getPageByID($item->page_id);
                $entries = [];

                foreach ($items as $i => $item) {
                    $entries[ $i ] = DocumentEntity::find($item->document_id);
                }
            }


            return $this->view('site.page.personal.test')
                ->with('list', 'comment')
                ->with('com_stat', sprintf('%d comments in %d categories', $n, $c))
                ->with('items', $items)
                ->with('entries', $entries);
        }
    }

}
