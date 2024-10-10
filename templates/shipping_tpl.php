<?php
declare(strict_types=1);


require_once (__DIR__ . '/../database/connection_db.php');
require_once (__DIR__ . '/../database/user_class.php');
require_once (__DIR__ . '/../database/item_class.php');
require_once (__DIR__ . '/../database/cart_class.php');

require_once (__DIR__ . '/../templates/common_tpl.php');


?>

<?php function draw_ShippingForm(Session $session, ?User $user, array $items){ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thrift Online</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://kit.fontawesome.com/23e2bceb56.js" crossorigin="anonymous"></script>
    <script src="../javascript/script.js" defer></script>
</head>

<body>
    <header class="navbar">
        <section id="navtop">
            <a href="index.php" id="logo">
                <img alt="logo" src="../images/logo.png" width=300>
            </a>
            <?php if ($user) {
                drawLoggedInOptions($user);
            } else {
                drawLoggedOutOptions();
            } ?>
        </section>
    </section>
</header>

    <main id="checkout">
        <section id="checkout_details">
        <h2>Checkout</h2>
        <form action="../actions/action_checkout.php" method="post">
            <div class="checkout_row">
                <div class="checkout_label">
                    <label for="payment_method">Payment Method</label>
                </div>
                <div class="checkout_input">
                    <select id="payment_method" name="payment_method">
                        <option value="card">Card</option>
                        <option value="upon_delivery">Upon Delivery</option>
                        <option value="mb_way">MB Way</option>
                    </select>
                </div>
            </div>
            <div class="checkout_row">
                <div class="checkout_label">
                    <label for="first_name">First Name</label>
                </div>
                <div class="checkout_input">
                    <input id="first_name" type="text" name="first_name" required>
                </div>
            </div>
            <div class="checkout_row">
                <div class="checkout_label">
                    <label for="last_name">Last Name</label>
                </div>
                <div class="checkout_input">
                    <input id="last_name" type="text" name="last_name" required>
                </div>
            </div>
            <div class="checkout_row">
                <div class="checkout_label">
                    <label for="phone">Phone</label>
                </div>
                <div class="checkout_input">
                    <input id="phone" type="tel" name="phone" pattern="^[0-9]+$" required>
                </div>
            </div>
            <h2>Shipping Address</h2>
            <div class="checkout_row">
                <div class="checkout_label">
                    <label for="country">Country</label>
                </div>
                <div class="checkout_input">
                    <select id="country" name="country" required>
                        <option value="">Select Country</option>
                        <option value="Albania">Albania</option>
                        <option value="Andorra">Andorra</option>
                        <option value="Armenia">Armenia</option>
                        <option value="Austria">Austria</option>
                        <option value="Azerbaijan">Azerbaijan</option>
                        <option value="Belarus">Belarus</option>
                        <option value="Belgium">Belgium</option>
                        <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                        <option value="Bulgaria">Bulgaria</option>
                        <option value="Croatia">Croatia</option>
                        <option value="Cyprus">Cyprus</option>
                        <option value="Czech Republic">Czech Republic</option>
                        <option value="Denmark">Denmark</option>
                        <option value="Estonia">Estonia</option>
                        <option value="Finland">Finland</option>
                        <option value="France">France</option>
                        <option value="Georgia">Georgia</option>
                        <option value="Germany">Germany</option>
                        <option value="Greece">Greece</option>
                        <option value="Hungary">Hungary</option>
                        <option value="Iceland">Iceland</option>
                        <option value="Ireland">Ireland</option>
                        <option value="Italy">Italy</option>
                        <option value="Kazakhstan">Kazakhstan</option>
                        <option value="Kosovo">Kosovo</option>
                        <option value="Latvia">Latvia</option>
                        <option value="Liechtenstein">Liechtenstein</option>
                        <option value="Lithuania">Lithuania</option>
                        <option value="Luxembourg">Luxembourg</option>
                        <option value="Malta">Malta</option>
                        <option value="Moldova">Moldova</option>
                        <option value="Monaco">Monaco</option>
                        <option value="Montenegro">Montenegro</option>
                        <option value="Netherlands">Netherlands</option>
                        <option value="North Macedonia">North Macedonia</option>
                        <option value="Norway">Norway</option>
                        <option value="Poland">Poland</option>
                        <option value="Portugal">Portugal</option>
                        <option value="Romania">Romania</option>
                        <option value="Russia">Russia</option>
                        <option value="San Marino">San Marino</option>
                        <option value="Serbia">Serbia</option>
                        <option value="Slovakia">Slovakia</option>
                        <option value="Slovenia">Slovenia</option>
                        <option value="Spain">Spain</option>
                        <option value="Sweden">Sweden</option>
                        <option value="Switzerland">Switzerland</option>
                        <option value="Turkey">Turkey</option>
                        <option value="Ukraine">Ukraine</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="Vatican City">Vatican City</option>
                    </select>
                </div>
            </div>
            <div class="checkout_row">
                <div class="checkout_label">
                    <label for="city">City</label>
                </div>
                <div class="checkout_input">
                    <input id="city" type="text" name="city" required>
                </div>
            </div>
            <div class="checkout_row">
                <div class="checkout_label">
                    <label for="parish">Parish</label>
                </div>
                <div class="checkout_input">
                    <input id="parish" type="text" name="parish" required>
                </div>
            </div>
            <div class="checkout_row">
                <div class="checkout_label">
                    <label for="address">Address</label>
                </div>
                <div class="checkout_input">
                    <input id="address" type="text" name="address" required>
                </div>
            </div>
            <div class="checkout_row">
                <div class="checkout_label">
                    <label for="zip_code">Zip Code</label>
                </div>
                <div class="checkout_input">
                <input id="zip_code" type="text" inputmode="numeric" name="zip_code" pattern="[0-9]{4}(?:-[0-9]{3})?" placeholder=" 1234-567" required>
                </div>
            </div>
            <div class="checkout_row">
                <button type="submit" class="submitButton">Place Order</button>
            </div>
        </form>
    </section>
        <aside id="cart">
            <h2>Shopping Cart</h2>
            <ul id="cart_items">
                <?php
                $total = 0;
                foreach($items as $item){
                    $total += floatval($item->price);
            ?>
            <section id="shop_items">
                <img src=<?="../uploads/medium_" . $item->image_path?> alt="" width="15%">
                <h3><?=$item->title;?></h3>
                <h4> <?=$item->price;?></h4>
            </section>    
            <?php } ?>
            </ul>
            <div id="shipping_cost">Shipping Cost: 0.00 </div>
            <div id="total_price">
                <p>Total to pay: <?=$total?></p>
            </div>
        </aside>


    </main>

    <?php } ?>