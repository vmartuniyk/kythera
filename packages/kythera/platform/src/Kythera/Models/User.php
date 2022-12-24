<?php

namespace Kythera\Models;

use Kythera\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laratrust\Traits\LaratrustUserTrait;

/**
 * @author virgilm
 *
 * @francesdath 2017-06-07
 *
 *
 */

class User extends Eloquent {

	use SoftDeletes;
	use LaratrustUserTrait;

	protected $dates = ['deleted_at'];

	public $timestamps = false;

	protected $table = "users";


	protected $fillable = array(
		'place_of_birth',
		'date_of_birth',
		'date_of_death',
		'gender',
		'hide',
		'firstname',
		'middlename',
		'lastname',
		'nickname',
		'maidenname',
		'username',
		'email',
		'password',
		'confirmation_code',
		'remember_token',
		'confirmed',
		'created_at',
		'updated_at'
	);


	public static $rules = array (
		'username' => 'required|unique:users,username'
	);


	//	@francesdath 2017-06-09
	//	extending $rulesUpdate for editing Users in Admin
	//	note cos we're using public static, we can't validate & exclude id being edited like so:
	//	'username' => 'required|unique:users,username,' . $id,

	public static $rulesUpdate = array (
			'email' => 'required|email|unique:users,email',
			'firstname' => 'required',
			'lastname' => 'required'
	);


	/*
	//	so we use boot instead

	public static function boot() {
		parent::boot();

		//	get the id of the user being edited
		//	seems like existing edited user data isn't carried across to here.
		//	note this works, just need to actually get id of user being edited
		$id = whatthefuck();
		self::$rulesUpdate['username'] = 'required|unique:users,username,' . $id;
	}
	*/


	public static $messages = array(
		'username.unique' => 'Username already exists.'
	);


	public function slug() {
		return Str::slug( $this->lastname );
	}



}
