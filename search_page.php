<?php
require_once('config.php');
require_once('class.a1.php');
require_once('SmartyConfig.php');

$search = new Search();
$smarty = new A1Smarty();

//Building the data for the 'region' drop-down list
$region_data = $search->get_regions();

//Building the data for the 'grape_variety' drop-down list
$variety_data = $search->get_grape_varieties();


//Building the data for the 'years' list
$year_data = $search->get_years();

$smarty->assign('regions', $region_data);
$smarty->assign('varieties', $variety_data);
$smarty->assign('years', $year_data);

$smarty->display('search.tpl');


?>
