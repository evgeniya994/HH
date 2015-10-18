<? require_once "inc/header.php"; ?>
<? require_once "inc/navigation.php"; ?>
<?
require_once "functions.php";
if ($USER['name_role'] != ADMIN_ROLE_NAME){
    die("Доступ запрещен.");
}

$users = getUsers();

if (is_null($users)){
	echo '<p>В Бд отсутствуют пользователи.</p>';
} else {

	echo '<table class="table table-hover">';
	echo '<thead>';
	echo '<tr><th>Ф.И.О</th>';
	echo '<th>Email</th>';
	echo '<th>Телефон</th>';
	echo '<th>Статус</th>';
	echo '<th>Адрес</th>';
	echo '<th>Login</th>';
	echo'<th>Пароль</th>';
	echo'<th></th></tr>';
	echo '</thead>';
	echo '<tbody>';
	foreach($users as $row)
	{

		echo '<tr><td>'.$row['fio'];
		echo '</td><td>'.$row['email'];
		echo '</td><td>'.$row['phone'];
		echo '</td><td>'.$row['name_status'];
		echo '</td><td>'.$row['fullAddress'];

		if (!empty($row['kv']) && $row['kv'] > 0) {
			echo ' кв.'.$row['kv'];
		}

		echo '</td><td>'.$row['login'];
		echo '</td><td>'.$row['password'];
		echo '</td><td><a href="editUser.php?userId='.$row['id_users'].'" class="btn btn-success">&#9998;</a>';
		echo'</td></tr>';

	}
	echo'</tbody>';
	echo'</table>';
}
?>
<?php include "inc/footer.php"; ?>