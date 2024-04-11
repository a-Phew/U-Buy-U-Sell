<?php

// create session and make sure user is valid admin in order to access page
session_start();
require "/home/mtware/connections/connect.php";
if($_SESSION['username'] != "" && $_SESSION['Admin'] == 1){
}
else{
    session_destroy();
    header('location:../index.html');
}


// if button to remove an item is clicked the following will run in order to delete it from the site
if(isset($_POST['remove'])){
    $id = $_POST['itemID'];

    $removeFromCarts = "delete from CartItems where ItemID = ?";
    $stmt1 = $conn->prepare($removeFromCarts);
    $stmt1->execute([$id]);
    

    $removeItem = "delete from Live_Items where ItemID = ?";
    $stmt1 = $conn->prepare($removeItem);
    $stmt1->execute([$id]);

    if($stmt1){
        echo "<script>alert('Item Removed From Site')</script>";
        header('Refresh: 0; URL=adminRemove.php');

    }
    else{
        echo "<script>alert('Item Could not be Removed')</script>";
        header('Refresh: 0; URL=adminRemove.php');
    }
}


// html to display everything on the page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>U Buy U Sell</title>
</head>
<body>
<header>
    <h3 class="navProductName"> UBUS </h3>

    <nav>
            <ul class="navList">
                <li> <a   href="adminPage.php"> Approve Items </a></li>
                <li> <a class="active"  href="adminRemove.php"> Remove Items </a></li>
            </ul>
    </nav>

    <a class="navLogout" href="../php/logout.php"> <button class="buttonsGrey" type="submit"> Logout </button></a>
</header>
<br>
<div style="text-align: center;"> 
    <h1 > Remove Items From The Website</h1>
    <br>
    <br>
    <table style="width: 100%;" >
            <thead>
                <tr>
                    <th> Item Name </th>
                    <th> View Item</th>
                    <th> Remove </th>
                </tr>
            </thead>
            <tbody>
               <?php

               //query to obtain live items and display on page
               $query = "Select * from Live_Items where isApproved = 1";
               $stmt = $conn->prepare($query);
               $stmt->execute();
       
               if($stmt){
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        echo"<tr>";
                        echo "<td>" .$row['Name']. "</td>";
                        echo "<td> <form action='adminViewItem.php' method='post'>  <input type='hidden' name='itemID' value=".$row['ItemID']."> <input type='submit' value='View' name='view'> </form> </td>";
                        echo "<td> <form action='adminRemove.php' method='post'>  <input type='hidden' name='itemID' value=".$row['ItemID']."> <input onclick='return confirm(\"Are you sure you want to remove this item?\");' type='submit' value='Remove' name='remove'> </form> </td>";
                        echo "</tr>";
                   }
               }
                
               ?>
            </tbody>
    </table>
</div>
</body>