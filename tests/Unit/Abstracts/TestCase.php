<?php

namespace uSIreF\Networking\Unit\Abstracts;

use ReflectionClass, ReflectionMethod;

/**
 * Abstract class for php unit test case.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase {

    /**
     * Returns accesable ReflectionMethod.
     *
     * @param string $className  Class name
     * @param string $methodName Method name
     *
     * @return ReflectionMethod
     */
    protected static function getTestableMethod(string $className, string $methodName): ReflectionMethod {
        $class  = new ReflectionClass($className);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

}