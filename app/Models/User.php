<?php

namespace App\Models;

use Overtrue\LaravelFollow\Traits\CanFollow;
use Overtrue\LaravelFollow\Traits\CanBeFollowed;
use Overtrue\LaravelFollow\Traits\CanLike;
use Illuminate\Support\Facades\DB;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User.
 *
 * @package namespace App\Models;
 */
class User extends Authenticatable
{
	use HasApiTokens, Notifiable;
	use CanFollow, CanBeFollowed, CanLike;
	use EntrustUserTrait;
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'email_token', 'pic', 'provider', 'provider_id', 'provider_name', 'verified'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'remember_token', 'access_token'
	];
	 
	public function reviews() {
		return $this->hasMany('App\Models\Review');
	}

	public function getWatchlist() {
		return DB::table('watchlists')->where('user_id', $this->id)->get();
	}

	public function hasWatchlisted($id, $type) {
		$item = DB::table('watchlists')->where('reviewable_type', $type)
									->where('user_id', $this->id)
									->where('reviewable_id', $id)->get()->first();
		return $item ? true : false;
	}

	public function isFollowingTv($tv_id) {
		return DB::table('tv_user')->where(['user_id' => $this->id, 'tv_id' => $tv_id])->exists();
	}

	public function getFollowingTvCount() {
		return DB::table('tv_user')->where('user_id', $this->id)->count();
	}
}
