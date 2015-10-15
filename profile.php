<?php
//если в сессии нет тек. пользователя, перенаправляем на тсраницу входа.
if (!$_SESSION['userId']){
    header("Location: index.php");
    die;
}

//информация текущего пользователя из базы
$currentUser = getUserById($_SESSION['userId']);
$userAddress = getUserFullAddress($currentUser['id_address']);
include "inc/header.php";
include "inc/navigation.php";
?>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <form class="form-horizontal" action="profile.php" method="POST">
                    <div class="form-group">
                        <label for="fio" class="col-sm-2 control-label">Ф.И.О</label>
                        <div class="col-sm-10">
                            <input type="text"  class="form-control" id="login" value="<?=$currentUser['fio']?>" name="fio">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text"  class="form-control"   id="email" value="<?=$currentUser['email']?>" name="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="col-sm-2 control-label">Телефон</label>
                        <div class="col-sm-10">
                            <input type="text"  class="form-control"   id="phone" value="<?=$currentUser['phone']?>" name="phone">
                        </div>
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
                    <Label>Адрес</Label>
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
                        <div class="form-group">
                            <div class="col-sm-12 col-md-offset-2">

                                <input type="text"  class="form-control"   id="houseNum" value="Дом <?=$userAddress['houseNum']?>" name="houseNum">

                                <input type="text"  class="form-control"   id="kv" value="Кв. <?=$userAddress['kv']?>" name="kv">

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="login" class="col-sm-2 control-label">Логин</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="login" value="<?=$currentUser['login']?>" name="login">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">Пароль</label>
                        <div class="col-sm-10">
                            <input type="password"  class="form-control" id="password" value="<?=$currentUser['password']?>" name="password">
                        </div>
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