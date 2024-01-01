<?php

namespace App\Exceptions;

use Core\Exception\ExceptionHandler;
use Exception;

class Handler extends ExceptionHandler
{
    public function handle(Exception $e)
    {
        // Handle all exceptions here. You can also return a custom response or a view.
    }
}
