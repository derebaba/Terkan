<?php

namespace App\Http\Controllers;

use App\Review;
use App\Traits\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Utilities\JavaScript\JavaScriptFacade as JavaScript;
use Tmdb\Laravel\Facades\Tmdb;

class SearchController extends Controller
{
	use Utils;

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
/*
		$tvs = Tmdb::getDiscoverApi()->discoverTV([
			'page' => $page,
			'with_genres' => $genre_id,
			'sort_by' => 'popularity.desc'
		])['results'];
		foreach ($tvs as &$tv)
			$tv['media_type'] = 'tv';
			

		$results = array_merge($movies, $tvs);

		usort($results, function ($item1, $item2) {
			return $item2['popularity'] <=> $item1['popularity'];
		});*/
		
		$reviewables = $this->getReviewablesFromResults($movies);	// $results
		//dd($reviewables);
		JavaScript::put([
			'stars' => $reviewables->pluck('vote_average')
		]);

		$query = Tmdb::getGenresApi()->getGenre($genre_id);

		$genres = Tmdb::getGenresApi()->getGenres();
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

	public function oldsearch(Request $request) {
		
		$query =  $request->q;
		$page = $request->page;
		//dd($query);
		$response = Tmdb::getSearchApi()->searchMulti($query);
		$results = $response['results'];
		$reviewables = $this->getReviewablesFromResults($results);

		JavaScript::put([
			'stars' => $reviewables->pluck('vote_average')
		]);
		
		$genres = Tmdb::getGenresApi()->getGenres();
		$genres = collect($genres['genres']);
		
		return view('search', [
			'reviewables' => $reviewables, 
			'query' => $query, 
			'genre_id' => -1,
			'genres' =>	$genres,
			'max_pages' => min(5, $response['total_pages']),
			'response' => $response
		]);
	}

	public function searchMovies(Request $request) {
		$query =  $request->q;
		$page = $request->page;

		$response = Tmdb::getSearchApi()->searchMovies($query, [
			'page' => $page,
		]);

		$tvResponse = Tmdb::getSearchApi()->searchTv($query);

		$results = $response['results'];
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
			'response' => $response,
			'results' => $results,
			'tvResponse' => $tvResponse
		]);
	}

	public function searchTv(Request $request) {
		$query =  $request->q;
		$page = $request->page;

		$movieResponse = Tmdb::getSearchApi()->searchMovies($query);

		$response = Tmdb::getSearchApi()->searchTv($query, [
			'page' => $page,
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

		return view('search.tv', [
			'movieResponse' => $movieResponse,
			'query' => $query,
			'response' => $response,
			'results' => $results,
		]);
	}
}
