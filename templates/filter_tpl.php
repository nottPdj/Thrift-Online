<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../database/item_class.php');
?>

<?php function drawFilter(array $categories, array $brands, array $sizes, array $conditions) { ?>
    <button id="filter">Filter</button>
    <section class="filter_sidebar">
        <?php 
            $genders = ['Men', 'Women', 'Kids'];
            drawCheckboxParameter('Gender', $genders);
            drawCheckboxParameter('Category', $categories); 
            drawCheckboxParameter('Brand', $brands); 
            drawCheckboxParameter('Size', $sizes); 
            drawCheckboxParameter('Condition', $conditions); 
            $price_bins = ['0€ - 15€', '15€ - 50€', '50€ - 100€', '100€ - 200€', '+ 200€'];
            drawRadioParameter('Price', $price_bins);
        ?>
        <button type="submit" class="submitButton">Reset</button>
    </section>
<?php } ?>

<?php function drawRadioParameter(string $title, array $fields) { ?>
    <div class="filter_type">
        <input type="checkbox" name="filter-type" id=<?=$title?>>
        <label class="hamburger" for=<?=$title?>><?=$title?></label>
        <form>
            <?php foreach ($fields as $field) { ?>
                <label><input type="radio" name="<?php echo $title ?>"><?=$field?></label>
            <?php } ?>
        </form>
    </div>
<?php } ?>

<?php function drawCheckboxParameter(string $title, array $fields) { ?>
    <div class="filter_type">
        <input type="checkbox" name="filter-type" id=<?=$title?>>
        <label class="hamburger" for=<?=$title?>><?=$title?></label>
        <form>
            <?php foreach ($fields as $field) { ?>
                <label><input type="checkbox" name="<?php echo $field ?>"><?=$field?></label>
            <?php } ?>
        </form>
    </div>
<?php } ?>
