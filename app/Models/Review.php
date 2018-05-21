<?php

namespace App\Models;

use Overtrue\LaravelFollow\Traits\CanBeLiked;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Review extends Model implements Transformable
{
	use CanBeLiked, TransformableTrait;
	
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
