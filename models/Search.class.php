<?php
require_once(PROJECT_ROOT . 'db.ini');
require_once('BaseClass.class.php');

class Search extends BaseClass{

    function __construct(){
        parent::__construct();
    }

    public function get_regions(){
        $query = 'select region_name as region, region_id from region order by region_name';
        return parent::execute($query);
    }

    function get_years(){
        $query = 'select distinct year from wine order by year';
        return parent::execute($query);
    }

    function get_grape_varieties(){
        $query = 'select variety, variety_id from grape_variety order by variety';
        return parent::execute($query);
    }

    function __destruct(){
        parent::__destruct();
    }
}
?>
