<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

/**
 * @author virgilm
 *
 */

class LanguageController extends Controller
{
    
    /**
     * Change interface language
     */
    
    /*
     * fixme:
     * Abstract this out to seperate function, See also routes.php
     * 	public function setLocale($locale)
	    {
		$this['config']->set('app.locale', $locale);

		$this['translator']->setLocale($locale);

		$this['events']->fire('locale.changed', array($locale));
	    }
     * */
    public function postLanguage()
    {
        $locales   = config('app.available_locales');
        $rules     = [
            'language' => 'in:' . implode(',', $locales)
        ];
        $locale_old = App::getLocale();
        $locale     = Input::get('locale');
        $uri        = Input::get('uri');
        $validator  = Validator::make(compact($locale), $rules);

        #try to switch locale
        if ($validator->passes()) {
            App::setLocale($locale);
            setlocale(LC_TIME, config('app.locales')[$locale]);
            
            //fixme: is this necessary
            Session::put('locale', $locale);
                        
            //try url translation. for now only admin pages
            $uri = str_replace($locale_old, $locale, $uri);
            if (preg_match('#admin/#', $uri)) {
                return redirect('/'.$uri);
            } else {
                return redirect('/'.$locale);
            }
        } else {
            return Redirect::route('index');
        }
    }
}
