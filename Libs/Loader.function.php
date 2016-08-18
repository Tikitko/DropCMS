<?php
/*
 *   DropCMS
 *   Ver. 0.0.2
 *   (c) 2016 Bykov Nikita
 *   Loader.function.php
 *
 */
    function Loader()
    {
        require_once __DIR__ . '/Config.class.php';
        require_once __DIR__ . '/Localization.class.php';
        require_once __DIR__ . '/Twig/Autoloader.php';
        require_once __DIR__ . '/Core.class.php';

        spl_autoload_register(
            function ($class) {
                if (0 !== strpos($class, 'Config') && 0 !== strpos($class, 'Localization') && 0 !== strpos($class, 'Twig') && 0 !== strpos($class, 'Core'))
                    include_once __DIR__ . '/Components/' . $class . '.class.php';
            }
        );
    }