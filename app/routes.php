<?php

use App\Middlewares\Logger;
use Core\Facade\Route;

Route::get("/", function () {
    return view("Welcome")->withLayout("layouts.DashboardLayout");
});
