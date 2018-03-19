<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Overtrue\LaravelFollow\Traits\CanFollow;
use Overtrue\LaravelFollow\Traits\CanBeFollowed;
use Overtrue\LaravelFollow\Traits\CanLike;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
	use Notifiable, HasRoles;
	use CanFollow, CanBeFollowed, CanLike;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'email_token', 'pic', 'provider', 'provider_id', 'verified'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token', 'access_token'
	];

	public function reviews() {
		return $this->hasMany('App\Review');
	}
}
