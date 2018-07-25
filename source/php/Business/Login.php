<?php
require_once 'Connectdb.php';

//MARK:- Start session to store Email and ID
session_start();

//MARK:- Variables
$_SESSION["account"] = "";
$_SESSION["id"] = "";
$pass = $_POST['fieldPass'];
$email = $_POST['fieldEmail'];
$isExist = 0;

//MARK:- Operation

//TO-DO: check Is unique email ??
$md5Pass = md5($pass);

//@var: connectivity's variables
$db=MySQLConnectivity:: get_instance();
$conn=$db->get_connection();
//@var: SQL query
$sql = "SELECT email from users";
$sqlPass = "SELECT password from users WHERE email = \"".$email."\"";
//TO-DO:connect SQL
$result = $conn->query($sql);
$resultPass = $conn->query($sqlPass);
if ($result->num_rows > 0) {
    //TO-DO:output data of each row
    while($row = $result->fetch_assoc()) {
        //TO-DO:check is email existed ?
        if($row["email"]==$GLOBALS['email']){
            $GLOBALS['isExist'] = 1;
        }
    }
} 
if($GLOBALS['isExist']==0) {
    echo "Account does not exist";
}
//TO-DO: if email existed
else{
    if($resultPass->num_rows >0) {
        while($row = $resultPass->fetch_assoc()) {
            if($row["password"]!=$GLOBALS['md5Pass']) {
                echo "Your password or email is incorrect";
            }
            else {
                //TO-DO:add session email
                $_SESSION['account'] = $GLOBALS['email'];
                $sql = "SELECT id from users where email = \"".$GLOBALS['email']."\"";
                $result = $conn->query($sql);
                if($result ->num_rows > 0){
                    while ($row=$result->fetch_assoc()) {
                        //TO-DO:add session id
                        $_SESSION['id'] = $row["id"];
                    }
                }
                echo "true";
            }
        }
    }
}
?>