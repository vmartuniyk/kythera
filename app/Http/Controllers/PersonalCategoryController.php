<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Kythera\Models\DocumentEntity;
use Kythera\Router\Facades\Router;


class PersonalCategoryController extends PageController

{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }
    public function index(){

        $categories = DocumentEntity::getUserEntries(Auth::user(), [], false);
        $c = count($categories);
        $n = 0;
//        dd($categories);
        foreach ($categories as $i => $category) {
            if ($page = Router::getPageByID($category->page_id)) {
                $category->category_uri = $page->path;
            }
            $n += $category->n;
        }

//        $comments = Comment::getUserComments(Auth::user());
//        $c = count($comments); $n = 0;
        $categoriesArraId =[];
        foreach ($categories as $category) {
            //if no page is found please check if category already is imported
            if ($page = Router::getPageByID($category->page_id)) {
//                dd($category);
                $category->category_uri = $page->path;
                array_push($categoriesArraId,$category->document_type_id);
            }

        }
//        dd($categoriesArraId);
//        $entries = [];
//        $items = [];
//        foreach ($categoriesArraId as $category){
//
//            $itemsAll = Comment::getUserComments(Auth::user(), [ $category ], true);
//            foreach ($itemsAll as $item){
//                array_push($items,$item );
//
//            }
//            if (count($itemsAll)) {
//                $item = $itemsAll[ 0 ] ;
//                $page = Router::getPageByID($item->page_id);
//
//
//                foreach ($itemsAll as $i => $item) {
//
//                    array_push($entries,DocumentEntity::find($item->document_id) );
//
//                }
//
//            }
//        }
       $items = [];

        foreach ($categoriesArraId as $category){
            $itemsAll = DocumentEntity::getUserEntries(Auth::user(), [ $category ], true);
            foreach ($itemsAll as $item){
                array_push($items,$item );
            }
        }


        return view('site.page.personal.categories')
                ->with('list', 'category')
                ->with('page', $page)
                ->with('items', $items)
                ->with('categories', $categories)
                ->with('cat_stat', sprintf('%d posts in %d categories', $n, $c));
//                ->with('entries', $entries);
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
