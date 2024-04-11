<?php

// start session and make sure user is logged in and not admin
session_start();
require "/home/mtware/connections/connect.php";

if($_SESSION['username'] != "" && $_SESSION['Admin'] == 0){
    
}else{
    session_destroy();
    header('location:../index.html');
}


//html to be displayed on the page
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
                <li> <a class="active" href="orderhistory.php"> Order History</a></li>
                <li> <a  href="carts.php"> Carts </a></li>
            </ul>
        </nav>
        <a class="navLogout" href="../php/logout.php"> <button class="buttonsGrey" type="submit"> Logout </button></a>
</header>

    <br>
    <br>
    <div style="text-align: center;">
        <h1> Your Previous Orders </h1>
        <br>
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th> Order Number </th>
                    <th> Quantity of Items </th>
                    <th> Purchase Date </th>
                    <th> View Order </th>
                </tr>
            </thead>
            <tbody>
            <?php
                // query to get all orders associated with a user and display them
               $getOrders = "Select Orders.OrderID, COUNT(OrderItems.OrderID) as Num, OrderItems.purchaseDate from Orders left outer join OrderItems on Orders.OrderID = OrderItems.OrderID where UserID = ? Group by Orders.OrderID";
               $stmt = $conn->prepare($getOrders);
               $stmt->execute([$_SESSION['ID']]);
               while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                echo"<tr>";
                echo "<td>" .$row['OrderID']. "</td>";
                echo "<td>" .$row['Num']. "</td>";
                echo "<td>" .$row['purchaseDate']. "</td>";
                echo "<td> <form action='orderItems.php' method='post'>  <input type='hidden' name='OrderID' value=".$row['OrderID']."> <input type='submit' value='View' name='View'> </form> </td>";
                echo "</tr>";
                }
               ?>
            </tbody>
        </table>
    </div>  
</body>
</html>