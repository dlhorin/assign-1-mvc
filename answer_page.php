<?php
require_once("config.php");
require_once("templates.php");
require_once('class.a1.php');
require_once('SmartyConfig.php');

$answer = new Answer();
$form_errors = NULL;
$server_error = false;
$table_data = NULL;

try{
    $answer->passForm($_GET);
}catch(Exception $e){
   header("Location: http://yallara.cs.rmit.edu.au/~e02439/wda/a1_B/search_page.php");
}

try{
    if($answer->isValidForm())
        $table_data = $answer->runSearch();
}catch(Exception $e){
    $server_error = true;
}

$form_errors = $answer->getErrors();

$smarty->assign('server_error', $server_error);
$smarty->assign('form_errors', $form_errors);
$smarty->assign('table_data', $table_data);

$smarty->display('answer.tpl');

?>



