<?php

namespace App\Traits;

use App\Review;
use App\Models\Reviewable;

trait Utils {

	public function getReviewables($reviews) {
		$reviewables = $reviews->map(function ($review, $key) {
			return Reviewable::createReviewableFromReview($review);
		});

		return $reviewables;
	}

	public function getReviewablesFromResults($results) {
		$reviewables = collect(array_map(function ($result) {
			return new Reviewable([
				'id' => $result['id'],
				'name' => $result['media_type'] == 'movie' ? $result['original_title'] : $result['original_name'],
				'poster' => $result['poster_path'],
				'type' => $result['media_type'],
				'vote_average' => Review::where('reviewable_type', $result['media_type'])
						->where('reviewable_id', $result['id'])
						->avg('stars')
			]);
		}, $results));

		return $reviewables;
	}
}