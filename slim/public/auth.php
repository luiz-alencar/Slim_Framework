<?php

namespace public;

use Tuupola\Middleware\HttpBasicAuthentication;

function auth(): HttpBasicAuthentication{

    return new HttpBasicAuthentication([
        "users" => [
            "root" => "123456"
        ]
        ]);
}