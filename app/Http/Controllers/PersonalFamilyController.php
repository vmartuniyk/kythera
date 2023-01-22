<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Kythera\Models\Person;



class PersonalFamilyController extends PageController

{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }
    public function index(){

        $persons = Person::getByUser(Auth::user());

        return view('site.page.personal.families')
                ->with('persons', $persons)
                ->with('person_stat', sprintf('%d family trees', count($persons)));
    }

}
