<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;

class AppearanceController extends Controller
{
    /**
     * Show the application's appearance settings.
     */
    public function __invoke()
    {
        return inertia('profile/Appearance');
    }
}
