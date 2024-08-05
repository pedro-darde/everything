<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IMBDService
{
    private string $baseUrl = 'https://api.themoviedb.org/3/';

    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('app.IMDB_KEY');
    }

    public function search(string $url, array $params, Carbon $keepUntil)
    {
        $params = http_build_query($params);
        $url = $this->baseUrl . $url . '?api_key=' . $this->apiKey . '&' . $params;

        if (Cache::has($url)) {
            Log::info('Cache hit for ' . $url);
            return Cache::get($url);
        }

        $response = Http::get($url)->throw()->json();

        Log::info('Cache miss for ' . $url, [$response]);

        Cache::put($url, $response, $keepUntil);

        return $response;
    }

    public function searchTrendingWeek($pagesToFetch = 1) {
        $endOfWeek = Carbon::now()->endOfWeek();
        $keepUntil = $endOfWeek->addDay();

        if ($pagesToFetch > 1) {
            $results = [];
            for ($i = 1; $i <= $pagesToFetch; $i++) {
                $data = $this->search('trending/movie/week', [
                    'language' => 'en-US',
                    'page' => $i,
                ], $keepUntil);

                $results = array_merge($results, $data['results']);
            }
            return $results;
        }

        $data = $this->search('trending/movie/week', [
            'language' => 'en-US',
        ], $keepUntil);

        return $data['results'];
    }

    public function searchTopRated($pagesToFetch = 1) {
        $keepUntil = Carbon::now()->endOfMonth();

        if ($pagesToFetch > 1) {
            $results = [];
            for ($i = 1; $i <= $pagesToFetch; $i++) {
                $data = $this->search('movie/top_rated', [
                    'language' => 'en-US',
                    'page' => $i,
                ], $keepUntil);

                $results = array_merge($results, $data['results']);
            }
            return $results;
        }

        $data = $this->search('movie/top_rated', [
            'language' => 'en-US',
        ], $keepUntil);

        return $data['results'];
    }
}
