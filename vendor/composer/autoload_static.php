<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7da5ed28fda590b1ced7623ba2e9569a
{
    public static $prefixLengthsPsr4 = array (
        'a' => 
        array (
            'app\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7da5ed28fda590b1ced7623ba2e9569a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7da5ed28fda590b1ced7623ba2e9569a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7da5ed28fda590b1ced7623ba2e9569a::$classMap;

        }, null, ClassLoader::class);
    }
}