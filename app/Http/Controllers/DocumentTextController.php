<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentText;
use Kythera\Models\DocumentLetter;
use Kythera\Support\ViewEntity;
use Kythera\Models\DocumentImage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Kythera\Router\Facades\Router;

/**
 * @author virgilm
 *
 */
class DocumentTextController extends PageController
{

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
        /*if ($item = DocumentText::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                    ->withUser()
                    ->whereUri($this->getCurrentPage(), $entry)->first()) {*/
                        if ($item = DocumentImage::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                ->withUser()
                ->whereUri($this->getCurrentPage(), $entry)->first()) {
            $item = ViewEntity::build($item);
            //echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';die;

            //set toolbar data
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
                Router::getEntityUri($item),
                301
            );
            /*
            return redirect(
                route(Router::getControllerUrl('entry'), (string)$item->uri),
                301
            );
            */
        } else {
            throw new NotFoundHttpException();
        }
    }


    public function missingMethod($parameters = [])
    {
        Log::info('DocumentTextController->missingMethod: '.print_r($parameters, 1));
    }
}
