<?php

// start session and make sure user is logged in and not admin
session_start();
require "/home/mtware/connections/connect.php";


if($_SESSION['username'] != "" && $_SESSION['Admin'] == 0){
    
}else{
    session_destroy();
    header('location:../index.html');
}

$OrderID = $_POST['OrderID'];



// html to be displayed on the page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>U Buy U Sell </title>
</head>
<body>
    <header>
            <h3 class="navProductName"> UBUS </h3>
            <nav>
                <ul class="navList">
                    <li> <a  href="homepage.php"> Home </a></li>
                    <li> <a  href="buying.php"> UBuy </a></li>
                    <li> <a  href="selling.php"> USell </a></li>
                    <li> <a  href="orderhistory.php"> Order History</a></li>
                    <li> <a  href="carts.php"> Carts </a></li>
                </ul>
            </nav>
            <a class="navLogout" href="../php/logout.php"> <button class="buttonsGrey" type="submit"> Logout </button></a>
    </header>
    <br>
    <br>
    <div style="text-align: center">
    <h1> Items in Order <?php echo $OrderID; ?> </h1>
    <br>
        <table style="width: 100%;" >
            <thead>
                <tr>
                    <th> Item Name </th>
                    <th> Item Price </th>
                </tr>
            </thead>
            <tbody>
               <?php
               //query to get all items associated with an order and display the items
               $getOrderItems = "Select * from OrderItems where OrderID = ?";
               $stmt2 = $conn->prepare($getOrderItems);
               $stmt2->execute([$OrderID]);
               while($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
                echo"<tr>";
                echo "<td>" .$row['Name']. "</td>";
                echo "<td>$" .number_format($row['Price'],2). "</td>";
                echo "</tr>";
                }
               ?>
            </tbody>
        </table>
    </div>
        
</body>
</html>