<?php
    require "/home/mtware/connections/connect.php";

// form variables
$name = $_POST['itemName'];
$desc = $_POST['desc'];
$price = $_POST['price'];
$category = $_POST['category'];
$isApproved = 0;

//query to add item information into database
$addItem = "insert into Live_Items(Name, Description, Price, Category, isApproved) values (?,?,?,?,?)";
$ps = $conn->prepare($addItem);
try{
	$ps->execute([$name,$desc,$price,$category,$isApproved]);
	echo "<script>alert('Item Added')</script>";
    header('Refresh: 0; URL=selling.php');
}catch (Exception $e){
	echo $e->getMessage();
	echo "<script> Error Adding Item </script>";
}
?>