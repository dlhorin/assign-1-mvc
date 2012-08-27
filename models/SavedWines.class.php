<?php
require_once('models/Wine.php');


class SavedWines{
    
    function saveWines($wine_array){
        if(!$wine_array)
            return;
        if(!is_array($wine_array))
            throw new Exception();
        if(!count($wine_array))
            return;
        if(get_class($wine_array[0]) !== 'Wine')
            throw new Exception();

        if(!isset($_SESSION['wines']))
        

