<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function pageLogin()
    {
        return Inertia::render('Admin/Login');
    }

    public function pageLogout()
    {

    }
}
