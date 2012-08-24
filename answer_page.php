<?php
require_once("config.php");
require_once('models/Answer.class.php');
require_once('models/Wine.class.php');
require_once('SmartyConfig.php');

session_start();

$smarty = new A1Smarty();
$answer = NULL;
$form_errors = NULL;
$server_error = false;
$table_data = NULL;

try{
    $answer = new Answer();
    $answer->passForm($_GET);
    $answer->validateForm();
    if($form_errors = $answer->getErrors()){
        $_SESSION['errors'] = $form_errors;
        $_SESSION['form'] = $_GET;
        throw new FormException();
    }
    $table_data = $answer->runSearch();
}
catch(FormException $e){
       header("Location: http://yallara.cs.rmit.edu.au/~e02439/wda/a1_C/search_page.php");
}
catch(PDOException $e){
    $server_error = true;
}

if(!$server_error && $table_data && isset($_SESSION['is_tracking'])){
    if(isset($_SESSION['wines']))
        $_SESSION['wines'] = array_merge($_SESSION['wines'], $table_data);
    else
        $_SESSION['wines'] = $table_data;
}


$smarty->assign('server_error', $server_error);
$smarty->assign('table_data', $table_data);

$smarty->display('views/answer.tpl');
?>
