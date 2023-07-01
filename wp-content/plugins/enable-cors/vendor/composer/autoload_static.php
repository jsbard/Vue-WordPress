<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitenablecors
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'DevKabir\\EnableCors\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'DevKabir\\EnableCors\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'DevKabir\\EnableCors\\Admin' => __DIR__ . '/../..' . '/src/Admin.php',
        'DevKabir\\EnableCors\\Helper' => __DIR__ . '/../..' . '/src/Helper.php',
        'DevKabir\\EnableCors\\Plugin' => __DIR__ . '/../..' . '/src/Plugin.php',
        'DevKabir\\EnableCors\\Setting' => __DIR__ . '/../..' . '/src/Setting.php',
        'DevKabir\\EnableCors\\SingletonTrait' => __DIR__ . '/../..' . '/src/SingletonTrait.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitenablecors::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitenablecors::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitenablecors::$classMap;

        }, null, ClassLoader::class);
    }
}
