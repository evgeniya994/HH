<?php

$errorLogin = "";
$errorPass = "";

//если метод запроса POST
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    require_once "functions.php";
    $login = $_POST['login'];
    $password = $_POST['password'];

    $user = getUserByLogin($login);
    if (is_null($user)){//или так: if($user === null)
        $errorLogin = "не правильный логин";
    } else {
        //проверям пароли.
        if ($user['password'] == $password){
            //все ок, записываем в сессию id пользователя.
            $_SESSION['userId'] = $user['id_users'];
            //перенаправляем на страницу профилья
            header("Location: profile.php");
            die;//exit;
        } else {
            $errorPass = "не правильный пароль";
        }
    }


} else {//метод запроса НЕ POST (значит GET)
    //показать форму.
    require_once "inc/header.php";
    require_once "inc/navigation.php";
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <form class="form-horizontal" action="login.php" method="POST">
                <div class="form-group <?=($errorLogin) ? 'has-error' : ''; ?>">
                    <label for="login" class="col-sm-2 control-label">Логин</label>
                    <div class="col-sm-10">
                        <input type="text" value="<?=($_POST['login'])?$_POST['login']:'';?>" class="form-control" id="login" placeholder="Логин" name="login">
                    </div>
                    <span class="help-block"><?=$errorLogin?></span>
                </div>
                <div class="form-group <?=($errorPass) ? 'has-error' : ''; ?>">
                    <label for="password" class="col-sm-2 control-label">Пароль</label>
                    <div class="col-sm-10">
                        <input type="password" value="<?=($_POST['password'])?$_POST['password']:'';?>" class="form-control" id="password" placeholder="Пароль" name="password">
                    </div>
                    <span class="help-block"><?=$errorPass?></span>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" class="btn btn-default">Отмена</button>
                        <button type="submit" class="btn btn-success">Войти</button
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "inc/footer.php"; ?>