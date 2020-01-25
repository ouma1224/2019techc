<?php
// 必須である投稿本文がない場合は何もせずに閲覧画面に飛ばす
if( empty($_POST['body']) ) { 
  header("HTTP/1.1 302 Found");
  header("Location: ./home.php");
  return;
}
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
require_once 'DB_Connection.php';
$dbh = getDB();
// INSERTする
$insert_sth = $dbh->prepare("INSERT INTO content (name, body, filename) VALUES (:name, :body, :filename)");
$insert_sth->execute(array(
    ':name' => $_POST['name'],
    ':body' => $_POST['body'],
    ':filename' => $filename,
));
// 投稿が完了したので閲覧画面に飛ばす
header("HTTP/1.1 303 See Other");
header("Location: ./home.php");
?>
