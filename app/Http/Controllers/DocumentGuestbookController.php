<?php

namespace App\Http\Controllers;

use Auth;
use Input;
use Log;
use Mail;
use Redirect;
use Session;
use Validator;
use JsValidator;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentGuestbook;
use Kythera\Models\DocumentText;
use Kythera\Support\ViewEntity;
use Kythera\Router\Facades\Router;

/**
 * @author virgilm
 *
 */
class DocumentGuestbookController extends PageController
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

    /**
     * Show list of entries
     */
    public function getIndex()
    {
        $rules = DocumentGuestbook::$rules;

        if (!Auth::check()) {
            $rules['g-recaptcha-response'] =  'required|captcha';
        }

        $validator = JsValidator::make(
            $rules,
            DocumentGuestbook::$messages,
            [],
            '#guestbook'
        );

        return view('site.document.guestbook.index')->with([
            'validator' => $validator,
            'page' => $this->getCurrentPage()
        ]);
    }


    /**
     * Show entry by uri
     */
    public function getEntry($entry = null)
    {
        if ($item = DocumentText::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                    ->withUser()
                    ->whereUri($this->getCurrentPage(), $entry)->first()) {
            $item = ViewEntity::build($item);

            return $this->view('view')
                        ->with('item', $item);
        } else {
            throw new NotFoundHttpException('Requested entry not found: '.$entry);
        }
    }


    /**
     * Show entry by id by redirecting as 301 to getEntry
     */
    public function getId($id)
    {
        if ($item = DocumentEntity::find($id)) {
            return redirect(
                route(Router::getControllerUrl('entry'), (string)$item->uri),
                301
            );
        } else {
            throw new NotFoundHttpException();
        }
    }


    public function missingMethod($parameters = [])
    {
        Log::info('DocumentMessageController->missingMethod: '.print_r($parameters, 1));
    }


    public function create(Request $request)
    {
        $rules = DocumentGuestbook::$rules;
        if (!Auth::user()) {
            $rules['g-recaptcha-response'] =  'required|captcha';
        }

        $validator = Validator::make($request->all(), $rules, DocumentGuestbook::$messages);

        if ($validator->passes()) {
            $title = sprintf('%s %s - %s', ucfirst(Input::get('firstname')), ucfirst(Input::get('surname')), ucfirst(Input::get('city')));
            $user  = Auth::check() ? Auth::user()->id : -1;
            if ($entity = DocumentGuestbook::create([
                'document_type_id' => 23,
                'enabled' => 0,
                'persons_id' => $user,
                'title' => $title,
                'content' => Input::get('message_content'),
                'related_village_id' => Input::get('village_id'),
                'uri' => \App\Models\Translation::slug($title),
            ])) {
                if ($user == -1) {
                    \DB::table('users_guestbook')->insert([
                        'documents_id' => $entity->id,
                        'firstname' => ucfirst(Input::get('firstname')),
                        'surname' =>  ucfirst(Input::get('surname')),
                        'city' =>  ucfirst(Input::get('city')),
                        'email' => strtolower(Input::get('email'))
                        ]);
                }
                return redirect(action('DocumentGuestbookController@getIndex'))
                    ->with('global', "Guestbook entry saved.");
            }
        }

        //error
        //return Redirect::route('site.page.guestbook', ['#entry'])
        return redirect(action('DocumentGuestbookController@getIndex') . '#entry')
            ->with('global', "Form is incomplete.")
            ->withErrors($validator)
            ->withInput();
    }


    public function contact($id)
    {
        if ($id && Auth::user()) {
            Session::set('guestbook.entry', $entry = DocumentGuestbook::find($id));
            Session::set('guestbook.contact', $user = DocumentGuestbook::find($id)->getUser2());

            return view('site.document.guestbook.contact')
                    //->with('page', $this->getCurrentPage())
                    ->with('entry', $entry)
                    ->with('user', $user);
        }

        //error
        return redirect(action('DocumentGuestbookController@getIndex'))
            ->with('global', "Author could not be contacted.");
    }


    public function send()
    {
        $entry = Session::pull('guestbook.entry');
        if ($user = Session::pull('guestbook.contact')) {
            $validator = Validator::make(Input::get('entry'), DocumentGuestbook::$rulesContact);
            if ($validator->passes()) {
                $data = [];
                $data['content'] = Input::get('entry.content');
                $data['host']    = 'http://'.$_SERVER['HTTP_HOST'];
                $data['email']   = Input::get('entry.e') ? Input::get('entry.e') : Auth::user()->email;
                $data['name']    = xhtml::fullname(Auth::user(), false);

                $to_email    = $user->xemail;
                $to_name     = $user->xfirstname.' '.$user->xlastname;
                $subject     = Input::get('entry.s');

                //FIXME: convert to event and queue mail
                Mail::queue('emails.guestbook.contact', $data, function ($message) use ($to_email, $to_name, $subject) {
                    $message
                        ->to(config('app.developer'))
                        ->subject($subject);
                });

                if (config('mail.notification.guestbook.contact', false)) {
                    Mail::queue('emails.guestbook.contact', $data, function ($message) use ($to_email, $to_name, $subject) {
                        $message
                            ->to($to_email, $to_name)
                            ->subject($subject);
                    });

                    Log::info('guestbook.contact', [$user->email, $to_email]);
                }

                return redirect(action('DocumentGuestbookController@getIndex'))
                    ->with('global', "Author is contacted.");
            }
        }

        //error
        return Redirect::route('guestbook.contact', $entry->id)
            ->withErrors($validator)
            ->withInput()
            ->with('global', "Form is incomplete.");
    }


    public function edit($id)
    {
        // show edit form
        if (($entry = DocumentGuestbook::find($id)) && ($editable = Auth::user()->isEditable($entry->persons_id))) {
            return view('site.document.guestbook.edit')
                    //->with('page', $this->getCurrentPage())
                    ->with('entry', $entry);
        }

        //error
        return redirect(action('DocumentGuestbookController@getIndex'));
    }


    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), DocumentGuestbook::$rules);
        if ($validator->passes()) {
            if (($entry = DocumentGuestbook::find($id)) && ($editable = Auth::user()->isEditable($entry->persons_id))) {
                $entry->document_type_id = 23;
                $entry->title   = sprintf('%s %s - %s', Input::get('firstname'), Input::get('surname'), Input::get('city'));
                $entry->content = Input::get('message_content');
                $entry->related_village_id = Input::get('village_id');
                $entry->save();
                if ($entry->persons_id==-1) {
                    \DB::table('users_guestbook')
                    ->where('documents_id', $entry->id)
                    ->update([
                        'firstname'=>Input::get('firstname'),
                        'surname'=>Input::get('surname'),
                        'email'=>Input::get('email'),
                        'city'=>Input::get('city')
                    ]);
                }
                return redirect(action('DocumentGuestbookController@getIndex'));
            }
        }

        //error
        return Redirect::route('guestbook.edit', [$id])
                ->withErrors($validator)
                ->withInput()
                ->with('global', "Form is incomplete.");
    }

    public function delete($id)
    {
        if (($entry = DocumentGuestbook::find($id)) && (Auth::user()->isAdmin())) {
            $entry->document_type_id = 23;
            $entry->delete();
            if ($entry->persons_id == -1) {
                \DB::table('users_guestbook')
                ->where('documents_id', $entry->id)
                ->delete();
            }
            return Redirect::back()->with('global', "The Guestbook entry $id has been deleted.");
        }

        //error
        return Redirect::back()->with('global', "An error has occured while trying to delete the Guestbook entry $id.");
    }
}
