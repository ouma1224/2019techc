<?php
// 接続 ref. https://www.php.net/manual/ja/pdo.connections.php
// $dbh = new PDO('mysql:host=database-1.ca1qzt127uj2.us-east-1.rds.amazonaws.com;dbname=Count', 'admin', 'testpass');
require_once 'DB_Connection_SNS.php';
$dbh = getDB();

?>

<?php
if(!($_COOKIE['User_Key'])){
        echo "<form action=\"./login_SNS.php\">";
        echo "<button>Login</button>";
        echo "</form>";

        echo "<form action = \"./create_user.php\">";
        echo "<button>新規登録</button>";
        echo "</form>";
}else{
	$select_sth = $dbh->prepare('SELECT * from users');
	$select_sth->execute();
	$rows = $select_sth->fetchAll();
	foreach($rows as $row):
		if($row['user_key'] == $_COOKIE['User_Key']){
			$user_id = $row['id'];
			$user_name = $row['user_name'];
			$icon_path = $row['img_path'];
		}
	endforeach;
	echo "<form action=\"./logout.php\">";
        echo "<button>Logout</button>";
        echo "</form>";
}
?>
<?php
// 行の中身を取る
$select_sth = $dbh->prepare('SELECT * FROM contents where user_id = ? ORDER BY id ASC');
$select_sth->execute(array($user_id));
$rows = $select_sth->fetchAll();

foreach ($rows as $row) : ?>
<div>
    <span><?php echo $row['name']; ?>さんの投稿 (投稿日: <?php echo $row['created_time']; ?>)</span>
    <p>
        <?php echo $row['body']; ?>
    </p>
    <?php if (!empty($row['filename'])) { ?>
    <p>
        <img src="/static/images/<?php echo $row['filename']; ?>" width="200px">
    </p>
    <?php } ?>
</div>
<?php endforeach; ?>

<hr>


<form method="POST" action="./write_SNS.php" enctype="multipart/form-data">
    <div>
        名前: <?php echo $user_name; ?> 
        <input type="file" name="image">
    </div>
    <div>
        <textarea name="body" rows="5" cols="100" required></textarea>
    </div>
<?php
if($_COOKIE['User_Key']){ echo "<input type=\"submit\">";}else{echo "Loginしてください";}
?>
</form>

