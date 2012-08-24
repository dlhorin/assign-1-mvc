<?php
require_once(PROJECT_ROOT . 'db.ini');
require_once('BaseClass.class.php');
require_once('Wine.class.php');

class FormException extends Exception{
    function __construct(){
        parent::__construct();
    }
}

class Answer extends BaseClass{
    const SUBMIT_KEY = 'submit_search';
    private $form = NULL;
    private $errors = NULL;
    private $has_errors = true;
    private $query = NULL;
    
    
    function __construct(){
        parent::__construct();
    }

    private function _int_or_blank($str){
        return preg_match('/^[0-9]*$/', $str);
    }
    private function _num_or_blank($str){
        if($str === '')
            return true;
        return is_numeric($str);
    }

    function passForm($form){        
        if(!array_key_exists(self::SUBMIT_KEY, $form))
            throw new FormException();

        $req_keys = array('wine_name', 'winery_name', 'region_id', 'variety_id',
                     'year_min', 'year_max', 'min_on_hand', 'min_ordered',
                     'cost_min', 'cost_max');

        //Check that each form element exists.
        //Sanitise all input for security
        foreach($req_keys as $key){
            if(!array_key_exists($key, $form))
                throw new FormException();
            $form[$key] = trim(filter_var($form[$key], FILTER_SANITIZE_STRING));
        }
        $this->has_errors = true;

        $this->form = $form;
    }


    /*
     * Sanitizes and validates the form
     * Returns true if the form is valid, or false if not
     */
    function validateForm(){
        //Don't try to validate if form hasn't been passed in
        if($this->form === NULL)
            return;

        $this->has_errors = false;

        //Validate the minimum amount in stock
        $val = $this->form['min_on_hand'];
        $this->errors['min_on_hand'] = '';
        if(!$this->_int_or_blank($val)){
            $this->errors['min_on_hand'] = 'Must be a whole number';
            $this->has_errors = true;
        }
        else if($val < 0){
            $this->errors['min_on_hand'] = 'Must be positive';
            $this->has_errors = true;
        }

        //Validate the minimum amount ordered
        $val = $this->form['min_ordered'];
            $this->errors['min_ordered'] = '';
        if(!$this->_int_or_blank($val)){
            $this->errors['min_ordered'] = 'Must be a whole number';
            $this->has_errors = true;
        }
        else if($val < 0){
            $this->errors['min_ordered'] = 'Must be positive';
            $this->has_errors = true;
        }


        //Check that year_min < year_max
            $this->errors['compare_years'] = '';
        if($this->form['year_min'] > $this->form['year_max']){
            $this->errors['compare_years'] = 'Minimum year must be less than maximum';
            $this->has_errors = true;
        }

        //Validate the minimum & maximum costs
        $val1 = $this->form['cost_min'];
        $val2 = $this->form['cost_max'];
            $this->errors['cost'] = '';
            $this->errors['compare_costs'] = '';
        if(!$this->_num_or_blank($val1) || !$this->_num_or_blank($val2)){
            $this->errors['cost'] = 'Must be numbers';
            $this->has_errors = true;
        }
        else if($val1 < 0 || $val2 < 0){
            $this->errors['cost'] = 'Must be positive';
            $this->has_errors = true;
        }
        else if($val1 != '' && $val2 != '' && $val1 > $val2){
            $this->errors['compare_costs'] = 'Minimum price must be less than maximum';
            $this->has_errors = true;
        }

        return $this->has_errors;
    }


    /*
     * Returns the form errors, or NULL if no errors
     * Always returns NULL if called
     * before validateForm - must be used after that function
     */
    function getErrors(){
        if(!$this->has_errors)
            return NULL;
        return $this->errors;
    }


    function runSearch(){
        if($this->has_errors)
            return NULL;

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
            AND ("" = :cost_min OR Cost>=:cost_min)
            AND ("" = :cost_max OR Cost<=:cost_max)

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

        return $wine_results;
    }


    function __destruct(){
        parent::__destruct();
    }

}

?>
