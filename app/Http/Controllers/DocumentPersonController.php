<?php

namespace App\Http\Controllers;

use Event;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentPermission;
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
class DocumentPersonController extends PageController
{

    protected $page;

    /**
     * Initializer.
     *
     * @access public
     * @return \BaseController
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function create()
    {
        $subject = new Person();
        $form = PersonFormFactory::create($subject, 'person');

        return view('site.document.person.create')
                        ->with('form', $form);
    }


    public function add($personsId, $member = 0)
    {
        $mother  = null;
        $father  = null;
        $persons = null;
        $spouse  = null;
        $other   = null;
        $child   = null;

        $subject = new Person($personsId);
        switch ($member) {
            case Person::MEMBER_PARENT:
                $persons = $subject->getPersons();
                $mother  = PersonFormFactory::create($subject, 'mother', null, null, $persons);
                $father  = PersonFormFactory::create($subject, 'father', null, null, $persons);
                break;
            case Person::MEMBER_PARTNER:
            case Person::MEMBER_SPOUSE:
                $persons = $subject->getPersons();
                $spouse  = PersonFormFactory::create($subject, 'spouse', null, null, $persons);
                break;
            case Person::MEMBER_CHILD:
                $persons = $subject->getPersons();
                $child   = PersonFormFactory::create($subject, 'child', null, null, $persons);
                break;
            default:
                throw new Exception('Undefined member: '. $member);
        }

        return view('site.document.person.add')
                        ->with('subject', $subject)
                        ->with('member', $member)
                        ->with('mother', $mother)
                        ->with('father', $father)
                        ->with('spouse', $spouse)
                        ->with('other', $other)
                        ->with('child', $child);
    }


    public function edit($entry_id)
    {
        $entry = DocumentPerson::find($entry_id);
        if (!$entry) {
            return Redirect::route('site.page.your.personal.page');
        }

        #check if entry is editable
        if (!DocumentEntity::isEditable($entry)) {
            return Redirect::route('site.page.your.personal.page');
        }

        $personsId = $entry->getPersonsId();

        $subject  = new Person($personsId);
        $subject->entry_id = $entry->id;
        $subject->life_story = $entry->content->getValue();

        //FIXME: date convertsion are wrong http://kfn.laravel.debian.mirror.virtec.org/en/family-trees/980/
        $subject->year_of_birth = $subject->date_of_birth ? \Carbon\Carbon::parse($subject->date_of_birth)->year : '';
        $subject->year_of_death = $subject->date_of_death ? \Carbon\Carbon::parse($subject->date_of_death)->year : '';
        $subject->avatar = Person::getImage($entry->id);

        $parents  = $subject->getParents(true);
        $spouses  = $subject->getPartners(true);
        $children = $subject->getChildren(true);

        $form     = PersonFormFactory::create($subject, 'person', $subject);

        //$members  = DocumentEntity::getUsers();

        return view('site.document.person.edit')
                        ->with('personsId', $personsId)
                        ->with('subject', $subject)
                        ->with('form', $form)
                        ->with('members', [])
                        ->with('parents', $parents)
                        ->with('spouses', $spouses)
                        ->with('children', $children);
    }


    public function store()
    {
        $member = Input::get('member', 0);
        switch ($member) {
            case Person::MEMBER_PERSON:
                if ($entry = Person::setPerson(array_add(Input::get('person'), 'file', @$_FILES['person']))) {
                    return Redirect::action('DocumentFamilyController@getEntry', $entry->id)
                        ->with('global', "Entry '" . $entry->title . "' successfully saved.");
                }
                break;
            case Person::MEMBER_PARENT:
                /* todo:4/11/2015; how to delete parents?!
    	         *
    	         * */
                //throw new \Exception('4/11/2015; not allowed to delete parent.');
                $motherId = Person::addMother(array_add(Input::get('mother'), 'file', @$_FILES['mother']));
                $fatherId = Person::addFather(array_add(Input::get('father'), 'file', @$_FILES['father']));
                if (!$motherId || !$fatherId) {
                    return Redirect::action('DocumentPersonController@add', [Input::get('mother.personsId'), \Kythera\Models\Person::MEMBER_PARENT])
                    ->withInput();
                }

                $person    = new Person(Input::get('mother.personsId', Input::get('father.personsId')));
                $parentIds = $person->getParents();
                if ($parentsId = Person::addParents(Input::get('mother.personsId'), $motherId, $fatherId)) {
                    $parentId = $motherId ? $motherId : $fatherId;
                    $entry = DocumentPerson::findByPersonsId($parentId);
                    return Redirect::action('DocumentFamilyController@getEntry', $entry->id)
                        ->with('global', "Entry '" . $entry->title . "' successfully saved.");
                }
                break;
            case Person::MEMBER_SPOUSE:
            case Person::MEMBER_PARTNER:
                if (Person::addPartner(array_add(Input::get('spouse'), 'file', @$_FILES['spouse']))) {
                    $entry = DocumentPerson::findByPersonsId(Input::get('spouse.personsId'));
                    return Redirect::action('DocumentFamilyController@getEntry', $entry->id)
                        ->with('global', "Entry '" . $entry->title . "' successfully saved.");
                }
                break;
            case Person::MEMBER_CHILD:
                if (Person::addChild(array_add(Input::get('child'), 'file', @$_FILES['child']))) {
                    $entry = DocumentPerson::findByPersonsId(Input::get('child.personsId'));
                    return Redirect::action('DocumentFamilyController@getEntry', $entry->id)
                        ->with('global', "Entry '" . $entry->title . "' successfully saved.");
                }
                break;
            default:
                throw new Exception('Undefined member: '.$member);
        }
    }


    public function delete($personsId, $member, $relativeId)
    {
        //echo __FILE__.__LINE__.'<pre>$personsId='.htmlentities(print_r($personsId,1)).'</pre>';
        //echo __FILE__.__LINE__.'<pre>$member='.htmlentities(print_r($member,1)).'</pre>';
        //echo __FILE__.__LINE__.'<pre>$relativeId='.htmlentities(print_r($relativeId,1)).'</pre>';die;
        //$member = Input::get('member', 0);
        $message = "Error deleting relative.";
        switch ($member) {
            case Person::MEMBER_PARENT:
                if (Person::deleteRelative($personsId, $member, $relativeId)) {
                    $message = "Parent successfully deleted.";
                }
                break;
            case Person::MEMBER_SPOUSE:
                if (Person::deleteRelative($personsId, $member, $relativeId)) {
                    $message = "Partner successfully deleted.";
                }
                break;
            case Person::MEMBER_PERSON:
                break;
            case Person::MEMBER_CHILD:
                if (Person::deleteRelative($personsId, $member, $relativeId)) {
                    $message = "Child successfully deleted.";
                }
                break;
            default:
                throw new Exception('Undefined member: '.$member);
        }

        $entry = DocumentPerson::findByPersonsId($personsId);
        return Redirect::action('DocumentPersonController@edit', $entry->id)
                            ->with('global', $message);
    }


    /**
     * Send invitation to kfn member for collaboration on a tree
     * @return \Illuminate\Http\RedirectResponse
     */
    public function invite()
    {
        #find user
        if ($user = \User::where('email', Input::get('email'))->first()) {
            if (DocumentPermission::add(Input::get('entryId'), $user->id)) {
                $entry     = DocumentPerson::find(Input::get('entryId'));
                $personsId = $entry->getPersonsId();
                $person    = new Person($personsId);
                $author    = DocumentEntity::withUser()->find(Input::get('entryId'));

                //notify invited
                Event::fire('collaborate.invitation', [$user, $entry, $author, $person]);

                return Redirect::back()
                    ->with('success', sprintf('User %s %s has been sent an invitation!', $user->firstname, $user->lastname));
            }
        }

        return Redirect::back()
            ->with('error', sprintf('Only registered users can be invited to collaborate.'));
    }
}
