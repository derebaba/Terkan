<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Review;

/**
 * Class ReviewTransformer.
 *
 * @package namespace App\Transformers;
 */
class ReviewTransformer extends TransformerAbstract
{
	protected $defaultIncludes = ['user'];
    /**
     * Transform the Review entity.
     *
     * @param \App\Models\Review $model
     *
     * @return array
     */
    public function transform(Review $model)
    {
        return [
            'id'	=> (int) $model->id,
			'stars'	=> (int) $model->stars, 
			'body'	=> $model->body,
			'user_id' => $model->user_id,
			'reviewable_id' => $model->reviewable_id, 
			'reviewable_type' => $model->reviewable_type,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
	}
	
	public function includeUser(Review $model)
    {
        return $this->item($model->user, new UserTransformer());
    }
}
