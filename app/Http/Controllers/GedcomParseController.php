<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;

class GedcomParseController extends Treechecker\ParseGedcomController
{

    /**
     * Overidden
     */
    public function getParse($gedcom_id)
    {
        parent::getParse($gedcom_id);

        return Redirect::action('GedcomController@getSummary', $gedcom_id);
    }
}
