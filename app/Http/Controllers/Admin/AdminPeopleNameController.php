<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Kythera\Models\PeopleName;

/**
 * @author virgilm
 *
 */
class AdminPeopleNameController extends AdminController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $names = $this->getIndexData();

        return view('admin.people.name.index')
                        ->with('names', $names);




        // find all used letters
        $letters =  [];
        foreach (range('A', 'Z') as $letter) {
            if ($count = PeopleName::where('lastname', 'like', $letter . '%')->count()) {
                $letters [$letter] = $count;
            }
        }

        // get requested letter
        if ($query = Input::get('l')) {
            Session::put('names.query', $query);
        }
        $query = Session::get('names.query', 'A');
        $items = PeopleName::where('lastname', 'like', $query . '%')->orderBy('lastname', 'asc')->orderBy('firstname', 'asc')->paginate(20);

        return view('admin.people.names.index')->with('content', 'List people names')->with('letters', $letters)->with('items', $items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $names = $this->getIndexData();
        $allCompounds = PeopleName::getAllCompounds();

        return view('admin.people.name.edit')
                        ->with('names', $names)
                        ->with('allCompounds', $allCompounds);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make(Input::all(), PeopleName::$rules, PeopleName::$messages);
        if ($validator->passes()) {
            $data = Input::all();
            $data['character_set_id'] = (App::getLocale() == 'en' ? 'latin' : 'greek');

            if ($name = PeopleName::create($data)) {
                if ($data['compound']) {
                    $name->addCompound($data['compound']);
                }
            }

            return Redirect::route('admin.name.edit', $name->id)
                                ->with('global', "Name '" . $name->name . "' successfully saved.");
        }

        //error
        return Redirect::route('admin.name.create')
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
        return 'Display the specified resource.';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $names = $this->getIndexData();

        if ($name = PeopleName::find($id)) {
            $compounds = $name->getCompounds();
            $allCompounds = PeopleName::getAllCompounds();

            return view('admin.people.name.edit')
                            ->with('title', "Edit '{$name->name}'")
                            ->with('name', $name)
                            ->with('compounds', $compounds)
                            ->with('names', $names)
                            ->with('allCompounds', $allCompounds);
        }

        // error
        return Redirect::route('admin.people.name.index')
                            ->with('global', 'Requested name not found.');
    }
/*
	public function edit($id) {
		// show edit form
		if ($item = PeopleName::find ( $id )) {
			return View::make ( 'admin.people.names.edit' )->with ( 'item', $item );
		}

		// error
		return Redirect::route ( 'admin.people.names.index' )->with ( 'global', 'Requested name not found.' );
	}
*/
    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update($id)
    {
        $validator = Validator::make(Input::all(), PeopleName::$rulesUpdate, PeopleName::$messages);
        if ($validator->passes()) {
            $data = Input::all();
            $data['character_set_id'] = (App::getLocale() == 'en' ? 'latin' : 'greek');

            if ($name = PeopleName::find($id)) {
                if ($name->fill($data)->save()) {
                    if ($data['compound']) {
                        $name->addCompound($data['compound']);
                    }
                }
            }

            return Redirect::route('admin.name.edit', $name->id)
                                ->with('global', "Name '" . $name->name . "' successfully saved.");
        }

        //error
        return Redirect::route('admin.name.edit', $id)
                            ->withErrors($validator)
                            ->withInput();
    }


    public function destroy($id)
    {
        if ($name = PeopleName::find($id)) {
            $name->deleteCompounds();
            $name->delete();
            $name = $name->name;
            return Redirect::route('admin.name.index')
                ->with('global', "Name '$name' deleted.");
        }

        //error
        return Redirect::route('admin.name.index')
                            ->with('global', 'Requested name not deleted.');
    }


    public function destroyCompound($id, $compoundId)
    {
        if ($name = PeopleName::find($id)) {
            $name->deleteCompound($compoundId);

            return redirect(URL::route('admin.name.edit', $name->id) . '#tab-compounds')
                                ->with('global', "Name '" . $name->name . "' successfully saved.");
        }

        //error
        return Redirect::route('admin.name.edit', $id)
                            ->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    /*
	public function destroy($id) {
		if ($item = PeopleName::find ( $id )) {
			if ($item->delete ()) {
				return Redirect::route ( 'admin.people.names.index' )->with ( 'global', 'Name deleted.' );
			}
		}

		// error
		return Redirect::route ( 'admin.people.names.index' )->with ( 'global', 'Requested name not deleted.' );
	}
	*/


    protected function getIndexData()
    {
        $names = [];

        #find all used letters
        $letters = PeopleName::getAlphabet(true);

        if ($selected = Input::get('letter')) {
            Session::set('admin.name.selected', $selected);
        }
        $selected = Session::get('admin.name.selected', 'A');

        #get all villages per letter
        foreach ($letters as $letter) {
            $names[$letter] = [];
            if ($letter != $selected) {
                continue;
            }

            if ($items = PeopleName::where('name', 'like', $letter.'%')
                    ->where('character_set_id', App::getLocale() == 'en' ? 'latin' : 'greek')
                    //->where('visible', 1)
                    //->cacheTags(array('letters'))
                    //->remember(10)
                    ->get()) {
                $names[$letter] = $items;
            }
        }

        return $names;
    }
}
