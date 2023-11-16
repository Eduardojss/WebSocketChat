<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use React\Http\Io\PauseBufferStream;

class TestController extends Controller
{
    public function connect(){
        return Inertia::render('Welcome', [
            'phpVersion' => PHP_VERSION,
        ]);
    }
}
