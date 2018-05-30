<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Contracts\Repositories\ReviewRepository;
use App\Models\Review;
use App\Validators\ReviewValidator;
use App\Models\User;

/**
 * Class ReviewRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class ReviewRepositoryEloquent extends BaseRepository implements ReviewRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Review::class;
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
        return "App\\Presenters\\ReviewPresenter";
	}
	
	public function getNewsFeed($userId) {
		return $this->findWhereIn('user_id', User::find($userId)->followings()->get()->pluck('id')->toArray());
	}
}
