<?php

// start session and make sure user is logged in and not admin
session_start();
if($_SESSION['username'] != "" && $_SESSION['Admin'] == 0){
    
}else{
    session_destroy();
    header('location:../index.html');
}


// when the user submits the form to add an item the following runs attempting to upload the item for approval 

if(isset($_POST['submit'])){
    require "/home/mtware/connections/connect.php";

    $name = $_POST['itemName'];
    $desc = $_POST['desc'];
    $price = doubleval($_POST['price']);
    $category = $_POST['category'];
    $isApproved = 0;
    $creatorID = $_SESSION['username'];


    // the following variable types are used to upload the photos 

    $image1DB = basename($_FILES['image1']['name']);
    $image2DB = basename($_FILES['image2']['name']);

    $image1DBNoSpace = str_replace(' ', '', $image1DB); 
    $image2DBNoSpace = str_replace(' ', '', $image2DB); 

    $image1Temp = $_FILES['image1']['tmp_name'];
    $image2Temp = $_FILES['image2']['tmp_name'];
    
    $folder1 = "../itemImages/".$image1DBNoSpace;
    $folder2 = "../itemImages/".$image2DBNoSpace;

    $filetype1 = pathinfo($folder1, PATHINFO_EXTENSION);
    $filetype2 = pathinfo($folder2, PATHINFO_EXTENSION);

    $allowTypes = array('jpg', 'png', 'jpeg');


    // a bunch of ifs checking to make sure everything is processed correctly, first checks image file type, then attempts to upload image to folder,
    // then sends all the required data to the database if iamge processing is successful 
    if(in_array($filetype1, $allowTypes)){
        if(in_array($filetype2, $allowTypes)){
            if(move_uploaded_file($image1Temp, $folder1)){
                if(move_uploaded_file($image2Temp, $folder2)){
                    $addItem = "insert into Live_Items(Name, Description, Price, Category, isApproved, image1, image2, CreatorID) values (?,?,?,?,?,?,?,?)";
                    $ps = $conn->prepare($addItem);
                    try{
                        $ps->execute([$name,$desc,$price,$category,$isApproved, $image1DBNoSpace, $image2DBNoSpace, $creatorID]);
                        echo "<script>alert('Item Uploaded')</script>";
                        header('Refresh: 0; URL=selling.php');
                    }catch (Exception $e){
                        echo $e->getMessage();
                        header('Refresh: 0; URL=404page.html');
                    }
                }
                else{
                    echo "<script>alert('Image 2 too large')</script>";
                    header('Refresh: 0; URL=selling.php');
                }
            }
            else{
                echo "<script>alert('Image 1 too large')</script>";
                header('Refresh: 0; URL=selling.php');
            }
        }
        else{
            echo "<script>alert('Image 2 Invalid form, must be jpg, png, or jpeg')</script>";
            header('Refresh: 0; URL=selling.php');
        } 
    }
    else{
        echo "<script>alert('Image 1 Invalid form, must be jpg, png, or jpeg')</script>";
        header('Refresh: 0; URL=selling.php');
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
                <li> <a class="active"  href="selling.php"> USell </a></li>
                <li> <a  href="orderhistory.php"> Order History</a></li>
                <li> <a  href="carts.php"> Carts </a></li>
            </ul>
        </nav>
        <a class="navLogout" href="../php/logout.php"> <button class="buttonsGrey" type="submit"> Logout </button></a>
        </header>
    <br>
    <br>
    

    <div class="signupContainer">
        <h1 class="signupH1"> Fill Out To Sell An Item</h1>
        <form action="selling.php" method="post" enctype="multipart/form-data">
            <div style="text-align: center">
                <input class="signupInput" maxlength="25" pattern="[a-zA-Z0-9 ]{1,25}" name="itemName" type="text" placeholder=" Item Name" required>
                <br>
                <br>
                <label for="images"> Upload Images (Must be less than 2MB): </label>
                <br>
                <br>
                <input type="file" name="image1" required>
                <input type="file" name="image2" required>
                <textarea class="signupTA" name="desc" id="desc" rows="10" cols="50" maxlength="500" placeholder="Description" required></textarea>
                <input class="signupInput" maxlength="8" name="price" pattern='[0-9]{1,5}\.[0-9]{1,2}' type="text" placeholder="Price (cannot exceed $10000.00)" step=".01" required>
                <br>
                <br>
                <label for="category"> Choose a Category: </label>
                <select name="category" required>
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
                <button name="submit" class="buttonsGrey" type="submit"> Confirm </button>
            </div>
                
        </form>
    </div> 
    
</body>
</html>