<?php
namespace Kythera\Models;

class HidrateUser extends \App\Models\User
{

	/**
	 * Database table name
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * Hidden fields for hidration
	 * @var array
	 */
	protected $hidden = array('confirmation_code', 'confirmed', 'password', 'updated_at', 'remember_token', 'created_at', 'deleted_at');

}
