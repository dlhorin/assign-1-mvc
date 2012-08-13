<?php
/**
 * Define the DB connection details separately so that 
 * they are not publicly available in github. 
 *
 */

/**
 * Hostname and port mysql is running on (can't use localhost)
 */
define('DB_HOST',   'yallara.cs.rmit.edu.au:58621');
/**
 * Name of database to connect to
 */
define('DB_NAME',   'winestore');
/**
 * Username to connect with
 */
define('DB_USER',   'winestore');

/**
 * Password to connect with
 */
define('DB_PW',     'winepass');

/**
 * Print mysql errors in a user-friendly manner
 */
function showerror(){
   die("Error ". mysql_errno() . " : " . mysql_error());
}

/**
 * Connect to mysql and select the winestore database
 */
function db_connect(){
   if(!($dbconn = @mysql_connect(DB_HOST, DB_USER, DB_PW))){
      showerror();
   }
 
   if(!(@mysql_select_db(DB_NAME, $dbconn))){
      showerror();
   }

   return $dbconn;
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

function db_get_array($query, $dbconn, &$data){
    $data = NULL;
    if( !($result = mysql_query($query, $dbconn)) )
        return -1;

    $num_rows = mysql_num_rows($result);
    if($num_rows == -1)
        return 0;

    $data = array();
    for($i = 0; $i<$num_rows; $i++)
        $data[$i] = mysql_fetch_assoc($result);


    return $num_rows;
}

function db_clean($string){
    return @mysql_real_escape_string(trim($string));
}
?>
