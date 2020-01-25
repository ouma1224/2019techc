<?php
require_once 'DB_Connection_SNS.php';
$dbh = getDB();
$temp = $_COOKIE['User_Key'];
$user_name;
$test;
$select_sth = $dbh->prepare('SELECT id,user_key, user_name FROM users WHERE user_key = ?');
$select_sth->execute(array($temp));
$rows = $select_sth->fetchAll();
foreach($rows as $row) :
	$user_name = $row['user_name'];
endforeach;
$user_key = uniqid() . bin2hex(random_bytes(random_int(1, 100)));
$num = strlen($user_key);
?>


<form method="POST" action="./test_img.php" enctype="multipart/form-data">
    <div>
        <input type="file" name="image">
    </div>
<input type="submit">
</form>

