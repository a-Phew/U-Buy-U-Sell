<?php

// start session and make sure user is logged in and not admin
session_start();
require "/home/mtware/connections/connect.php";

if($_SESSION['username'] != "" && $_SESSION['Admin'] == 0){
    
}else{
    session_destroy();
    header('location:../index.html');
}

// when buy button is clicked the following runs in order to process the order, address is uploaded to user table, new order is created,
// orderID is obtained, items in the cart are fetched, each item is removed from all other carts, removed from live items, and added to the new order


if(isset($_POST['submit'])){

    $CARTID = $_POST['CARTID'];


    $addShipping = "update User set address = ?, city = ?, state = ?, zip = ? where User_ID = ?";
    $stmt1 = $conn->prepare($addShipping);
    $stmt1->execute([$_POST['address'], $_POST['city'], $_POST['state'], intval($_POST['zip']), $_SESSION['ID']]);

    if($stmt1){
        $createOrder = "insert into Orders (UserID) values (?)";
        $stmt2 = $conn->prepare($createOrder);
        $stmt2->execute([$_SESSION['ID']]);

        if($stmt2){
            $getOrderID = "select LAST_INSERT_ID() as ID";
            $stmt3 = $conn->prepare($getOrderID);
            $stmt3->execute();
            $row1 = $stmt3->fetch(PDO::FETCH_ASSOC);

            $orderID = $row1['ID'];

            if($stmt3){
                $getItems = "Select Live_Items.ItemID, Name, Description, Price from Live_Items left outer join CartItems on Live_Items.ItemID = CartItems.ItemID where CartItems.CartID = ?";
                $stmt4 = $conn->prepare($getItems);
                $stmt4->execute([$CARTID]);

                $results = $stmt4->fetchAll(PDO::FETCH_ASSOC);

                foreach($results as $row2){
                    $ITEMID = $row2['ItemID'];
                    $ITEMNAME = $row2['Name'];
                    $DESCRIPTION = $row2['Description'];
                    $PRICE = doubleval($row2['Price']);

                    $removeItemsCart = "delete from CartItems where ItemID = ?";
                    $stmt5 = $conn->prepare($removeItemsCart);
                    $stmt5->execute([$ITEMID]);

                    $removeItem = "delete from Live_Items where ItemID = ?";
                    $stmt6 = $conn->prepare($removeItem);
                    $stmt6->execute([$ITEMID]);

                    $addToOrder = "insert into OrderItems (ItemID, OrderID, Name, Description, Price, purchaseDate) values (?,?,?,?,?,curdate())";
                    $stmt7 = $conn->prepare($addToOrder);
                    $stmt7->execute([$ITEMID, $orderID, $ITEMNAME, $DESCRIPTION, $PRICE]);

                }

                echo "<script>alert('Purchase Complete')</script>";
                header('Refresh: 0; URL=orderhistory.php');
            }
            else{
                echo "<script>alert('Id Not found')</script>";
                header('Refresh: 0; URL=carts.php');
            }
        }
        else{
            echo "<script>alert('Order Not Created')</script>";
            header('Refresh: 0; URL=carts.php');
        }    
    }
    else{
        echo "<script>alert('Payment could not be processed')</script>";
        header('Refresh: 0; URL=carts.php');
    }

}
else{
    //on page load if no submit has been posted check if cart is empty if so return to carts not allowing checkout
    $cartID = $_POST['cartID'];

    $cartInfo = "Select CartID from CartItems where CartID = ?";
    $stmt8 = $conn->prepare($cartInfo);
    $stmt8->execute([$cartID]);

    if($stmt8->rowCount() == 0){
        echo "<script>alert('No Items In Cart')</script>";
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
    <h1> Items You are Purchasing </h1>
    <br>
        <table style="width: 100%;" >
            <thead>
                <tr>
                    <th> Name </th>
                    <th> Price </th>
                </tr>
            </thead>
            <tbody>
               <?php
               //query to display each of the items being purchased
               $getCartItems = "Select Live_Items.ItemID, Live_Items.Name, Live_Items.Price from Live_Items left outer join CartItems on Live_Items.ItemID = CartItems.ItemID where CartItems.CartID = ?";
               $stmt = $conn->prepare($getCartItems);
               $stmt->execute([$cartID]);
               while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $price += $row['Price'];
                echo"<tr>";
                echo "<td>" .$row['Name']. "</td>";
                echo "<td> $" .number_format($row['Price'],2). "</td>";
                echo "</tr>";
                }
               ?>
            </tbody>
        </table>
        <br>
        <h1> Total Price </h1>
        <h3 style="margin: 5px 0px;"> <?php echo "$" .number_format($price,2).""; ?></h3>
        <div class=paymentParent>
            <div class="paymentChild">
                <form method="post" action="checkout.php">
                    <label class="paymentLabel" for="cardInfo"> Card Info</label>
                    <br>
                    <input type="hidden" name="CARTID" id="cartID" value="<?php echo $cartID; ?>">
                    <input class="paymentInput" type="text" pattern="[a-zA-Z ]+" placeholder="Name on Card" required>
                    <input class="paymentInput" minlength="16" maxlength="16" type="text" pattern="[0-9]{16}" placeholder="Card Number" required>
                    <input class="paymentInput" minlength="3" maxlength="3" type="text" pattern="[0-9]{3}" placeholder="CVV" required>
                    <input class="paymentInput" type="text" maxlength="5" pattern="[0-9]{5}" placeholder="Zip" required>
                    <br>
                    <br>
                    <label class="paymentLabel" for="Address"> Shipping Details</label>
                    <br>
                    <input class="paymentInput" name="address" type="text" pattern="[0-9a-zA-Z ]+" maxlength="50" placeholder="Address" required>
                    <input class="paymentInput" name="city" type="text" pattern="[A-Za-z ]+" maxlength="50" placeholder="City" required>
                    <br>
                    <select class="paymentSelect" name="state" id="state" required>
                        <option value="" disabled selected>State</option>
                        <option value="AL"> AL</option>
                        <option value="AK"> AK</option>
                        <option value="AZ"> AZ</option>
                        <option value="AR"> AR</option>
                        <option value="CA"> CA</option>
                        <option value="CO"> CO</option>
                        <option value="CT"> CT</option>
                        <option value="DE"> DE </option>
                        <option value="FL"> FL</option>
                        <option value="GA"> GA</option>
                        <option value="HI"> HI</option>
                        <option value="ID"> ID</option>
                        <option value="IN">IN</option>
                        <option value="IA"> IA</option>
                        <option value="KS"> KS</option>
                        <option value="KY"> KY</option>
                        <option value="LA"> LA</option>
                        <option value="ME"> ME</option>
                        <option value="MD">MD</option>
                        <option value="MA"> MA</option>
                        <option value="MI"> MI</option>
                        <option value="MN"> MN</option>
                        <option value="MS"> MS</option>
                        <option value="MO"> MO</option>
                        <option value="MT"> MT</option>
                        <option value="NE"> NE</option>
                        <option value="NV"> NV</option>
                        <option value="NH"> NH</option>
                        <option value="NJ"> NJ</option>
                        <option value="NM"> NM</option>
                        <option value="NY"> NY</option>
                        <option value="NC"> NC</option>
                        <option value="ND"> ND</option>
                        <option value="OH"> OH</option>
                        <option value="OK"> OK</option>
                        <option value="OR"> OR</option>
                        <option value="PA"> PA</option>
                        <option value="RI"> RI</option>
                        <option value="SC"> SC</option>
                        <option value="SD"> SD</option>
                        <option value="TN"> TN</option>
                        <option value="TX"> TX</option>
                        <option value="UT"> UT</option>
                        <option value="VT"> VT</option>
                        <option value="VA"> VA</option>
                        <option value="WA"> WA </option>
                        <option value="WV"> WV </option>
                        <option value="WI"> WI</option>
                        <option value="WY"> WY</option>
                    </select>
                    <br>
                    <input class="paymentInput" type="text" maxlength="5" pattern="[0-9]{5}" name="zip" placeholder="Zip" required>
                    <br>
                    <button class="buttonsGrey"type="submit" name="submit"> Buy</button>
                </form>
            </div>
        </div>
    </div>
      
    
</body>
</html>