<?php
require_once('config.php');
require_once('templates.php');
require_once('class.a1.php');

$search = new Search();

//Building the data for the 'region' drop-down list
$region_data = $search->get_regions();

//Building the data for the 'grape_variety' drop-down list
$variety_data = $search->get_grape_varieties();


//Building the data for the 'years' list
$year_data = $search->get_years();

$search = NULL;

include('header.php');
include('search_panel.php');
include('footer.php');

?>


