<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function index()
    {
        return "version 1.0.0";
    }
}
