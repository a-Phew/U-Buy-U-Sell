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


    //if button for approval was clicked the following will run
    if(isset($_POST['approve'])){
        $id = $_POST['itemID'];

        $approveItem = "update Live_Items set isApproved = 1 where ItemID = ?";
        $stmt2 = $conn->prepare($approveItem);
        $stmt2->execute([$id]);

        if($stmt2){
            echo "<script>alert('Item Approved')</script>";
            header('Refresh: 0; URL=adminPage.php');
        }
        else{
            echo "<script>alert('Approval Failed')</script>";
            header('Refresh: 0; URL=adminPage.php');
        }
    }


    // if button for unapproval is clicked the following will run
    if(isset($_POST['unapprove'])){
        $id = $_POST['itemID'];

        $unapproveItem = "delete from Live_Items where ItemID = ?";
        $stmt3 = $conn->prepare($unapproveItem);
        $stmt3->execute([$id]);

        if($stmt3){
            echo "<script>alert('Item Unapproved')</script>";
            header('Refresh: 0; URL=adminPage.php');
        }
        else{
            echo "<script>alert('Item could not be unapproved')</script>";
            header('Refresh: 0; URL=adminPage.php');
        }
    }



    // html to be displayed on page
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
                <li> <a class="active"  href="adminPage.php"> Approve Items </a></li>
                <li> <a  href="adminRemove.php"> Remove Items </a></li>
            </ul>
    </nav>

    <a class="navLogout" href="../php/logout.php"> <button class="buttonsGrey" type="submit"> Logout </button></a>
</header>
<br>

<div style="text-align: center;"> 
    <h1 > Items Needing Approval </h1>
    <br>
    <br>
    <table style="width: 100%;" >
            <thead>
                <tr>
                    <th> Item Name </th>
                    <th> View Item</th>
                    <th> Approve </th>
                    <th> Unapprove </th>
                </tr>
            </thead>
            <tbody>
               <?php

               // query to obtain items needing approval and appending them to page
               $query = "Select * from Live_Items where isApproved = 0";
               $stmt = $conn->prepare($query);
               $stmt->execute();
       
               if($stmt){
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        echo"<tr>";
                        echo "<td>" .$row['Name']. "</td>";
                        echo "<td> <form action='adminViewItem.php' method='post'>  <input type='hidden' name='itemID' value=".$row['ItemID']."> <input type='submit' value='View' name='view'> </form> </td>";
                        echo "<td> <form action='adminPage.php' method='post'>  <input type='hidden' name='itemID' value=".$row['ItemID'].">  <input onclick='return confirm(\"Are you sure you want to approve this item?\");' type='submit' value='Approve' name='approve'> </form> </td>";
                        echo "<td> <form action='adminPage.php' method='post'>  <input type='hidden' name='itemID' value=".$row['ItemID']."> <input onclick='return confirm(\"Are you sure you want this item unapproved?\");' type='submit' value='Unapprove' name='unapprove'> </form> </td>";
                        echo "</tr>";
                   }
               }
                
               ?>
            </tbody>
    </table>
</div>
    


</body>
</html>