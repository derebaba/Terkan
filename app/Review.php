<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFollow\Traits\CanBeLiked;

class Review extends Model
{
	use CanBeLiked;
	
	protected $fillable = [
		'stars', 'body', 'user_id', 'reviewable_id', 'reviewable_type'
	];

	//  protected $with = ['user']; sonra dene

	public function user() {
		return $this->belongsTo('App\Models\User');
	}

	public function scopeMovie($query) {
		return $query->where('reviewable_type', 'movie');
	}

	public function scopeTv($query) {
		return $query->where('reviewable_type', 'tv');
	}

	public function isMovie() {
		return $this->reviewable_type == "movie";
	}

	public function isTv() {
		return $this->reviewable_type == "tv";
	}
}
