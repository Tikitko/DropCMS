<?php
/*
 *   DropCMS
 *   Ver. 0.0.3
 *   (c) 2016 Nikita Bykov
 *   Core.class.php
 *
 */
class Core {
    private $MODULES_DIR;
    private $TEMPLATES_DIR;

    private $ERROR = 'error';
    private $TEMPLATE = 'template';
    private $DATA = 'data';

    public function __construct(string $modules_dir='',string $templates_dir='') {
        $this->MODULES_DIR = $modules_dir;
        $this->TEMPLATES_DIR = $templates_dir;
        require_once __DIR__.'/Config.class.php';
        require_once __DIR__.'/Localization.class.php';
        // require_once __DIR__.'/Twig/Autoloader.php';
        if(!class_exists('Config')) die('Config not loaded!');
        if(!class_exists('Localization')) die('Localization not loaded!');
        // if(!class_exists('Twig_Autoloader')) die('Twig not loaded!');
        if(empty($this->MODULES_DIR)||!is_dir($this->MODULES_DIR)) die(Localization::ERROR_LOAD_MODULES_DIR);
        if(empty($this->TEMPLATES_DIR)||!is_dir($this->TEMPLATES_DIR)) die(Localization::ERROR_LOAD_TEMPLATES_DIR);
        spl_autoload_register(function($class){
            if(0===strpos($class,'Twig')) {
                $class_file_path = __DIR__.'/'.str_replace(array('_',"\0"),array('/',''),$class).'.php';
                if(file_exists($class_file_path)) include_once $class_file_path;
            } else {
                $class_file_path = __DIR__.'/Components/'.$class.'.class.php';
                if(file_exists($class_file_path)) include_once $class_file_path;
            }
        });
    }

    private function get_module_file():string {
        $get_module = isset($_GET[Config::MODULE_GET])?$_GET[Config::MODULE_GET]:'';
        $module_path = $this->MODULES_DIR.$get_module.Config::MODULE_EXPANSION;
        if(!empty($get_module)&&is_file($module_path)) $return_module = $get_module.Config::MODULE_EXPANSION;
        else $return_module = Config::MODULE_DEFAULT.Config::MODULE_EXPANSION;
        return (string) $return_module;
    }

    private function module_sandbox(string $include, array $args=array()) {
        return (new class($include) {
            public function __construct($i) { include_once $i; }
            public function start($a) {
                if(method_exists('module','main')) return (new module($a))->main();
                return NULL;
            }
        })->start($args);
    }

    private function module_executor(string $module_file,array $module_args=array()):array {
        if(is_file($this->MODULES_DIR.$module_file)) {
            $output = $this->module_sandbox($this->MODULES_DIR.$module_file,$module_args);
            if(is_null($output)) $output = array($this->ERROR => 2);
            elseif(!is_array($output)) $output = array($this->ERROR => 3);
            elseif(!isset($output[$this->TEMPLATE],$output[$this->DATA])) $output = array($this->ERROR => 4);
            elseif(!is_array($output[$this->DATA])) $output = array($this->ERROR => 5);
        }
        else $output = array($this->ERROR => 1);
        return (array) $this->module_error_handler($output);
    }

    private function module_error_handler(array $array):array {
        switch (isset($array[$this->ERROR])?$array[$this->ERROR]:-1) {
            case 0: die(Localization::ERROR); break;
            case 1: die(Localization::ERROR_MODULE_OPEN); break;
            case 2: die(Localization::ERROR_MODULE_CONSTRUCTION); break;
            case 3: die(Localization::ERROR_MODULE_OUTPUT_NO_ARRAY); break;
            case 4: die(Localization::ERROR_MODULE_OUTPUT_1); break;
            case 5: die(Localization::ERROR_MODULE_OUTPUT_2); break;
        }
        return (array) $array;
    }

    public function constructor(array $modules_args=array()):void {
        $module_file = $this->get_module_file();
        $module_args = isset($modules_args[$module_file])?$modules_args[$module_file]:array();
        $data_array = $this->module_executor($module_file, $module_args);
        // Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem($this->TEMPLATES_DIR,Config::TWIG_LOADER_OPTIONS);
        $twig = new Twig_Environment($loader,Config::TWIG_ENVIRONMENT_OPTIONS);
        echo $twig->render($data_array[$this->TEMPLATE],$data_array[$this->DATA]);
    }
}