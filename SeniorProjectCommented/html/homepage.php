<?php

// start session and make sure user is logged in and not admin
session_start();
if($_SESSION['username'] != "" && $_SESSION['Admin'] == 0){
    
}else{
    session_destroy();
    header('location:../index.html');
}

require "/home/mtware/connections/connect.php";

// query used to obtain userID, adding to session variable to be used across site

$query1 = 'select User_ID from User where username = ?';
$ps = $conn->prepare($query1);
$ps->execute([$_SESSION['username']]);
foreach($ps as $row){
  $_SESSION['ID'] = $row['User_ID'];
}


//html to be displaed on page
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
                <li> <a class="active" href="homepage.php"> Home </a></li>
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
    <h1> Items You Have Uploaded </h1>
    <br>
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th> Item Name </th>
                    <th> Price </th>
                    <th> Edit Item </th>
                    <th> Delete Item</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //query used to obtain all the items that have been uploaded by the user
                $createdItems = "Select * from Live_Items where creatorID = ? and isApproved= 1";
                $stmt = $conn->prepare($createdItems);
                $stmt->execute([$_SESSION['username']]);
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        $price = number_format($row['Price'],2);
                        echo"<tr>";
                        echo "<td>" .$row['Name']. "</td>";
                        echo "<td> $" .$price. "</td>";
                        echo "<td> <form action='editItem.php' method='post'>  <input type='hidden' name='itemID' value=".$row['ItemID']."> <input type='submit' value='Edit' name='edit'> </form> </td>";
                        echo "<td> <form action='../php/removeItem.php' method='post'>  <input type='hidden' name='itemID' value=".$row['ItemID']."> <input onclick='return confirm(\"Are you sure you want to delete this item?\");' type='submit' value='Delete' name='delete'> </form> </td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    

    
</body>
</html>