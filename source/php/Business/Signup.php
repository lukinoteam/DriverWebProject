<?php
require_once __DIR__ . "/../DataAccess/MySQL/MySQLDA.php";
require_once 'InitHomeFolder.php';


$name = $_POST['fieldName'];
$email = $_POST['fieldEmail'];
$pass = $_POST['fieldPass'];
$gender = $_POST['fieldGender'];
$genderInt = 0;
//check Is unique email ??
$isUnique = 1;
if ($GLOBALS['gender'] == "MALE") {
    $GLOBALS['genderInt'] = 1;
} else if ($GLOBALS['gender'] == "FEMALE") {
    $GLOBALS['genderInt'] = 2;
} else {
    $GLOBALS['genderInt'] = 3;
}
$dob = $_POST['fieldDate'];
$id = "";
$md5Pass = md5($pass);
//Create Connection
//define table name , attribute to select
//take unique id
$table = 'users';
$attribute = 'email';
$no_condition = "";
$query = new MySQLDA();
$GLOBALS['id'] = $query->takeUUID($table);
//selectQuery = Select email from users;
$selectQuery = $query->select($table, $attribute, $no_condition);
if ($selectQuery->num_rows > 0) {
    // output data of each row
    while ($row = $selectQuery->fetch_assoc()) {
        if ($row["email"] == $GLOBALS['email']) {
            $GLOBALS['isUnique'] = 0;
        }
    }
}
//$insert = "INSERT INTO users (id,email,fullname,password,dob,gender) VALUES ('$id','$email','$name','$md5Pass','$dob','$genderInt')";
$arrayData = array(
    "id" => $id,
    "email" => $email,
    "fullname" => $name,
    "password" => $md5Pass,
    "dob" => $dob,
    "gender" => $genderInt,
);
$insert = $query->insert($table, $arrayData);
//if the email is unique we insert data
if ($GLOBALS['isUnique'] == 1) {
    if ($GLOBALS['insert']) {
        echo "true";
        init_home_folder($id);
    } else {
        echo "fasle";
    }
} else {
    echo "Account has already existed";
}
