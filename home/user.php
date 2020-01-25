<?php
// 接続 ref. https://www.php.net/manual/ja/pdo.connections.php
// $dbh = new PDO('mysql:host=database-1.ca1qzt127uj2.us-east-1.rds.amazonaws.com;dbname=Count', 'admin', 'testpass');
require_once 'DB_Connection_SNS.php';
$dbh = getDB();

//user_name取得
if(!($_COOKIE['user_key'])){
	echo "<form action=\"./login.php\">";
        echo "<button>Login</button>";
        echo "</form>";

        echo "<form action = \"./create_user.php\">";
        echo "<button>新規登録</button>";
        echo "</form>";
}else{

	echo "<form action=\"./logout.php\">";
        echo "<button>Logout</button>";
        echo "</form>";

	$select_sth = $dbh->prepare('SELECT id, user_key, user_name FROM users WHERE user_key = ?');
	$select_sth->execute(array($_COOKIE['user_key']));
	$rows = $select_sth->fetchAll();
	foreach($rows as $row):
		$user_id = $row['user_id'];
		$user_name = $row['user_name'];
	endforeach;


	// 行の中身を取る
	$select_sth = $dbh->prepare('SELECT user_name, user_id, body, file_path, created_time FROM content WHRE user_id = ? ORDER BY id ASC');
	$select_sth->execute(array($user_id));
	$rows = $select_sth->fetchAll();

	 foreach ($rows as $row) : 
?>
<div>
    <span><?php if ($row['name']) { echo $row['name']; } else { echo "名無し"; } ?>さんの投稿 (投稿日: <?php echo $row['created_time']; ?>)</span>
    <p>
        <?php echo $row['body']; ?>
    </p>
    <?php if (!empty($row['file_path'])) { ?>
    <p>
        <img src="/static/images/<?php echo $row['file_path']; ?>" width="200px">
    </p>
    <?php } ?>
</div> 

<?php  endforeach;  ?>

<hr>


<form method="POST" action="./user_write.php" enctype="multipart/form-data">
    <div>
    名前: <?php echo $user_name; ?><input type="hidden" value = <?php echo $user_name ?>>
        <input type="file" name="image">
    </div>  
    <div>
        <textarea name="body" rows="5" cols="100" required></textarea>
    </div> 
<?php
if($_COOKIE['user_key']){ echo "<input type=\"submit\">";}else{echo "Loginしてください";}
?>
</form> 
<?php } ?>
