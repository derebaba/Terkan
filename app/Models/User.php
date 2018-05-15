<?php

namespace App\Models;

use Overtrue\LaravelFollow\Traits\CanFollow;
use Overtrue\LaravelFollow\Traits\CanBeFollowed;
use Overtrue\LaravelFollow\Traits\CanLike;
use Illuminate\Support\Facades\DB;
use Sofa\Eloquence\Eloquence;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class User.
 *
 * @package namespace App\Models;
 */
class User extends Authenticatable implements Transformable
{
    use TransformableTrait;
	use HasApiTokens, Notifiable;
	use CanFollow, CanBeFollowed, CanLike;
	use Eloquence;

	// no need for this, but you can define default searchable columns:
	protected $searchableColumns = ['name', 'email', 'provider_name'];
	
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
		return $this->hasMany('App\Review');
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
}