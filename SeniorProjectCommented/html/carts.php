<?php

// start session and make sure user is logged in and not admin
session_start();
require "/home/mtware/connections/connect.php";


if($_SESSION['username'] != "" && $_SESSION['Admin'] == 0){
    
}else{
    session_destroy();
    header('location:../index.html');
}

//if button to add cart is pressed the following runs to attempt to add a cart to a users account, checks if cart max has been reached first 

if(isset($_POST['submit'])){
    $checkNum = "Select * from Cart where UserID = ?";
    $stmt = $conn->prepare($checkNum);
    $stmt->execute([$_SESSION['ID']]);

    if($stmt){
        if($stmt->rowCount() == 10){
            echo "<script>alert('Cart Maximum has already been reached')</script>";
            header('Refresh: 0; URL=carts.php');
        }
        else{
            $addCart = "Insert into Cart (UserId, CartName) values (?, ?)";
            $stmt1 = $conn->prepare($addCart);
            $stmt1->execute([$_SESSION['ID'], $_POST['cartName']]);

            if($stmt1){
                echo "<script>alert('Cart Added')</script>";
                header('Refresh: 0; URL=carts.php');
            }
            else{
                echo "<script>alert('Cart Could Not be added')</script>";
                header('Refresh: 0; URL=carts.php');
            }
        }
    }
    else{
        echo "<script>alert('Query Failed')</script>";
        header('Refresh: 0; URL=carts.php');
    }
}

//html to be displayed on page
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
                    <li> <a class="active"  href="carts.php"> Carts </a></li>
                </ul>
            </nav>
            <a class="navLogout" href="../php/logout.php"> <button class="buttonsGrey" type="submit"> Logout </button></a>
    </header>
    <br>
    <br>
    <div style="text-align: center">
    <h1> Your Carts</h1>
    <br>
        <table style="width: 100%;" >
            <thead>
                <tr>
                    <th> </th>
                    <th> Cart Name </th>
                    <th> Items in Cart </th>
                    <th> View Items </th>
                    <th> Checkout </th>
                </tr>
            </thead>
            <tbody>
               <?php
               //query used to display the carts associated with the user 
               $getCarts = "Select CartID, CartName, (Select COUNT(*) from CartItems where CartID = Cart.CartID) as Num from Cart where UserID = ?";
               $stmt2 = $conn->prepare($getCarts);
               $stmt2->execute([$_SESSION['ID']]);
               while($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
                echo"<tr>";
                echo "<td> <form action='../php/removeCart.php' method='post'>  <input type='hidden' name='cartID' value=".$row['CartID']."> <input onclick='return confirm(\"Are you sure you want to delete this cart?\");' type='submit' value='Delete' name='delete'> </form> </td>";
                echo "<td>" .$row['CartName']. "</td>";
                echo "<td>" .$row['Num']. "</td>";
                echo "<td> <form action='cartItems.php' method='post'> <input type='hidden' name='cartName' value=".$row['CartName'].">  <input type='hidden' name='cartID' value=".$row['CartID']."> <input type='submit' value='View' name='view'> </form> </td>";
                echo "<td> <form action='checkout.php' method='post'>  <input type='hidden' name='cartID' value=".$row['CartID']."> <input type='submit' value='Buy' name='buy'> </form> </td>";
                echo "</tr>";
                }

                
               ?>
            </tbody>
        </table>
        <br>
        <br>

        <h1 style="text-align: center; padding-top:10px"> Fill out to add a new Cart (Maximum of 10 carts allowed)</h1>
        <br>
        <div class=newCartParent>
            <div class="newCartChild">
                <form method="post" action="carts.php">
                    <input maxlength="20" pattern="[a-zA-Z0-9 ]{1,20}" class="newCartInput" type="text" name="cartName" placeholder="Cart Name">
                    <br>
                    <br>
                <button class="buttonsGrey"type="submit" name="submit"> Create Cart </button>
                </form>
            </div>
        </div>
    </div>
      
    
</body>
</html>