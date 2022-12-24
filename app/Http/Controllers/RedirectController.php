<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Kythera\Models\DocumentEntity;

/**
 * @author virgilm
 *
 */


class RedirectController extends Controller
{
    
    public function index()
    {

        //http://kfn.laravel.debian.mirror.virtec.org/en/redirect?nav=117-1200&searchResult=searchResult

        //automatically redirect internal links
        if ($url = \Kythera\Models\PageEntity::convert(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '', false)) {
            \Log::alert(sprintf('URL-AUTO: %s > %s', $_SERVER['REQUEST_URI'], $url));
            header('Location: '.$url, 301);
            exit;
        }

        $ref = rand(1000, 10000);
        if (!config('app.debug')) {
            $data = [
                'ref' => $ref,
                'referrer' => urldecode(URL::previous()),
                'server' => print_r($_SERVER, 1),
                'get' => print_r($_GET, 1),
                'post' => print_r($_POST, 1)
            ];

            /*
            Mail::send('emails.error.redirect', $data, function($message) use ($ref)
            {
                $message
                    ->to(config('app.developer'), 'developer')
                    ->subject('[KFN] Redirect #'.$ref);
            });
            */
        }

        //log failed redirections
        \Log::alert(sprintf('URL-SKIPPED2: %s', @$_SERVER['REQUEST_URI']));

        return view('site.errors.redirect')
                        ->with('ref', $ref)
                        ->with('data', Input::all())
                        ->with('destination', '');
    }
}
