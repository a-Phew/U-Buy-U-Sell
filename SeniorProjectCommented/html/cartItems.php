<?php

// start session and make sure user is logged in and not admin
session_start();
require "/home/mtware/connections/connect.php";


if($_SESSION['username'] != "" && $_SESSION['Admin'] == 0){
    
}else{
    session_destroy();
    header('location:../index.html');
}


// query used to get the items found within the cart
$cartID = $_POST['cartID'];

$CartInfo = "Select * from Cart where CartID = ?";
$stmt3 = $conn->prepare($CartInfo);
$stmt3->execute([$cartID]);
$row1 = $stmt3->fetch(PDO::FETCH_ASSOC);


// if remove button is clicked the following run in order to remove the item from the cart
if(isset($_POST['delete'])){

    $removeFromCart = "delete from CartItems where CartID = ? and ItemID = ?";
    $stmt1 = $conn->prepare($removeFromCart);
    $stmt1->execute([$cartID, $_POST['itemID']]);

    if($stmt1){
        echo "<script>alert('Item removed from cart')</script>";
        header('Refresh: 0; URL=carts.php');
    }
    else{
        echo "<script>alert('Item could not be removed')</script>";
        header('Refresh: 0; URL=carts.php');
    }

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
                    <li> <a  href="orderhistory.php"> Order History</a></li>
                    <li> <a  href="carts.php"> Carts </a></li>
                </ul>
            </nav>
            <a class="navLogout" href="../php/logout.php"> <button class="buttonsGrey" type="submit"> Logout </button></a>
    </header>
    <br>
    <br>
    <div style="text-align: center">
    <h1> Items in <?php echo $row1['CartName']; ?> </h1>
    <br>
        <table style="width: 100%;" >
            <thead>
                <tr>
                    <th> Item Name </th>
                    <th> Item Price </th>
                    <th> Remove </th>
                </tr>
            </thead>
            <tbody>
               <?php
               //query used to display the items within the cart
               $getCartItems = "Select Live_Items.ItemID, Live_Items.Name, Live_Items.Price from Live_Items left outer join CartItems on Live_Items.ItemID = CartItems.ItemID where CartItems.CartID = ?";
               $stmt2 = $conn->prepare($getCartItems);
               $stmt2->execute([$cartID]);
               while($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
                echo"<tr>";
                echo "<td>" .$row['Name']. "</td>";
                echo "<td>$" .number_format($row['Price'],2). "</td>";
                echo "<td> <form action='cartItems.php' method='post'> <input type='hidden' name='cartID' value=".$cartID.">  <input type='hidden' name='itemID' value=".$row['ItemID']."> <input onclick='return confirm(\"Are you sure you want to remove this item from the cart?\");' type='submit' value='Remove' name='delete'> </form> </td>";
                echo "</tr>";
                }
               ?>
            </tbody>
        </table>
        <br>
        <br>
        <br>
        <div class=newCartParent>
            <div class="newCartChild">
                <form method="post" action="checkout.php">
                    <input type="hidden" name="cartID" id="cartID" value="<?php echo $cartID; ?>">
                <button class="buttonsGrey"type="submit" name="checkout"> Checkout </button>
                </form>
            </div>
        </div>
    </div>
      
    
</body>
</html>