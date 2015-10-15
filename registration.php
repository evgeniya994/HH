<? require_once "inc/header.php"; ?>
<? require_once "inc/navigation.php"; ?>
<?php
require_once "functions.php";

$errorFio= "";
$errorEmail= "";
$errorPhone= "";
$errorLogin= "";
$errorPassword= "";

//если метод запроса POST
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    //1. получить данные формы
    $post = getUserPostData();
    //2. проверка введеных данных
    if((mb_strlen($post['fio']) < 10) ||preg_match('~[^а-яёА-ЯЁa-zA-Z ]~u', $post['fio'])){//или так: if($user === null)
        $errorFio = "Ф.И.О введено не верно";
    } if (mb_strlen($post['phone']) < 11){ $errorPhone = "Номер телефона должен быть не менее 11 цифр";}

    if ((mb_strlen($post['login']) < 10) ||preg_match( '/[^0-9a-zA-Z]/', $post['login'])){
        $errorLogin= "Логин может содержать только цифры и латинские буквы.";}

    if (mb_strlen($post['password']) < 10||$post['password'] != $post['confirm_password']) {
        $errorPassword= "Неверно введен пароль";
    }

    $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
    if (preg_match($pattern, $post['email']) !== 1) {
        $errorEmail= "Не правильный адрес почты";
    }
    if (!is_null(getUserByEmail($post['email']))) {
        $errorEmail ="Указанная почта \"{$post['email']}\" уже используется другим человеком.";
    }
    if (!is_null(getUserByLogin($post['login']))) {
        $errorLogin= "Указанный login \"{$post['login']}\" уже используется другим человеком.";
    }
    else {
        //3. Сохранить в базу
        $res = saveUser($post);
        if (is_numeric($res)) {//сохранилось?
            $_SESSION['userId'] = $res;
            header("Location: profile.php");
            die;
        } else {
            $error = $res;
        }
    }
}
else {//метод запроса НЕ POST (значит GET)
    //показать форму.

}
?>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <form class="form-horizontal" action="registration.php" method="POST">
                            <div class="form-group <?=($errorFio) ? 'has-error' : ''; ?>">
                                <label for="fio" class="col-sm-2 control-label">Ф.И.О</label>
                                <div class="col-sm-10">
                                    <input type="text" value="<?=($_POST['fio'])?$_POST['fio']:'';?>" class="form-control" id="fio" placeholder="Ф.И.О" name="fio">
                                </div>
                                <span class="help-block"><?=$errorFio?></span>
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
                         <div class="form-group">
                             <div class="col-sm-12 col-md-offset-2">

                            <input type="text"  class="form-control"   id="houseNum" placeholder="Дом" name="houseNum">
                         <input type="text"  class="form-control"   id="kv" placeholder="Квартира" name="kv">
                             </div>
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
                        <label for="confirm_password" class="col-sm-2 control-label">Повторный пароль</label>
                        <div class="col-sm-10">
                            <input type="password" value="<?=($_POST['confirm_password'])?$_POST['confirm_password']:'';?>" class="form-control" id="confirm_password" placeholder="Повторный пароль" name="confirm_password">
                        </div>
                        <span class="help-block"><?=$errorPassword?></span>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success">Зарегистрироваться</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>


<?php include "inc/footer.php"; ?>