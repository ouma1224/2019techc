<?php
// 必須である投稿本文がない場合は何もせずに閲覧画面に飛ばす
if( empty($_POST['body']) ) { 
  header("HTTP/1.1 302 Found");
  header("Location: ./user_home_SNS.php");
  return;
}

$user_name = "";
$user_id = "";
$user_key = $_COOKIE['User_Key'];

require_once 'DB_Connection_SNS.php';
$dbh =getDB();
$select_sth = $dbh->prepare('SELECT id,user_key, user_name FROM users WHERE user_key = ?');
$select_sth->execute(array($user_key));
$rows = $select_sth->fetchAll();

foreach($rows as $row):
	$user_name = $row['user_name'];
	$user_id = $row['id'];
endforeach;

// ファイルのアップロード処理
$filename = null;
// ファイルの存在確認
if ($_FILES['image']['size'] > 0) {
    // 画像かどうかのチェック
    if (exif_imagetype($_FILES['image']['tmp_name'])) {
        // アップロードされたファイルの元々のファイル名から拡張子を取得
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        // ランダムな値でファイル名を生成
        $filename = uniqid() . "." . $ext;
        $filepath = "/src/2019techc/public/static/images/" . $filename;
        // ファイルを保存
        move_uploaded_file($_FILES['image']['tmp_name'], $filepath);
    }
}
// 接続 ref. https://www.php.net/manual/ja/pdo.connections.php
$dbh = getDB();
// INSERTする
$insert_sth = $dbh->prepare("INSERT INTO contents (user_name, user_id, body, file_path) VALUES (:user_name, :user_id, :body, :file_path)");
$insert_sth->execute(array(
	':user_name' => $user_name,
	':user_id' => $user_id,
    	':body' => $_POST['body'],
   	':file_path' => $filename,
));
// 投稿が完了したので閲覧画面に飛ばす
header("HTTP/1.1 303 See Other");
header("Location: ./user_home_SNS.php");
?>
