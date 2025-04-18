<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderIniteff51f0239c822d63f94cafc30a9fd0f
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderIniteff51f0239c822d63f94cafc30a9fd0f', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderIniteff51f0239c822d63f94cafc30a9fd0f', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticIniteff51f0239c822d63f94cafc30a9fd0f::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
