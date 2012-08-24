<?php
require_once("config.php");
require_once('models/Answer.class.php');
require_once('models/Wine.class.php');
require_once('SmartyConfig.php');

session_start();

$smarty = new A1Smarty();

if(!isset($_SESSION['is_tracking']))
    header('Location: '.$_SERVER['HTTP_REFERER']);

if(!isset($_SESSION['wines']) || !$_SESSION['wines'])
    echo 'You have not viewed any wines yet';

else
    echo 'There are wines to view';

if(isset($_SESSION['wines']) && count($_SESSION['wines']))
    $smarty->assign('table_data', $_SESSION['wines']);
$smarty->display('views/saved_wines.tpl');
?>
