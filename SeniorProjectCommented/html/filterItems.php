<?php

// start session and make sure user is logged in and not admin
session_start();

if($_SESSION['username'] != "" && $_SESSION['Admin'] == 0){
    
}else{
    session_destroy();
    header('location:../index.html');
}

require "/home/mtware/connections/connect.php";


// get the filter and if its null return to all items page
$filter = $_POST['filter'];
if($filter == "/"){
    header('location:buying.php');
}


//html to display on the page
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
                    <li> <a class="active" href="buying.php"> UBuy </a></li>
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
        <h4> Filter By Category:</h4>
        <div class=newFilterParent>
            <div class="newFilterChild">
                <form method="post" action="filterItems.php">
                    <select class="newFilterSelect" name="filter" id="filter" required>
                        <option value="/">All Items</option>
                        <option value="clothing">Clothing</option>
                        <option value="cooking">Cooking</option>
                        <option value="crafts">Crafts</option>
                        <option value="decoration">Decoration</option>
                        <option value="entertainment">Entertainment</option>
                        <option value="outdoors">Outdoors</option>
                        <option value="shoes">Shoes</option>
                        <option value="sports">Sports</option>
                    </select>
                    <br>
                    <br>
                <button class="buttonsGrey"type="submit" name="submit"> Apply Filter </button>
                </form>
            </div>
        </div>
        <br>
        <h1> Items Available For Purchase </h1>
        <br>
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th> Item Name </th>
                    <th> Price </th>
                    <th> View Item </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    //query to get all items that are associated with the specific filter
                    $liveItems = "Select * from Live_Items where isApproved= 1 and creatorID != ? and Category = ?";
                    $stmt = $conn->prepare($liveItems);
                    $stmt->execute([$_SESSION['username'], $filter]);
                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            $price = number_format($row['Price'],2);
                            echo"<tr>";
                            echo "<td>" .$row['Name']. "</td>";
                            echo "<td> $" .$price. "</td>";
                            echo "<td> <form action='viewItem.php' method='post'>  <input type='hidden' name='itemID' value=".$row['ItemID']."> <input type='submit' value='View' name='view'> </form> </td>";
                            echo "</tr>";
                        }
                ?>
            </tbody>
        </table>

        
    </div>
    
    
</body>
</html>