<?php

namespace App\Http\Controllers\Admin;

/**
 * @author virgilm
 *
 */
class AdminDashboardController extends AdminController
{
    
    /**
     * Admin dashboard
     */
    public function getIndex()
    {
        /*
		 * #documents: SELECT count(*) as count FROM documents WHERE enabled=1
		 * $names = DB::select('select count(*) as n from names;');
		 * return view('admin.index.index')
		 * ->with('names', $names[0]->n);
		 */
        return view('admin.index.index');
    }
}
