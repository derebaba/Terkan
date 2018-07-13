<?php

namespace App\Transformers;

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
