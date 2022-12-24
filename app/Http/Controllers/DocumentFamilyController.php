<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentPerson;
use Kythera\Models\FamilyPerson;
use Kythera\Models\FamilyTree;
use Kythera\Models\Person;
use Kythera\Support\PersonFormFactory;
use Kythera\Support\ViewEntity;
use Symfony\Component\Finder\Exception\ExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author virgilm
 *
 */
class DocumentFamilyController extends PageController
{

    //protected $page;

    /**
     * Initializer.
     *
     * @access public
     * @return \BaseController
     */
    public function __construct()
    {
        parent::__construct();

        if (Input::has('selLet')) {
            Session::put('family.selLet', Input::get('selLet'));
        }
        if (Input::has('selName')) {
            Session::put('family.selName', Input::get('selName'));
        }
        //$names = FamilyTree::getFamilyNames(Input::get('selLet', 0), Input::get('selName', null));
    }


    /**
     * Show list of entries
     */
    public function getIndex()
    {
        #find all used letters
        $alphabet = FamilyTree::getAlphabet();
        $names    = FamilyTree::getFamilyNames(Input::get('selLet', 0), Input::get('selName', null));
        $trees    = DB::table('family')
                        ->select('entry_id', DB::raw('count(*) as n'))
                        ->join('parents', 'family.parents_id', '=', 'parents.id')
                        ->join('individuum', 'parents.partner1', '=', 'individuum.persons_id')
                        ->groupBy('parents_id')->get();
        //echo __FILE__.__LINE__.'<pre>$trees='.htmlentities(print_r($trees,1)).'</pre>';die;
        if (!config('app.debug')) {
            $trees = [];
        }

        return $this->view('index')
                ->with('alphabet', $alphabet)
                ->with('trees', $trees)
                ->with('names', $names);
    }


    /**
     *
     * @param id $entry_id
     * @throws NotFoundHttpException
     */
    public function getEntry($entry_id)
    {
        if ($entry = DocumentPerson::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
            ->withUser()
            ->where('document_entities.id', $entry_id)->first()) {
            $entry   = ViewEntity::build($entry);
            $subject = Person::findByEntryId($entry_id);
            $info    = Auth::check() ? '' : Person::AjaxGetPersonInfo($subject->personsId);
            $tree    = Person::getTree($entry->id, $subject->personsId);
            //echo __FILE__.__LINE__.'<pre>$tree='.htmlentities(print_r($tree,1)).'</pre>';die;
            $json    = json_encode($tree);

            Session::put('family.entry_id', $entry_id);

            return $this->view('view')
                    ->with('entry', $entry)
                    ->with('subject', $subject)
                    ->with('info', $info)
                    ->with('json', $json);
        } else {
            throw new NotFoundHttpException('Requested entry not found: '.$entry_id);
        }
    }
}
