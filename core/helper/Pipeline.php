<?php

namespace Core\Helper;

use Closure;
use Core\Main\Application;

class Pipeline
{

    private array $pipeline;
    private int $index = 0;

    private Closure $next;
    private $thenClosure;

    private mixed $passingObject;

    public function __construct()
    {
    }

    public function pass($passingObject)
    {
        $this->passingObject = $passingObject;
        return $this;
    }

    public function makePipeline($pipes)
    {
        $this->pipeline = is_array($pipes) ? $pipes : func_get_args();
        return $this;
    }

    public function atLastRun(callable $thenClosure)
    {
        $this->thenClosure = $thenClosure;

        $this->next = function ($request) {
            $this->index++;
            return $this->executeInPipeline($request, $this->thenClosure);
        };

        return $this;
    }

    public function execute()
    {
        return $this->executeInPipeline($this->passingObject, $this->thenClosure);
    }

    public function executeInPipeline($request, $thenClosure)
    {
        if ($this->index >= count($this->pipeline)) {
            return $thenClosure($request);
        }

        $class = $this->pipeline[$this->index];

        $pipe = Application::getInstance()->make($class);

        return $pipe->handle($request, $this->next);
    }
}
