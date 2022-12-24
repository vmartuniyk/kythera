<?php

namespace App\Http\Controllers;

use Event;
use App\Models\Comment;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Kythera\Entity\Entity;
use Kythera\Models\DocumentAudio;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentImage;
use Kythera\Models\DocumentText;
use Kythera\Models\DocumentUploader;
use Mockery\CountValidator\Exception;
use Kythera\Router\Facades\Router;

/**
 *
 * @author virgilm
 *
 */
class DocumentCommentController extends Controller
{

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
     * Fixme: #7055
     * After login/logout/login session var entity.view.id still exists when we do a GET on /en/comment/create.
     * Input will be empty and the fallback redirect fails because we don't have a http referrer.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {

        if ($id = Session::get('entity.view.id')) {
            if (Auth::guest()) {
                //save
                Session::set('comment.'.$id, Input::all());

                #return login page
                return Redirect::guest(action('UsersController@getLogin'));
            }

            $input = Input::all() ? Input::all() : Session::pull('comment.'.$id);
            if ($input) {
                $validator = Validator::make($input, Comment::$rules);
                if ($validator->passes()) {
                    if ($entity = DocumentEntity::find($id)) {
                        if ($comment = Comment::create([
                                'persons_id' => Auth::user()->id,
                                'document_id' => $entity->id,
                                'l' => App::getLocale(),
                                'comment' => $input['comment'],
                                'enabled' => 1
                        ])) {
                            //notify author
                            Event::fire('comment.created', $comment);

                            return redirect(Router::getItemUrl($entity))
                                ->with('global', "Comment successfully saved.");
                        }
                    }
                }
            }
        }

        //error
        $entity   = DocumentEntity::find($id);
        $redirect = URL::previous() ? URL::previous() : Router::getItemUrl($entity);
        return redirect($redirect)
            ->with('global', "Comment not saved.");
    }


    public function edit($id)
    {
        if (($comment = Comment::find($id)) && ($editable = Auth::user()->isEditable($comment->persons_id))) {
            if ($entry = DocumentEntity::find($comment->document_id)) {
                //todo: session var can be deleted.
                Session::set('comment.'.$id.'.entry.id', $entry->id);
                return view('site.document.comment.edit')
                    ->with('comment', $comment)
                    ->with('entry', $entry);
            }
        }

        //error
        return redirect(URL::previous())
            ->with('global', "Comment not accessable.");
    }


    public function update($id)
    {
        $validator = Validator::make(Input::all(), Comment::$rules);
        if ($validator->passes()) {
            if ($comment = Comment::find($id)) {
                $comment->comment = Input::get('comment');
                if ($comment->save()) {
                    //notify author
                    Event::fire('comment.updated', $comment);

                    //$entry_id = Session::pull('comment.'.$id.'.entry.id');
                    if ($entity = DocumentEntity::find($comment->document_id)) {
                        return redirect(Router::getItemUrl($entity))
                            ->with('global', "Comment successfully saved.");
                    }
                }
            }
        }

        //error
        return redirect(URL::previous())
            ->with('global', "Comment not accessable.");
    }


    public function enable($id, $value)
    {
        if ($comment = Comment::find($id)) {
            if ($comment->enabled != $value) {
                $comment->enabled = $value;
                if ($result = $comment->save()) {
                }
            }
            return redirect(URL::previous());
        }
    }
}
