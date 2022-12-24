<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Input;
use Redirect;
use Session;
use URL;
use Validator;
use App\Models\User;
//use Kythera\Models\User;
use App\DataTables\Admin\UsersDataTable;
use App\Http\Requests;

/**
 * @author virgilm
 *
 * @francesdath 2017-06-07-10
 *
 *
 */

class AdminUserController extends AdminController
{

    // required for hasRole & used in function index() below

    public function isAdmin()
    {
        return Auth::user()->hasRole('administrator');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'assigned_roles');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index(UsersDataTable $dataTable)
    {
        // Debug
        // return \DataTables::eloquent(User::withTrashed()->select())->make(true);

        return $dataTable->with(['type' => Input::get('type', 'active')])->render('admin.user.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

    public function create()
    {
        return view('admin.users.edit');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */

    public function store()
    {

        //	@francesdath 2017-06-06
        //	not sure this is being used
        // validate
        //	validator works with model in workbench, doesn't work?
        //	removed cos it breaks things?:
        // User::$rules, User::$messages );

        $validator = Validator::make(Input::all(), User::$rules);

        if ($validator->passes()) {
            if ($name = User::create(Input::all())) {
                // change this to reload same edit page with changes
                return Redirect::route('admin.users.index')->with('global', 'Your name has been saved.');
            }
        }

        // error
        return Redirect::route('admin.users.create')->withErrors($validator)->withInput();
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */

    public function show($id)
    {
        return 'Display the specified resource.';
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */

    //	@francesdath 2017-06-07
    //	display edit user form

    public function edit($id)
    {

        if ($item = User::find($id)) {
            return view('admin.user.edit')
                ->with('item', $item);
        }

        // error
        return Redirect::route('admin.user.index')
            ->with('global', 'Requested user not found.');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */

    //	@francesdath 2017-06-09-10
    //	updating user data after editing

    public function update($id)
    {

        $rulesUpdate = [
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'firstname' => 'required',
            'lastname' => 'required'
        ];

        // note using \Kythera\Models\User requires this
        // $validator = Validator::make( Input::all(), User::$rulesUpdate );
        // but getting id in model of \Kythera\Models\User being edited causes 'can't find auth'

        $validator = Validator::make(Input::all(), $rulesUpdate);

        // validate the edited data. if it fails, go back to user edit page with error messages

        if ($validator->fails()) {
            return Redirect::route('admin.user.edit', $id)
                ->withErrors($validator)
                ->withInput(Input::except('password'))
                ->with('global', 'User "' . $item->firstname . ' ' . $item->lastname . '" details have not been saved.');
        } else {
            // data validates, then save it and back to user edit page with success message
            $item = User::find($id);
            $item->username = Input::get('username');
            $item->firstname = Input::get('firstname');
            $item->lastname = Input::get('lastname');
            $item->email = Input::get('email');
            $item->save();

            return Redirect::route('admin.user.edit', $id)
                ->with('global', 'User "' . $item->firstname . ' ' . $item->lastname . '" details have been saved.');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */

    public function destroy($id)
    {
        //
    }


    /**
     * Enable actions
     *
     * @param Entity $id
     * @param string $action
     *
     * promote|degrade Update admin permissions, confirm new user
     */

    public function action($id, $action)
    {
        switch (strtolower($action)) {
            //	disabling user.
            case 'disable':
                if ($user = User::find($id)) {
                    if ($user->delete()) {
                        return redirect(URL::previous())
                            ->with('global', 'User "' . $user->firstname . ' ' . $user->lastname . '" has been disabled.');
                    } else {
                        // error
                        return redirect(URL::previous())
                            ->with('global', 'Something went wrong. Requested user "' . $user->firstname . ' ' . $user->lastname . '" was not disabled.');
                    }
                }
                break;

            //	re-enabling user
            case 'enable':
                if ($user = User::withTrashed()->find($id)) {
                    if ($user->restore()) {
                        return redirect(URL::previous())
                            ->with('global', 'User "' . $user->firstname . ' ' . $user->lastname . '" has been enabled.');
                    } else {
                        // error
                        return redirect(URL::previous())
                            ->with('global', 'Something went wrong. Requested user "' . $user->firstname . ' ' . $user->lastname . '" was not enabled.');
                    }
                }
                break;

            //	turn normal user into administrator with hasRole( 'adminstrator' )
            case 'promote':
                if ($user = User::find($id)) {
                    if (! $user->hasRole('administrator')) {
                        $user->attachRole('administrator');
                        return redirect(URL::previous())
                            ->with('global', 'User "' . $user->firstname . ' ' . $user->lastname . '" has been promoted to administrator.');
                    } else {
                        // error
                        return redirect(URL::previous())
                            ->with('global', 'Something went wrong. Requested user "' . $user->firstname . ' ' . $user->lastname . '" was not promoted to administrator.');
                    }
                }
                break;

            //	turn admin user back into normal user
            case 'degrade':
                if ($user = User::find($id)) {
                    if ($user->hasRole('administrator')) {
                        $user->detachRole('administrator');
                        return redirect(URL::previous())
                            ->with('global', 'User "' . $user->firstname . ' ' . $user->lastname . '" is no longer administrator.');
                    } else {
                        // error
                        return redirect(URL::previous())
                            ->with('global', 'Something went wrong. Requested user "' . $user->firstname . ' ' . $user->lastname . '" was not demoted from administrator.');
                    }
                }
                break;

            //	confirm new user
            case 'confirm':
                if ($user = User::find($id)) {
                    if (! $user->confirmed) {
                        $user->confirmed = 1;
                        $user->save();
                        return redirect(URL::previous())
                            ->with('global', 'User "' . $user->firstname . ' ' . $user->lastname . '" has been confirmed.');
                    } else {
                        // error
                        return redirect(URL::previous())
                            ->with('global', 'Something went wrong. User "' . $user->firstname . ' ' . $user->lastname . '" was not confirmed.');
                    }
                }
                break;

            //	Unconfirm new user
            case 'unconfirm':
                if ($user = User::find($id)) {
                    if ($user->confirmed) {
                        $user->confirmed = 0;
                        $user->save();
                        return redirect(URL::previous())
                            ->with('global', 'User "' . $user->firstname . ' ' . $user->lastname . '" has been Unconfirmed.');
                    } else {
                        // error
                        return redirect(URL::previous())
                            ->with('global', 'Something went wrong. User "' . $user->firstname . ' ' . $user->lastname . '" was already Unconfirmed.');
                    }
                }
                break;

            //	delete user permanently.
            case 'delete':
                if ($user = \App\Models\User::withTrashed()->find($id)) {
                    if ($user->forceDelete()) {
                        return redirect(URL::previous())
                            ->with('global', 'User "' . $user->firstname . ' ' . $user->lastname . '" has been permanently deleted.');
                    } else {
                        // error
                        return redirect(URL::previous())
                            ->with('global', 'Something went wrong. Requested user "' . $user->firstname . ' ' . $user->lastname . '" could not be deleted.');
                    }
                }
                break;
                //	delete user permanently.
            case 'activate-admin-email':
                if ($user = User::find($id)) {
                    if (! $user->enable_email) {
                        $user->enable_email = 1;
                        $user->save();
                        return redirect(URL::previous())
                            ->with('global', 'User "' . $user->firstname . ' ' . $user->lastname . '" has enabled admin emails.');
                    } else {
                        // error
                        return redirect(URL::previous())
                            ->with('global', 'Something went wrong. User "' . $user->firstname . ' ' . $user->lastname . '" was not confirmed.');
                    }
                }
                break;
                case 'deactivate-admin-email':
                    if ($user = User::find($id)) {
                        if ($user->enable_email) {
                            $user->enable_email = 0;
                            $user->save();
                            return redirect(URL::previous())
                                ->with('global', 'User "' . $user->firstname . ' ' . $user->lastname . '" has enabled admin emails.');
                        } else {
                            // error
                            return redirect(URL::previous())
                                ->with('global', 'Something went wrong. User "' . $user->firstname . ' ' . $user->lastname . '" was not confirmed.');
                        }
                    }
                    break;

            default:
        }
    }
}
