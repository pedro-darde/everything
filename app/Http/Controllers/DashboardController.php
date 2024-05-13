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
            ]
        ];
        return Inertia::render('Root/Index', [
            'availableSystems' => $availableSystems
        ]);
    }
}
