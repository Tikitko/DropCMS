<?php
/*
 *   DropCMS
 *   Ver. 0.0.2
 *   (c) 2016 Bykov Nikita
 *   index.php
 *
 */
    require_once 'Libs/Loader.function.php'; Loader();

    $core = new Core('modules/','templates/');
    $core->constructor(array(
        'static.module.php'=>array('pages_dir'=>'content/pages/'),
        'articles.module.php'=>array('articles_dir'=>'content/articles/')
    ));