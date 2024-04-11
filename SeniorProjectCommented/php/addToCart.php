<?php
    require "/home/mtware/connections/connect.php";

    // form variables
    $itemID = $_POST['itemID'];
    $cartID = $_POST['cart'];

    //query to check if item is in cart already
    $checkCart = "select * from CartItems where CartID = ? and ItemID = ?";
    $stmt1 = $conn->prepare($checkCart);
    $stmt1->execute([$cartID, $itemID]);

    //if item in cart already then dont attempt to insert, if not then run query to insert item and cart into database
    if($stmt1->rowCount() == 1){
        echo "<script>alert('Item Already in Cart')</script>";
        header('Refresh: 0; URL=../html/buying.php');
    }
    else{
        $addToCart = "insert into CartItems (CartID, ItemID) values(?,?)";
        $stmt2 = $conn->prepare($addToCart);
        try{
            $stmt2->execute([$cartID, $itemID]);
            echo "<script>alert('Item Added To Cart')</script>";
            header('Refresh: 0; URL=../html/buying.php');
        }catch (Exception $e){
            echo $e->getMessage();
            echo "<script>alert('Item Could not be added')</script>";
            header('Refresh: 0; URL=../html/buying.php');
        }
    }
?>