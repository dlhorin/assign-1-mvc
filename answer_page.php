<?php
require_once("config.php");
require_once("db.php");
require_once("templates.php");
require_once("useful_functions.php");
require_once('class.a1.php');

$answer = new Answer();
$error_array = NULL;
$table_data = NULL;

try{
    $answer->passForm($_GET);
}catch(Exception $e){
   header("Location: http://yallara.cs.rmit.edu.au/~e02439/wda/a1_B/search_page.php");
}

if($answer->isValidForm())
    $table_data = $answer->runSearch();

$error_array = $answer->getErrors();

?>

<html>
<head><title>WDA Assignment 1, Part B</title></head>

<body>

<h1>WDA Assignmen1 1, Part B</h1>
<h3>Daisy Horin 5 August, 2012</h3>
<br />


<?php

if($error_array && count($error_array)){
    foreach($error_array as $key=>$error)
        echo $error."<br/>\n";
}

else{
?>
    <h4>Your Search Results</h4>
<?php
    generate_table($table_data, "results");
}
?>

<form action="search_page.php" method="get">
<input type="submit" value="Back to Search Page"/>
</form>

</body>
</html>



