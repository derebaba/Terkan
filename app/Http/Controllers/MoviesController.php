<?php

namespace App\Http\Controllers;

use App\Movie;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tmdb\Laravel\Facades\Tmdb;
use Tmdb\Repository\MovieRepository;
use Laracasts\Utilities\JavaScript\JavaScriptFacade as JavaScript;

class MoviesController extends Controller
{
	private $movies;

	function __construct(MovieRepository $movies)
	{
		$this->movies = $movies;
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//  if (Auth::check())  $movies = Movie::where('user_id', Auth::user()->id);
		//$movies = Movie::all();
		$movies = $this->movies->getPopular();
		return view('movies.index', ['movies' => $movies]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Movie  $movie
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$movie = Tmdb::getMoviesApi()->getMovie($id);
		$reviews = Review::movie()->where('reviewable_id', $id)->get();
		$userReview = null;

		JavaScript::put([
			'averageRating' => $reviews->avg('stars'),
			'stars' => $reviews->pluck('stars')
		]);

		if (Auth::check()) {
			$userReview = $reviews->where('user_id', Auth::user()->id)->first();
			$reviews = $reviews->reject(function ($value, $key) {
				return $value->user_id == Auth::user()->id;
			});

			if ($userReview)
				$reviews->push($userReview)->reverse();
		}
		
		return view('movies.show', [
			'movie' => $movie, 
			'page_title' => $movie['original_title'],
			'reviews' => $reviews, 
			'userReview' => $userReview]);
	}

}
