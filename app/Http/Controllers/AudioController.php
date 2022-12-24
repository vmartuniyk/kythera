<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

/**
 * @author virgilm
 *
 */
class AudioController extends Controller
{
        
    public function audio($path, $name)
    {
        $filename = sprintf('audio/%d/%s', $path, $name);

        if (file_exists(public_path().'/'.$filename)) {
            $headers = ['X-KFN' => config('app.kfn_version')];
            return Response::download($filename, $name, $headers);
        }

        //File not found
        abort(404);
    }
}
