<?php
/*
 *   DropCMS
 *   Ver. 0.0.3
 *   (c) 2016 Nikita Bykov
 *   index.php
 *
 */
if(PHP_VERSION_ID >= 70100) {
    require_once 'Libs/Core.class.php';
    $core = new Core('modules/', 'templates/');
    $core->constructor(array(
        'articles.module.php' => array('articles_dir' => 'content/articles/'),
        'gallery.module.php' => array('gallery_dir' => 'content/gallery/'),
        'static.module.php' => array('pages_dir' => 'content/pages/')
    ));
} else die('Version of PHP does not match!');