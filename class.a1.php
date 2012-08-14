<?php
require_once("config.php");

class A1{
    private $db;

    function __construct(){
        try {
            $this->db = new PDO(
                "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";
                dbname=" . DB_NAME,
                DB_USER,
                DB_PW
            );
        } catch(PDOException $e) {
            die($e->getMessage());
        }
        if(!isset($this->db))
            echo "db null in construct";
    }

    function execute($query){
        if(!isset($this->db))
            echo "db null in execute";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


    function __destruct(){
        $db = NULL;
    }
}

class Search extends A1{

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
        $query = "select variety, variety_id from grape_variety order by variety";
        return parent::execute($query);
    }


    function __destruct(){
        parent::__destruct();
    }
}

?>
