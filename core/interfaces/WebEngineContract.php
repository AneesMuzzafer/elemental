<?php

namespace Core\Interfaces;

use Core\Request\Request;
use Core\Response\Response;

interface WebEngineContract
{
    public function run(Request $request): Response;
}
