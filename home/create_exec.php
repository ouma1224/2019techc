<?php
if(empty($_POST['user_name']) || empty($_POST['pass']) || empty($_POST['repass']) || strlen($_POST['user_name']) < 3 || strlen($_POST['user_name']) > 20 || strlen($_POST['pass']) < 6 || strlen($_POST['pass']) > 100 ){

	header("HTTP/1.1 302 Found");
	header("Location: ./create_user.php?error=1");
	return;
}
require_once 'DB_Connection_SNS.php';
$dbh = getDB();

session_start();
$user_name = $_POST['user_name'];
$_SESSION["user_name"] = $user_name;
$pass = $_POST['pass'];
$repass = $_POST['repass'];
$flag = false;

#同じuser_idがないか　あったら入力フォームに戻す
$select_sth = $dbh->prepare('SELECT user_id FROM users');
$select_sth->execute();
$rows = $select_sth->fetchAll();
foreach($rows as $row):
	if($user_name == $row['user_name']){
		$flag = true;
		
	}
endforeach;
if($flag == true){
	header("HTTP/1.1 302 Found");
	header("Location: ./create_user.php?error=2");
	return;	
}
#確認用のパスワードと比べあってたら暗号化しデータベースに登録
if($pass == $repass){
	$hash = password_hash($pass, PASSWORD_BCRYPT);
	$user_key = uniqid() . bin2hex(random_bytes(random_int(1, 100)));
	require_once 'DB_Connection_SNS.php';
	$dbh = getDB();
	$insert_sth = $dbh->prepare("INSERT INTO users (user_name,user_key, pass) VALUES (:user_name, :user_key,  :pass)");
	$insert_sth->execute(array(
		':user_name' => $user_name,
		'user_key' => $user_key,
		':pass' => $hash,
	));
	session_unset();
}
header("HTTP/1.1 303 See Other");
header("Location: ./login_SNS.php");

?>
