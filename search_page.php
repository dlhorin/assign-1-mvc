<?php
require_once("config.php");
require_once("templates.php");

$db = db_connect();

//Building the data for the 'region' drop-down list
$query = "select region_name as region, region_id from region order by region";
$region_data = db_get_array($query, $db);

//Building the data for the 'grape_variety' drop-down list
$query = "select variety, variety_id from grape_variety order by variety";
$variety_data= db_get_array($query, $db);


//Building the data for the 'years' list
$query = "select distinct year from wine order by year";
$year_data= db_get_array($query, $db);

$db = NULL;

?>

<html>
<head><title>WDA Assignment 1, Part B</title></head>

<body>

<h1>WDA Assignmen1 1, Part B</h1>
<h3>Daisy Horin 5 August, 2012</h3>
<br />

<form action="answer_page.php" method="get">
<h4> Search for a wine</h4>
<table id="search_table">

<tr>
<td>Enter wine name (or part of):</td> <td><input type="text" name="wine_name" size="20" /></td>
</tr>

<tr>
<td>Enter winery name (or part of):</td> <td><input type="text" name="winery_name" size="20" /></td>
</tr>

<tr>
<td>Select region:</td>
<td>
<?php
generate_dropdown($region_data, "region", "region_id", "region")
?>
</td>
</tr>

<tr>
<td>Select grape variety:</td>
<td>
<?php
generate_dropdown($variety_data, "variety", "variety_id", "grape_variety");
?>
</td>
</tr>

<tr>
<td>Year: (select range)</td>
<td>
<?php
generate_dropdown($year_data, "year", "year", "year_min");
?>

  -to-  

<?php
reset($year_data);
generate_dropdown($year_data, "year", "year", "year_max");
?>
</td>
</tr>

<tr>
<td>Minimum amount in stock:</td> <td><input type="text" name="min_on_hand" size="20" /></td>
</tr>

<tr>
<td>Minimum amount ordered:</td> <td><input type="text" name="min_ordered" size="20" /></td>
</tr>

<tr>
<td>Price Range:</td>
<td>
<input type="text" name="cost_min" size="6" />
  -to-  
<input type="text" name="cost_max" size="6" />
</td>
</tr>
</table>

<input type="submit" value="Submit"/>

</form>
</body>
</html>
