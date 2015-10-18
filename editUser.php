<?php
require_once "functions.php";

//информация текущего пользователя из базы
$currentUser = getUserById($_GET['userId']);
$userAddress = getUserFullAddress($currentUser['id_address']);

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    //1. получить данные формы
    $post = getUserProfilePostData();

    if ((mb_strlen($post['fio']) < 10) || !preg_match('/^[\sа-яa-z]/i', $post['fio'])) {
        $errorFio = "Ф.И.О введено не верно";
    }

    if ((mb_strlen($post['phone']) < 11) || preg_match('/[^0-9]/', $post['phone'])) {
        $errorPhone = "Номер телефона указан некорректно";
    }

    if (empty($post['houseNum']) || preg_match('/^[0-9]+[\/а-яА-ЯЁ]/', $post['HouseNum'])) {
        $errorHouseNum = "Вы не указали дом";
    }

    if ((mb_strlen($post['login']) < 4) || preg_match('/[^0-9a-zA-Z]/', $post['login'])) {
        $errorLogin = "Логин может содержать только цифры и латинские буквы.";
    }

    if (mb_strlen($post['password']) < 10) {
        $errorPassword = "Неверно введен пароль";
    }

    $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
    if (preg_match($pattern, $post['email']) !== 1) {
    $errorEmail = "Не правильный адрес почты";
}

if ($currentUser['email'] != $post['email']){//Если (текущий логин отличается от того что ввели)
    if (!is_null(getUserByEmail($post['email']))) {//смотрим в базе есть ли такой, Если есть то ошибка
        $errorEmail ="Указанная почта \"{$post['email']}\" уже используется другим человеком.";
        }
    }

    if ($currentUser['login'] != $post['login']){//Если (текущий логин отличается от того что ввели)
        if (!is_null(getUserByLogin($post['login']))) {//смотрим в базе есть ли такой, Если есть то ошибка
            $errorLogin ="Указанный login \"{$post['login']}\" уже используется другим человеком.";
        }
    }

    $post['kv'] = abs((int)$post['kv']);
    if ($post['kv'] == 0){
        $errorKv = "кв. должна быть > 0";
    }

    if ($errorFio == "" && $errorEmail == "" && $errorPhone == "" && $errorHouseNum == "" && $errorLogin == "" &&
        $errorPassword == "" &&
        $errorKv == ""
    ) {
        $res = updateUser($post);
        if ($res) {//сохранилось?
            //$_SESSION['userId'] = $res; можно не перезаписывать id, он не изменился.

            header("Location: admin.php");
            die;
        } else {
            $error = $res;
        }
    }


}
else {//метод запроса НЕ POST (значит GET)
    //показать форму.

}
include "inc/header.php";
include "inc/navigation.php";
?>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <form class="form-horizontal" action="editUser.php?userId=<?=$_GET['userId']?>" method="POST">

                    <input type="hidden" name="id_users" value="<?=$_GET['userId']?>"/>
                    <div class="form-group  <?=($errorFio) ? 'has-error' : ''; ?>">
                        <label for="fio" class="col-sm-2 control-label">Ф.И.О</label>
                        <div class="col-sm-10">
                            <input type="text"  class="form-control" id="login" value="<?=$currentUser['fio']?>" name="fio">
                        </div>
                        <span class="help-block"><?=$errorFio?></span>
                    </div>
                    <div class="form-group  <?=($errorEmail) ? 'has-error' : ''; ?>">
                        <label for="email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text"  class="form-control"   id="email" value="<?=$currentUser['email']?>" name="email">
                        </div>
                        <span class="help-block"><?=$errorEmail?></span>
                    </div>
                    <div class="form-group  <?=($errorPhone) ? 'has-error' : ''; ?>">
                        <label for="phone" class="col-sm-2 control-label">Телефон</label>
                        <div class="col-sm-10">
                            <input type="text"  class="form-control"   id="phone" value="<?=$currentUser['phone']?>" name="phone">
                        </div>
                        <span class="help-block"><?=$errorPhone?></span>
                    </div>
                    <div class="form-group">
                        <label for="id_status" class="col-sm-2 control-label">Статус</label>
                        <div class="col-sm-10">
                            <select id="id_status" name="id_status" class="form-control">
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
                        </div>
                    </div>
                    <Label>Адрес </Label>
                    <input type="hidden" name="id_address" value="<?=$currentUser['id_address']?>"/>
                    <div class="form-group">
                        <label for="id_city" class="col-sm-2 control-label">Город</label>
                        <div class="col-sm-10">
                            <select id="id_city" name="id_city" class="form-control">
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
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="id_street" class="col-sm-2 control-label">Улица</label>
                        <div class="col-sm-10">
                            <select id="id_street" name="id_street" class="form-control">
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
                        </div>
                    </div>
                    <div class="inline">
                        <div class="form-group  <?=($errorHouseNum) ? 'has-error' : ''; ?>">
                            <div class="col-sm-12 col-md-offset-2">

                                <input type="text"  class="form-control"   id="houseNum" value="<?=$userAddress['houseNum']?>" name="houseNum">

                                <input type="number"  class="form-control" id="kv" value="<?=$userAddress['kv']?>" name="kv">

                            </div>
                            <span class="help-block"><?=$errorHouseNum?></span>
                            <span class="help-block"><?=$errorKv?></span>
                        </div>
                    </div>
                    <div class="form-group  <?=($errorLogin) ? 'has-error' : ''; ?>">
                        <label for="login" class="col-sm-2 control-label">Логин</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="login" value="<?=$currentUser['login']?>" name="login">
                        </div>
                        <span class="help-block"><?=$errorLogin?></span>
                    </div>
                    <div class="form-group  <?=($errorPassword) ? 'has-error' : ''; ?>">
                        <label for="password" class="col-sm-2 control-label">Пароль</label>
                        <div class="col-sm-10">
                            <input type="password"  class="form-control" id="password" value="<?=$currentUser['password']?>" name="password">
                        </div>
                        <span class="help-block"><?=$errorPassword?></span>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success">Изменить</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

<?php include "inc/footer.php"; ?>