<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tmdb\Laravel\Facades\Tmdb;

class MoviesController extends Controller
{
    public function popular()
    {
        return response()->json(Tmdb::getDiscoverApi()->discoverMovies()['results']);
    }
}
