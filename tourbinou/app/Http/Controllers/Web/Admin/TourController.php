<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class TourController extends Controller
{
    public function pageIndex()
    {
        return Inertia::render('Admin/Tour/List');
    }

    public function pageStore()
    {
        return Inertia::render('Admin/Tour/Save');
    }

    public function pageUpdate()
    {
        return Inertia::render('Admin/Tour/Save');
    }
}
