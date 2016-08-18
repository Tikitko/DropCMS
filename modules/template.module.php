<?php
/*
 *   DropCMS
 *   Ver. 0.0.2
 *   (c) 2016 Bykov Nikita
 *   template.module.php
 *
 */
    class module
    {
        public $args;
        public function __construct($args = array()) {$this->args = (array)$args;}
        public function main() : array {
            $array = BasePage::create();
            return (array) $array;
        }
    }