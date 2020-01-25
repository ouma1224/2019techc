<?php

session_start();

$_SESSION['error_user_mes'];
$_SESSION['error_pass_mes'];
$user_name = $_POST['user_name'];
$pass = $_POST['pass'];
$_SESSION['user_name'] = $user_name;

require_once 'DB_Connection_SNS.php';
$dbh = getDB();

$select_sth = $dbh->prepare('SELECT * from users ');
$select_sth->execute();
$rows = $select_sth->fetchAll();

$flag = false;

foreach($rows as $row):
	if($row['user_name'] == $user_name){
		if(!password_verify($pass,$row['pass'])){
			$_SESSION['error_pass_mes'] = "passmiss";
		}else{
			setcookie("User_Key",$row['user_key'],time()+24*60*60*3);
			$flag = true;
			break;
		}
	}else{
		$_SESSION['error_user_mes'] = 'id_null';
	}
endforeach;

$link = "";
if($flag){
	$link = "./user_home_SNS.php";
}else{
	$link = "./login_SNS.php";
}

header("HTTP/1.1 303 See Other");
header("Location: {$link} ");
?>

