<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserApiController;

Route::apiResource('users', UserApiController::class);