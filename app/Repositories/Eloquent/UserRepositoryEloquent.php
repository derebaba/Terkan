<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Criteria\RequestCriteria;
use Tmdb\Laravel\Facades\Tmdb;
use App\Contracts\Repositories\UserRepository;
use App\Models\User;
use App\Validators\UserValidator;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
	protected $fieldSearchable = [
		'name' => 'ilike',
		'email', 
		'provider_name' => 'ilike'
	];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
	
	public function presenter()
    {
        return "App\\Presenters\\UserPresenter";
	}

	public function getFollowingTvs($userId)
	{
		$followingTvIds = DB::table('tv_user')->where('user_id', $userId)->pluck("tv_id");
		$followingTvs = [];

		foreach ($followingTvIds as $followingTvId) {
			$tv = Tmdb::getTvApi()->getTvshow($followingTvId);
			array_push($followingTvs, $tv);
		}

		return $followingTvs;
	}
}
