<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index() {
        $availableSystems = [
            'CSV Validator And Validator' => [
                'description' => 'This system allows you to import CSV files and validate them.',
                'route' => 'validator'
            ],
            'Movies System' => [
                'description' => 'This system allows you to view movies.',
                'route' => 'movies'
            ],
        ];
        return Inertia::render('Root/Index', [
            'availableSystems' => $availableSystems
        ]);
    }
}
