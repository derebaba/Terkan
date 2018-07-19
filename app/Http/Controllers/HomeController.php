<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use DateTime;
use Illuminate\Support\Facades\DB;
use Laracasts\Utilities\JavaScript\JavaScriptFacade as JavaScript;
use Illuminate\Http\Request;
use App\Models\Review;
use Tmdb\Laravel\Facades\Tmdb;
use App\Traits\Utils;
use App\Contracts\Repositories\UserRepository;


class HomeController extends Controller
{
	use Utils;

	/**
	 * @var UserRepository
	 */
	protected $userRepository;

	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	public function home() {
		if (!Auth::check())
			return $this->welcome();

		$reviews = Review::whereIn('user_id', Auth::user()->followings()->get()->pluck('id'))->get()->reverse()->values();
		$reviewables = $this->getReviewables($reviews);
		
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
			'followingTvs' => $this->userRepository->getFollowingTvs(Auth::user()->id),
			'recommendations' => $recommendations
		]);
		
	}

	public function welcome() {
		//	get latest popular reviews
		$reviews = Review::all()->take(-20)->reverse()->values();
		$reviewables = $this->getReviewables($reviews);

		//	get genres for navbar
		$movieGenres = Tmdb::getGenresApi()->getMovieGenres();
		clock($movieGenres);
		$movieGenres = $movieGenres['genres'];

		$tvGenres = Tmdb::getGenresApi()->getMovieGenres();
		$tvGenres = $tvGenres['genres'];

		//	latest
		$movies = Tmdb::getDiscoverApi()->discoverMovies()['results'];
		foreach ($movies as &$movie)
			$movie['media_type'] = 'movie';
		
		$recommendations = $this->getReviewablesFromResults($movies);

		JavaScript::put([
			'popularStars' => $recommendations->pluck('vote_average'),
			'stars' => $reviews->pluck('stars')
		]);

		return view('welcome', [
			'reviews' => $reviews,
			'reviewables' => $reviewables,
			'genre_id' => -1,
			'movieGenres' => $movieGenres,
			'recommendations' => $recommendations,
			'tvGenres' => $tvGenres,
		]);
	}
}
