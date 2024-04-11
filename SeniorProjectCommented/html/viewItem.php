<?php

// start session and make sure user is logged in and not admin
session_start();

if($_SESSION['username'] != "" && $_SESSION['Admin'] == 0){
    
}else{
    session_destroy();
    header('location:../index.html');
}

require "/home/mtware/connections/connect.php";


// query to obtain information regarding a specific item
$ItemInfo = "select * from Live_Items where ItemID = ?";
$stmt = $conn->prepare($ItemInfo);
$stmt->execute([$_POST['itemID']]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);


//html to be displayed on the page, echos are used to display specific item information
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
        <h1 class="h1Item"> <?php echo $row['Name']; ?> </h1>
        <br>
        <?php echo "<img src=../itemImages/".$row['image1']." alt='First image provided for item' width='600' height = '300'>"; ?>
        <?php echo "<img src=../itemImages/".$row['image2']." alt='Second image provided for item' width='600' height = '300'>"; ?>
        <div>
            <h2 style="margin: 15px"> Description </h2>
            <p style="font-size: 20px"> <?php echo $row['Description']; ?> </p>
            <h2 style="margin-top: 40px; margin-bottom: 15px"> Price </h2>
            <p style="font-size: 20px"> $<?php echo number_format($row['Price'], 2); ?> </p>
            <div class=newCartParent>
                <div class="newCartChild">
                    <form method="post" action="../php/addToCart.php">
                        <input type="hidden" name="itemID" value="<?php echo $row['ItemID']; ?>">
                        <label for="cart"> Select Cart:</label>
                        <select name="cart" id="cart" required>
                        <option value="" disabled selected>Pick Cart</option>
                            <?php
                                // query to display all the users carts in order to add the item to a cart
                                $stmt1 = $conn -> prepare("select CartID, CartName from Cart where UserID = ?");
                                $stmt1 -> execute([$_SESSION['ID']]);
                                while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)){
                                    echo "<option value = '".$row1['CartID']."'>".$row1['CartName']."</option>";
                                }
                            ?>
                        </select>
                        <br>
                        <br>
                    <button class="buttonsGrey"type="submit" name="submit"> Add to Cart </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    
</body>
</html>