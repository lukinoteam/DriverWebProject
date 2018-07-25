<?php
require_once __DIR__ ."/../DataAccess/MySQL/MySQLDA.php";
session_start();
$oldPass = $_POST['fieldOldPass'];
$newPass = $_POST['fieldNewPass'];
$email = $_SESSION['account'];
$md5OldPass = md5($oldPass);
$md5NewPass = md5($newPass);
$isPass = 0;
$query = new MySQLDA();
    //Confirm password
    //$sqlPass = "SELECT password FROM users WHERE email = \"".$email."\"";
    $attr = "password";
    $table = "users";
    $condition = "email = \"".$email."\"";
    $select_pass = $query->select($table,$attr,$condition);
     if ($select_pass->num_rows > 0) {
    // output data of each row
        while($row = $select_pass->fetch_assoc()) {
            if($row["password"]==$GLOBALS['md5OldPass']){
               $GLOBALS['isPass']=1;
            }
        }
    }
    if($GLOBALS['isPass']==1){
        //update password
        //$sql = "UPDATE users SET password = \"".$md5NewPass."\" WHERE email = \"".$email."\"";
        $data = "password = \"".$md5NewPass."\"";
        $update=$query->update($table,$data,$condition);
        if($update){
            session_unset();
    		echo $GLOBALS['isPass'];
    	}
    }
    else{
    	echo "Your password is incorrect";
    } 
    
  ?>

