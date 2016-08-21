<?php
/*
 *   DropCMS
 *   Ver. 0.0.3
 *   (c) 2016 Nikita Bykov
 *   Localization.class.php
 *
 */
class Localization
{
    /* CORE LOCALIZATION */
    const ERROR = 'Ошибка!';
    const ERROR_MODULE_OUTPUT_1 = self::ERROR . ' ' . 'Данные, которые вернул модуль, не соответствуют шаблону!';
    const ERROR_MODULE_OUTPUT_2 = self::ERROR . ' ' . 'Внутренние данные не являются массивом!';
    const ERROR_MODULE_CONSTRUCTION = self::ERROR . ' ' . 'Конструкция модуль не соответствует шаблону!';
    const ERROR_MODULE_OPEN = self::ERROR . ' ' . 'Модуль не существует либо не удалось его подключить!';
    const ERROR_MODULE_OUTPUT_NO_ARRAY = self::ERROR . ' ' . 'Данные которые вернул модуль, не являются массивом!';

    const ERROR_LOAD_TEMPLATES_DIR = self::ERROR . ' ' . 'Не удалось подключить дерикторию с шаблонами!';
    const ERROR_LOAD_MODULES_DIR = self::ERROR . ' ' . 'Не удалось подключить дерикторию с модулями!';


    /* OTHER LOCALIZATION */
    const CONSTRUCTOR_ERROR_404_TITLE = 'Ошибка 404!';
    const CONSTRUCTOR_ERROR_404_TEXT = 'Страница не найдена!';

    const CONSTRUCTOR_NAVIGATION_PAGE = 'Страница';
    const CONSTRUCTOR_NAVIGATION_PAGE_START = 'В начало';
    const CONSTRUCTOR_NAVIGATION_PAGE_END = 'В конец';
    const CONSTRUCTOR_NAVIGATION_PAGE_BACK = '<';
    const CONSTRUCTOR_NAVIGATION_PAGE_NEXT = '>';

    const ERROR_ARTICLES_MODULE_LOAD_DIR = 'Не удалось подключить дерикторию со статьями!';
    const ERROR_ARTICLES_MODULE_EMPTY_TITLE = self::ERROR;
    const ERROR_ARTICLES_MODULE_EMPTY_TEXT = 'По данному адресу публикаций на сайте не найдено!';
    const ARTICLES_MODULE_TITLE = 'Статьи';

    const ERROR_GALLERY_MODULE_LOAD_DIR = 'Не удалось подключить дерикторию с галереей!';
    const ERROR_GALLERY_MODULE_EMPTY_TITLE = self::ERROR;
    const ERROR_GALLERY_MODULE_EMPTY_TEXT = 'По данному адресу изображений на сайте не найдено!';
    const GALLERY_MODULE_TITLE = 'Галерея';

    const ERROR_STATIC_MODULE_LOAD_DIR = 'Не удалось подключить дерикторию со страницами!';
}