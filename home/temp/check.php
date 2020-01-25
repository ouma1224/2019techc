<?php

session_start();

$_SESSION['error_user_mes'];
$_SESSION['error_pass_mes'];
$user_id = $_POST['name'];
$pass = $_POST['pass'];
$_SESSION['user_name'] = $user_id;

require_once 'DB_Connection.php';
$dbh = getDB();

$select_sth = $dbh->prepare('SELECT * from users ');
$select_sth->execute();
$rows = $select_sth->fetchAll();

$flag = false;

foreach($rows as $row):
	if($row['user_id'] == $user_id){
		if(!password_verify($pass,$row['pass'])){
			$_SESSION['error_pass_mes'] = "passmiss";
			break;
		}else{
			setcookie("User_name",$user_id,time()+24*60*60*3);
			$flag = true;
			break;
		}
	}else{
		$_SESSION['error_user_mes'] = 'id_null';
	}
endforeach;

$link = "";
if($flag){
	$link = "./home.php";
}else{
	$link = "./login.php";
}

header("HTTP/1.1 303 See Other");
header("Location: {$link} ");
?>

