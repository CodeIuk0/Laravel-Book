<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthFromApi extends Controller
{
    static function GetUser()
    {
        return response()->json(Auth::check() ? User::with("library","books","comments")->find(Auth::user()->id) : null);
    }
}
