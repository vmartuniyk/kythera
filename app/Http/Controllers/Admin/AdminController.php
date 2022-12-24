<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * @author virgilm
 *
 */
class AdminController extends Controller
{
    /**
     * Initializer.
     *
     * @access public
     * @return \BaseController
     */
    public function __construct()
    {
        $this->middleware('role:administrator');
    }


    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (! is_null($this->layout)) {
            $this->layout = view($this->layout);
        }
    }
}
