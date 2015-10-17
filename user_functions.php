<?php
function saveUser(array $data)
{
    global $handle;
    $handle->autocommit(false);
    //начало транзакции
    $handle->begin_transaction();

    $query = "INSERT INTO addresses (
              `id_city`,
              `id_street`,
              `houseNum`,
              `kv`
              )
              VALUES (
                {$data['id_city']},
                {$data['id_street']},
                '{$data['houseNum']}',
                '{$data['kv']}'
              )";
    $result = $handle->query($query);
    if ($result === false) {
        //откат изменений
        $handle->rollback();
        return "Не удалось сохранить адрес.";
    }
    $id_address = $handle->insert_id;//получаем ID сохраненного только что адреса.
    $query = "INSERT INTO users (
              `fio`,
              `email`,
              `id_status`,
              `phone`,
              `login`,
              `password`,
              `id_address`
              )
              VALUES (
                '{$data['fio']}',
                '{$data['email']}',
                {$data['id_status']},
                '{$data['phone']}',
                '{$data['login']}',
                '{$data['password']}',
                {$id_address}
              )";
    $result = $handle->query($query);
    if ($result === true) {
        //если сохранился успешно, возвращаем ID
        //return $handle->insert_id;
        $userId = $handle->insert_id;
        //применение изменений.
        $handle->commit();
        return $userId;
    } else {
        //откат изменений
        $handle->rollback();
        return "Не удалось сохранить пользователя.";
    }
}

/**
 * Возвращает данные из формы регистрации
 * @return array
 */
function getUserPostData()
{
    $data = array();
    $data['fio'] = $_POST['fio'];
    $data['email'] = $_POST['email'];
    $data['phone'] = $_POST['phone'];
    $data['id_status'] = $_POST['id_status'];
    $data['id_city'] = $_POST['id_city'];
    $data['id_street'] = $_POST['id_street'];
    $data['houseNum'] = $_POST['houseNum'];
    $data['kv'] = $_POST['kv'];
    $data['login'] = $_POST['login'];
    $data['password'] = $_POST['password'];
    $data['confirm_password'] = $_POST['confirm_password'];
    return $data;
}

/**
 * Возвращает данные из формы профиля
 * @return array
 */
function getUserProfilePostData()
{
    $data = array();
    $data['fio'] = $_POST['fio'];
    $data['email'] = $_POST['email'];
    $data['phone'] = $_POST['phone'];
    $data['id_status'] = $_POST['id_status'];
    $data['id_city'] = $_POST['id_city'];
    $data['id_street'] = $_POST['id_street'];
    $data['houseNum'] = $_POST['houseNum'];
    $data['kv'] = $_POST['kv'];
    $data['login'] = $_POST['login'];
    $data['password'] = $_POST['password'];
    $data['id_users'] = $_POST['id_users'];
    $data['id_address'] = $_POST['id_address'];
    return $data;
}


/**
 * Возвращает пользователя по E-mail или null если такой почты не в бд
 * @param $email
 * @return array|null
 */
function getUserByEmail($email)
{
    global $handle;
    $query = "SELECT *
	       FROM users
	       WHERE email='{$email}'";
    $result = $handle->query($query);
    if ($result->num_rows == 0) {
        return null;
    }
    return $result->fetch_assoc();
}
function getUserByLogin($login)
{
    global $handle;
    $query = "SELECT *
	       FROM users
	       WHERE login='{$login}'";
    $result = $handle->query($query);
    if ($result->num_rows == 0) {
        return null;
    }
    return $result->fetch_assoc();
}
/**
 * Возвращает полный адрес по ID
 * @param $addressId
 * @return array|null
 */
function getUserFullAddress($addressId)
{
    global $handle;
    $sql = "SELECT CONCAT(cities.name_city, ', ', streets.name_street, ', ', houseNum) as 'fullAddress',
cities.name_city, streets.name_street, houseNum, kv,addresses.id_street,addresses.id_city
FROM addresses
LEFT JOIN cities ON (addresses.id_city = cities.id_city)
LEFT JOIN streets ON (addresses.id_street = streets.id_street)
WHERE addresses.id_address = {$addressId}";
    $result = $handle->query($sql);
    if ($result->num_rows == 0) {
        return null;
    }
    return $result->fetch_assoc();
}

function getUserById($userId)
{
    global $handle;
    $sql = "SELECT *
FROM users
WHERE id_users = {$userId}";
    $result = $handle->query($sql);
    if ($result->num_rows == 0) {
        return null;
    }
    return $result->fetch_assoc();
}

function updateUser(array $data)
{
    global $handle;
    $handle->autocommit(false);
    //начало транзакции
    $handle->begin_transaction();

    $query = "UPDATE addresses
              SET
                id_city={$data['id_city']},
                id_street={$data['id_street']},
                houseNum='{$data['houseNum']}',
                kv='{$data['kv']}'

               WHERE id_address={$data['id_address']}";
    $result = $handle->query($query);
    if ($result === false) {
        //откат изменений
        $handle->rollback();
        return "Не удалось сохранить адрес.";
    }
    //$id_address = $handle->update_id;//получаем ID сохраненного только что адреса.
    $query = "UPDATE users
              SET
                fio='{$data['fio']}',
                email='{$data['email']}',
                id_status={$data['id_status']},
                phone='{$data['phone']}',
                login='{$data['login']}',
                password='{$data['password']}'

              WHERE id_users={$data['id_users']}";
    $result = $handle->query($query);
    if ($result === true) {
        //применение изменений.
        $handle->commit();
        return $result;
    } else {
        //откат изменений
        $handle->rollback();
        return "Не удалось сохранить пользователя.";
    }
}
function getUsers()
{
    global $handle;
    $sql = "SELECT
users.fio,users.email,users.phone,statuses.name_status,
CONCAT(cities.name_city, ', ', streets.name_street, ', ', houseNum) as 'fullAddress',
users.login,users.password,addresses.kv
FROM users
LEFT JOIN addresses ON (users.id_address=addresses.id_address)
LEFT JOIN statuses ON (users.id_status=statuses.id_status)
LEFT JOIN cities ON (addresses.id_city=cities.id_city)
LEFT JOIN streets ON (addresses.id_street=streets.id_street)";
    $result = $handle->query($sql);
    if ($result->num_rows == 0) {
        return null;
    }
    return $result->fetch_assoc();
}