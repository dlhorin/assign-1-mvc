<?php
session_start();
$_SESSION['is_tracking'] = true;
header('Location: '.$_SERVER['HTTP_REFERER']);
?>
