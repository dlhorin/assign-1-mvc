<?php
session_start();
unset($_SESSION['is_tracking']);
session_destroy();
$_SESSION[] = array();
header('Location: '.$_SERVER['HTTP_REFERER']);
?>
