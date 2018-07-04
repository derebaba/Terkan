<?php

namespace App\Transformers;

use JD\Cloudder\Facades\Cloudder;
use League\Fractal\TransformerAbstract;
use App\Models\User;

/**
 * Class UserTransformer.
 *
 * @package namespace App\Transformers;
 */
class UserTransformer extends TransformerAbstract
{
    /**
     * Transform the User entity.
     *
     * @param \App\Models\User $model
     *
     * @return array
     */
    public function transform(User $model)
    {
        $model->pic = Cloudder::secureShow($model->pic);

        return [
            'id' => $model->id,
            'name' => $model->name,
			'pic' => $model->pic,
			'followersCount' => $model->followers()->count(),
			'followingUserCount' => $model->followings()->count(),
			'followingTvCount' => $model->getFollowingTvCount(),
			'watchlistCount' => $model->getWatchlist()->count(),
        ];
    }
}
