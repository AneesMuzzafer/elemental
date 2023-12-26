<?php

namespace Core\Interfaces;

interface Viewable
{
    public function view($view, $data = []);
}
