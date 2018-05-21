<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use App\Http\Requests\EditReview;
use App\Traits\ShowReviewable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Utilities\JavaScript\JavaScriptFacade as JavaScript;
use Tmdb\Laravel\Facades\Tmdb;

class ReviewsController extends Controller
{
	use ShowReviewable;

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if (Auth::check()) {
			if (!isset($request->stars))
				return back()->withInput()->withErrors(['You can\'t give 0 stars']);
				
			$review = Review::create([
				'body' => $request->body, 
				'stars' => $request->stars, 
				'user_id' => Auth::user()->id, 
				'reviewable_id' => $request->reviewable_id,
				'reviewable_type' => $request->reviewable_type
			]);
		} else {
			return back()->withInput()->with('errors', 'You must be logged in write a review');
		}

		if (isset($review)) {
			return back()->with('success', 'Your review is added successfully');
		}

		return back()->withInput()->with('errors', 'Error adding review');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Review  $review
	 * @return \Illuminate\Http\Response
	 */
	public function show(Review $review)
	{
			//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Review  $review
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Review $review)
	{
		JavaScript::put([
			'stars' => $review->stars
		]);

		if ($review->isMovie())
			return view('reviews.edit', [
				'review' => $review, 
				'reviewable' => Tmdb::getMoviesApi()->getMovie($review->reviewable_id)
			]);
		else if ($review->isTv())
			return view('reviews.edit', [
				'review' => $review, 
				'reviewable' => Tmdb::getTvApi()->getTvshow($review->reviewable_id)
			]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Review  $review
	 * @return \Illuminate\Http\Response
	 */
	public function update(EditReview $request, Review $review)
	{
		if (!isset($request->stars))
			return back()->withInput()->withErrors(['You can\'t give 0 stars']);
		$review->body = $request->body;
		$review->stars = $request->stars;
		$review->save();

		return $this->showReviewable($review);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Review  $review
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Review $review)
	{
		if (Review::destroy($review->id))
			return back()->with('success', 'Review deleted successfully');

		return back()->with('errors', 'Error deleting review');
	}

	public function like(Request $request, Review $review) {
		if (Auth::user()->toggleLike($review))
			return back();
		
		return back()->with('errors', 'Error liking');
	}
}
