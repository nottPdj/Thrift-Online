<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../database/item_class.php');
?>

<?php function drawChat() { ?>
    <section id="chat">
        <header>
            <button class="chat_header_button" id="chat_back"><i class="fa-solid fa-arrow-left"></i></button>
            <a href=""><h3></h3></a>
            <button class="chat_header_button" id="close_chat"><i class="fa-solid fa-minus"></i></button>
        </header>
        <div></div>
    </section>
<?php } ?>