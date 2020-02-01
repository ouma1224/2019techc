<?php
// 必須である投稿本文がない場合は何もせずに閲覧画面に飛ばす
if( empty($_POST['user_name']) ) { 
  header("HTTP/1.1 302 Found");
  header("Location: ./edit_user_SNS.php?error=0");
  return;
}

$user_name = $_POST['user_name'];
$error_flag = null;
#名前を変更する場合同じ名前がないか検索
require_once 'DB_Connection_SNS.php';
$dbh = getDB();
$select_sth = $dbh->prepare("select user_name, user_key from users where user_name = ? AND user_key <> ?");
$select_sth->execute(array($_POST['user_name'],$_COOKIE['User_Key']));
$rows = $select_sth->fetchAll();

if(!(empty($rows))){
	$error_flag = 1;
}
$pass = null;
$filename = null;

if(!(empty($_POST['pass']))){
	if(!($_POST['pass'] == $_POST['repass'])){
		$error_flag = 2;
	}else{
		$pass = password_hash($_POST['pass'],PASSWORD_BCRYPT);
	}
}else{
	$select_sht = $dbh->prepare("SELECT * from users WHERE user_key = ?");
	$select_sht->execute(array($_COOKIE['User_Key']));
	$rows = $select_sht->fetchAll();
	foreach($rows as $row):
		$pass = $row['pass'];
		$filename = $row["img_path"];
	endforeach;
}

if(!(empty($error_flag))){
	$link = "./edit_user_SNS.php?error=" . $error_flag;
	header("HTTP/1.1 302 Found");
	header("Location: {$link}");
	return;
}
// ファイルのアップロード処理

// ファイルの存在確認
if ($_FILES['image']['size'] > 0) {
    // 画像かどうかのチェック
    if (exif_imagetype($_FILES['image']['tmp_name'])) {
        // アップロードされたファイルの元々のファイル名から拡張子を取得
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        // ランダムな値でファイル名を生成
        $filename = uniqid() . "." . $ext;
        $filepath = "/src/2019techc/public/static/icon/" . $filename;
        // ファイルを保存
        move_uploaded_file($_FILES['image']['tmp_name'], $filepath);
    }
}


$update_day = date('Y-m-d H:i:s');
$update_sth = $dbh->prepare("UPDATE users set user_name = (:user_name), img_path = (:img_path), pass = (:pass), update_day = (:update_day) where user_key = (:user_key)");
$update_sth->execute(array(
	':user_name' => $user_name,
	':img_path' => $filename,
	':pass' => $pass,
	':update_day' => $update_day,
	':user_key' => $_COOKIE['User_Key'],
));

// 投稿が完了したので閲覧画面に飛ばす
header("HTTP/1.1 303 See Other");
header("Location: ./user_home_SNS.php");

?>
