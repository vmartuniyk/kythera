<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Kythera\Models\PageEntity;
use Kythera\Models\Village;

/**
 * @author virgilm
 *
 */
class AdminVillageController extends AdminController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $villages = $this->getIndexData();

        return view('admin.village.index')
                        ->with('villages', $villages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $villages = $this->getIndexData();
        $allCompounds = Village::getAllCompounds();

        return view('admin.village.edit')
                        ->with('villages', $villages)
                        ->with('allCompounds', $allCompounds);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make(Input::all(), Village::$rules, Village::$messages);
        if ($validator->passes()) {
            $data = Input::all();
            $data['character_set_id'] = (App::getLocale() == 'en' ? 'latin' : 'greek');

            if ($village = Village::create($data)) {
                if ($data['compound']) {
                    $village->addCompound($data['compound']);
                }
            }

            return Redirect::route('admin.village.edit', $village->id)
                                ->with('global', "Village '" . $village->village_name . "' successfully saved.");
        }

        //error
        return Redirect::route('admin.village.create')
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
        $villages = $this->getIndexData();

        if ($village = Village::find($id)) {
            $compounds = $village->getCompounds();
            $allCompounds = Village::getAllCompounds();

            return view('admin.village.edit')
                            ->with('title', "Edit '{$village->village_name}'")
                            ->with('village', $village)
                            ->with('compounds', $compounds)
                            ->with('villages', $villages)
                            ->with('allCompounds', $allCompounds);
        }

        // error
        return Redirect::route('admin.village.index')
                ->with('global', 'Requested village not found.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update($id)
    {
        $validator = Validator::make(Input::all(), Village::$rulesUpdate, Village::$messages);
        if ($validator->passes()) {
            $data = Input::all();
            $data['character_set_id'] = (App::getLocale() == 'en' ? 'latin' : 'greek');

            if ($village = Village::find($id)) {
                if ($village->fill($data)->save()) {
                    if ($data['compound']) {
                        $village->addCompound($data['compound']);
                    }
                }
            }

            return Redirect::route('admin.village.edit', $village->id)
                                ->with('global', "Village '" . $village->village_name . "' successfully saved.");
        }

        //error
        return Redirect::route('admin.village.edit', $id)
                            ->withErrors($validator)
                            ->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        if ($village = Village::find($id)) {
            $village->deleteCompounds();
            $village->delete();
            $name = $village->village_name;
            return Redirect::route('admin.village.index')
                                ->with('global', "Village '$name' deleted.");
        }

        //error
        return Redirect::route('admin.village.index')
                            ->with('global', 'Requested village not deleted.');
    }


    public function destroyCompound($id, $compoundId)
    {
        if ($village = Village::find($id)) {
            $village->deleteCompound($compoundId);

            return redirect(URL::route('admin.village.edit', $village->id) . '#tab-compounds')
                                ->with('global', "Village '" . $village->village_name . "' successfully saved.");
        }

        //error
        return Redirect::route('admin.village.edit', $id)
                            ->withInput();
    }


    public function createCompound($villageId)
    {
        echo __FILE__.__LINE__.'<pre>$villageId='.htmlentities(print_r($villageId, 1)).'</pre>';
        die;
    }


    protected function getIndexData()
    {
        $villages = [];

        #find all used letters
        $letters = Village::getAlphabet();

        if ($selected = Input::get('letter')) {
            Session::set('admin.village.selected', $selected);
        }
        $selected = Session::get('admin.village.selected', 'A');

        #get all villages per letter
        foreach ($letters as $letter) {
            $villages[$letter] = [];
            if ($letter != $selected) {
                continue;
            }

            if ($items = Village::where('village_name', 'like', $letter.'%')
                                ->where('character_set_id', App::getLocale() == 'en' ? 'latin' : 'greek')
                                //->where('visible', 1)
                                //->cacheTags(array('letters'))
                                //->remember(10)
                                ->get()) {
                $villages[$letter] = $items;
            }
        }

        return $villages;
    }
}
