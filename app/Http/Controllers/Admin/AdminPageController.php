<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Models\Translation;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Kythera\Models\PageEntity;

/**
 * @author virgilm
 *
 */
class AdminPageController extends AdminController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $pages = Page::orderBy('created_at', 'desc')
            ->paginate(12);
        return view('admin.page.index')
            ->with('items', $pages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($page = null)
    {
        // Show the page
        return view('admin.page.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        // We need to update the Input object since where using the Validator class
        $parent = json_decode(Input::get('parent'));
        Input::merge([
            'folder_id' => $parent->f,
            'parent_id' => $parent->p
        ]);

        $validator = Validator::make(Input::all(), PageEntity::getRules());
        if ($validator->passes()) {
            $uri = Input::get('uri') ? Input::get('uri') : Input::get('title');
            if ($page = PageEntity::create([
                'folder_id' => Input::get('folder_id'),
                'parent_id' => Input::get('parent_id') ? Input::get('parent_id') : null,
                'title' => Input::get('title'),
                'uri' => Translation::slug($uri),
                'content' => Input::get('content'),
                'sort' => 1000,
                'controller_id' => Input::get('controller') ? Input::get('controller') : null
            ])) {
                return Redirect::route('admin.page.edit', $page->id)
                        ->with('global', "Page '" . $page->title . "' successfully saved.");
            }
        }

        // error
        return Redirect::route('admin.page.create')
                ->withErrors($validator)
                ->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        /*
		 * $pages = Page::all(array('id', 'title'));
		 * $parents = array();
		 * $parents[] = 'Select parent';
		 * foreach ($pages as $page) {
		 * if ($page->id!=$id)
		 * $parents[$page->id] = $page->title;
		 * }
		 */
        if ($page = PageEntity::find($id)) {
            // $page = Translation::model($page, App::getLocale());

            Session::put('admin.page.edit', $id);

            return view('admin.page.edit')
                    ->with('title', "Edit '{$page->title}'")
                    ->with('page', $page);
            // ->with('pages', $parents);
        }

        // error
        return Redirect::route('admin.page.index')
                ->with('global', 'Requested page not found.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update($id)
    {
        // We need to update the Input object since where using the Validator class
        $parent = json_decode(Input::get('parent'));
        Input::merge([
            'folder_id' => $parent->f,
            'parent_id' => $parent->p
        ]);
        Session::set('page_folder', $parent->f);

        $data = Input::all();
        echo __FILE__.__LINE__.'<pre>='.htmlentities(print_r($data, 1)).'</pre>';

        $validator = Validator::make(Input::all(), PageEntity::getRules($id));
        if ($validator->passes()) {
            if ($page = PageEntity::find($id)) {
                $uri = Input::get('uri') ? Input::get('uri') : Input::get('title');
                $page->folder_id = Input::get('folder_id');
                $page->parent_id = Input::get('parent_id') ? Input::get('parent_id') : null;
                $page->title = Input::get('title');
                //Make an exception for the homepage which should ALWAYS be /
                $page->uri = $page->isHomepage() ? '/' : Translation::slug($uri);
                $page->content = (Input::get('content'));
                $page->controller_id = Input::get('controller') ? Input::get('controller') : null;
                
                if ($image = Input::file('image')) {
                    if ($image->getError() === 0) {
                        $file = $image->move(public_path('photos').'/1/', $image->getClientOriginalName());
                        $page->image = $file->getFilename();
                    }
                }
                
                if ($colorbox_image = Input::file('colorbox_image')) {
                    if ($colorbox_image->getError() === 0) {
                        $file = $colorbox_image->move(public_path('photos').'/1/', $colorbox_image->getClientOriginalName());
                        $page->colorboximage = $file->getFilename();
                    }
                }
                $page->colorbox      = Input::get('colorbox');
                $page->colorboxtitle = Input::get('colorbox_title');
                $page->colorboxurl   = parse_url(Input::get('colorbox_url'), PHP_URL_SCHEME) ? Input::get('colorbox_url') : 'http://'.Input::get('colorbox_url');

                if ($page->save()) {
                    //echo __FILE__.__LINE__.'<pre>$page='.htmlentities(print_r($page,1)).'</pre>';die;
                    return Redirect::route('admin.page.edit', $id)
                        ->with('global', "Page '" . $page->title . "' successfully saved.");
                }
            }
        }

        // error
        return redirect(URL::route('admin.page.edit', $id))
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'pages' => $id
                ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        if ($page = PageEntity::find($id)) {
            if ($page->delete()) {
                return Redirect::route('admin.page.index')->with('global', 'Page deleted.');
            }
        }

        // error
        return Redirect::route('admin.page.index')->with('global', 'Requested page not deleted.');
    }

    /**
     *
     * Depricated
     *
     * Update folder/page order from ajax
     *
     * fixme: move to model and call as a AdminCommand
     */
    public function order()
    {
        if ($data = Input::all()) {
            Log::info($data);
            $folder = str_replace('f', '', Input::get('f'));
            $pages = Input::get('p');
            foreach ($pages as $i => $page) {
                $page = str_replace('p', '', $page);
                $query = sprintf('UPDATE pages SET folder_id=%d, sort=%d WHERE id=%d LIMIT 1;', $folder, ($i + 1) * 10, $page);
                Log::info($query);
                if ($page = PageEntity::find($page)) {
                    $page->parent_id = ($folder == $page->folder_id) ? $page->parent_id : null;
                    $page->folder_id = $folder;
                    $page->sort = ($i + 1) * 10;
                    $page->save();
                }
            }
            return Response::json(true);
        } else {
            return Response::json(false);
        }
    }


    /**
     * Depricated instead listening to PageEntity save event
     */
    private function clearCache()
    {
        $key = 'router_'.App::getLocale();
        if (Cache::has($key)) {
            $result = Cache::forget($key);
        }
    }
}
