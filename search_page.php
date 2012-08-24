<?php
require_once('config.php');
require_once('models/Search.class.php');
require_once('SmartyConfig.php');

session_start();

$server_error = false;
$smarty = new A1Smarty();
$region_data = $variety_data = $year_data = NULL;
try{
    $search = new Search();

    //Building the data for the 'region' drop-down list
    $region_data = $search->get_regions();

    //Building the data for the 'grape_variety' drop-down list
    $variety_data = $search->get_grape_varieties();

    //Building the data for the 'years' list
    $year_data = $search->get_years();
}
catch(PDOException $e){
    $server_error = true;
}

if(isset($_SESSION['errors']))
    $smarty->assign('errors', $_SESSION['errors']);

if(isset($_SESSION['form']))
    $smarty->assign('form', $_SESSION['form']);

$smarty->assign('server_error', $server_error);
$smarty->assign('regions', $region_data);
$smarty->assign('varieties', $variety_data);
$smarty->assign('years', $year_data);

$smarty->display('search.tpl');

$_SESSION['errors'] = NULL;
$_SESSION['form'] = NULL;
?>
