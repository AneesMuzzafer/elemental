<?php

use Core\Router\Router;

Router::get("/", function () {
    return "Rendered in /";
});

Router::get("/abc", function () {
    return "Rendered in /abc";
});
