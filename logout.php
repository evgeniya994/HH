<?php
require_once "functions.php";
/**
 * Завершение сеанса
 */
if ($_SESSION['userId']){
    unset($_SESSION['userId']);
}
header("Location: index.php");
die;