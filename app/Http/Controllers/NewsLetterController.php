<?php

namespace App\Http\Controllers;

use Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Kythera\Models\Subscriber;
use Kythera\Router\Facades\Router;

/**
 * @author virgilm
 *
 */


/*
CREATE TABLE IF NOT EXISTS `newsletter_subscriber` (
  `persons_id` int(11) DEFAULT '0',
  `register_token` varchar(40) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `enabled` int(1) DEFAULT '0',
  UNIQUE KEY `id` (`persons_id`,`email`)
) ENGINE=Innodb DEFAULT CHARSET=utf8;
*/
//INSERT INTO `document_types` (`id`, `string_id`, `table_name`, `controller`, `label`, `group_label`) VALUES ('0', 'KFN_NEWS_LETTER', NULL, 'NewsLetterController', NULL, NULL);

class NewsLetterController extends PageController
{

    public function getIndex()
    {
        if (Auth::check()) {
            $user       = Auth::user();
            $subscriber = Subscriber::find($user->id);
        } else {
            $user       = null;
            $subscriber = null;
        }

        return $this->view('index')
                ->with('user', $user)
                ->with('subscriber', $subscriber);
    }


    public function getSubscribe($email = null)
    {
        if (Auth::check() && $email) {
            if ($subscriber = Subscriber::where('persons_id', Auth::user()->id)->first()) {
                $subscriber->enabled = 1;
                $result = $subscriber->save();

                return $this->view('confirm')
                    ->with('subscriber', $subscriber)
                    ->with('confirmed', $result);
            } else {
                $subscriber = Subscriber::create([
                    'persons_id' => Auth::user()->id,
                    'firstname' => null,
                    'lastname' => null,
                    'email' => null,
                    'enabled' => 1,
                    'register_token' => Subscriber::getToken(Input::get('email'))
                ]);
                $result = $subscriber->save();

                return $this->view('confirm')
                    ->with('subscriber', $subscriber)
                    ->with('confirmed', $result);
            }
        }

        return Redirect::action('NewsLetterController@getIndex');
    }


    public function postSubscribe()
    {
        if (Auth::check()) {
            /*
    		//subscribe
    		$subscriber = Subscriber::where('persons_id', Auth::user()->id);
    		$subscriber->enabled = 1;
    		$subscriber->save();

    		$subscriber->email = Auth::user()->email;

    		return Redirect::action('NewsLetterController@getIndex')
	    		->with('subscribed', true)
	    		->with('subscriber', $subscriber);
    		*/
        } else {
            $validator = Validator::make(Input::all(), Subscriber::$rules, Subscriber::$messages);
            if ($validator->passes()) {
                if ($subscriber = Subscriber::where('email', Input::get('email'))->first()) {
                    $subscriber->firstname = Input::get('firstname');
                    $subscriber->lastname = Input::get('lastname');
                    $subscriber->enabled = 1;
                    $result = $subscriber->save();

                    return $this->view('confirm')
                        ->with('confirmed', $result);
                } else {
                    $subscriber = Subscriber::create([
                        'firstname' => Input::get('firstname'),
                        'lastname' => Input::get('lastname'),
                        'email' => Input::get('email'),
                        'enabled' => 0,
                        'register_token' => Subscriber::getToken(Input::get('email'))
                    ]);
                    $subscriber->save();

                    //send confirmation
                    Event::fire('newsletter.confirm', $subscriber);

                    return $this->view('confirm')
                        ->with('subscriber', $subscriber);
                }
            }

            return Redirect::action('NewsLetterController@getIndex')
                ->withErrors($validator)
                ->withInput();
        }
    }


    public function getConfirm($email, $token)
    {
        $result = false;
        if ($subscriber = Subscriber::where('email', $email)->first()) {
            if ($token == Subscriber::getToken($email)) {
                $subscriber->enabled = 1;
                $result = $subscriber->save();
            }
        }

        return $this->view('confirm')
            ->with('confirmed', $result);
    }


    public function getUnsubscribe($email, $token)
    {
        $result = false;
        if (Auth::check()) {
            if ($subscriber = Subscriber::where('persons_id', Auth::user()->id)->first()) {
                if ($token == Subscriber::getToken($email)) {
                    $subscriber->enabled = 0;
                    $result = $subscriber->save();
                }
            }
        } else {
            if ($subscriber = Subscriber::where('email', $email)->first()) {
                if ($token == Subscriber::getToken($email)) {
                    $subscriber->enabled = 0;
                    $result = $subscriber->save();
                }
            }
        }

        return $this->view('confirm')
            ->with('unsubscribed', $result);
    }
}
