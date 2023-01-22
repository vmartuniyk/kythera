<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentText;
use Kythera\Support\ViewEntity;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Kythera\Router\Facades\Router;

/**
 * @author virgilm
 *
 * @francesdath 2017-06-07-16
 *
 */


class PersonalComentPageController extends PageController
{

    /**
    * Initializer.
    *
    * @access public
    * @return \BaseController
    */

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }


    /**
    * Make view wrapper to make the current request page available in all templates.
    * @param string $view
    * @return View
    */

    protected function view($view = 'site.page.default')
    {
//        dd($this->getCurrentPage());
        switch ($this->getCurrentPage()->controller) {
            case 'PersonalComentPageController':
                $view = 'site.page.personal.'.$view;
                break;
        }

        return view($view)
            ->with('page', $this->getCurrentPage());
    }


    /**
    * Show list of entries
    *
    * links to page: your-personal-page
    */

    public function getIndex()
    {

        return $this->view('com');
    }
    public function getComments()
    {

        return $this->view('com');
    }



    /**
    * Show list of categories
    */

    public function getCategory($id = null)
    {
//        dd('cat');
        if ($id) {
            $items = DocumentEntity::getUserEntries(Auth::user(), [ $id ], true);

            if (count($items)) {
                $item = $items[ 0 ];
                $page = Router::getPageByID($item->page_id);

                return $this->view('index')
                    ->with('list', 'category')
                    ->with('page', $page)
                    ->with('items', $items);
            }
        }
    }


    /**
    * Show list of comments
    */

    public function getComment($id = null)
    {

        if ($id) {

            $items = Comment::getUserComments(Auth::user(), [ $id ], true);

            if (count($items)) {
                $item = $items[ 0 ] ;
                $page = Router::getPageByID($item->page_id);
                $entries = [];

                foreach ($items as $i => $item) {
                    $entries[ $i ] = DocumentEntity::find($item->document_id);
                }
            }


                return $this->view('test')
                    ->with('list', 'comment')
                    ->with('page', $page)
                    ->with('items', $items)
                    ->with('entries', $entries);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */

    //	@francedath 2017-06-15
    //	for page your-personal-page/edit

    public function edit($id = null)
    {

        $id = Auth::user()->id;
        $user = User::find($id);

        if ($user) {
            return view('site.page.personal.edit')
                ->with('user', $user);
        } else {
            return Redirect::back()
                ->with('global', 'Cannot edit personal profile.');
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param int $id
    * @return Response
    */

    /*	@francesdath 2017-06-09-15
		updating user data after editing
		separate loops for updating profile info and updating password
		if only user info is changed, and no password is entered then only user info is updated
		if password is changed, then all is updated
	*/


    public function update($id = null)
    {

        $id = Auth::user()->id;
        $user = User::find($id);

        $fullname = Auth::user()->full_name;

        if (Auth::check()) {
            Validator::extend('hashmatch', function ($attribute, $value, $parameters) {

                return Hash::check($value, Auth::user()->{$parameters[ 0 ]});
            });

            $messages = [
                'hashmatch' => 'Your current password must match your account password.'
            ];

            $rulesUpdate = [
                'username' => 'required|unique:users,username,' . $id,
                'email' => 'required|email|unique:users,email,' . $id,
                'firstname' => 'required',
                'lastname' => 'required'
            ];

            $rulesPassword = [
                'username' => 'required|unique:users,username,' . $id,
                'email' => 'required|email|unique:users,email,' . $id,
                'firstname' => 'required',
                'lastname' => 'required',
                'current_password' => 'required|hashmatch:password',
                'new_password' => 'required|confirmed|different:current_password',
                'new_password_confirmation' => 'required|different:current_password|same:new_password'
            ];

            $current_password = Input::get('current_password');

            if (empty($current_password)) {
                $validator = Validator::make(Input::only('username', 'email', 'firstname', 'lastname'), $rulesUpdate);

                if ($validator->fails()) {
                    return redirect(URL::previous())
                        ->withErrors($validator)
                        ->withInput(Input::except('password'))
                        ->with('error', $fullname . ', your details have not been saved.');
                } else {
                    $user->username = Input::get('username');
                    $user->firstname = Input::get('firstname');
                    $user->lastname = Input::get('lastname');
                    $user->email = Input::get('email');
                    $user->save();

                    return redirect(URL::previous())
                    ->with('message', $user->firstname . ' ' . $user->lastname . ', your details have been saved.');
                }
            } else {
                $validator = Validator::make(Input::all(), $rulesPassword, $messages);

                if ($validator->fails()) {
                    return redirect(URL::previous())
                        ->with('error', 'The following errors occurred:')
                        ->withErrors($validator)->withInput();
                } else if (! Hash::check(Input::get('current_password'), $user->password)) {
                    return redirect(URL::previous())
                        ->withInput()
                        ->withError('error', 'Current password is not correct.');
                } else {
                    $user->username = Input::get('username');
                    $user->firstname = Input::get('firstname');
                    $user->lastname = Input::get('lastname');
                    $user->email = Input::get('email');
                    $user->save();

                    $password = Input::get('new_password');

                    DB::table('users')
                        ->where('id', $id)
                        ->update([ 'password' => Hash::make($password) ]);

                        return redirect(URL::previous())
                            ->with('message', 'Your password & details has been changed. ' . $password);
                }
            }
        }
    }
}
