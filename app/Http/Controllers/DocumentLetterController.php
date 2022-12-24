<?php

namespace App\Http\Controllers;

use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentImage;
use Kythera\Models\DocumentLetter;
use Kythera\Support\ViewEntity;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author virgilm
 *
 */
class DocumentLetterController extends DocumentImageController
{

    /**
     * Show entry by uri
     */
    public function getEntry($entry = null)
    {
        if ($item = DocumentLetter::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                ->withUser()
                ->whereUri($this->getCurrentPage(), $entry)->first()) {
                $item = ViewEntity::build($item, ViewEntity::VIEW_MODE_VIEW);
                ///echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';die;

                return $this->view('view')
                            ->with('item', $item);
        } else {
            throw new NotFoundHttpException('Requested entry not found: ' . $entry);
        }
    }
}
