<?php
require_once('config.php');
require(USERDIR . "/php/Smarty-3.1.11/libs/Smarty.class.php");

class A1Smarty extends Smarty{
    function __construct(){
        parent::__construct();
        $this->template_dir = USERDIR . "/.HTMLinfo/wda/a1_C/views";
        $this->compile_dir = USERDIR . "php/Smarty-Work-Dir/templates_c";
        $this->cache_dir = USERDIR . "php/Smarty-Work-Dir/cache";
        $this->config_dir = USERDIR . "php/Smarty-Work-Dir/configs";
        $this->error_reporting = E_ALL;
    }
}

?>
