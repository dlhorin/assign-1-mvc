<?php
require_once(PROJECT_ROOT . 'db.ini');
require_once('BaseClass.class.php');
require_once('Wine.class.php');

class Answer extends BaseClass{
    const SUBMIT_KEY = 'submit_search';
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

    }

    function passForm($form){        
        if(!array_key_exists(self::SUBMIT_KEY, $form))
      throw new Exception();
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

        $wine_query = <<<_END
        SELECT
            wine.wine_id AS wine_id,
            wine_name AS Wine,
            year AS Year,
            winery_name AS Winery,
            region_name AS 'Region',
            on_hand AS 'AmountStocked',
            cost AS Cost,
            sum(qty) AS 'AmountSold',
            sum(price) AS Revenue 

        FROM 
            wine, winery, region, wine_variety,
            grape_variety, inventory, items

        WHERE
                wine.wine_id=wine_variety.wine_id
            AND inventory.wine_id=wine.wine_id
            AND wine.wine_id=items.wine_id

            AND wine.winery_id=winery.winery_id
            AND winery.region_id=region.region_id
            AND wine_variety.variety_id=grape_variety.variety_id

            AND wine_name like :wine_name
            AND winery_name like :winery_name

            AND ("" = :region_id OR 1=:region_id OR region.region_id=:region_id)
            AND ("" = :variety_id OR wine_variety.variety_id=:variety_id)
            AND ("" = :year_min OR wine.year>=:year_min)
            AND ("" = :year_max OR wine.year<=:year_max)
            AND ("" = :min_on_hand OR on_hand>=:min_on_hand)
            AND ("" = :cost_min OR Price>=:cost_min)
            AND ("" = :cost_max OR Price<=:cost_max)

            GROUP BY wine.wine_id

            HAVING ("" = :min_ordered OR SUM(qty)>=:min_ordered)

            ORDER BY wine.wine_name
_END;

        $variety_query = <<< _END
        SELECT   GROUP_CONCAT(variety) AS varieties
        FROM     grape_variety, wine_variety, wine 
        WHERE    wine.wine_id=wine_variety.wine_id
        AND      wine_variety.variety_id=grape_variety.variety_id
        AND      wine.wine_id=:wine_id
        GROUP BY wine.wine_id
_END;


        $wine_results = NULL;
        try{
            $wine_stmt = $this->db->prepare($wine_query);
            $variety_stmt = $this->db->prepare($variety_query);

            $wine_stmt->bindValue(':wine_name', '%'.$this->form['wine_name'].'%');
            $wine_stmt->bindValue(':winery_name', '%'.$this->form['winery_name'].'%');
            $wine_stmt->bindValue(':region_id', $this->form['region_id']);
            $wine_stmt->bindValue(':variety_id', $this->form['variety_id']);
            $wine_stmt->bindValue(':year_min', $this->form['year_min']);
            $wine_stmt->bindValue(':year_max', $this->form['year_max']);
            $wine_stmt->bindValue(':min_on_hand', $this->form['min_on_hand']);
            $wine_stmt->bindValue(':cost_min', $this->form['cost_min']);
            $wine_stmt->bindValue(':cost_max', $this->form['cost_max']);
            $wine_stmt->bindValue(':min_ordered', $this->form['min_ordered']);

            $wine_stmt->execute();
            $wine_stmt->setFetchMode(PDO::FETCH_CLASS, 'Wine');
            
            $i = 0;
            while($temp = $wine_stmt->fetch(PDO::FETCH_CLASS)){
                $wine_results[$i] = $temp;
                $variety_stmt->bindValue(':wine_id', $wine_results[$i]->wine_id);
                $variety_stmt->execute();
                $variety_result = $variety_stmt->fetch(PDO::FETCH_ASSOC);
                $wine_results[$i]->grape_varieties = $variety_result['varieties'];
                $i++;
            }
            
        }
        catch(PDOException $e){
            throw new Exception('Server Error');
        }
        return $wine_results;
    }


    function __destruct(){
        parent::__destruct();
    }

}

?>
