<?php
require_once("config.php");
require_once('SmartyConfig.php');
require_once('models/Wine.class.php');
require_once('twitteroauth/twitter_helper_funcs.php');

session_start();


$smarty = new A1Smarty();

if(!isset($_SESSION['is_tracking']))
    header('Location: '.$_SERVER['HTTP_REFERER']);

if(isset($_SESSION['wines']) && count($_SESSION['wines'])){
    $smarty->assign('table_data', $_SESSION['wines']);

    $just_tweeted = false;
    if(isset($_SESSION['tw']['oauth_token'])){
        $tweet_text = 'Wines viewed at the Wine Searcher: ';
        $wines = $_SESSION['wines'];
        foreach($wines as $wine)
            $tweet_text .= $wine->Wine .' ' . $wine->Year .'; ';
        $tweet_text = substr($tweet_text, 0, 139);
        
        if(make_tweet($tweet_text))
            $just_tweeted = true;
    }
    $callback = 'http://yallara.cs.rmit.edu.au/~e02439/wda/a1_C/view_saved_wines.php';
    $tweeturl = tweeturl($callback);
    $smarty->assign('just_tweeted', $just_tweeted);
    $smarty->assign('tweeturl', $tweeturl);

}
$smarty->display('views/saved_wines.tpl');
?>
