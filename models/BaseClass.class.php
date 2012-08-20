<?php
require_once(PROJECT_ROOT . 'db.ini');

abstract class BaseClass{
    protected $db = NULL;

    function __construct(){
        try {
            $this->db = new PDO(
                'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';
                dbname=' . DB_NAME,
                DB_USER,
                DB_PW
            );

        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    function execute($query){
        if(!isset($this->db))
            throw new Exception("Database Handler NULL in execute");
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    //Matches int or empty string
    function is_int_string($num_str){
        $num_str = trim($num_str);
        $regex = '/^[0-9]*$/';
        return preg_match($regex, $num_str);
    }

    function strempty($str){
        if(!isset($str))
            return false;
        if($str === NULL)
            return false;
        if($str === '')
            return false;
        return true;
    }


    function __destruct(){
        $this->db = NULL;
    }
}

?>
