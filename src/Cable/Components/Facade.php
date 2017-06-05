<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 05/10/2017
 * Time: 18:07
 */

namespace Cable\Facade;

/*
 * This file belongs to the CableFramework
 *
 * @author vahitserifsaglam <vahit.serif119@gmail.com>
 * @see http://vserifsaglam.com
 *
 * Thanks for using
 */
use Cable\Container\Container;
use Cable\Container\ContainerInterface;

/**
 * Class Facade
 * @package Anonym\Patterns
 */
class Facade
{
    /**
     * the resolved object instances
     *
     * @var array
     */
    protected static $resolvedInstance;


    /**
     * @var Container
     */
    public static $container;

    /**
     * @return Container
     */
    public static function getContainer()
    {
        return self::$container;
    }

    /**
     * @param Container $container
     */
    public static function setContainer(ContainerInterface $container)
    {
        self::$container = $container;
    }

    /**
     * get the facade class
     *
     * @throws FacadeException
     * @return string|Object
     */
    protected static function getFacadeClass()
    {
        throw new FacadeException('i can not call myself');
    }

    /**
     * Resolve the facade root instance from the container.
     *
     * @param  string $name
     * @return mixed
     */
    protected static function resolveFacadeClass($name)
    {
        if (is_object($name)) {
            return $name;
        }
        if (isset(static::$resolvedInstance[$name])) {
            return static::$resolvedInstance[$name];
        }

        return static::$resolvedInstance[$name] = static::$container[$name];
    }


    /**
     * @param $class
     * @param $name
     * @return \ReflectionMethod
     * @throws FacadeException
     */
    private static function checkReturnedIsValidInstance($class, $name)
    {
        try {
            return new \ReflectionMethod($class, $name);
        } catch (\ReflectionException $exception) {
            throw new FacadeException(
                sprintf(
                    'something went wrong with %s facade, message : %s',
                    get_called_class(),
                    $exception->getMessage()
                )
            );
        }
    }

    /**
     * @param string $name
     * @param array $arguments
     * @throws FacadeException
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        $class = static::resolveFacadeClass(
            static::getFacadeClass()
        );

        $method = static::checkReturnedIsValidInstance(
            $class,
            $name
        );

        // resolve the class
        return $method->invokeArgs(
            $class,
            $arguments
        );
    }


}
