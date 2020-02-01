<?php

require_once 'DB_Connection_SNS.php';
$dbh = getDB();
$select_sth = $dbh->prepare("select user_name, user_key from users where user_name = ? AND user_key <> ?");
$select_sth->execute(array('test',$_COOKIE['User_Key']));
$rows = $select_sth->fetchAll();

if(!(empty($rows))){
	$error_flag = 1;
	echo "aaaa";
}
$user_name = "test";
$pass = "$2y$10$3oEMzcwGXtN3rlAU.FIM8.b3xzzrRpKDcXDBh1IKCsi7K41pBBRqC";
$filename = "sample.jpg";
$update_day = "2020-02-01 08:20:56";
$update_sth = $dbh->prepare("UPDATE users SET user_name = (:user_name), img_path = (:img_path), pass = (:pass), update_day = (:update_day) WHERE user_key = (:user_key)");
$update_sth->execute(array(
        ':user_name' => $user_name,
        ':img_path' => $filename,
        ':pass' => $pass,
        ':update_day' => $update_day,
        ':user_key' => $_COOKEI['User_Key'],
));

$select_sth = $dbh("select * from users where user_key = ?");
$select_sth->execute(array($_COOKIE
