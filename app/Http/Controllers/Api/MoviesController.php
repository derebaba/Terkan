<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tmdb\Laravel\Facades\Tmdb;

class MoviesController extends BaseController
{
    public function popular()
    {
        return $this->sendResponse(Tmdb::getDiscoverApi()->discoverMovies()['results']);
    }
}
