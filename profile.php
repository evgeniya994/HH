<?php
session_start();
require_once "functions.php";
require_once "user_functions.php";
//если в сессии нет тек. пользователя, перенаправляем на тсраницу входа.
if (!$_SESSION['userId']){
    header("Location: index.php");
    die;
}

//информация текущего пользователя из базы
$currentUser = getUserById($_SESSION['userId']);
$userAddress = getUserFullAddress($currentUser['id_address']);


?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<div class="form">
    <form action="profile.php" method="POST">

        <p><label for="fio">Ф.И.О<input type="text" name="fio" id="fio" value="<?=$currentUser['fio']?>"></label></p>
        <p><label for="email">Email<input type="text" name="email" id="email" value="<?=$currentUser['email']?>"></label></p>
        <p><label for="phone">Телефон<input type="text" name="phone" id="phone" value="<?=$currentUser['phone']?>"></label></p>

        <p><label for="id_status">Статус</label>
            <select name="id_status">
                <?
                $statuses = getStatuses();
                foreach($statuses as $row) {
                    $selected = '';
                    if ($currentUser['id_status'] == $row["id_status"]){
                        $selected = 'selected="selected"';
                    }
                    echo '<option '.$selected.' value='.$row["id_status"].'>'.$row['name_status'].'</option>';
                }
                ?>
            </select>
        </p>

        <p><label>Адрес</label></p>

        <p><label id="id_city">Город</label>
            <select name="id_city">
                <?
                $cities = getCities();
                foreach($cities as $row){
                    $selected = '';
                    if ($userAddress['id_city'] == $row["id_city"]){
                        $selected = 'selected="selected"';
                    }
                    echo '<option '.$selected.' value='.$row["id_city"].'>'.$row['name_city'].'</option>';
                }
                ?>
            </select>
        </p>

        <p><label id="id_street">Улица</label>
            <select name="id_street">
                <?

                $streets = getStreets();
                foreach($streets as $row) {
                    $selected = '';
                    if ($userAddress['id_street'] == $row["id_street"]){
                        $selected = 'selected="selected"';
                    }
                    echo '<option '.$selected.' value='.$row["id_street"].'>'.$row['name_street'].'</option>';
                }
                ?>
            </select>
        </p>

        <p><label for="houseNum">Дом</label><input type="text" name="houseNum" value="<?=$userAddress['houseNum']?>"></p>
        <p><label for="kv">Квартира</label><input type="text" name="kv" value="<?=$userAddress['kv']?>"></p>


        <p><label for="login">Login<input type="text" id="login" name="login" value="<?=$currentUser['login']?>"></label></p>

        <p><label for="password">Пароль<input type="text" id="password" name="password" value="<?=$currentUser['password']?>"></label></p>

        <p><input type="submit" value="Изменить"></p>
    </form>
</div>

</body>
</html>