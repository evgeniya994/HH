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

    $user = getUserById($userId);
    //2. проверка введеных данных.
    $check = checkUserForm($post);
    if ($check !== true) {//где-то ошибка
        //тут обработка ошибки.
        $error = $check;
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
    include "inc/header.php";
    include "inc/navigation.php";
}
?>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <form class="form-horizontal" action="registration.php" method="POST">
                            <div class="form-group <?=($errorFio) ? 'has-error' : ''; ?>">
                                <label for="fio" class="col-sm-2 control-label">Ф.И.О</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" id="fio" placeholder="Ф.И.О" name="fio">
                                </div>
                                <span class="help-block"><?=$errorFio?></span>
                            </div>
                    <div class="form-group <?=($errorEmail) ? 'has-error' : ''; ?>">
                        <label for="email" class="col-sm-2 control-label" >Email</label>
                        <div class="col-sm-10">
                            <input type="text"  class="form-control"   id="email" placeholder="Email" name="email">
                        </div>
                        <span class="help-block"><?=$errorEmail?></span>
                    </div>
                    <div class="form-group <?=($errorPhone) ? 'has-error' : ''; ?>">
                            <label for="phone" class="col-sm-2 control-label">Телефон</label>
                            <div class="col-sm-10">
                                <input type="text"  class="form-control"   id="phone" placeholder="Телефон" name="phone">
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
                            <input type="text" class="form-control" id="login" placeholder="Логин" name="login">
                        </div>
                        <span class="help-block"><?=$errorLogin?></span>
                    </div>
                    <div class="form-group <?=($errorPassword) ? 'has-error' : ''; ?>">
                        <label for="password" class="col-sm-2 control-label">Пароль</label>
                        <div class="col-sm-10">
                            <input type="password"  class="form-control" id="password" placeholder="Пароль" name="password">
                        </div>
                        <span class="help-block"><?=$errorPassword?></span>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password" class="col-sm-2 control-label">Повторный пароль</label>
                        <div class="col-sm-10">
                            <input type="password"  class="form-control" id="confirm_password" placeholder="Повторный пароль" name="confirm_password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="button" class="btn btn-success">Зарегистрироваться</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>


<?php include "inc/footer.php"; ?>