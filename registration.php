<?php
require_once "functions.php";


//если метод запроса POST
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    //первым делом проверяем капчу.
    if ($_SESSION["code"] != $_POST["captcha"]) {
        //сообщаем строку false, если код не соответствует
        //$errorCaptcha = "{$_SESSION['code']} != {$_POST['captcha']}";
        $errorCaptcha = "Введённый код не совпадает.";
    } else {
        //1. получить данные формы
        $post = getUserPostData();

        //2. проверка введеных данных
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

        if (!is_null(getUserByEmail($post['email']))) {
            $errorEmail = "Указанная почта \"{$post['email']}\" уже используется другим человеком.";
        }

        if (!is_null(getUserByLogin($post['login']))) {
            $errorLogin = "Указанный login \"{$post['login']}\" уже используется другим человеком.";
        }
        $post['kv'] = abs((int)$post['kv']);
        if ($post['kv'] == 0) {
            $errorKv = "кв. должна быть > 0";
        }
        if ($errorFio == "" && $errorEmail == "" && $errorPhone == "" && $errorHouseNum == "" && $errorLogin == "" &&
            $errorPassword == "" &&
            $errorKv == ""
        ) {
            $res = saveUser($post);
            if (is_numeric($res)) {//сохранилось?
                //$res - ID текущего пользователя, только созданного
                sendUserToMail($res);//отправить письмо


                $_SESSION['userId'] = $res;
                header("Location: profile.php");
                die;
            } else {
                $error = $res;
            }
        }
    }
}  else {//метод запроса НЕ POST (значит GET)
    //показать форму.

}
include "inc/header.php";
include "inc/navigation.php";
?>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form class="form-horizontal" action="registration.php" method="POST">
                    <div class="form-group <?=($errorFio) ? 'has-error' : ''; ?>">
                        <label for="fio" class="col-md-4 control-label">Ф.И.О</label>
                        <div class="col-md-8">
                            <input type="text" value="<?=($_POST['fio'])?$_POST['fio']:'';?>" class="form-control" id="fio" placeholder="Ф.И.О" name="fio">
                            <span class="help-block"><?=$errorFio?></span>
                        </div>
                    </div>
                    <div class="form-group <?=($errorEmail) ? 'has-error' : ''; ?>">
                        <label for="email" class="col-sm-2 control-label" >Email</label>
                        <div class="col-sm-10">
                            <input type="text" value="<?=($_POST['email'])?$_POST['email']:'';?>" class="form-control"   id="email" placeholder="Email" name="email">
                        </div>
                        <span class="help-block"><?=$errorEmail?></span>
                    </div>
                    <div class="form-group <?=($errorPhone) ? 'has-error' : ''; ?>">
                            <label for="phone" class="col-sm-2 control-label">Телефон</label>
                            <div class="col-sm-10">
                                <input type="text" value="<?=($_POST['phone'])?$_POST['phone']:'';?>" class="form-control"   id="phone" placeholder="Телефон" name="phone">
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
                                echo '<option value='.$row["id_status"].'>'.$row['name_status'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                        </div>
                    <Label>Адрес</Label>
                    <div class="form-group">
                        <label for="id_city" class="col-sm-2 control-label">Город</label>
                        <div class="col-sm-10">
                            <select id="id_city" name="id_city" class="form-control">
                                <?
                                $cities = getCities();
                                foreach($cities as $row){
                                    echo '<option value='.$row["id_city"].'>'.$row['name_city'].'</option>';
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
                                    echo '<option value='.$row["id_street"].'>'.$row['name_street'].'</option>';
                                }
                                ?>
                            </select>
                        </div>

                    </div>

                    <div class="inline">
                         <div class="form-group <?=($errorHouseNum) ? 'has-error' : ''; ?>">
                             <div class="col-sm-12 col-md-offset-2">
                                <input type="text"  class="form-control" value="<?=($_POST['houseNum'])?$_POST['houseNum']:'';?>"  id="houseNum" placeholder="Дом" name="houseNum">
                                <input type="text"  class="form-control" value="<?=($_POST['kv'])?$_POST['kv']:'';?>" id="kv" placeholder="Квартира" name="kv">
                             </div>
                             <span class="help-block"><?=$errorHouseNum?></span>
                             <span class="help-block"><?=$errorKv?></span>
                         </div>

                    </div>
                    <div class="form-group <?=($errorLogin) ? 'has-error' : ''; ?>">
                        <label for="login" class="col-sm-2 control-label">Логин</label>
                        <div class="col-sm-10">
                            <input type="text" value="<?=($_POST['login'])?$_POST['login']:'';?>" class="form-control" id="login" placeholder="Логин" name="login">
                        </div>
                        <span class="help-block"><?=$errorLogin?></span>
                    </div>
                    <div class="form-group <?=($errorPassword) ? 'has-error' : ''; ?>">
                        <label for="password" class="col-sm-2 control-label">Пароль</label>
                        <div class="col-sm-10">
                            <input type="password" value="<?=($_POST['password'])?$_POST['password']:'';?>"  class="form-control" id="password" placeholder="Пароль" name="password">
                        </div>
                        <span class="help-block"><?=$errorPassword?></span>
                    </div>
                    <div class="form-group <?=($errorPassword) ? 'has-error' : ''; ?>">
                        <div class="col-sm-4">
                            <label for="confirm_password" class="col-sm-2 control-label">Повторный пароль</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="password" value="<?=($_POST['confirm_password'])?$_POST['confirm_password']:'';?>" class="form-control" id="confirm_password" placeholder="Повторный пароль" name="confirm_password">
                        </div>
                        <span class="help-block"><?=$errorPassword?></span>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">



                            <!--Изображение, содержащее код CAPTCHA-->
                            <img id="img-captcha" src="captcha/img.php">
                            <!--Элемент, запрашивающий новый код CAPTCHA-->
                            <div id="reload-captcha" class="btn btn-default"><i class="glyphicon glyphicon-refresh"></i> Обновить</div>
                            <!--Блок для ввода кода CAPTCHA-->
                            <div class="form-group has-feedback">
                                <label id="label-captcha" for="text-captcha" class="control-label">Пожалуйста, введите указанный на изображении код:</label>
                                <input id="text-captcha" name="captcha" type="text" class="form-control" required="required" value="">
                                <span class="glyphicon form-control-feedback"></span>
                                <span class="help-block"><?=$errorCaptcha?></span>
                            </div>


                            <button type="submit" class="btn btn-success">Зарегистрироваться</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>


<?php include "inc/footer.php"; ?>