<? require_once "inc/header.php"; ?>
<? require_once "inc/navigation.php"; ?>
<?
require_once "functions.php";
$handle = new mysqli('HH', 'mysql', 'mysql', 'HH');
$query = "SELECT
users.fio,users.email,users.phone,statuses.name_status,
CONCAT(cities.name_city, ', ', streets.name_street, ', ', houseNum) as 'fullAddress',
users.login,users.password,addresses.kv
FROM users
LEFT JOIN addresses ON (users.id_address=addresses.id_address)
LEFT JOIN statuses ON (users.id_status=statuses.id_status)
LEFT JOIN cities ON (addresses.id_city=cities.id_city)
LEFT JOIN streets ON (addresses.id_street=streets.id_street)

";
$result = $handle->query($query);
$res=$result->num_rows;

echo '<table">';

echo '<tr><th>Ф.И.О</th>';
echo '<th>Email</th>';
echo '<th>Телефон</th>';
echo '<th>Статус</th>';
echo '<th>Адрес</th>';
echo '<th>Login</th>';
echo'<th>Пароль</th>';
echo'<th></th>';

for ($i=0;$i<$res;$i++)
{
	$row=$result->fetch_assoc();
	
	echo '<tr><td>'.$row['fio'];
	echo '</td><td>'.$row['email'];
	echo '</td><td>'.$row['phone'];
	echo '</td><td>'.$row['name_status'];
	echo '</td><td>'.$row['fullAddress'];
	echo ' кв.'.$row['kv'];
	echo '</td><td>'.$row['login'];
	echo '</td><td>'.$row['password'];
	echo '</td><td>';
	
}

echo'</table>';
?>
<?php include "inc/footer.php"; ?>