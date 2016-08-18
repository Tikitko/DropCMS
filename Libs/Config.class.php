<?php
/*
 *   DropCMS
 *   Ver. 0.0.2
 *   (c) 2016 Bykov Nikita
 *   Config.class.php
 *
 */
    class Config
    {
        /* CORE SETTINGS */
        const MODULE_DEFAULT = 'static';
        const MODULE_GET = 'do';
        const MODULE_EXPANSION = '.module.php';

        const TWIG_LOADER_OPTIONS = array();
        const TWIG_ENVIRONMENT_OPTIONS = array();


        /* MAIN SETTINGS */
        const HTTP_HOME_URL = 'http://DropCMS.ru/';
        const HOME_TITLE = 'DropCMS';
        const DESCRIPTION = 'DropCMS - Система управления сайтом';
        const KEYWORDS = 'DropCMS, DropCMS, DropCMS';

        const STATIC_MODULE_DEFAULT_PAGE = 'main';

        const ARTICLES_MODULE_ON_PAGE = 5;
    }