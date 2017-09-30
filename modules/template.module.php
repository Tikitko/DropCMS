<?php
/*
 *   DropCMS
 *   Ver. 0.1
 *   (c) 2017 Nikita Bykov
 *   template.module.php
 *
 */
class module
{
    public const MODULE_ID = 0;
    public const MODULE_TITLE = "";
    public const MODULE_DESCRIPTION = "";
    public const MODULE_AUTHOR = "";
    public const MODULE_VERSION = "";
    public $args;
    public function __construct(array $args=array()) {
        $this->args = $args;
    }
    public function main():array {
        $array = PageConstructors::BasePageCreate();
        return (array) $array;
    }
}