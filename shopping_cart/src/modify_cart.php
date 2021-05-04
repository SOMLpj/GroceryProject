<?php
session_start();
include 'config.php';

$pdo = pdo_connect_mysql();

$id = $_POST['id'];
if (isset($_POST['id'])) {
    switch ($_POST['action']) {
        case 'remove':
            $stmt = $pdo->prepare("DELETE FROM item_in_cart WHERE item_id = '$id'");
            $stmt->execute();
            echo 1;
            exit;
            break;
        case 'decrement':
            $newVal = $_POST['newVal'];
            $stmt = $pdo->prepare("UPDATE item_in_cart SET quantity = '$newVal' WHERE item_id = '$id'");
            $stmt->execute();
            echo 1;
            exit;
            break;
        case 'increment':
            $newVal = $_POST['newVal'];
            $stmt = $pdo->prepare("UPDATE item_in_cart SET quantity = '$newVal' WHERE item_id = '$id'");
            $stmt->execute();
            echo 1;
            exit;
            break;
        case 'getSubtotal':
            $subtotal = getSubtotal($pdo);
            echo $subtotal;
            exit;
            break;
        case 'getCartItemCount':
            $itemCount = getCartItemCount($pdo);
            echo $itemCount;
            exit;
            break;
        default:
            echo 0;
            exit;
            break;
    }
}
else {
    echo 0;
}

function getSubtotal($pdo) {
    $user_id = $_SESSION['user_id'];
    // Fetch cart id
    $stmt = $pdo->prepare("SELECT cart_id FROM shopping_cart WHERE FK_customer_id = '$user_id'");
    $stmt->execute();
    $cart_id = $stmt->fetchColumn();

    // Fetch items in cart using the cart id
    $stmt = $pdo->prepare("SELECT * FROM item_in_cart WHERE FK_cart_id = '$cart_id'");
    $stmt->execute();
    $items_in_cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch product information that are in cart
    $stmt = $pdo->prepare("SELECT product.* FROM item_in_cart, product WHERE product.product_id = item_in_cart.FK_product_id AND item_in_cart.FK_cart_id = '$cart_id'");
    $stmt->execute();
    $product_info = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate subtotal
    $subtotal = 0.00;
    foreach ($items_in_cart as $item) {
        $item_info = [];
        foreach($product_info as $info) {
            if ($info['product_id'] === $item['FK_product_id']) {
                $item_info = $info;
            }
        }        
        $subtotal += $item['quantity'] * $item_info['price'];
    }

    return $subtotal;
}

function getCartItemCount($pdo) {
    $user_id = $_SESSION['user_id'];

    // Fetch cart id
    $stmt = $pdo->prepare("SELECT cart_id FROM shopping_cart WHERE FK_customer_id = '$user_id'");
    $stmt->execute();
    $cart_id = $stmt->fetchColumn();

    // Fetch items in cart using the cart id
    $stmt = $pdo->prepare("SELECT * FROM item_in_cart WHERE FK_cart_id = '$cart_id'");
    $stmt->execute();
    $items_in_cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate item count
    $item_in_cart_count = 0;
    foreach ($items_in_cart as $item) {     
        $item_in_cart_count += $item['quantity'];
    }

    return $item_in_cart_count;
}
?>