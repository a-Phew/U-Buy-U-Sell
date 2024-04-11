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

// query used to obtain information regarding a specific item

$ItemInfo = "select * from Live_Items where ItemID = ?";
$stmt = $conn->prepare($ItemInfo);
$stmt->execute([$_POST['itemID']]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);


// html displayed on the page, the php echos are used to display specific parts associated with an item
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
                <li> <a   href="adminRemove.php"> Remove Items </a></li>
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
        </div>
    </div>
</body>