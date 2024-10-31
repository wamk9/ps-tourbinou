<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DestinationController extends Controller
{
    public function pageIndex()
    {
        return Inertia::render('Admin/Destination/List');
    }

    public function pageStore()
    {
        return Inertia::render('Admin/Destination/Save');
    }

    public function pageUpdate()
    {
        return Inertia::render('Admin/Destination/Save');
    }
}
