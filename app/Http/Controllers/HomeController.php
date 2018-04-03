<?php

namespace App\Http\Controllers;

use App\Review;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Utilities\JavaScript\JavaScriptFacade as JavaScript;
use Tmdb\Laravel\Facades\Tmdb;

class HomeController extends Controller
{
	use Utils;
	/*
	public function __construct()
	{
		$this->middleware('auth');
	}
 */
	public function home() {
		if (!Auth::check())
			return $this->welcome();
		//	TODO: arkadaşlarının reviewlarını al
		$reviews = Review::whereIn('user_id', Auth::user()->followings()->get()->pluck('id'))->get()->reverse()->values();
		$reviewables = $this->getReviewables($reviews);
		/*
		Tmdb api kullanmanın alternatif yolu
		$token  = new \Tmdb\ApiToken(env('TMDB_KEY'));
		$client = new \Tmdb\Client($token);
		$repository = new \Tmdb\Repository\GenreRepository($client);
		$genre      = $repository->load(28);
		*/

		$genres = Tmdb::getGenresApi()->getMovieGenres();
		$genres = collect($genres['genres']);	//	19 ve sonrası tv
		//dd($genres);
		$movies = Tmdb::getDiscoverApi()->discoverMovies()['results'];
		foreach ($movies as &$movie)
			$movie['media_type'] = 'movie';
		
		$recommendations = $this->getReviewablesFromResults($movies);

		JavaScript::put([
			'recommendationStars' => $recommendations->pluck('vote_average'),
			'stars' => $reviews->pluck('stars')
		]);

		
		return view('home', [
			'reviews' => $reviews,
			'reviewables' => $reviewables,
			'genre_id' => -1,
			'genres' =>	$genres,
			'recommendations' => $recommendations
		]);
		
	}

	public function welcome() {
		//	get latest popular reviews
		$reviews = Review::all()->take(-10)->reverse()->values();
		$reviewables = $this->getReviewables($reviews);

		JavaScript::put([
			'stars' => $reviewables->pluck('vote_average')
		]);

		$genres = Tmdb::getGenresApi()->getGenres();
		$genres = collect($genres['genres']);	//	19 ve sonrası tv

		return view('welcome', [
			'reviews' => $reviews,
			'reviewables' => $reviewables,
			'genre_id' => -1,
			'genres' =>	$genres,
		]);
	}
}
