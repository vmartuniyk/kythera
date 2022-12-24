<?php

namespace App\Http\Controllers;

use Event;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Kythera\Html\Facades\Message;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentMessage;
use Kythera\Support\ViewEntity;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Kythera\Router\Facades\Router;

/**
 * @author virgilm
 *
 */
class DocumentMessageController extends PageController
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
        //
    }


    /**
     * Show list of entries
     */
    public function getIndex()
    {
        
        //set toolbar data
        Session::put('selected.catId', $this->getCurrentPage()->controller_id);
        Session::forget('selected.item');
        
        
        return $this->view('index');
    }


    /**
     * Show entry by uri
     */
    public function getEntry($entry = null)
    {
        if ($item = DocumentMessage::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                    ->withUser()
                    ->whereUri($this->getCurrentPage(), $entry)->first()) {
            $item = ViewEntity::build($item);
            
            //save for edit toolbar
            if (Auth::check()) {
                Session::put('selected.item', $item);
            } else {
                Session::forget('selected.item');
            }
            
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


    /**
     * Create a form for submitting new item for the message board
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        return view('site.document.message.create');
    }


    public function store()
    {
        //required login
        if (Auth::guest()) {
            //save data
            Session::set('message', Input::get('entry'));

            //return login page
            return Redirect::guest(action('UsersController@getAccess'));
        }

        $data = Input::get('entry') ? Input::get('entry') : Session::pull('message');
        $validator = Validator::make($data, DocumentMessage::$rules_create, DocumentMessage::$messages);
        if ($validator->passes()) {
            if ($entity = DocumentMessage::add($data, null)) {
                //notify admin
                Event::fire('entity.created', $entity);

                //return to main list
                return Redirect::route('site.page.message.board');
            }
        }

        return Redirect::back()
            ->with('global', 'Error saving post.')
            ->withErrors($validator)
            ->withInput(['entry' => $data]);
    }


    public function update($id)
    {
        $data = Input::get('entry') ? Input::get('entry') : Session::pull('message');
        $entity = DocumentMessage::find($id);

        if (!$entity->isParent()) {
            return $this->updateReply($entity);
        }

        $validator = Validator::make($data, DocumentMessage::$rules_update, DocumentMessage::$messages);
        if ($validator->passes()) {
            if ($entity &&($editable = Auth::user()->isEditable($entity->persons_id))) {
                if ($entity->set($data, null)) {
                    //notify admin
                    Event::fire('entity.updated', $entity);

                    return redirect(Router::getItemUrl($entity));
                }
            }
        }

        return Redirect::back()
            ->with('global', 'Error saving post.')
            ->withErrors($validator)
            ->withInput(['entry' => $data]);
    }


    public function updateReply($entity)
    {
        $data = Input::get('entry') ? Input::get('entry') : Session::pull('message');
        $validator = Validator::make($data, DocumentMessage::$rules_reply_update, DocumentMessage::$messages);
        if ($validator->passes()) {
            $entity->content = $data['content'];
            if ($entity->save()) {
                //notify admin
                Event::fire('entity.updated', $entity);

                return redirect(Router::getItemUrl($entity->getParent(true)));
            }
        }

        return Redirect::back()
            ->with('global', 'Error saving post.')
            ->withErrors($validator)
            ->withInput(['entry' => $data]);
    }

    /**
     * Save reply but must be logged in first.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reply()
    {
        if ($id = Session::get('entity.view.id')) {
            //required login
            if (Auth::guest()) {
                //save
                Session::set('message.'.$id, Input::all());

                #return login page
                return Redirect::guest(action('UsersController@getAccess'));
            }

            $input = Input::all() ? Input::all() : Session::pull('message.'.$id);
            $validator = Validator::make($input, DocumentMessage::$rules_reply);

            if ($validator->passes()) {
                if ($entity = DocumentMessage::find($id)) {
                    if ($message = DocumentMessage::create([
                        'document_type_id' => $entity->document_type_id,
                        'persons_id' => Auth::user()->id,
                        'enabled' => 1,
                        'l' => App::getLocale(),
                        'content' => $input['content'],
                        'title' => 'Reply - '. $entity->title,
                        'uri' => \App\Models\Translation::slug('Reply '. $entity->title),
                    ])) {
                        //update relations
                        DB::insert(sprintf('INSERT INTO messageboard (documents_id, parent_id) VALUES (%d, %d)', $message->id, $entity->id));

                        //notify admin
                        Event::fire('entity.created', $message);

                        //notify author
                        Event::fire('message.created', [$entity, $message]);

                        return redirect(Router::getItemUrl($entity));
                            //->with('global', "Reply successfully saved.");
                    }
                }
            }
        }

        //error
        return Redirect::back()
            ->with('global', "Reply not saved.");
    }
}
