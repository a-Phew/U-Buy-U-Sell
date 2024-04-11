<?php
    require "/home/mtware/connections/connect.php";

    $itemID = $_POST['itemID'];
    
    //query to delete item from all carts
    $removeFromCarts = "delete from CartItems where ItemID = ?";
    $stmt1 = $conn->prepare($removeFromCarts);
    $stmt1->execute([$itemID]);

    //query to delete the item from live items
    $removeItem = "delete from Live_Items where ItemID = ?";
    $stmt2 = $conn->prepare($removeItem);
    try{
        $stmt2->execute([$itemID]);
        echo "<script>alert('Item Deleted')</script>";
        header('Refresh: 0; URL=../html/homepage.php');
    }catch (Exception $e){
        echo $e->getMessage();
        echo "<script>alert('Item could not be deleted')</script>";
        header('Refresh: 0; URL=../html/homepage.php');
    }

?> 