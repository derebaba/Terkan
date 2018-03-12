<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait ShowReviewable {
	
	public function showReviewable($review) {
		if ($review->isMovie()) {
			return redirect()->route('movies.show', [$review->reviewable_id]);
		} else {
			return redirect()->route('tvs.show', [$review->reviewable_id]);
		}
	}
};

