<?php
require_once 'DB_Connection_SNS.php';
$dbh = getDB();

$user_name = "";
$user_id = "";
$img_path = "";
$user_key = $_COOKIE['User_Key'];

$select_sth = $dbh->prepare('SELECT * FROM users WHERE user_key = ?');
$select_sth->execute(array($user_key));
$rows = $select_sth->fetchAll();
foreach($rows as $row):
	$user_name = $row['user_name'];
	$user_id = $row['id'];
	$icon_path = $row['img_path'];
endforeach;

if(empty($icon_path)){
	$icon_path = 'sample.jpg';
}

?>
<head>

<style type = "text/css">

.trim-image-to-circle {
    background-image: url("/static/icon/<?php echo $icon_path ?>");  /* 表示する画像 */
    background-size: cover; 
    width:  80px;       /* ※縦横を同値に */
    height: 80px;       /* ※縦横を同値に */
    border-radius: 50%;  /* 角丸半径を50%にする(=円形にする) */
    /*object-fit: contain;*/
    background-position: center;  /* 横長画像の左上を基準に表示 */
    display: inline-block;          /* 複数の画像を横に並べたい場合 */
}

</style>

</head>

<form method = "POST" action="./edit_execute_SNS.php" enctype="multipart/form-data">
	<div>
		名前：<input type = "text" name = "user_name" value = <?php echo $user_name ?> >
	</div>
	<div>
		password : <input type = "password" name = "pass" minlength = "6" maxlength = "100" >
	</div>
	<div>
		repassword : <input type = "password" name = "repass">

	</div>
	<div class = "icon_image">

		<p class = "trim-image-to-circle" ></p>icon_image: <input type = "file" name = "image">
	</div>
	<input type = "submit">
</form>
