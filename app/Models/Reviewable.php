<?php

namespace App\Models;

use App\Models\Review;
use Illuminate\Database\Eloquent\Model;
use Tmdb\Laravel\Facades\Tmdb;

class Reviewable extends Model
{
	//public $id, $name, $poster, $type;	//	$type is either movie or tv
	protected $fillable = [
		'id', 'name', 'poster', 'type', 'vote_average', 'vote_count'
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

		$reviews = Review::where('reviewable_type', $ret->type)->where('reviewable_id', $ret->id);

		$ret->vote_count = $reviews->count() == 0 ? 0 : $reviews->count();
		$ret->vote_average = $reviews->avg('stars');
		return $ret;
	}

}
