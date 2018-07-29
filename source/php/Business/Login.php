<?php
session_start();
require_once __DIR__ ."/../DataAccess/MySQL/MySQLDA.php";
$_SESSION["account"] = "";
$_SESSION["id"] = "";
$pass = $_POST['fieldPass'];
$email = $_POST['fieldEmail'];
//check Is unique email ??
$isExist = 0;
$md5Pass = md5($pass);
$table = 'users';
$attrEmail = 'email';
$query = new MySQLDA();
//$sqlSelectEmail = "SELECT email from users";
$noCondition = "";
$select_email = $query->select($table,$attrEmail,$noCondition);
    if ($select_email->num_rows > 0) {
    // output data of each row
        while($row = $select_email->fetch_assoc()) {
            //check is email existed ?
            if($row["email"]==$GLOBALS['email']){
                $GLOBALS['isExist'] = 1;
            }
        }
    } 
    if($GLOBALS['isExist']==0){
        echo "Account does not exist";
    }
//if email existed
    else{
        $attrPass = "password";
        $condition = "email = \"".$email."\"";
        $select_pass = $query->select($table,$attrPass,$condition);
        if($select_pass->num_rows >0){
            while($row = $select_pass->fetch_assoc()){
                if($row["password"]!=$GLOBALS['md5Pass']){
                    echo "Your password or email is incorrect";
                }
                else {
                    //add session email
                    $_SESSION['account'] = $GLOBALS['email'];
                    $attrID = "id";
                    $con = "email = \"".$GLOBALS['email']."\"";
                    $select_id = $query->select($GLOBALS['table'],$attrID,$con);
                    if($select_id->num_rows > 0){
                    	while ($row=$select_id->fetch_assoc()){
                            //add session id
                    		$_SESSION['id'] = $row["id"];
                    	}
                    }
                    echo "true";

                }
            }

        }
    }
   
  ?>