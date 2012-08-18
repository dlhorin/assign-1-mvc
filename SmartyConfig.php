<?php
require_once('config.php');
require(USERDIR . "/php/Smarty-3.1.11/libs/Smarty.class.php");
$smarty = new Smarty();
//$smarty->template_dir = USERDIR . "/php/Smarty-Work-Dir/templates";
$smarty->template_dir = USERDIR . "/.HTMLinfo/wda/a1_C";
$smarty->compile_dir = USERDIR . "/php/Smarty-Work-Dir/templates_c";
$smarty->cache_dir = USERDIR . "/php/Smarty-Work-Dir/cache";
$smarty->config_dir = USERDIR . "/php/Smarty-Work-Dir/configs";
$smarty->error_reporting = E_ALL;

?>
