<?php
/*
 *   DropCMS
 *   Ver. 0.0.4
 *   (c) 2016 Nikita Bykov
 *   PageConstructors.class.php
 *
 */
class PageConstructors
{
    public static function BasePageCreate():array {
        return (array) array(
            'template' => 'main.twig',
            'data' => array(
                'title' => Config::HOME_TITLE,
                'base_url' => Config::HTTP_HOME_URL,
                'description' => Config::DESCRIPTION,
                'keywords' => Config::KEYWORDS
            ));
    }
    public static function BaseLinkCreate():string {
        $get_key = Config::MODULE_GET;
        $main_link = !empty($_GET[$get_key])?(string)'?'.$get_key.'='.$_GET[$get_key].'&':'?';
        return (string) Config::HTTP_HOME_URL.$main_link;
    }
    public static function ClearString(string $string):string {
        return (string) trim(preg_replace(array('/<.*?>/is','/[\s]{2,}/'),' ',$string));
    }
    public static function Error404Add(array &$array,string $section):bool {
        if(isset($array['data'][$section]['title'],$array['data'][$section]['text'])) {
            self::ToTitleAdd($array,Localization::CONSTRUCTOR_ERROR_404_TITLE);
            $array['data'][$section]['title'] = Localization::CONSTRUCTOR_ERROR_404_TITLE;
            $array['data'][$section]['text'] = Localization::CONSTRUCTOR_ERROR_404_TEXT;
            return true;
        }
        return false;
    }
    public static function ToTitleAdd(array &$array,string $string,string $section='',bool $add_head=true):bool {
        $status = false;
        if(isset($array['data']['title'])&&$add_head) {
            $d1 = ' - ';
            $array['data']['title'] = $string.$d1.$array['data']['title'];
            $status = true;
        }
        if(!empty($section)&&isset($array['data'][$section]['title'])) {
            $d2 = '  ';
            $array['data'][$section]['title'] = $string.$d2.$array['data'][$section]['title'];
            $status = true;
        }
        return (bool) $status;
    }
    public static function PagesNavAdd(array &$array,string $section,int $current_page,int $pages_count):bool {
        $status = false;
        $base_link = self::BaseLinkCreate().'page'.'=';
        for($i=1,$y=0,$j=0;$i<=$pages_count;$i++,$y=0) {
            if(!$status) $status = true;
            if($pages_count==1) break;
            if($i==1&&$current_page!=1) {
                $title = Localization::CONSTRUCTOR_NAVIGATION_PAGE.' '.$current_page;
                PageConstructors::ToTitleAdd($array,$title,$section);
            }
            $temp_array=array();
            if($i==1) {
                $temp_array[$y]['active'] = ($pages_count>=2&&$i!=$current_page)?true:false;
                $temp_array[$y]['link'] = $temp_array[$y]['active']?(string)$base_link.$i:'';
                $temp_array[$y]['title'] = Localization::CONSTRUCTOR_NAVIGATION_PAGE_START;
                $y++;
                $temp_array[$y]['active'] = (($current_page-1)>0)?true:false;
                $temp_array[$y]['link'] = $temp_array[$y]['active']?(string)$base_link.($current_page-1):'';
                $temp_array[$y]['title'] = Localization::CONSTRUCTOR_NAVIGATION_PAGE_BACK;
                $y++;
            }
            if($current_page-5<$i&&$current_page+5>$i) {
                $temp_array[$y]['active'] = ($i!=$current_page)?true:false;
                $temp_array[$y]['link'] = $temp_array[$y]['active']?(string)$base_link.$i:'';
                $temp_array[$y]['title'] = $i;
                $y++;
            }
            if($i==$pages_count) {
                $temp_array[$y]['active'] = (($current_page)<$pages_count)?true:false;
                $temp_array[$y]['link'] = $temp_array[$y]['active']?(string)$base_link.($current_page+1):'';
                $temp_array[$y]['title'] = Localization::CONSTRUCTOR_NAVIGATION_PAGE_NEXT;
                $y++;
                $temp_array[$y]['active'] = ($pages_count>=2&&$i!=$current_page)?true:false;
                $temp_array[$y]['link'] = $temp_array[$y]['active']?(string)$base_link.$i:'';
                $temp_array[$y]['title'] = Localization::CONSTRUCTOR_NAVIGATION_PAGE_END;
            }
            foreach($temp_array as $value) {
                $array['data'][$section]['pages_links'][$j]['active'] = $value['active'];
                $array['data'][$section]['pages_links'][$j]['link'] = $value['link'];
                $array['data'][$section]['pages_links'][$j]['title'] = $value['title'];
                $j++;
            }
        }
        return (bool) $status;
    }
}