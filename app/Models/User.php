<?php

namespace App\Models;

use Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use SoftDeletes;

  	use Authenticatable, CanResetPassword, LaratrustUserTrait;

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'users';

  	/**
  	 * The attributes excluded from the model's JSON form.
  	 *
  	 * @var array
  	 */
  	protected $hidden = array('password', 'remember_token', 'confirmation_code', 'hide', 'confirmed');

    protected $fillable = [
      'place_of_birth', 'date_of_death', 'date_of_birth', 'gender',
      'firstname', 'middlename', 'lastname', 'nickname', 'maidenname',
      'username', 'email', 'password', 'confirmation_code'
    ];

    public function getFullNameAttribute() {
        if ($this->middlename) {
            return sprintf('%s %s %s', $this->firstname, $this->middlename, $this->lastname);
        } else {
            return sprintf('%s %s', $this->firstname, $this->lastname);
        }
    }

    public function isOwner($user_id)
    {
        return Auth::user()->id === $user_id;
    }


    public function isAdmin()
    {
        return Auth::user()->hasRole('administrator');
    }


    public function isEditable($user_id)
    {
        return $this->isOwner($user_id) || $this->isAdmin();
    }
}
