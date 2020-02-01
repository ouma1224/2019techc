<?php
// 接続 ref. https://www.php.net/manual/ja/pdo.connections.php
// $dbh = new PDO('mysql:host=database-1.ca1qzt127uj2.us-east-1.rds.amazonaws.com;dbname=Count', 'admin', 'testpass');
require_once 'DB_Connection_SNS.php';
$dbh = getDB();
// 行の中身を取る
$select_sth = $dbh->prepare('SELECT * FROM contents ORDER BY id ASC');
$select_sth->execute();
$rows = $select_sth->fetchAll();
?>

<?php
if(!($_COOKIE['User_name'])){
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
}
?>
<?php foreach ($rows as $row) : ?>
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
<?php endforeach; ?>


<hr>


<form method="POST" action="./write.php" enctype="multipart/form-data">
    <div>
	名前: <select name = "name">
		<option value = "">著名で送る</option>
		<option value = <?php echo $_COOKIE['User_name'] ?> > <?php echo $_COOKIE['User_name'] ?> </option>
	      </select>
        <input type="file" name="image">
    </div>  
    <div>
        <textarea name="body" rows="5" cols="100" required></textarea>
    </div> 
<?php
if($_COOKIE['User_name']){ echo "<input type=\"submit\">";}else{echo "Loginしてください";}
?>
</form> 
