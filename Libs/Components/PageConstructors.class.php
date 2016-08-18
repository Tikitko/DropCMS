<?php
/*
 *   DropCMS
 *   Ver. 0.0.2
 *   (c) 2016 Bykov Nikita
 *   PageConstructors.class.php
 *
 */
    class PageConstructors
    {
        public static function BasePageCreate() : array {
            return (array) array(
                'template' => 'main.tpl',
                'data' => array(
                    'title' => Config::HOME_TITLE,
                    'base_url' => Config::HTTP_HOME_URL,
                    'description' => Config::DESCRIPTION,
                    'keywords' => Config::KEYWORDS
                ));
        }
        public static function Error404Add(&$array,$section) : bool {
            if(!empty($array) && !empty($section) && is_array($array)) {
                $array['data']['title'] = Localization::ERROR_404_TITLE . (isset($array['data']['title']) ? ' - ' . $array['data']['title'] : '');
                $array['data'][$section]['title'] = Localization::ERROR_404_TITLE;
                $array['data'][$section]['text'] = Localization::ERROR_404_TEXT;
                return true;
            }
            return false;
        }
        public static function HTMLtoString($string) : string {
            return (string) preg_replace(array('/<.*?>/is','/[\s]{2,}/'), array('',' '), (string)$string);
        }
        public static function GetBaseLink() : string {
            return Config::HTTP_HOME_URL.((isset($_GET[Config::MODULE_GET]) && !empty($_GET[Config::MODULE_GET]))?('?'.Config::MODULE_GET.'='.$_GET[Config::MODULE_GET].'&'):'?');
        }
    }