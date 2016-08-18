<?php
/*
 *   DropCMS
 *   Ver. 0.0.2
 *   (c) 2016 Bykov Nikita
 *   articles.module.php
 *
 */
    class module
    {
        public $args;
        public function __construct($args = array()) {$this->args = (array)$args;}
        public function main() : array {
            if(!isset($this->args['articles_dir']) || !is_dir($this->args['articles_dir'])) die(Localization::ERROR_ARTICLES_MODULE_LOAD_DIR);
            $articles_dir = $this->args['articles_dir'];
            $get_article = isset($_GET['a']) ? $_GET['a'] : '';
            $array = PageConstructors::BasePageCreate();
            $array['data']['content_tpl'] = 'articles.tpl';
            $array['data']['title'] = Localization::ARTICLES_MODULE_TITLE.' - '.$array['data']['title'];
            if(!empty($get_article) && is_file($articles_dir.$get_article.'.json')) {
                $array['data']['article']['full'] = true;
                $article_data = json_decode(file_get_contents($articles_dir.$get_article.'.json'),true);
                if(!empty($article_data['title'])) $array['data']['title'] = $article_data['title'].' - '.$array['data']['title'];
                if(!empty($article_data['description'])) $array['data']['description'] = $article_data['description'];
                if(!empty($article_data['keywords'])) $array['data']['keywords'] = $article_data['keywords'];
                $array['data']['article']['time'] = date("Y-m-d H:i",explode('__',$get_article)[0]);
                $array['data']['article']['title'] = $article_data['article_title'];
                $array['data']['article']['text'] = $article_data['article_text'];
            }
            elseif(!empty($get_article) && !is_file($articles_dir.$get_article.'.json')) {
                $array['data']['article']['full'] = true;
                PageConstructors::Error404Add($array,'article');
            }
            elseif(empty($get_article)) {
                $get_page = (isset($_GET['page']) && is_numeric($_GET['page']) && (int)$_GET['page'] > 0) ? (int)$_GET['page'] : 1;
                $base_link = PageConstructors::GetBaseLink();
                $array['data']['article']['full'] = false;
                $array['data']['article']['articles_title'] = Localization::ARTICLES_MODULE_TITLE;
                $files_articles_dir = scandir($articles_dir); $articles_files = array();
                foreach($files_articles_dir as $value) {
                    if(in_array($value,array('.','..')) || is_dir($articles_dir.$value) || substr($value, strrpos($value, '.') + 1) != 'json') continue;
                    $articles_files[] = $value;
                }
                $start_article = ($get_page-1)*Config::ARTICLES_MODULE_ON_PAGE;
                $end_article = (($get_page-1)*Config::ARTICLES_MODULE_ON_PAGE)+Config::ARTICLES_MODULE_ON_PAGE;
                $echo_not_empty = false;
                for($i=$start_article,$j=0;$i<$end_article;$i++) {
                    if(!isset($articles_files[$i])) break;
                    if(!$echo_not_empty) $echo_not_empty = true;
                    $article_data = json_decode(file_get_contents($articles_dir.$articles_files[$i]),true);
                    $array['data']['article']['articles_list'][$j]['time'] = date("Y-m-d H:i",(int)(explode('__',$get_article)[0]));
                    $array['data']['article']['articles_list'][$j]['title'] = $article_data['article_title'];
                    $array['data']['article']['articles_list'][$j]['text'] = $article_data['article_text'];
                    $array['data']['article']['articles_list'][$j]['link'] = $base_link.'a'.'='.str_replace('.json','',$articles_files[$i]);
                    $j++;
                }
                if($echo_not_empty  && $get_page != 1) {
                    $array['data']['title'] = Localization::ARTICLES_MODULE_PAGE.' '.$get_page.' - '.$array['data']['title'];
                    $array['data']['article']['articles_title'] = Localization::ARTICLES_MODULE_PAGE.' '.$get_page.' - '.$array['data']['article']['articles_title'];
                }
                $articles_on_page = count($articles_files) / Config::ARTICLES_MODULE_ON_PAGE;
                $page_count = (int)(count($articles_files) % Config::ARTICLES_MODULE_ON_PAGE == 0 ? $articles_on_page : $articles_on_page + 1);
                for($i=1,$j=0;$i<=$page_count;$i++) {
                    if(!$echo_not_empty) break;
                    if($i==1 && $page_count >= 2 && $i!=$get_page) {
                        $array['data']['article']['articles_pages_links'][$j]['link'] = $base_link.'page'.'='.$i;
                        $array['data']['article']['articles_pages_links'][$j]['title'] = Localization::ARTICLES_MODULE_PAGE_START;
                        $j++;
                        if(($get_page - 1) > 0) {
                            $array['data']['article']['articles_pages_links'][$j]['link'] = $base_link . 'page' . '=' . ($get_page - 1);
                            $array['data']['article']['articles_pages_links'][$j]['title'] = Localization::ARTICLES_MODULE_PAGE_BACK;
                            $j++;
                        }
                    }
                    if($get_page-5 < $i && $get_page+5 > $i) {
                        $array['data']['article']['articles_pages_links'][$j]['link'] = ($i != $get_page) ? ($base_link . 'page' . '=' . $i) : '#';
                        $array['data']['article']['articles_pages_links'][$j]['title'] = $i;
                        $j++;
                    }
                    if($i==$page_count && $page_count >= 2 && $i!=$get_page) {
                        if(($get_page) < $page_count) {
                            $array['data']['article']['articles_pages_links'][$j]['link'] = $base_link . 'page' . '=' . ($get_page + 1);
                            $array['data']['article']['articles_pages_links'][$j]['title'] = Localization::ARTICLES_MODULE_PAGE_NEXT;
                            $j++;
                        }
                        $array['data']['article']['articles_pages_links'][$j]['link'] = $base_link.'page'.'='.$i;
                        $array['data']['article']['articles_pages_links'][$j]['title'] = Localization::ARTICLES_MODULE_PAGE_END;
                        $j++;
                    }
                }
            }
            return (array) $array;
        }
    }