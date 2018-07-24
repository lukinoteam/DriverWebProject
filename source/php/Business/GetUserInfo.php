<?php
//MARK:- Start session to get user's infor
session_start();
//TO-DO: Get user's from initialized session 
$myArr = array($_SESSION['account'],$_SESSION['id']);
$myJSON = json_encode($myArr);
echo $myJSON;
?>