<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show() {
        return Inertia::render('User/Show');
    }
}
