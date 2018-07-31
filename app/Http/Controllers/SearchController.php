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

	public function searchAutocomplete(Request $request) {
		$query =  $request->search;

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
		$query =  $request->search;
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
		$query =  $request->search;
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
		$query =  $request->search;
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
			'with_original_language' => $request->languageCode
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
			'request' => $request,
			'results' => $results,
			'route' => 'movies',
			'max_pages' => min(5, $response['total_pages']),
			'response' => $response,
		]);
	}

	public function discoverTv(Request $request) {
		$response = Tmdb::getDiscoverApi()->discoverTv([
			'page' => $request->page,
			'with_original_language' => $request->languageCode
		]);
		
		$results = $response['results'];
		foreach ($results as &$result) {
			$reviews = Review::where('reviewable_type', 'tv')->where('reviewable_id', $result['id']);
			$result['vote_count'] = $reviews->count();
			$result['vote_average'] = $reviews->avg('stars');
		}

		JavaScript::put([
			'stars' => array_column($results, 'vote_average')
		]);

		return view('discover.tv', [
			'request' => $request,
			'results' => $results,
			'route' => 'TV',
			'max_pages' => min(5, $response['total_pages']),
			'response' => $response,
		]);
	}
}
