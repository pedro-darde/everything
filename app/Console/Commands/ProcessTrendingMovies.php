<?php

namespace App\Console\Commands;

use App\Jobs\ConsultMovieExtraInfo;
use App\Models\Gender;
use App\Models\Movie;
use App\Models\MovieGender;
use App\Models\Trending;
use App\Services\IMBDService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessTrendingMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-trending-movies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';



    /**
     * Execute the console command.
     */
    public function handle(IMBDService $service)
    {
        $this->info('Processing trending movies');

        $result = $service->searchTrendingWeek(4);

        try {
            Trending::query()->truncate();
            DB::beginTransaction();
            foreach ($result as $movie) {
                $movieModel = Movie::firstOrCreate([
                    'external_id' => $movie['id']
                ],
                    $movie
                );

                Trending::firstOrCreate([
                    'movie_id' => $movieModel->id
                ],
                    [
                        'movie_id' => $movieModel->id
                    ]
                );

                foreach ($movie['genre_ids'] as $genderId) {
                    $genderDatabase = Gender::query()
                        ->where('external_id', $genderId)
                        ->first();

                    MovieGender::firstOrCreate([
                        'movie_id' => $movieModel->id,
                        'gender_id' => $genderDatabase->id
                    ],
                        [
                            'movie_id' => $movieModel->id,
                            'gender_id' => $genderDatabase->id
                        ]
                    );
                }

                ConsultMovieExtraInfo::dispatch($movieModel);
            }
            DB::commit();
            $this->info('Processed ' . count($result) . ' movies');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error processing movies: ' . $e->getMessage());
        }

        return 0;
    }
}
