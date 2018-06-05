<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Laracasts\Utilities\JavaScript\JavaScriptFacade as JavaScript;
use App\Contracts\Repositories\UserRepository;
use Tmdb\Laravel\Facades\Tmdb;

class SearchController extends Controller
{
	use Utils;

	/**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
	}

	//	TODO: Reviewable classını komple kaldır (?)
	/**
	 * Undocumented function
	 *
	 * @param [int] $genre_id	Tmdb id of the genre
	 * @param [int] $page	page number of search results
	 * @return void
	 */
	public function browseByGenre($genre_id, $page) {
		$response = Tmdb::getDiscoverApi()->discoverMovies([
			'page' => $page,
			'with_genres' => $genre_id,
			'sort_by' => 'popularity.desc'	//	default
		]);
		$movies = $response['results'];
		foreach ($movies as &$movie)
			$movie['media_type'] = 'movie';
		
		$reviewables = $this->getReviewablesFromResults($movies);	// $results
		//dd($reviewables);
		JavaScript::put([
			'stars' => $reviewables->pluck('vote_average')
		]);

		$query = Tmdb::getGenresApi()->getGenre($genre_id);

		$genres = Tmdb::getGenresApi()->getMovieGenres();
		$genres = collect($genres['genres']);
		
		return view('browse', [
			'reviewables' => $reviewables, 
			'query' => $query['name'],
			'genre_id' => $genre_id,
			'genres' =>	$genres,
			'max_pages' => min(5, $response['total_pages']),
			'response' => $response,
		]);
	}

	/**
	 * Undocumented function
	 *
	 * @param [int] $genre_id	Tmdb id of the genre
	 * @param [int] $page	page number of search results
	 * @return void
	 */
	public function browseTvByGenre($genre_id, $page) {
		$response = Tmdb::getDiscoverApi()->discoverTV([
			'page' => $page,
			'with_genres' => $genre_id,
			'sort_by' => 'popularity.desc'
		]);

		$tvs = $response['results'];
		foreach ($tvs as &$tv)
			$tv['media_type'] = 'tv';
			
		$reviewables = $this->getReviewablesFromResults($tvs);

		JavaScript::put([
			'stars' => $reviewables->pluck('vote_average')
		]);

		$query = Tmdb::getGenresApi()->getGenre($genre_id);

		$genres = Tmdb::getGenresApi()->getTvGenres();
		$genres = collect($genres['genres']);
		
		return view('search.browseTv', [
			'reviewables' => $reviewables, 
			'query' => $query['name'],
			'genre_id' => $genre_id,
			'genres' =>	$genres,
			'max_pages' => min(5, $response['total_pages']),
			'response' => $response,
		]);
	}

	public function searchAutocomplete(Request $request) {
		$query =  $request->q;

		$response = Tmdb::getSearchApi()->searchMulti($query)['results'];
		foreach ($response as $index => $item) {
			if ($item['media_type'] === "person")
				unset($response[$index]);
		}
		//	add users
		$users = $this->userRepository->skipPresenter()->all()->toArray();
		foreach ($users as &$user)
			$user['media_type'] = 'user';
		$response = array_merge($response, $users);
		$response = array_slice($response, 0, 10);

		return Response::json($response, 200, array('Content-Type' => 'application/javascript'));
	}

	public function searchMovies(Request $request) {
		$query =  $request->q;
		$page = $request->page;

		$movieResponse = Tmdb::getSearchApi()->searchMovies($query, [
			'page' => $page,
		]);

		$tvResponse = Tmdb::getSearchApi()->searchTv($query);

		$peopleResults = $this->userRepository->skipPresenter()->all();
		
		$results = $movieResponse['results'];
		foreach ($results as &$result) {
			$reviews = Review::where('reviewable_type', 'movie')->where('reviewable_id', $result['id']);
			$result['vote_count'] = $reviews->count();
			$result['vote_average'] = $reviews->avg('stars');
		}

		JavaScript::put([
			'stars' => array_column($results, 'vote_average')
		]);

		return view('search.movie', [
			'query' => $query,
			'peopleResults' => $peopleResults,
			'movieResponse' => $movieResponse,
			'results' => $results,
			'tvResponse' => $tvResponse
		]);
	}

	public function searchPeople(Request $request) {
		$query =  $request->q;
		$page = $request->page;
		
		$movieResponse = Tmdb::getSearchApi()->searchMovies($query);

		$tvResponse = Tmdb::getSearchApi()->searchTv($query);

		$peopleResults = $this->userRepository->skipPresenter()->all();

		return view('search.people', [
			'query' => $query,
			'movieResponse' => $movieResponse,
			'peopleResults' => $peopleResults,
			'tvResponse' => $tvResponse
		]);
	}

	public function searchTv(Request $request) {
		$query =  $request->q;
		$page = $request->page;

		$movieResponse = Tmdb::getSearchApi()->searchMovies($query);

		$tvResponse = Tmdb::getSearchApi()->searchTv($query, [
			'page' => $page,
		]);

		$peopleResults = $this->userRepository->skipPresenter()->all();

		$results = $tvResponse['results'];
		foreach ($results as &$result) {
			$reviews = Review::where('reviewable_type', 'tv')->where('reviewable_id', $result['id']);
			$result['vote_count'] = $reviews->count();
			$result['vote_average'] = $reviews->avg('stars');
		}

		JavaScript::put([
			'stars' => array_column($results, 'vote_average')
		]);

		return view('search.tv', [
			'movieResponse' => $movieResponse,
			'query' => $query,
			'peopleResults' => $peopleResults,
			'tvResponse' => $tvResponse,
			'results' => $results,
		]);
	}

	public function discoverMovies(Request $request) {
		$response = Tmdb::getDiscoverApi()->discoverMovies([
			'page' => $request->page,
		]);
		
		$results = $response['results'];
		foreach ($results as &$result) {
			$reviews = Review::where('reviewable_type', 'movie')->where('reviewable_id', $result['id']);
			$result['vote_count'] = $reviews->count();
			$result['vote_average'] = $reviews->avg('stars');
		}

		JavaScript::put([
			'stars' => array_column($results, 'vote_average')
		]);

		return view('discover.movies', [
			'results' => $results,
			'max_pages' => min(5, $response['total_pages']),
			'response' => $response,
		]);
	}
}
