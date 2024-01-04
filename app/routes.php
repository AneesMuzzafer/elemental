<?php

use App\Middlewares\Logger;
use Core\Facade\Route;

Route::get("/", function () {
    return view("Welcome")->withLayout("layouts.DashboardLayout");
});

Route::get("/register", function () {
    return view("Register")->withLayout("layouts.DashboardLayout");
});

Route::get("/login", function () {
    return view("Login")->withLayout("layouts.DashboardLayout");
});
