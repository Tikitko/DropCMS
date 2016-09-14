<?php
/*
 *   DropCMS
 *   Ver. 0.0.4
 *   (c) 2016 Nikita Bykov
 *   articles.module.php
 *
 */
class module
{
    public const MODULE_ID = 1;
    public const MODULE_TITLE = "Articles Module";
    public const MODULE_DESCRIPTION = "Articles module for DropCMS";
    public const MODULE_AUTHOR = "Nikita Bykov";
    public const MODULE_VERSION = "0.0.4";
    public $args;
    public function __construct(array $args=array()) {
        $this->args = $args;
    }
    public function main():array {
        $error_load = Localization::ERROR_ARTICLES_MODULE_LOAD_DIR;
        if(!isset($this->args['articles_dir'])||!is_dir($this->args['articles_dir'])) die($error_load);
        $articles_dir = $this->args['articles_dir'];
        $get_article = isset($_GET['a'])?(string)$_GET['a']:'';
        $base_link = PageConstructors::BaseLinkCreate();
        $array = PageConstructors::BasePageCreate();
        $array['data']['content_tpl'] = 'articles.twig';
        PageConstructors::ToTitleAdd($array,Localization::ARTICLES_MODULE_TITLE);
        $relevant_files = glob($articles_dir.'*__'.str_replace(array('*','__'),'',$get_article).'.json');
        $article_file = !empty($get_article)?(string)basename(current($relevant_files)):'';
        if(!empty($article_file)&&is_file($articles_dir.$article_file)) {
            $article_data = json_decode(file_get_contents($articles_dir.$article_file),true);
            if(!empty($article_data['title'])) PageConstructors::ToTitleAdd($array,$article_data['title']);
            if(!empty($article_data['description'])) $array['data']['description'] = $article_data['description'];
            if(!empty($article_data['keywords'])) $array['data']['keywords'] = $article_data['keywords'];
            $array['data']['article']['full'] = true;
            $array['data']['article']['time'] = date("Y-m-d H:i",explode('__',$article_file)[0]);
            $array['data']['article']['title'] = $article_data['article_title'];
            $array['data']['article']['text'] = $article_data['article_text'];
        } elseif(!empty($article_file)&&!is_file($articles_dir.$article_file)) {
            $array['data']['article']['full'] = true;
            PageConstructors::Error404Add($array,'article');
        } elseif(empty($article_file)) {
            $get_page = isset($_GET['page'])?(int)$_GET['page']:1;
            $on_page = Config::ARTICLES_MODULE_ELEMENTS_ON_PAGE;
            $articles_dir_all_files = scandir($articles_dir);
            $articles_files = array();
            foreach($articles_dir_all_files as $value) {
                if(in_array($value,array('.','..'))||is_dir($articles_dir.$value)) continue;
                if(substr($value,strrpos($value,'.')+1)!='json') continue;
                $articles_files[] = $value;
            }
            $start_article = ($get_page-1)*$on_page;
            $end_article = $start_article+$on_page;
            for($i=$start_article,$j=0,$not_empty=false;$i<$end_article&&isset($articles_files[$i]);$i++) {
                if($i==$start_article) {
                    $not_empty = true;
                    $array['data']['article']['full'] = false;
                    $array['data']['articles']['title'] = Localization::ARTICLES_MODULE_TITLE;
                }
                $article_data = json_decode(file_get_contents($articles_dir.$articles_files[$i]),true);
                $timestamp = (int)(explode('__',$article_file)[0]);
                $title = substr(PageConstructors::ClearString($article_data['article_title']),0,150);
                $text = substr(PageConstructors::ClearString($article_data['article_text']),0,400).'...';
                $main_link = 'a'.'='.preg_replace(array('/.json/','/.*?__/'),'',$articles_files[$i]);
                $array['data']['articles']['list'][$j]['time'] = date("Y-m-d H:i",$timestamp);
                $array['data']['articles']['list'][$j]['title'] = $title;
                $array['data']['articles']['list'][$j]['text'] = $text;
                $array['data']['articles']['list'][$j]['link'] = $base_link.$main_link;
                $j++;
                if($i==($end_article-1)||!isset($articles_files[$i+1])){
                    $pages_temp = count($articles_files)/$on_page;
                    $pages_count = (int)(count($articles_files)%$on_page==0?$pages_temp:$pages_temp+1);
                    PageConstructors::PagesNavAdd($array,'articles',$get_page,$pages_count);
                }
            }
            if(!$not_empty) {
                $array['data']['article']['full'] = true;
                $array['data']['article']['title'] = Localization::ERROR_ARTICLES_MODULE_EMPTY_TITLE;
                $array['data']['article']['text'] = Localization::ERROR_ARTICLES_MODULE_EMPTY_TEXT;
            }
        }
        return (array) $array;
    }
}