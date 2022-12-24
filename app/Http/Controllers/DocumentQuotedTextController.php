<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentQuotedText;
use Kythera\Models\DocumentText;
use Kythera\Support\ViewEntity;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author virgilm
 *
 */
class DocumentQuotedTextController extends DocumentTextController
{
    
    /**
     * Show entry by uri
     */
    public function getEntry($entry = null)
    {
        if ($item = DocumentQuotedText::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
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
}
