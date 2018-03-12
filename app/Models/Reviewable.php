<?php

namespace App\Models;

use App\Review;
use Illuminate\Database\Eloquent\Model;
use Tmdb\Laravel\Facades\Tmdb;

class Reviewable extends Model
{
	//public $id, $name, $poster, $type;	//	$type is either movie or tv
	protected $fillable = [
		'id', 'name', 'poster', 'type', 'vote_average',
	];
	
	public static function createReviewableFromReview($review) {
		$reviewable;
		$ret = new Reviewable();

		if ($review->isMovie()) {
			$reviewable = Tmdb::getMoviesApi()->getMovie($review->reviewable_id);
			$ret->name = $reviewable['original_title'];
		} else {
			$reviewable = Tmdb::getTvApi()->getTvshow($review->reviewable_id);
			$ret->name = $reviewable['original_name'];
		}
		$ret->id = $review->reviewable_id;
		$ret->type = $review->reviewable_type;
		$ret->poster = $reviewable['poster_path'];
		$ret->vote_average = Review::where('reviewable_type', $ret->type)
									->where('reviewable_id', $ret->id)
									->avg('stars');

		return $ret;
	}

}
