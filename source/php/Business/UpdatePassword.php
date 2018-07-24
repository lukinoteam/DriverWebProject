<?php
    require_once 'Connectdb.php';
    //MARK:- Start session to store Email and ID
    session_start();
    
    //MARK: Variables
    $oldPass = $_POST['fieldOldPass'];
    $newPass = $_POST['fieldNewPass'];
    $email = $_SESSION['account'];
    $md5OldPass = md5($oldPass);
    $md5NewPass = md5($newPass);
    //@var: Connectivity's instances
    $db=MySQLConnectivity:: getInstance();
    $conn=$db->getConnection();
    //TO-DO: Confirm password
    $sqlPass = "SELECT password FROM users WHERE email = \"".$email."\"";
    //TO-DO: Update password
    $sql = "UPDATE users SET password = \"".$md5NewPass."\" WHERE email = \"".$email."\"";
    //TO-DO: Connect sql;
    $result = $conn ->query($sqlPass);
    if ($result->num_rows > 0) {
        //TO-DO: Output data of each row
        while($row = $result->fetch_assoc()) {
            if($row["password"]==$GLOBALS['md5OldPass']) {
                $GLOBALS['isPass']=1;
            }
        }
    }
    if($GLOBALS['isPass']==1) {
        if($conn -> query($sql)==TRUE) {
            session_unset();
            echo $GLOBALS['isPass'];
        }
    } else {
        echo "Your password is incorrect";
    } 
?>

