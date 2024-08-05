<?php

namespace App\Jobs;

use App\Models\Movie;
use App\Services\IMBDService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConsultMovieExtraInfo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Movie $movie)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(IMBDService $service): void
    {
        $movieData = $service->search('movie/' . $this->movie->external_id, [], now()->endOfMonth());

        $this->movie->extra_info = [
            'production_companies' => $movieData['production_companies'],
            'budget' => $movieData['budget'],
            'adult' => $movieData['adult'],
            'production_countries' => $movieData['production_countries'],
            'revenue' => $movieData['revenue'],
            'runtime' => $movieData['runtime'],
            'spoken_languages' => $movieData['spoken_languages'],
            'status' => $movieData['status'],
            'tagline' => $movieData['tagline'],
        ];

        $this->movie->save();

    }
}
