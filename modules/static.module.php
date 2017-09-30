<?php
/*
 *   DropCMS
 *   Ver. 0.1
 *   (c) 2017 Nikita Bykov
 *   static.module.php
 *
 */
class module
{
    public const MODULE_ID = 4;
    public const MODULE_TITLE = "Static Module";
    public const MODULE_DESCRIPTION = "Static module for DropCMS";
    public const MODULE_AUTHOR = "Nikita Bykov";
    public const MODULE_VERSION = "0.1";
    public $args;
    public function __construct(array $args=array()) {
        $this->args = $args;
    }
    public function main():array {
        $error_load = Localization::ERROR_STATIC_MODULE_LOAD_DIR;
        if(!isset($this->args['pages_dir'])||!is_dir($this->args['pages_dir'])) die($error_load);
        $pages_dir = $this->args['pages_dir'];
        $get_page = isset($_GET['page'])?(string)$_GET['page']:'';
        $array = PageConstructors::BasePageCreate();
        $array['data']['content_tpl'] = 'static.twig';
        $default_page_path = $pages_dir.Config::STATIC_MODULE_DEFAULT_PAGE.'.json';
        $new_page_path = $pages_dir.$get_page.'.json';
        if(empty($get_page)&&is_file($default_page_path)) $load_page_path = $default_page_path;
        elseif(!empty($get_page)&&is_file($new_page_path)) $load_page_path = $new_page_path;
        else $load_page_path = '';
        if(!empty($load_page_path)) {
            $page_data = json_decode(file_get_contents($load_page_path),true);
            if(!empty($page_data['title'])) PageConstructors::ToTitleAdd($array,$page_data['title']);
            if(!empty($page_data['description'])) $array['data']['description'] = $page_data['description'];
            if(!empty($page_data['keywords'])) $array['data']['keywords'] = $page_data['keywords'];
            $array['data']['static']['title'] = $page_data['page_title'];
            $array['data']['static']['text'] = $page_data['page_text'];
        } else {
			$array['data']['static']['title'] = "";
            $array['data']['static']['text'] = "";
			PageConstructors::Error404Add($array,'static');
		}
        return (array) $array;
    }
}