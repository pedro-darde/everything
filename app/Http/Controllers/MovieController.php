<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\MovieGender;
use App\Models\TopRatedMovies;
use App\Models\Trending;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MovieController extends Controller
{
    public function index(): \Inertia\Response
    {
        $trending = Trending::with(['movie', 'movie.genders'])
                        ->get()
                        ->pluck('movie');

        $topRated = TopRatedMovies::with(['movie', 'movie.genders'])
            ->get()
            ->pluck('movie');

        return Inertia::render('Movies/Index', [
            'trendings' => $trending,
            'topRated' => $topRated
        ]);
    }

    public function view($movie): \Inertia\Response
    {
        $movie = Movie::with('genders')
            ->findOrFail($movie);

        return Inertia::render('Movies/View', ['movie' => $movie]);
    }

    public function addToWatchlist(Request $request): \Illuminate\Http\RedirectResponse
    {

    }
}
