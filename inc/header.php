<?php require_once "functions.php"; ?>
<?php
$USER = null;//текущий пользователь
if ($_SESSION['userId']){
    $USER = getUserById($_SESSION['userId']);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>

    <!-- Bootstrap -->
    <link href="libs/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet"/>

    <link rel="stylesheet" href="css/style.css" />
</head>
<body>