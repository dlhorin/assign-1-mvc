<?php

/**
 * Connect to mysql and select the winestore database
 */

function db_connect(){
    try{
        $db = new PDO(
            "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PW
        );
    } catch(PDOException $e){
        die($e->getMessage());
    }
    return $db;
}


function db_showerror($message = NULL){
   if( ($errno = mysql_errno()) )
      echo "<b>DB Error:</b> [$errno] : " . mysql_error() . "<br/>\n";
   else if($message && trim($message)!="")
      echo "<b>Error:</b> $message<br/>\n";
   return $errno;
}

function db_geterror($message = NULL){
   if( ($errno = mysql_errno()) )
      return "<b>DB Error:</b> [$errno] : " . mysql_error() . "<br/>\n";
   if($message && trim($message)!="")
      return "<b>Error:</b> $message<br/>\n";
   return NULL;
}

function db_clean($string){
    return (trim($string));
}

function db_get_array($query, $db){
    try{
        $statement = $db->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
            die($e->getMessage());
    }
}
?>
