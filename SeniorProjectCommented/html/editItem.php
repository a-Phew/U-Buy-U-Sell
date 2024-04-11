<?php

// start session and make sure user is logged in and not admin
session_start();
if($_SESSION['username'] != "" && $_SESSION['Admin'] == 0){
    
}else{
    session_destroy();
    header('location:../index.html');
}

require "/home/mtware/connections/connect.php";

//if edit button is clicked the following runs in order to get the information associated with the item

if(isset($_POST['edit'])){
    $id = $_POST['itemID'];
    $getItemInfo = "select * from Live_Items where ItemID = ?";
    $stmt = $conn->prepare($getItemInfo);
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
}


// when edits are submitted the following runs to attempt to update the information within the database, item is set back to unapproved
if(isset($_POST['submit'])){
    $item = $_POST['itemID'];
    $name = $_POST['itemName'];
    $desc = $_POST['desc'];
    $price = doubleval($_POST['price']);


    $image1DB = basename($_FILES['image1']['name']);
    $image2DB = basename($_FILES['image2']['name']);

    $image1Temp = $_FILES['image1']['tmp_name'];
    $image2Temp = $_FILES['image2']['tmp_name'];
    

    $folder1 = "../itemImages/".$image1DB;
    $folder2 = "../itemImages/".$image2DB;

    if(move_uploaded_file($image1Temp, $folder1)){
        if(move_uploaded_file($image2Temp, $folder2)){
            $editItem = "update Live_Items set Name = ?, Description = ?, Price = ?, image1 = ?, image2 = ?, isApproved = 0 where ItemID = ?";
            $ps = $conn->prepare($editItem);
            try{
                $ps->execute([$name,$desc,$price,$image1DB,$image2DB, $item]);
                echo "<script>alert('Edits submitted for Approval')</script>";
                header('Refresh: 0; URL=homepage.php');
            }catch (Exception $e){
                echo $e->getMessage();
                header('Refresh: 0; URL=404page.html');
            }
        }
        else{
            echo "<script>alert('Image 2 failed')</script>";
            header('Refresh: 0; URL=homepage.php');
        }
    }
    else{
        echo "<script>alert('Image 1 failed')</script>";
        header('Refresh: 0; URL=homepage.php');
    }
    
}


//html to display on the page, echos are used to display information already associated with the item
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
    

    <div class="signupContainer">
        <h1 class="signupH1"> Edit your Item</h1>
        <form action="editItem.php" method="post" enctype="multipart/form-data">
            <div style="text-align: center">
                <input type='hidden' name='itemID' value=' <?php echo $id; ?>'>
                <input class="signupInput" maxlength="25" pattern="[a-zA-Z0-9 ]{1,25}" name="itemName" type="text" placeholder="Item Name" value= <?php echo $row['Name']; ?> required>
                <br>
                <br>
                <label for="images"> Please Reupload Images (Must be less than 2MB): </label>
                <br>
                <br>
                <input type="file" name="image1">
                <input type="file" name="image2">
                <textarea class="signupTA" name="desc" id="desc" rows="10" cols="50" maxlength="500" placeholder="Description" required> <?php echo $row['Description']; ?></textarea>
                <input class="signupInput" maxlength="8" name="price" pattern='[0-9]{1,5}\.[0-9]{1,2}' type="text" placeholder="Price (cannot exceed $10000.00)" step=".01" value= <?php echo number_format($row['Price'],2); ?> required>
                <br>
                <br>
                <br>
                <button name="submit" class="buttonsGrey" type="submit"> Confirm </button>
            </div>    
        </form>
    </div> 
    
</body>
</html>