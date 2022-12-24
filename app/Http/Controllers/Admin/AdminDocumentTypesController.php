<?php

namespace App\Http\Controllers\Admin;

use App\Models\DocumentType;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AdminDocumentTypesController extends AdminController
{
    
    /**
     * Display a listing of documenttypes
     *
     * @return Response
     */
    public function index()
    {
        $items = DocumentType::paginate(50);
        
        return view('admin.model.index', compact('items'));
    }
    
    /**
     * Show the form for creating a new documenttype
     *
     * @return Response
     */
    public function create()
    {
        return view('documenttypes.create');
    }
    
    /**
     * Store a newly created documenttype in storage.
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make($data = Input::all(), Documenttype::$rules);
        
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        
        Documenttype::create($data);
        
        return Redirect::route('documenttypes.index');
    }
    
    /**
     * Display the specified documenttype.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $documenttype = Documenttype::findOrFail($id);
        
        return view('documenttypes.show', compact('documenttype'));
    }
    
    /**
     * Show the form for editing the specified documenttype.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $documenttype = Documenttype::find($id);
        
        return view('documenttypes.edit', compact('documenttype'));
    }
    
    /**
     * Update the specified documenttype in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update($id)
    {
        $documenttype = Documenttype::findOrFail($id);
        
        $validator = Validator::make($data = Input::all(), Documenttype::$rules);
        
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        
        $documenttype->update($data);
        
        return Redirect::route('documenttypes.index');
    }
    
    /**
     * Remove the specified documenttype from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        Documenttype::destroy($id);
        
        return Redirect::route('documenttypes.index');
    }
}
