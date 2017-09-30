<?php
/*
 *   DropCMS
 *   Ver. 0.1
 *   (c) 2017 Nikita Bykov
 *   Core.class.php
 *
 */
class Core {
    public const TITLE = "DropCMS CORE";
    public const DESCRIPTION = "The main CORE component of the DropCMS";
    public const AUTHOR = "Nikita Bykov";
    public const VERSION = "0.1";

    private const CORE_COMPONENTS_DIR = 'Components/';
    private const CORE_TWIG_DIR = 'Twig/';
    private const CORE_CONFIG_FILE = 'Config.class.php';
    private const CORE_LOCALIZATION_FILE = 'Localization.class.php';
    private const CORE_TWIG_LOADER_FILE = 'Autoloader.php';

    private const L_ERROR = 'error';
    private const L_TEMPLATE = 'template';
    private const L_DATA = 'data';

    private const EXPANSION_PHP = '.php';
    private const EXPANSION_CLASS = '.class.php';
    private const EXPANSION_MODULE = '.module.php';

    private const DIR = __DIR__.DIRECTORY_SEPARATOR;

    private $MODULES_DIR;
    private $TEMPLATES_DIR;

    public function __construct(string $modules_dir='',string $templates_dir='') {
        $this->MODULES_DIR = $modules_dir;
        $this->TEMPLATES_DIR = $templates_dir;
        $config_path = self::DIR.self::CORE_CONFIG_FILE;
        if(is_file($config_path)) require_once $config_path;
        if(!class_exists('Config')) die('Config not loaded!');
        $localization_path = self::DIR.self::CORE_LOCALIZATION_FILE;
        if(is_file($localization_path)) require_once $localization_path;
        if(!class_exists('Localization')) die('Localization not loaded!');
        $twig_loader_path = self::DIR.self::CORE_TWIG_DIR.self::CORE_TWIG_LOADER_FILE;
        if(is_file($twig_loader_path)) require_once $twig_loader_path;
        if(!class_exists('Twig_Autoloader')) die('Twig Loader not loaded!');
        Twig_Autoloader::register();
        spl_autoload_register(function($class){
            $class_path = self::DIR.self::CORE_COMPONENTS_DIR.$class.self::EXPANSION_CLASS;
            if(file_exists($class_path)) include_once $class_path;
        });
		if(empty($this->MODULES_DIR)||!is_dir($this->MODULES_DIR)) die(Localization::ERROR_LOAD_MODULES_DIR);
        if(empty($this->TEMPLATES_DIR)||!is_dir($this->TEMPLATES_DIR)) die(Localization::ERROR_LOAD_TEMPLATES_DIR);
    }

    private function get_module_file():string {
        $get_module = strtolower(isset($_GET[Config::MODULE_GET])?(string)$_GET[Config::MODULE_GET]:'');
        $module_path = $this->MODULES_DIR.$get_module.self::EXPANSION_MODULE;
        if(!empty($get_module)&&is_file($module_path)) $return_module = $get_module.self::EXPANSION_MODULE;
        else $return_module = Config::MODULE_DEFAULT.self::EXPANSION_MODULE;
        return (string) $return_module;
    }

    private function module_sandbox(string $include,array $args=array()) {
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
            if(is_null($output)) $output = array(self::L_ERROR => 2);
            elseif(!is_array($output)) $output = array(self::L_ERROR => 3);
            elseif(!isset($output[self::L_TEMPLATE],$output[self::L_DATA])) $output = array(self::L_ERROR => 4);
            elseif(!is_array($output[self::L_DATA])) $output = array(self::L_ERROR => 5);
        }
        else $output = array(self::L_ERROR => 1);
        return (array) $this->module_error_handler($output);
    }

    private function module_error_handler(array $array):array {
        switch (isset($array[self::L_ERROR])?(int)$array[self::L_ERROR]:-1) {
            case 0: die(Localization::ERROR); break;
            case 1: die(Localization::ERROR_MODULE_OPEN); break;
            case 2: die(Localization::ERROR_MODULE_CONSTRUCTION); break;
            case 3: die(Localization::ERROR_MODULE_OUTPUT_NO_ARRAY); break;
            case 4: die(Localization::ERROR_MODULE_OUTPUT_DATA_1); break;
            case 5: die(Localization::ERROR_MODULE_OUTPUT_DATA_2); break;
        }
        return (array) $array;
    }

    public function constructor(array $modules_args=array()):void {
        $module_file = $this->get_module_file();
        $module_args = isset($modules_args[$module_file])?(array)$modules_args[$module_file]:array();
        $data_array = $this->module_executor($module_file,$module_args);
        $loader = new Twig_Loader_Filesystem($this->TEMPLATES_DIR,Config::TWIG_LOADER_OPTIONS);
        $twig = new Twig_Environment($loader,Config::TWIG_ENVIRONMENT_OPTIONS);
        echo $twig->render($data_array[self::L_TEMPLATE],$data_array[self::L_DATA]);
    }
}