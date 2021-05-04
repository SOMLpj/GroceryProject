<?php

// Function for adding only one quantity to cart
function add_one_to_cart($product_id, $user_id) {
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare("SELECT quantity FROM item_in_cart WHERE FK_customer_id = $user_id AND FK_product_id = $product_id");
    $stmt->execute();
    $item_in_cart_quantity = $stmt->fetchColumn();

    // Fetch stock of item to add
    try {
        $stmt = $pdo->prepare("SELECT stock FROM product WHERE product_id = $product_id");
        $stmt->execute();
        $item_stock = $stmt->fetchColumn();
    // Catch exception if stock is not found
    } catch (Exception $e) {
        $item_stock = 0;
    }

    // If (inventory stock - item quantity) is less than or equal to 0, do not allow add to cart
    if ($item_stock - $item_in_cart_quantity <= 0) {
        echo '<script>alert("Unable to add to cart. Quantity in cart exceeds current item stock.")</script>';    
    } else {
        // Create new item in cart if current quantity is 0
        if ($item_in_cart_quantity == 0) {
            $stmt = $pdo->prepare("INSERT INTO item_in_cart VALUES (0, $user_id, $product_id, 1)");
            $stmt->execute();
        // Add 1 to quantity if quantity is not 0
        } else {
            $stmt = $pdo->prepare("UPDATE item_in_cart SET quantity = quantity + 1 WHERE FK_customer_id = $user_id AND FK_product_id = $product_id");
            $stmt->execute();
        }
        echo '<script>alert("Successfully updated cart.")</script>'; 
        header("Refresh:0");
    }
}

// Function for adding specified quantity to cart
function add_to_cart($product_id, $user_id, $quantity_to_add) {
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare("SELECT quantity FROM item_in_cart WHERE FK_customer_id = $user_id AND FK_product_id = $product_id");
    $stmt->execute();
    $item_in_cart_quantity = $stmt->fetchColumn();
   
    // Fetch stock of item to add
    try {
        $stmt = $pdo->prepare("SELECT stock FROM product WHERE product_id = $product_id");
        $stmt->execute();
        $item_stock = $stmt->fetchColumn();
    } catch (Exception $e) {
        $item_stock = 0;
    }

    // If (inventory stock - quantity in cart - item quantity) is less than or equal to 0, do not allow add to cart
    if ($item_stock - $item_in_cart_quantity - $quantity_to_add <= 0) {
        echo '<script>alert("Unable to add to cart. Quantity in cart exceeds current item stock.")</script>';    
    } else {
        // Create new item in cart if current quantity is 0
        if ($item_in_cart_quantity == 0) {
            $stmt = $pdo->prepare("INSERT INTO item_in_cart VALUES (0, $user_id, $product_id, $quantity_to_add)");
            $stmt->execute();
        // Add 1 to quantity if quantity is not 0
        } else {
            $stmt = $pdo->prepare("UPDATE item_in_cart SET quantity = quantity + $quantity_to_add WHERE FK_customer_id = $user_id AND FK_product_id = $product_id");
            $stmt->execute();
        }
        echo '<script>alert("Successfully updated cart.")</script>'; 
    }
}
?>