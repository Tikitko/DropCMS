<?php
/*
 *   DropCMS
 *   Ver. 0.1
 *   (c) 2017 Nikita Bykov
 *   index.php
 *
 */
require_once 'engine/Core.class.php';
$core = new Core(__DIR__.'/modules/',__DIR__.'/templates/');
$core->constructor(array(
    'articles.module.php' => array('articles_dir' => 'content/articles/'),
    'gallery.module.php' => array('gallery_dir' => 'content/gallery/'),
    'static.module.php' => array('pages_dir' => 'content/pages/')
));