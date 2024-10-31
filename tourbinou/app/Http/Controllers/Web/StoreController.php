<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StoreController extends Controller
{
    public function pageIndex()
    {
        return Inertia::render('Store/Home');
    }
}
