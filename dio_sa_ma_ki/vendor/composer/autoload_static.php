<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd04a6a4216eec9684d7a430e94632a78
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'M' => 
        array (
            'Monorganisation\\Emailproject\\' => 29,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Monorganisation\\Emailproject\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd04a6a4216eec9684d7a430e94632a78::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd04a6a4216eec9684d7a430e94632a78::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitd04a6a4216eec9684d7a430e94632a78::$classMap;

        }, null, ClassLoader::class);
    }
}
