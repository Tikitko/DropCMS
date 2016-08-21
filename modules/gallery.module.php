<?php
/*
 *   DropCMS
 *   Ver. 0.0.3
 *   (c) 2016 Nikita Bykov
 *   gallery.module.php
 *
 */
class module
{
    public $args;
    public function __construct(array $args=array()) {$this->args = $args;}
    public function main():array {
        $error_load = Localization::ERROR_GALLERY_MODULE_LOAD_DIR;
        if(!isset($this->args['gallery_dir'])||!is_dir($this->args['gallery_dir'])) die($error_load);
        $gallery_dir = $this->args['gallery_dir'];
        $get_page = isset($_GET['page'])?(int)$_GET['page']:1;
        $array = PageConstructors::BasePageCreate();
        $array['data']['content_tpl'] = 'gallery.twig';
        PageConstructors::ToTitleAdd($array,Localization::GALLERY_MODULE_TITLE);
        $on_page = Config::GALLERY_MODULE_ELEMENTS_ON_PAGE;
        $gallery_dir_all_files = scandir($gallery_dir);
        $images_files = array();
        foreach($gallery_dir_all_files as $value) {
            if(in_array($value,array('.','..'))||is_dir($gallery_dir.$value)) continue;
            $file_expansion = substr($value,strrpos($value,'.')+1);
            if(!in_array($file_expansion,Config::GALLERY_MODULE_EXPANSIONS_OF_IMAGES)) continue;
            $images_files[] = $value;
        }
        $start_image = ($get_page-1)*$on_page;
        $end_image = $start_image+$on_page;
        for($i=$start_image,$j=0,$not_empty=false;$i<$end_image&&isset($images_files[$i]);$i++) {
            if($i==$start_image) {
                $not_empty = true;
                $array['data']['images']['title'] = Localization::GALLERY_MODULE_TITLE;
            }
            $array['data']['images']['list'][$j]['link'] = Config::HTTP_HOME_URL.$gallery_dir.$images_files[$i];
            $array['data']['images']['list'][$j]['title'] = $images_files[$i];
            $j++;
            if($i==($end_image-1)||!isset($images_files[$i+1])){
                $pages_temp = count($images_files)/$on_page;
                $pages_count = (int)(count($images_files)%$on_page==0?$pages_temp:$pages_temp+1);
                PageConstructors::PagesNavAdd($array,'images',$get_page,$pages_count);
            }
        }
        if(!$not_empty) {
            $array['data']['images']['title'] = Localization::ERROR_GALLERY_MODULE_EMPTY_TITLE;
            $array['data']['images']['text'] = Localization::ERROR_GALLERY_MODULE_EMPTY_TEXT;
        }
        return (array) $array;
    }
}