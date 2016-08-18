<?php
/*
 *   DropCMS
 *   Ver. 0.0.2
 *   (c) 2016 Bykov Nikita
 *   Localization.class.php
 *
 */
    class Localization
    {
        /* CORE LOCALIZATION */
        const ERROR = 'Ошибка!';
        const ERROR_MODULE_OUTPUT = self::ERROR . ' ' . 'Данные, которые вернул модуль, не соответствуют шаблону!';
        const ERROR_MODULE_CONSTRUCTION = self::ERROR . ' ' . 'Конструкция модуль не соответствует шаблону!';
        const ERROR_MODULE_OPEN = self::ERROR . ' ' . 'Модуль не существует либо не удалось его подключить!';
        const ERROR_MODULE_OUTPUT_NO_ARRAY = self::ERROR . ' ' . 'Данные которые вернул модуль, не являются массивом!';

        const ERROR_LOAD_TEMPLATES_DIR = self::ERROR . ' ' . 'Не удалось подключить дерикторию с шаблонами!';
        const ERROR_LOAD_MODULES_DIR = self::ERROR . ' ' . 'Не удалось подключить дерикторию с модулями!';


        /* MAIN LOCALIZATION */
        const ERROR_404_TITLE = 'Ошибка 404!';
        const ERROR_404_TEXT = 'Страница не найдена!';

        const ERROR_STATIC_MODULE_LOAD_DIR = 'Не удалось подключить дерикторию со страницами!';

        const ERROR_ARTICLES_MODULE_LOAD_DIR = 'Не удалось подключить дерикторию с новостями!';
        const ARTICLES_MODULE_TITLE = 'Статьи';
        const ARTICLES_MODULE_PAGE = 'Страница';
        const ARTICLES_MODULE_PAGE_START = 'В начало';
        const ARTICLES_MODULE_PAGE_END = 'В конец';
        const ARTICLES_MODULE_PAGE_BACK = 'Назад';
        const ARTICLES_MODULE_PAGE_NEXT = 'Вперед';
    }