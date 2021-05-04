<?php 
session_start();
include 'config.php';
$pdo = pdo_connect_mysql();

$_SESSION['user_id'] = 1;

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

// Fetch customer's first name
$stmt = $pdo->prepare("SELECT first_name FROM customer WHERE customer_id = '$user_id'");
$stmt->execute();
$first_name = $stmt->fetchColumn();

// print_r($items_in_cart);
// echo "<br>";
// print_r($product_info);
// print_r($first_name);

$item_in_cart_count = 0;

// Calculate subtotal and item count
$subtotal = 0.00;
foreach ($items_in_cart as $item) {
    $item_info = [];
    foreach($product_info as $info) {
        if ($info['product_id'] === $item['FK_product_id']) {
            $item_info = $info;
        }
    }

    // echo '<br>';
    // echo 'Q: ' . $item['quantity'] . ' FK: '. $item['FK_product_id'] . ' P: ' . $item_info['price'] . '<br>';
    
    $subtotal += $item['quantity'] * $item_info['price'];
    $item_in_cart_count += $item['quantity'];
}
// echo 'Subtotal: ' . $subtotal;
// echo 'Item count: ' . $item_in_cart_count;

if ($item_in_cart_count == 0 || $item_in_cart_count == 1) {
    $item_in_cart_count_text = $item_in_cart_count .  ' item';
}
else {
    $item_in_cart_count_text = $item_in_cart_count .  ' items';
} 



?>

<!DOCTYPE html>
<html>
    <head>
        <title>Shopping Cart</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="../js/cart.js"></script>
        <link rel="stylesheet" href="../css/cart.css">
    </head>

    <header>
        <!-- Navigation bar -->
        <div class="menu-bar-container">
            <div class="menu-bar-logo-wrapper">
                <div class="menu-bar-logo">
                    <img src="../resource/icon/logo.svg">
                </div>
            </div>
            <div class="menu-bar-search-wrapper">
                <form>
                    <input type="text" placeholder="Search">
                    <button type="submit">
                        <img src="../resource/icon/search-icon.svg">
                    </button>
                </form>
            </div>
            <div class="menu-bar-user-wrapper">
                <div class="user-info-wrapper">
                    <div class="menu-bar-user-icon">
                        <img src="../resource/icon/user-icon.svg">
                    </div>
                    <div class="user-name">
                        <span><?=$first_name?></span>
                    </div>
                </div>
            </div>
            <div class="menu-bar-cart-wrapper">
                <div class="cart-info-wrapper">
                    <div class="menu-bar-cart-icon">
                        <img src="../resource/icon/cart-icon.svg">
                    </div>
                    <span>Cart:&nbsp;</span>
                    <span id="cart-item-count-text"><?=$item_in_cart_count?></span>
                </div>
            </div>
        </div>
    </header>


    <body>
        <div class="main-container">
            <div class="main-cart-container">
                <div class="cart-wrapper">
                    <div class="item-row-wrapper cart-title-wrapper">
                        <div class="product-img-wrapper"></div>
                        <div class="product-name-wrapper product-name-title-wrapper">
                            Item
                        </div>
                        <div class="product-price-wrapper">Price</div>
                        <div class="product-quantity-wrapper">Quantity</div>
                        <div class="product-total-wrapper">Total</div>
                        <div class="product-remove-wrapper"></div>
                    </div>
                    <div class="empty-cart-msg-row-wrapper" id="empty-msg-row">
                        <span>Your cart is empty.</span>
                    </div>
                    <?php
                    foreach ($items_in_cart as $item):
                        $id = $item['item_id'];
                        $quantity = $item['quantity'];
                        $item_info = [];
                        foreach($product_info as $info) {
                            if ($info['product_id'] === $item['FK_product_id']) {
                                $item_info = $info;
                                break;
                            }
                        }
                        $image = $item_info['image'];
                        $product_name = $item_info['product_name'];
                        $price = $item_info['price'];
                    ?>
                    <div class="item-row-wrapper" id="item-row-<?=$id?>">
                        <div class="product-img-wrapper" id="img-<?=$id?>">
                            <?php
                            $img = !empty($image) ? $image : '../resource/img/default/product_large.png';
                            $img_id = !empty($image) ? $product_name : 'Product image not available';
                            ?>
                            <img src="<?=$img?>" alt="<?=$img_id?>">
                        </div>
                        <div class="product-name-wrapper">
                            <span id="product-name">
                                <?=$product_name?>
                            </span>
                        </div>
                        <div class="product-price-wrapper">
                            <span id="item-price-text-<?=$id?>">$<?=$price?></span>
                        </div>
                        <div class="product-quantity-wrapper">
                            <!-- Decrement button -->
                            <button class="decrement" data-id="<?=$id?>">
                                &minus;
                            </button>
                            <!-- Item quantity -->
                            <span id="quantity-text-<?=$id?>"><?=$quantity?></span>
                            <!-- Increment button -->
                            <button class="increment" data-id="<?=$id?>">
                                &plus;
                            </button>
                        </div>
                        <div class="product-total-wrapper">
                            <?php
                            $item_total = $quantity * $price;
                            ?>
                            <span id="item-total-text-<?=$id?>">$<?=$item_total?></span>
                        </div>
                        <!-- Remove button -->
                        <div class="product-remove-wrapper">
                            <button class="remove" data-id="<?=$id?>">
                                Remove
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="main-summary-container">
                <div class="summary-wrapper">
                    <div class="summary-title">Summary:</div>
                    <div class="summary-item-count" id="summary-item-count-text">
                        <?=$item_in_cart_count_text?>
                    </div>
                    <div class="summary-subtotal" id="subtotal-text">
                        Subtotal: $<?=$subtotal?>
                    </div>
                    <div class="summary-check-out">
                        <button id="check-out-btn">Check out</button>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>