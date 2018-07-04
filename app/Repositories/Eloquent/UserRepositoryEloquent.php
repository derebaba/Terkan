<?php

namespace App\Repositories\Eloquent;

use JD\Cloudder\Facades\Cloudder;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
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
	
	public function full($id)
	{
		$user = User::find($id);
		$user->pic = Cloudder::secureShow($user->pic);
		return $user;
	}
}
