<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticIniteff51f0239c822d63f94cafc30a9fd0f
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Newspack_Block_Theme\\Core' => __DIR__ . '/../..' . '/includes/class-core.php',
        'Newspack_Block_Theme\\Jetpack' => __DIR__ . '/../..' . '/includes/class-jetpack.php',
        'Newspack_Block_Theme\\Patterns' => __DIR__ . '/../..' . '/includes/class-patterns.php',
        'Newspack_Block_Theme\\Subtitle_Block' => __DIR__ . '/../..' . '/includes/blocks/subtitle-block/class-subtitle-block.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticIniteff51f0239c822d63f94cafc30a9fd0f::$classMap;

        }, null, ClassLoader::class);
    }
}
