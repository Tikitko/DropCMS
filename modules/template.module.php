<?php
/*
 *   DropCMS
 *   Ver. 0.0.3
 *   (c) 2016 Nikita Bykov
 *   template.module.php
 *
 */
class module
{
    public $args;
    public function __construct(array $args=array()) {$this->args = $args;}
    public function main():array {
        $array = PageConstructors::BasePageCreate();
        return (array) $array;
    }
}