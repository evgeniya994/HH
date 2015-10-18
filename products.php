<? include "inc/header.php"; ?>
<? include "inc/navigation.php"; ?>
<?
require_once "functions.php";
?>

<div class="container product-list">
    <div class="row">
    <?
    $products = getProducts($USER['id_status']);
    foreach ($products as $row) {
        $image = $row['image'];
        echo '<div class="col-md-4">';
        echo ' <img src="'.$image.'" width="220" height="220" class="img-thumbnail"><br/>';
        echo '<span>'.$row['name_product'].'</span>';
        echo ' - '.$row['price'];
        echo ' руб.';
        echo '</div>';
    }
    ?>
    </div>
</div>
<?php include "inc/footer.php"; ?>
