<?php
    require "/home/mtware/connections/connect.php";


$login = false;
$userName = $_POST['usr'];
$pwd = $_POST['pwd'];

// query to get information regarding user to process login

$getUser = 'select * from User where (username= :name)';
$values = [':name' => $userName];
$ps = $conn->prepare($getUser);
try{
	$ps->execute($values);
}catch (PDOException $e){
	echo "<script>alert('Query Failed')</script>";
    die();
}


$row = $ps->fetch(PDO::FETCH_ASSOC);


// check if query returned a user and if so check if password matches hash
if(is_array($row)){
    if(password_verify($pwd, $row['password'])){
        $login = true;
    }
}


// see if user logging in is admin, if so send to admin area
if($login == true and $row['isAdmin'] == 1){
    session_start();
    $_SESSION['username'] = $userName;
    $_SESSION['Admin']  = 1;
    header('Refresh: 0; URL=../html/adminPage.php');
}
//see if user logging in is general user, if so send to general user pages
elseif($login == true and $row['isAdmin'] == 0){
    session_start();
    $_SESSION['username'] = $userName;
    $_SESSION['Admin']  = 0;
    header('Refresh: 0; URL=../html/homepage.php');
}
// if neither is true then login information is invalid
else{
    echo "<script>alert('Invalid Username or Password')</script>";
    header('Refresh: 0; URL=../index.html');
}

?>