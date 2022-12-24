<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

/**
 * @author virgilm
 *
 */
class VideoController extends Controller
{
        
    public function video($path, $name)
    {
        $filename = sprintf('video/%d/%s', $path, $name);

        if (file_exists(public_path().'/'.$filename)) {
            $headers = ['X-KFN' => config('app.kfn_version')];
            return Response::download($filename, $name, $headers);
        }

        //File not found
        abort(404);
    }
}
