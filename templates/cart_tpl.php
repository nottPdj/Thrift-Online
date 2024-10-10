<?php
    declare(strict_types = 1);
?>

<?php function drawCart(array $cart_items) { 
    $total = 0; ?>
    <section id=shopping_cart class="hide">
    <header>
        <button><i class="fa-solid fa-chevron-right"></i></button>
        <h3>Shopping Cart</h3>
    </header>
    <div id="cart_items">
        <?php foreach ($cart_items as $item) {
            $total += floatval($item->price); ?>
            <div class="horizontal_item">
                <a href=<?="../pages/item.php?item=" . $item->id?>>
                    <img src=<?="../uploads/medium_" . $item->image_path?> alt="">
                </a>

                <div id="item_details">
                    <h4><?=$item->title?></h4>
                    <p><?='Size: ' . $item->size?></p>
                    <h3><?=$item->price?></h3>
                </div>
                <button id="cart_remove" type="submit" data-id=<?=$item->id?>><i class="fa-solid fa-xmark"></i></button>
            </div>
        <?php } ?>
    </div>
    <div id="total">
        <h3>TOTAL</h3>
        <h3><?=$total?></h3>
    </div>
    <form action="../pages/shipping_form.php" method='post'>
        <button type=submit class="cart_button submitButton" id="checkout_button">Checkout</button>
    </form>
</section>
<?php } ?>
