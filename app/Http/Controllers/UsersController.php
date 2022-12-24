<?php

namespace App\Http\Controllers;

use App;
use Auth;
use Mail;
use URL;
use Validator;
use JsValidator;
use Input;
use Log;
use Redirect;
use Session;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Requests\Auth\LoginRequest;

use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentGuestbook;
use Kythera\Router\Facades\Router;
use Kythera\Support\ViewEntity;
use App\Models\User;


/**
 * @author Tamer Hassan
 *
 */

/**
 * UsersController Class
 *
 * Implements actions regarding user management
 */

class UsersController extends PageController
{
    use AuthenticatesUsers;
    use ThrottlesLogins;

    /**
    * User model instance
    * @var User
    */
    protected $user;

    protected $registerRules = [
        'firstname'             => 'required|max:255',
        'lastname'              => 'required|max:255',
        'email'                 => 'required|email|max:255|unique:users,email',
        'password'              => 'required|confirmed|min:5',
        'g-recaptcha-response'  => 'required|captcha',
    ];

    protected $loginRules = array(
        'email' => 'required',
        'password' => 'required',
    );

    /**
    * Create a new authentication controller instance.
    *
    * @return void
    */
    public function __construct(Guard $auth, User $user)
    {
      $this->user = $user;
      $this->auth = $auth;
      if ($this->auth->guest()) {
        $this->middleware('guest', ['except' => 'getLogout']);
      }
      
    }

    /**
     * Get the login username to be used by the controller.
     *
     * Depending on if the given input is of the type email, it is passed along as either
     * the name or email of the user.
     *
     * @return string
     */
    public function loginUsername()
    {
        $field = filter_var(request('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$field => request('email')]);
        return $field;
    }

    /**
     * Get the path to the login route.
     *
     * @return string
     */
    public function loginPath()
    {
        return property_exists($this, 'loginPath') ? App::getLocale() . $this->loginPath : App::getLocale() . '/users/access';
    }

    /**
    * Get a validator for an incoming registration request.
    *
    * @param  array  $data
    * @return \Illuminate\Contracts\Validation\Validator
    */
    protected function validator(array $data)
    {
      return Validator::make($data, $this->registerRules);
    }

    /**
     * Displays the form for account registration
     *
     * @return  Illuminate\Http\Response
     */
    public function getRegister()
    {
        $validator = JsValidator::validator(
            $this->validator([])
        );

        return view('site.user.signup')->with([
            'validator' => $validator
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $confirmation_code = md5(uniqid(mt_rand(), true));

        return User::create([
            'firstname'         => $data['firstname'],
            'lastname'          => $data['lastname'],
            'username'          => $data['email'],
            'email'             => $data['email'],
            'password'          => bcrypt($data['password']),
            'confirmation_code' => $confirmation_code,
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $user = $this->create($request->all());

        $this->sendActivationMail($user);

        return Redirect::back()
            ->with('status', 'Thanks for registering your account. We have sent you a confirmation link to your email address. Please check your inbox and, if you can find it, your spam folder');
    }


    public function authenticated(Request $request, $user)
    {
        if (!$user->confirmed) {
            $user->confirmation_code = md5(uniqid(mt_rand(), true));
            $user->save();
            $this->sendActivationMail($user);
            auth()->logout();
            return back()->with('warning', 'You need to confirm your account before you can login. We have now resent you an activation code, please check your email inbox.');
        }
        return redirect()->intended('/' . App::getLocale());
    }


    protected function sendActivationMail($user)
    {
        Mail::send('site.user.emails.confirm',
            ['user' => $user],
            function($message) use ($user) {
                $message->to($user->email, $user->firstname . ' ' . $user->lastname)
                    ->subject('Account registration confirmation at Kythera-Family.net');
            }
        );
    }

    /**
     * Displays the login form
     *
     * @return  Illuminate\Http\Response
     */
    public function getLogin()
    {
        if (Auth::check()) {
            return redirect('/');
        } else {
            $rules = array(
                'email' => 'required',
                'password' => 'required',
            );
            $validator = JsValidator::make($rules);

            return view('site.user.login')->with([
                'validator' => $validator
            ]);
        }
    }

    /**
     * Displays the login/register form
     *
     * @return  Illuminate\Http\Response
     */
    public function getAccess()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        else {
            $registerValidator = JsValidator::make(
                $this->registerRules,
                [],
                [],
                '#register'
            );

            $loginValidator = JsValidator::make($this->loginRules);

            return view('site.user.access')->with([
                'registerValidator' => $registerValidator,
                'loginValidator' => $loginValidator
            ]);
        }
    }


    /**
     * Attempt to confirm account with code
     *
     * @param  string $code
     *
     * @return  Illuminate\Http\Response
     */
    public function getConfirm($code)
    {
        if ($user = User::where('confirmation_code', $code)->first())
        {
            $user->confirmed = 1;
            $user->confirmation_code = md5(uniqid(mt_rand(), true));  // reset it for security
            $user->save();

            auth()->login($user);

            return redirect('/' . App::getLocale());
        }
        else
        {
            return Redirect::action('UsersController@getLogin')
                ->with('warning', 'Wrong or expired confirmation code. Please check the link, or login to request a new confirmation link.');
        }
    }

    /**
     * Displays the forgot password form
     *
     * @return  Illuminate\Http\Response
     */
    // public function getForgot()
    // {
    //     return view('site.user.forgot_password');
    // }

    /**
     * Attempt to send change password link to the given email
     *
     * @return  Illuminate\Http\Response
     */
    // public function postForgot()
    // {
    //     if (Confide::forgotPassword(Input::get('email'))) {
    //         $notice_msg = Lang::get('confide::confide.alerts.password_forgot');
    //         return Redirect::action('UsersController@getLogin')
    //             ->with('notice', $notice_msg);
    //     } else {
    //         $error_msg = Lang::get('confide::confide.alerts.wrong_password_forgot');
    //         return Redirect::action('UsersController@postForgot')
    //             ->withInput()
    //             ->with('error', $error_msg);
    //     }
    // }

    /**
     * Shows the change password form with the given token
     *
     * @param  string $token
     *
     * @return  Illuminate\Http\Response
     */
    // public function getReset($token)
    // {
    //     return view(config('confide::reset_password_form'))
    //             ->with('token', $token);
    // }

    /**
     * Attempt change password of the user
     *
     * @return  Illuminate\Http\Response
     */
    // public function postReset()
    // {
    //     $repo = App::make('UserRepository');
    //     $input = [
    //         'token'                 =>Input::get('token'),
    //         'password'              =>Input::get('password'),
    //         'password_confirmation' =>Input::get('password_confirmation'),
    //     ];
    //
    //     // By passing an array with the token, password and confirmation
    //     if ($repo->resetPassword($input)) {
    //         $notice_msg = Lang::get('confide::confide.alerts.password_reset');
    //         return Redirect::action('UsersController@getLogin')
    //             ->with('notice', $notice_msg);
    //     } else {
    //         $error_msg = Lang::get('confide::confide.alerts.wrong_password_reset');
    //         return Redirect::action('UsersController@getReset', ['token'=>$input['token']])
    //             ->withInput()
    //             ->with('error', $error_msg);
    //     }
    // }





    /**
     * Log the user out of the application and stay on the same page.
     *
     * @return  Illuminate\Http\Response
     */
    public function getLogout()
    {
        Auth::logout();

        return redirect('/');
//        return Redirect::intended(URL::previous());
    }


    public function getContact($entryId, $categoryId = null)
    {
        if ($entryId && Auth::check()) {
            Session::set('contact.entry', $entry = DocumentEntity::find($entryId));
            Session::set('contact.user', $user = User::find($entry->persons_id));

            $view = view('site.page.user.index');
            $view->with('entry', $entry);
            $view->with('user', $user);

            //show user categories
            $categories = DocumentEntity::getUserEntries($user, [], false);
            $c = count($categories);
            $n = 0;
            foreach ($categories as $i => $category) {
                if ($page = Router::getPageByID($category->page_id)) {
                    $category->category_uri = $page->path;
                }
                $n += $category->n;
            }
            $view->with('categories', $categories);
            $view->with('cat_stat', sprintf('%d posts in %d categories', $n, $c));

            //show category items
            if ($categoryId) {
                $items = DocumentEntity::getUserEntries($user, [$categoryId], false);
                if (count($items)) {
                    $item = $items[0];
                    $page = Router::getPageByID($item->page_id);
                    $view->with('list', 'category');
                    $view->with('page', $page);
                    $view->with('items', $items);
                }
            }

            /*
    		//show user comments
    		$comments = Comment::getUserComments($user);
    		$c = count($comments); $n = 0;
    		foreach ($comments as $comment) {
    		    //if no page is found please check if category already is imported
    		    if ($page = Router::getPageByID($comment->page_id)) {
    		        $comment->category = $page;
    		    }
    		    $n += $comment->n;
    		}

    		$view->with('comments', $comments);
    		$view->with('com_stat', sprintf('%d comments in %d categories', $n, $c));
    		*/

            //family trees
            //$trees = DocumentPerson::getUserEntries(Auth::user(), array(63));
            $trees = [];
            $view->with('trees', $trees);
            $view->with('tree_stat', sprintf('%d family trees', count($trees)));

            return $view;
        }

        //error
        return view('site.page.user.index')
            ->with('global', "Author could not be contacted.");
    }


    public function postContact()
    {
        $entry = Session::pull('contact.entry');
        $user = Session::pull('contact.user');
       

        if (!empty($entry) && !empty($user)) {
            echo "<pre>";
            
            $validator = Validator::make(Input::get('entry'), DocumentGuestbook::$rulesContactnew);
             
            if ($validator->passes()) {
                $data = [];
                $data['host']    = 'http://'.$_SERVER['HTTP_HOST'];

                //site administrator
                $data['sender_email'] = config('app.administrator');
                $data['sender_name']  = 'kythera-family.net administrator';

                //sender (authenticated user)
                $data['from_email']   = Input::get('entry.e') ? Input::get('entry.e') : Auth::user()->email;
                $data['from_name']    = Auth::user()->firstname;

                //recipient (entry author)
                $data['recipient_email'] = $user->email;
                $data['recipient_name']  = Auth::user()->firstname;

                $data['subject'] = Input::get('entry.s');
                $data['content'] = Input::get('entry.content');

                //echo __FILE__.__LINE__.'<pre>$data='.htmlentities(print_r($data,1)).'</pre>';die;

                //FIXME: convert to event and queue mail
                Mail::send('emails.user.contact', $data, function ($message) use ($data) {
                    $message
                        ->from($data['sender_email'], $data['sender_name'])
                        ->to($data['recipient_email'], $data['recipient_name'])
                        ->replyTo($data['from_email'], $data['from_name'])
                        ->bcc($data['sender_email'], $data['sender_name'])
                        ->subject($data['subject']);

                    Log::info('user.contact', $data);
                });

                return redirect(Router::getItemUrl($entry))
                    ->with('global', "You message to the author has been sent.");
            }
        }

        //error
        return redirect('/')
            ->with('global', "Author could not be contacted.");
    }
}
