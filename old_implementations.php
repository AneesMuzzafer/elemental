<?php

//Resolve route Callback / Method from Container with route params passed.
function resolveMethod(array | callable $action, array $args)
{
    $i = 0;
    if (!is_array($action)) {

        $reflectionFunction = new \ReflectionFunction($action);
        $parameters = $reflectionFunction->getParameters();

        if (count($parameters) == 0) {

            return $reflectionFunction->invoke();
        }

        $resolvedParameters = array_map(function ($parameter) use ($args, &$i) {
            $type = $parameter->getType();

            if ($type == null) {
                if ($i < count($args)) {
                    return $args[$i++]["value"];
                }
            } else {
                return $this->make($type->getName());
            }
        }, $parameters);

        return $reflectionFunction->invokeArgs($resolvedParameters);
    } else {

        $object = $action[0];
        $method = $action[1];

        $reflectionMethod = new \ReflectionMethod($object, $method);
        $parameters = $reflectionMethod->getParameters();

        if (count($parameters) == 0) {

            return $reflectionMethod->invoke($object);
        }

        $resolvedParameters = array_map(function ($parameter) {
            $type = $parameter->getType();

            return $this->make($type->getName());
        }, $parameters);

        return $reflectionMethod->invokeArgs($object, $resolvedParameters);
    }
}
