<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings; 
use App\Models\TemporaryFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class CompanyLogoController extends Controller
{
    /**
     * Display the company logo settings page.
     */
    public function __invoke()
    {
        return Inertia::render('Settings/Logo');
    }
}