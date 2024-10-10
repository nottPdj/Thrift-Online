<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../database/user_class.php');
    require_once(__DIR__ . '/../database/item_class.php');
?>

<?php function drawItemMiniature($item_user_info) { ?>
    <a href=<?= "../pages/item.php?item=" . $item_user_info['id'] ?>>
        <article>
            <div class="img_username">
                <img src=<?="../uploads/small_" . $item_user_info['user_image_path']?> alt="">
                <h3><?=$item_user_info['username']?></h3>
            </div>
            <div class="crop">
                <img src=<?="../uploads/medium_" . $item_user_info['image_path']?>>
            </div>
            <h4><?=$item_user_info['title']?></h4>
            <h3><?=$item_user_info['price']?>€</h3>
            <p>Size: <?=$item_user_info['size']?></p>
        </article>
    </a>
<?php } ?>


<?php function drawItemMiniatures($items_users_info) { ?>
    <section class="items">
        <?php foreach ($items_users_info as $item_user_info) { 
            drawItemMiniature($item_user_info);
        } ?>
    </section>
<?php } ?>


<?php function drawItemInfo(PDO $db, ?User $logged_user, User $seller, Item $item, bool $in_cart) { ?>
    <main id="item">
        <section id="user">
            <a href=<?= "../pages/profile.php?user=" . $seller->id?>>
                <img src=<?="../uploads/medium_" . $seller->image_path?> alt="">
                <h3><?=$seller->username?></h3>
            </a>
            <?php 
                if (User::isAdmin($db, intval($logged_user->id))) { ?>
                    <div id="admin_options">
                    <a href=<?="../actions/action_admin.php?action=remove_item&id=$item->id"?> class="userButton">Remove Item</a>
            <?php } ?>
            <button href="#" class="userButton" data-id=<?=$seller->id?>><i class="fa-solid fa-message"></i></button>
        </section>
        <section id="photos">
            <img src=<?="../uploads/original_" . $item->image_path?> alt="">
        </section>
        <?php if ($logged_user->id !== $seller->id) { ?>
            <?php if ($in_cart) { ?>
                <button class="addToCart inCart" data-id=<?=$item->id?>>Remove from cart</button>
            <?php } else { ?>
                <button class="addToCart" data-id=<?=$item->id?>>Add to cart</button>
            <?php } ?>
        <?php } ?>
        <aside id="item_info">
            <h3 id="price"><?=$item->price?>€</h3>
            <section>
                <ul id="info_titles">
                    <li>GENDER</li>
                    <li>BRAND</li>
                    <li>SIZE</li>
                    <li>CONDITION</li>
                </ul>
                <ul id="info_fields">
                    <li><?=$item->gender?></li>
                    <li><?=$item->brand?></li>
                    <li><?=$item->size?></li>
                    <li><?=$item->conditions?></li>
                </ul>
            </section>
            <h3><?=$item->title?></h3>
            <h4><?=$item->category?></h4>
            <p><?=$item->description?></p>
        </aside>
    </main>
<?php } ?>

<?php function drawParameterForm() { ?>

        <h2>Add Item Parameter</h2>
        <form action=<?php "../actions/action_admin.php" ?> method="post">
            <input id="action" type="text" name="action" value="add_parameter" hidden>            
            <div class="form_row">
                <div class="form_label">
                    <label for="new_field">Parameter</label>
                </div>    
                <div class="form_input">
                    <select id="new_field" name="new_field" required>
                        <option value="category">Category</option>
                        <option value="size">Size</option>
                        <option value="conditions">Conditions</option>
                    </select>
                </div>    
            </div>
            <div class="form_row">
                <div class="form_label">
                    <label for="parameter">New field</label>
                </div>    
                <div class="form_input">
                    <input id="parameter" type="text" name="parameter" required>
                </div>    
            </div>
            <div class="form_row">
                <button type="submit" class="submitButton">Add field</button>  
            </div>
        </form>

<?php } ?>

<?php function drawItemForm(array $categories, array $sizes, array $conditions, bool $isAdmin) { ?>
    <main id="add_item">
        <?php if ($isAdmin)
            drawParameterForm(); 
        ?>
        <h2>Add Item</h2>
        <form action="../actions/action_sell_item.php" method="post" enctype="multipart/form-data">
            <div class="form_row">
                <div class="form_label">
                    <label for="title">Title</label>
                </div>    
                <div class="form_input">
                    <input id="title" type="text" name="title" required>
                </div>    
            </div>
            <div class="form_row">
                <div class="form_label">
                    <label for="price">Price</label>
                </div>    
                <div class="form_input">
                    <input id="price" type="number" name="price" min="0" step="0.01" required>
                </div>    
            </div>
            <div class="form_row">
                <div class="form_label">
                    <label for="gender">Gender</label>
                </div>    
                <div class="form_input">
                    <select id="gender" name="gender" required>
                        <option hidden disabled selected value></option>
                        <option value="Men">Men</option>
                        <option value="Women">Women</option>
                        <option value="Kids">Kids</option>
                    </select>
                </div>    
            </div>
            <div class="form_row">
                <div class="form_label">
                    <label for="price">Category</label>
                </div>    
                <div class="form_input">
                    <select id="category" name="category" required>
                        <option hidden disabled selected value></option>
                        <?php foreach ($categories as $category) { ?>
                            <option value="<?=$category?>"><?=$category?></option>
                        <?php } ?>
                    </select>
                </div>    
            </div>
            <div class="form_row">
                <div class="form_label">
                    <label for="brand">Brand</label>
                </div>    
                <div class="form_input">
                    <input id="brand" type="text" name="brand">
                </div>    
            </div>
            <div class="form_row">
                <div class="form_label">
                    <label for="size">Size</label>
                </div>    
                <div class="form_input">
                    <select id="size" name="size" required>
                        <option hidden disabled selected value></option>
                        <?php foreach ($sizes as $size) { ?>
                            <option value="<?=$size?>"><?=$size?></option>
                        <?php } ?>
                    </select>
                </div>    
            </div>
            <div class="form_row">
                <div class="form_label">
                    <label for="conditions">Conditions</label>
                </div>    
                <div class="form_input">
                    <select id="conditions" name="conditions" required>
                        <option hidden disabled selected value></option>
                        <?php foreach ($conditions as $condition) { ?>
                            <option value="<?=$condition?>"><?=$condition?></option>
                        <?php } ?>
                    </select>
                </div>    
            </div>
            <div class="form_row">
                <div class="form_label">
                    <label for="description">Item photos</label>
                </div>    
                <div class="form_input">
                    <input id="photos" type="file" name="photos" accept="images/png, images/jpeg" multiple required>
                </div>    
            </div>
            <div class="form_row">
                <div class="form_label">
                    <label for="description">Description</label>
                </div>    
                <div class="form_input">
                    <textarea id="description" name="description" cols="30" rows="6" placeholder="Write a description..."></textarea>
                </div>    
            </div>
            <div class="form_row">
                <button type="submit" class="submitButton">Add item</button>  
            </div>
        </form>
    </main>
<?php } ?>