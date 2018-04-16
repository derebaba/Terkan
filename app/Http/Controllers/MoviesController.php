<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use App\IMDbapi;
use Laracasts\Utilities\JavaScript\JavaScriptFacade as JavaScript;
use App\Movie;
use Tmdb\Repository\MovieRepository;
use Illuminate\Http\Request;
use App\Review;
use Tmdb\Laravel\Facades\Tmdb;

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

		$client = new Client();
		$res = $client->get('http://www.theimdbapi.org/api/movie?movie_id='. $movie['imdb_id']);
		//$res->getStatusCode();
		
		$imdbData = json_decode($res->getBody());

		//dd($imdbData);
		JavaScript::put([
			'averageRating' => $reviews->avg('stars'),
			'imdb_stars' => $imdbData->rating / 2,
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
