<?php
require_once("config.php");
require_once("db.php");
require_once("templates.php");
require_once("useful_functions.php");

//Refer back to the search page if not sent from there

if(!isset($_GET["wine_name"]))
   header("Location: http://yallara.cs.rmit.edu.au/~e02439/wda/a1_B/search_page.php");


//Extract and clean the 'GET' values

$wine_name = db_clean($_GET["wine_name"]);
$winery_name = db_clean($_GET["winery_name"]);
$region_id = db_clean($_GET["region"]);
$variety_id = db_clean($_GET["grape_variety"]);
$year_min = db_clean($_GET["year_min"]);
$year_max = db_clean($_GET["year_max"]);
$min_on_hand = db_clean($_GET["min_on_hand"]);
$min_ordered = db_clean($_GET["min_ordered"]);
$cost_min = db_clean($_GET["cost_min"]);
$cost_max = db_clean($_GET["cost_max"]);




$error_array = array();
$test = false;

//Test for errors

if( !is_int_string($year_min) ){
    array_push($error_array, "<i>Start Year</i> is not a valid year");
    $test = true;
}

if( !is_int_string($year_max) ){
    array_push($error_array, "<i>End Year</i> is not a valid year");
    $test = true;
}


//If we have two integer year values, do sanity check: start <= end
if(!$test && $year_min!="" && $year_max!=""){
    if( ((int) $year_min) > ((int) $year_max) )
        array_push($error_array, "Start Year is greater than End Year");
}


if( !is_int_string($region_id) )
    array_push($error_array, "<i>Region ID</i> should be an integer");

if( !is_int_string($variety_id) )
    array_push($error_array, "<i>Variety ID</i> should be an integer");

if( !is_int_string($min_on_hand) )
    array_push($error_array, "<i>Minimum amount in stock</i> should be an integer");

if( !is_int_string($min_ordered) )
    array_push($error_array, "<i>Minimum amount ordered</i> should be an integer");


$test = false;
if( $cost_min!="" && !is_numeric($cost_min) ){
    array_push($error_array, "<i>Minimum Price</i> is not a valid monetary value");
    $test = true;
}

if( $cost_max!="" && !is_numeric($cost_max) ){
    array_push($error_array, "<i>Maximum Price</i> is not a valid monetary value");
    $test = true;
}


if( !$test && $cost_min!="" && $cost_max!="" && ((float)$cost_min) > ((float)$cost_max) )
    array_push($error_array, "Minimum Price is greater than Maximum Price");





//If you don't have any errors, build your query
$query = NULL;

if(!count($error_array)){

   //Basic query first, then add the restrictors
    
   $query = "select wine_name as 'Wine', year as Year, winery_name as 'Winery', region_name as 'Region', on_hand, cost as Price, sum(qty) as num_ordered, sum(price) as Revenue from wine, winery, region, wine_variety, grape_variety, inventory, items where wine.winery_id=winery.winery_id and winery.region_id=region.region_id and wine.wine_id=wine_variety.wine_id and wine_variety.variety_id=grape_variety.variety_id and wine.wine_id=items.wine_id and inventory.wine_id=wine.wine_id";

   if($wine_name!="")
      $query = $query . " AND wine_name LIKE '%" . $wine_name . "%'";
   if($winery_name!="")
      $query = $query . " AND winery_name LIKE '%" . $winery_name . "%'";

   if($region_id != '' and $region_id != '1')
      $query = $query . " AND region.region_id= '" . $region_id . "'";

   $query = $query . " AND wine_variety.variety_id = " . $variety_id;

   $query = $query . " AND year <= " . $year_max;
   $query = $query . " AND year >= " . $year_min;

   if($min_on_hand!= "")
      $query = $query . " AND on_hand >= " . $min_on_hand;

   if($cost_min != "")
      $query = $query . " AND cost >= " . $cost_min;
   if($cost_max != "")
      $query = $query . " AND cost <= " . $cost_max;

   $query = $query . " group by wine.wine_id";

   if($min_ordered!= "")
      $query = $query . " HAVING num_ordered >= " . $min_ordered;

   $query = $query . " order by wine.wine_name";
}


//Try to connect to the database

$db= db_connect();



//Now use the query

$table_data = NULL;

if($query){
    $table_data = db_get_array($query, $db);
    if(count($table_data) == 0)
        array_push($error_array, "Your search produced no results");
}

?>

<html>
<head><title>WDA Assignment 1, Part B</title></head>

<body>

<h1>WDA Assignmen1 1, Part B</h1>
<h3>Daisy Horin 5 August, 2012</h3>
<br />

<h4>Your Search Results</h4>


<?php

if(count($error_array) || !$table_data){
    foreach($error_array as $error)
        echo $error;
}

else
    generate_table($table_data, "results");
?>

<form action="search_page.php" method="get">
<input type="submit" value="Back to Search Page"/>
</form>

</body>
</html>



