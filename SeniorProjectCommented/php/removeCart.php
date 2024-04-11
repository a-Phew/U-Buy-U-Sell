<?php
    require "/home/mtware/connections/connect.php";

    $cartID = $_POST['cartID'];

    //query to delete all items from the cart
    $removeItemsFromCart = "delete from CartItems where CartID = ?";
    $stmt1 = $conn->prepare($removeItemsFromCart);
    $stmt1->execute([$cartID]);


    if($stmt1){
        // query to delete the cart from the users account
        $removeCart= "delete from Cart where CartID = ?";
        $stmt = $conn->prepare($removeCart);
        try{
            $stmt->execute([$cartID]);
            echo "<script>alert('Cart Deleted')</script>";
            header('Refresh: 0; URL=../html/carts.php');
        }catch (Exception $e){
            echo $e->getMessage();
            echo "<script>alert('Cart could not be deleted')</script>";
            header('Refresh: 0; URL=../html/carts.php');
        }
    }
    else{
        echo "<script>alert('Items could not be removed')</script>";
        header('Refresh: 0; URL=../html/carts.php');
    }
    

?>