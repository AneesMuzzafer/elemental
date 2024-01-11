<?php

return [
    "db" => [
        "driver" => getenv("DB_DRIVER") ?? "mysql",
        "host" => getenv("DB_HOST") ?? $_SERVER['SERVER_ADDR'],
        "port" => getenv("DB_PORT") ?? "3306",
        "database" => getenv("DB_DATABASE") ?? "elemental",
        "username" => getenv("DB_USERNAME") ?? "root",
        "password" => getenv("DB_PASSWORD") ?? "",
    ],
];
