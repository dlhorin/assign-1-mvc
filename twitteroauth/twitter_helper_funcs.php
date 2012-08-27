<?php

/*
 * Using twitteroauth library from github.com/abraham/twitteroauth
 */

/* Start session and load library. */
require_once('twitteroauth.php');
define('CONSUMER_KEY', 'r1uEBxB4NAd4C1P5BlQRQ');
define('CONSUMER_SECRET', 'u2Rl01d385PvCdg3570N7hU8GU1guh2PwP75eNCpSE');


/*
 * Code adapted from github.com/abraham/twitteroauth/redirect.php
 */
function tweeturl($callback){
    $_SESSION['tw'] = array();

    /* Build TwitterOAuth object with client credentials. */
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
     
    /* Get temporary credentials. */
    $request_token = $connection->getRequestToken($callback);

    /* Save temporary credentials to session. */
    $_SESSION['tw']['oauth_token'] = $token = $request_token['oauth_token'];
    $_SESSION['tw']['oauth_token_secret'] = $request_token['oauth_token_secret'];
     
    /* If last connection failed don't return authorization link. */
    if (200 != $connection->http_code) {
        $_SESSION['tw'] = array();
        return false;
    }

    /* Build authorize URL. */
    $url = $connection->getAuthorizeURL($token);
    return $url;
}

/*
 * Code adapted from github.com/abraham/twitteroauth/callback.php
 * and github.com/abraham/twitteroauth/index.php
 * This function should only be invoked on the callback page
 */
function make_tweet($text){

    /* If this is not callback, return false */
    if(!isset($_REQUEST['oauth_verifier'])){
        $_SESSION['tw'] = array();
        return false;
    }
    
    /* 
     * If there is no oauth_token in $_SESSION,
     * or the oauth_token is old, return false
     */
    if( !isset($_SESSION['tw']['oauth_token']) || 
        ( $_SESSION['tw']['oauth_token'] !== $_REQUEST['oauth_token'] )
      ){
        $_SESSION['tw'] = array();
        return false;
    }

    /* 
     * Create TwitteroAuth object with app key/secret and
     * token key/secret from default phase
     */
    $connection = new TwitterOAuth( CONSUMER_KEY,
                                    CONSUMER_SECRET, 
                                    $_SESSION['tw']['oauth_token'],
                                    $_SESSION['tw']['oauth_token_secret']
                                  );


    /* Request access tokens from twitter */
    $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

    /* If last connection failed return false */
    if(200 != $connection->http_code){
        $_SESSION['tw'] = array();
        return false;
    }

    /* Create a new TwitterOAuth object with consumer/user tokens */
    $connection = new TwitterOAuth( CONSUMER_KEY, 
                                    CONSUMER_SECRET, 
                                    $access_token['oauth_token'], 
                                    $access_token['oauth_token_secret']
                                  );

    
    /* Post the text to twitter */
    $connection->post('statuses/update', array('status' => $text));

    /* If last connection failed return false */
    if(200 != $connection->http_code){
        $_SESSION['tw'] = array();
        return false;
    }

    /* Clear the session anyway, and return true */
    $_SESSION['tw'] = array();
    return true;
}


?>
