<?php

namespace App\Console\Commands;

use App\Models\Gender;
use App\Services\IMBDService;
use Illuminate\Console\Command;

class ProcessGenderMovie extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-gender-movie';

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
        $result = $service->search('genre/movie/list', [], now()->endOfMonth());
        $this->info('finded ' . count($result['genres']) . ' genres');

        foreach ($result['genres'] as $genre) {
            Gender::firstOrCreate([
                'external_id' => $genre['id']
            ],
            [
                'name' => $genre['name'],
                'external_id' => $genre['id']
            ]
            );
        }
    }
}
