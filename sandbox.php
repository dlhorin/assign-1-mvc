<?php
require_once("Smarty_config.php");

//$smarty = new Smarty_config();
$smarty = new Smarty();
$smarty->error_reporting = E_ALL;
$smarty->template_dir = USERDIR . ".HTMLinfo/";
$smarty->compile_dir= SMARTYDIR . "templates_c/";
$smarty->config_dir= SMARTYDIR . "configs/";
$smarty->cache_dir= SMARTYDIR . "cache/";
$smarty->assign('name', 'Daisy');
//require_once('/home/staff/e02439/.HTMLinfo/first_template.tpl');
$smarty->display('/home/staff/e02439/.HTMLinfo/first_template.tpl');
echo "end";

?>
