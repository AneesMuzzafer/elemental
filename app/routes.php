<?php

use App\Controllers\UserController;
use App\Middlewares\Logger;
use Core\Facade\Route;

Route::get("/", function () {
    return view("Welcome")->withLayout("layouts.DashboardLayout");
});

Route::apiResource("users", UserController::class);
