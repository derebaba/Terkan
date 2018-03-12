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
		
		return view('search', [
			'reviewables' => $reviewables, 
			'query' => $query['name'],
			'genre_id' => $genre_id,
			'genres' =>	$genres,
			'max_pages' => min(5, $response['total_pages']),
			'response' => $response,
		]);
	}

	public function search(Request $request) {
		
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
}
