<?php
    require "/home/mtware/connections/connect.php";

	//form variables, pashword is immediately hashed
	$fName = $_POST['fName'];
	$lName = $_POST['lName'];
	$email = $_POST['email'];
	$userName = $_POST['usr'];
	$pwd = password_hash($_POST['pwd1'],PASSWORD_DEFAULT);
	$isAdmin = 0;

	// query to check if username is unique
	$checkUser = "select * from User where username = ?";
    $stmt1 = $conn->prepare($checkUser);
    $stmt1->execute([$userName]);

	//if username is not unique then return to form, if it is unique then insert new user into database
    if($stmt1->rowCount() == 1){
        echo "<script>alert('Username Already Exists')</script>";
        header('Refresh: 0; URL=../html/signup.html');
    }
	else{
		$addUser = "insert into User(username, password, Fname, Lname, email,isAdmin) values (?,?,?,?,?,?)";
		$stmt2 = $conn->prepare($addUser);
		try{
			$stmt2->execute([$userName,$pwd,$fName,$lName,$email,$isAdmin]);
			echo "<script> alert('Account Created') </script>";
			header('Refresh: 0; URL=../index.html');
		}catch (Exception $e){
			echo $e->getMessage();
			echo "<script> alert('Query Failed') </script>";
			header('Refresh: 0; URL=../index.html');
		}
	}

?>
