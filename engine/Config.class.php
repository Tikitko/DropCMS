<?php
class Config
{
    /* CORE SETTINGS */
    const MODULE_GET = 'do';
    const MODULE_DEFAULT = 'static';

    const TWIG_LOADER_OPTIONS = array();
    const TWIG_ENVIRONMENT_OPTIONS = array(/* 'cache' => 'content/cache/' */);


    /* OTHER SETTINGS */
    const HTTP_HOME_URL = 'http://DropCMS.ru/';
    const HOME_TITLE = 'DropCMS';
    const DESCRIPTION = 'DropCMS - Система управления сайтом';
    const KEYWORDS = 'DropCMS, DropCMS, DropCMS';

    const ARTICLES_MODULE_ELEMENTS_ON_PAGE = 5;

    const GALLERY_MODULE_ELEMENTS_ON_PAGE = 20;
    const GALLERY_MODULE_EXPANSIONS_OF_IMAGES = array('jpg','png');

    const STATIC_MODULE_DEFAULT_PAGE = 'main';
}