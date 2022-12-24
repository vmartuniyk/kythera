<?php
namespace Kythera\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use \Carbon\Carbon;
use Kythera\Models;
use Illuminate\Support\Facades\Config;

class SubscriberException extends \Exception {}

class Subscriber extends Eloquent
{

    public $timestamps = false;


    protected $table = "newsletter_subscriber";


    protected $primaryKey = 'persons_id';


    protected $fillable = array(
   		'persons_id',
   		'firstname',
   		'lastname',
   		'email',
   		'enabled',
   		'register_token'
    );


    public static $rules =  array(
   		'firstname' => 'required|min:3',
   		'lastname' => 'required|min:2',
   		'email' => 'required|email'
    );


    public static $messages = array(
   		'firstname.required' => 'Firstname is required.',
   		'lastname.required' => 'Lastname is required.',
   		'email.required' => 'Email is required.'
    );


    public static function getToken($email)
    {
    	$salt = Config::get('app.key');
    	return sha1($email.$salt);
    }


}