<?php
/*
 *   DropCMS
 *   Ver. 0.0.2
 *   (c) 2016 Bykov Nikita
 *   Core.class.php
 *
 */
    class Core {
        private $MODULES_DIR;
        private $TEMPLATES_DIR;

        private $ERROR = 'error';
        private $TEMPLATE = 'template';
        private $DATA = 'data';

        public function __construct($modules_dir = '',$templates_dir = '')
        {
            $this->MODULES_DIR = $modules_dir;
            $this->TEMPLATES_DIR = $templates_dir;
            if(!class_exists('Config')) die('Config not loaded!');
            if(!class_exists('Localization')) die('Localization not loaded!');
            if(!class_exists('Twig_Autoloader')) die('Twig not loaded!');
            if(empty($this->MODULES_DIR) || !is_dir($this->MODULES_DIR)) die(Localization::ERROR_LOAD_MODULES_DIR);
            if(empty($this->TEMPLATES_DIR) || !is_dir($this->TEMPLATES_DIR)) die(Localization::ERROR_LOAD_TEMPLATES_DIR);
        }

        private function get_module_file(): string {
            $get_module = isset($_GET[Config::MODULE_GET]) ? $_GET[Config::MODULE_GET] : '';
            if(!empty($get_module) && is_file($this->MODULES_DIR.$get_module.Config::MODULE_EXPANSION)) $return_module = $get_module.Config::MODULE_EXPANSION;
            else $return_module = Config::MODULE_DEFAULT.Config::MODULE_EXPANSION;
            return (string) $return_module;
        }

        private function module_sandbox($include='',$args = array()) {
            return (new class((string)$include) {
                public function __construct($i) { include_once $i; }
                public function start($a) { if(method_exists('module','main')) { $module = new module($a); return $module->main(); } return NULL; }
            })->start((array)$args);
        }

        private function module_executor($module_file = '',$module_args = array()): array {
            if(!empty($module_file) && is_file($this->MODULES_DIR.$module_file)) {
                $module_output = $this->module_sandbox($this->MODULES_DIR.$module_file,(array)$module_args);
                if(is_null($module_output)) $module_output = array($this->ERROR => 2);
                elseif(!is_array($module_output)) $module_output = array($this->ERROR => 3);
                elseif(!isset($module_output[$this->TEMPLATE],$module_output[$this->DATA]) || !is_array($module_output[$this->DATA])) $module_output = array($this->ERROR => 4);
            }
            else $module_output = array($this->ERROR => 1);
            return (array) $this->module_error_handler($module_output);
        }

        private function module_error_handler($array = array()) : array {
            if(isset($array[$this->ERROR]))
                switch ($array[$this->ERROR]) {
                    case 0: die(Localization::ERROR); break;
                    case 1: die(Localization::ERROR_MODULE_OPEN); break;
                    case 2: die(Localization::ERROR_MODULE_CONSTRUCTION); break;
                    case 3: die(Localization::ERROR_MODULE_OUTPUT_NO_ARRAY); break;
                    case 4: die(Localization::ERROR_MODULE_OUTPUT); break;
                }
            return (array) $array;
        }

        public function constructor($modules_args = array()) {
            $module_file = $this->get_module_file();
            $data_array = $this->module_executor($module_file,(array)(isset($modules_args[$module_file])?$modules_args[$module_file]:array()));

            // START DEBUG
            echo '<pre>';
            print_r($data_array);
            echo '</pre>';
            // END DEBUG

            Twig_Autoloader::register();
            $loader = new Twig_Loader_Filesystem($this->TEMPLATES_DIR,Config::TWIG_LOADER_OPTIONS);
            $twig = new Twig_Environment($loader,Config::TWIG_ENVIRONMENT_OPTIONS);
            echo $twig->render($data_array[$this->TEMPLATE], $data_array[$this->DATA]);
        }
    }