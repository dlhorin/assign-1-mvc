<?php
ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);
define("USERDIR", "/home/staff/e02439/");
/**
 * Define the DB connection details separately 
 *
 */

/**
 * Hostname mysql is running on (can't use localhost)
 */
define('DB_HOST',   'yallara.cs.rmit.edu.au:');
/**
 * Port that mysql is running on
 */
define('DB_PORT',   '58621');
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

function db_connect(){
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PW
    );
}
