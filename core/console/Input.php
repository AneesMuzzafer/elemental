<?php

namespace Core\Console;

class Input
{
    public array $args = [];

    public function __construct(array $args = [])
    {
        $this->args = array_slice($args, 1);
    }

    public static function read(array $args): static
    {
        return (new static($args));
    }
}
