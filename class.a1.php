<?php
require_once('config.php');

abstract class A1{
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
        $query = 'select variety, variety_id from grape_variety order by variety';
        return parent::execute($query);
    }

    function __destruct(){
        parent::__destruct();
    }
}

class Answer extends A1{
    const SUBMIT_KEY = 'submit_form';
    const STR = 1;
    const IS_INT_ = 2;
    const IS_NUM = 4;
    const IS_POS = 8;
    const NOT_EMPTY = 16;

    private $form = NULL;
    private $errors = NULL;

    private $code_int = 1;

    private $to_validate = NULL;

    private $query = NULL;    
    
    
    function __construct(){
        parent::__construct();

        $this->to_validate = array(
            array('key' => 'wine_name', 'code'=>NULL, 'msg'=>NULL),
            array('key'=>'winery_name', 'code'=>NULL, 'msg'=>NULL),
            array('key'=>'region_id', 'code'=>self::IS_INT_ | self::IS_POS, 'msg'=>'Region ID must be a positive whole number'),
            array('key'=>'variety_id', 'code'=>self::IS_INT_ | self::IS_POS, 'msg'=>'Variety ID must be a positive whole number'),
            array('key'=>'year_min', 'code'=>self::IS_INT_ | self::IS_POS, 'msg'=>'Minumum Year must a be positive whole number'),
            array('key'=>'year_max', 'code'=>self::IS_INT_ | self::IS_POS, 'msg'=>'Maximum Year must be a positive whole number'),
            array('key'=>'min_on_hand', 'code'=>self::IS_INT_ | self::IS_POS, 'msg'=>'Minumum Amount Stocked must be a positive whole number'),
            array('key'=>'min_ordered', 'code'=>self::IS_INT_ | self::IS_POS, 'msg'=>'Minimum Amount Ordered must be a positive whole number'),
            array('key'=>'cost_min', 'code'=>self::IS_NUM | self::IS_POS, 'msg'=>'Minimum price must be a positive number'),
            array('key'=>'cost_max', 'code'=>self::IS_NUM | self::IS_POS, 'msg'=>'Maximum price must be a positive number'),
        );


        $this->query = "select wine_name as 'Wine', year as Year, winery_name as 'Winery', region_name as 'Region', on_hand, cost as Price, sum(qty) as num_ordered, sum(price) as Revenue from wine, winery, region, wine_variety, grape_variety, inventory, items where wine.winery_id=winery.winery_id and winery.region_id=region.region_id and wine.wine_id=wine_variety.wine_id and wine_variety.variety_id=grape_variety.variety_id and wine.wine_id=items.wine_id and inventory.wine_id=wine.wine_id";

        $this->query = $this->query . ' AND wine_name like :wine_name';

        $this->query = $this->query . ' AND winery_name like :winery_name';
        $this->query = $this->query . ' AND ("" = :region_id OR 1=:region_id OR region.region_id=:region_id)';
        $this->query = $this->query . ' AND ("" = :variety_id OR wine_variety.variety_id=:variety_id)';
        $this->query = $this->query . ' AND ("" = :year_min OR wine.year>=:year_min)';
        $this->query = $this->query . ' AND ("" = :year_max OR wine.year<=:year_max)';
        $this->query = $this->query . ' AND ("" = :min_on_hand OR on_hand>=:min_on_hand)';
        $this->query = $this->query . ' AND ("" = :cost_min OR Price>=:cost_min)';
        $this->query = $this->query . ' AND ("" = :cost_max OR Price<=:cost_max)';

        $this->query = $this->query . ' GROUP BY wine.wine_id';

        $this->query = $this->query . ' HAVING ("" = :min_ordered OR num_ordered>=:min_ordered)';

          $this->query = $this->query . ' ORDER BY wine.wine_name';
    }

    function passForm($form){
      //  if(!isset($form) || $form === NULL || !is_array($form) || !array_key_exists(self::SUBMIT_KEY, $form))
      //      return false;
        $this->form = $form;
    }

    function validateKey($key, $code, $msg=NULL){
        if(!array_key_exists($key, $this->form)){
            throw new Exception();
        }

        $val = $this->form[$key];

        if($val === ''){
            if($code & self::NOT_EMPTY){
                $this->form[$key] = false;
                return false;
            }
            return '';
        }

        if($code & self::IS_INT_){
            $val = filter_var($val, FILTER_SANITIZE_NUMBER_INT);
            $val = filter_var($val, FILTER_VALIDATE_INT);
        }
        elseif($code & self::IS_NUM){
            $val = filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT);
            $val = filter_var($val, FILTER_VALIDATE_FLOAT);
        }
        else{
            $val = filter_var($val);
        }

        if($code & self::IS_POS){
            if($val < 0)
                $val = false;
        }

        $this->form[$key] = $val;
        return $val;
            
    }



    function isValidForm(){
        if($this->form === NULL)
            echo "Null Form";

        $this->errors = NULL;
        echo "<br/>\n";
        foreach($this->to_validate as $data){
           if($this->validateKey($data['key'], $data['code']) === false){
                if($data['msg'] !== NULL){
                    $this->errors[$data['key']] = $data['msg'];
                }
            }

        }

        if($this->form['year_min'] !==false && $this->form['year_max']!==false 
                                    && $this->form['year_min'] > $this->form['year_max']){
            $this->errors['compare_years'] = 'Minimum year must be less than Maximum year';
        }

        if($this->form['cost_min'] !==false && $this->form['cost_max'] !== false
                                        && $this->form['cost_min'] > $this->form['cost_max']){
            $this->errors['compare_costs'] = 'Minimum cost must be less than maximum cost';
        }
             
        if($this->errors)
            return false;
        return true;
    }


    function getErrors(){
        return $this->errors;
    }


    function runSearch(){
        $result = NULL;
        try{
            $stmt = $this->db->prepare($this->query);

            $stmt->bindValue(':wine_name', '%'.$this->form['wine_name'].'%');
            $stmt->bindValue(':winery_name', '%'.$this->form['winery_name'].'%');
            $stmt->bindValue(':region_id', $this->form['region_id']);
            $stmt->bindValue(':variety_id', $this->form['variety_id']);
            $stmt->bindValue(':year_min', $this->form['year_min']);
            $stmt->bindValue(':year_max', $this->form['year_max']);
            $stmt->bindValue(':min_on_hand', $this->form['min_on_hand']);
            $stmt->bindValue(':cost_min', $this->form['cost_min']);
            $stmt->bindValue(':cost_max', $this->form['cost_max']);
            $stmt->bindValue(':min_ordered', $this->form['min_ordered']);

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
            $this->errors['SQLerror'] = 'Server Error: Your query could not be processed';
        }
        return $result;
    }


    function __destruct(){
        parent::__destruct();
    }

}

?>
