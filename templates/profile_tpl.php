<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../database/user_class.php');

    require_once(__DIR__ . '/item_tpl.php');
?>

<?php function drawUserProfile(PDO $db, ?User $logged_user, User $user, array $items, ) { ?>
    <main id="profile">
        <section id="profile_info">
            <div class="img_wrapper">
                <img src=<?="../uploads/medium_" . $user->image_path?> alt='profile_photo'>
            </div>
            <div id="about">
                <h2><?=$user->username?></h2>
                <?php if ($logged_user !== null && $logged_user->id === $user->id) { ?>
                    <a href="../pages/edit_profile.php" class="userButton">Edit Profile</a>
                <?php }  else { ?>
                    <button class="userButton" data-id=<?=$user->id?>>Chat</button>
                <?php } ?>
                <h4>About:</h4>
                <p><?=$user->name?></p>
                <p><?=$user->email?></p>
                <?php 
                if (User::isAdmin($db, $logged_user->id) && $logged_user->id !== $user->id) { ?>
                    <div id="admin_options">
                    <a href=<?="../actions/action_admin.php?action=ban&id=$user->id"?> class="userButton">Ban User</a>
                    <a href=<?="../actions/action_admin.php?action=set_admin&id=$user->id&on_off=" . !User::isAdmin($db, $user->id)?> class="userButton">
                    <?php if (User::isAdmin($db, $user->id)) { ?> Unset Admin 
                    <?php } else { ?> Set Admin <?php } ?></a>
                    </div>
                <?php } ?>
            </div>
        </section>
        <section id="profile_items">
            <p><?=count($items)?> items</p>
            <?php drawItemMiniatures($items); ?>
        </section>
    </main>
<?php } ?>

<?php function drawProfileForm(User $user){?>
    <main id="edit_profile">
    <h2>Edit your profile</h2>
    <form action="../actions/action_edit_profile.php" method="post" enctype="multipart/form-data">
    <div class="form_row" id="edit_photo"> 
        <div class="img_editprofile">
          <img src=<?="../uploads/medium_" . $user->image_path ?> alt="">
        </div> 
        <div class="form_input" id>
            <label for="photos" class="btn">Upload Photo</label>
            <input id="photos" type="file" name="photos" accept="images/png, images/jpeg" multiple placeholder="Select photos...">
        </div>
    </div>
        <div class="form_row">
            <div class="form_label">
                <label for="title">Name</label>
            </div>    
            <div class="form_input">
                <input id="name" type="text" name="name" value ="<?= $user->name ?>">
            </div>    
        </div>
        <div class="form_row">
            <div class="form_label">
                <label for="brand">Username</label>
            </div>    
            <div class="form_input">
                <input id="username" type="text" name="username" value ="<?= $user->username?>"></label>
            </div>    
        </div>
        <div class="form_row">
            <div class="form_label">
                <label for="email">Email</label>
            </div>    
            <div class="form_input">
                <input id="email" type="text" name="email" value ="<?= $user->email ?>"></label>
            </div>    
        </div>
        <div class="form_row">
            <div class="form_label">
                <label for="current_password">Current password</label>
            </div>    
            <div class="form_input">
                <input type="password" name="current_password" required></label>
            </div>    
        </div>
        <div class="form_row">
            <div class="form_label">
                <label for="password">New password</label>
            </div>    
            <div class="form_input">
                <input type="password" name="password"></label>
            </div>    
        </div>
        <div class="form_row">
            <div class="form_label">
                <label for="confirm_password">Confirm new password</label>
            </div>    
            <div class="form_input">
                <input type="password" name="confirm_password"></label>
            </div>    
        </div>
        <div class="form_row">
            <button type="submit" class="submitButton">Update</button>  
        </div>
    </form>
</main>
<?php } ?>