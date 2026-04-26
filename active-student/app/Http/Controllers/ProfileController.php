<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function updateTheme(Request $request)
    {
        $request->validate(['theme' => 'required|in:light,dark']);
        Auth::user()->update(['theme' => $request->theme]);
        return response()->noContent();
    }
}