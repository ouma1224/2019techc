<?php
// 接続 ref. https://www.php.net/manual/ja/pdo.connections.php
// $dbh = new PDO('mysql:host=database-1.ca1qzt127uj2.us-east-1.rds.amazonaws.com;dbname=Count', 'admin', 'testpass');
require_once 'DB_Connection_SNS.php';
$dbh = getDB();

if(!($_COOKIE['User_Key'])){
	$user_error_flag = true;
}else{
	$select_sth = $dbh->prepare('SELECT * from users');
        $select_sth->execute();
        $rows = $select_sth->fetchAll();
        foreach($rows as $row):
                if($row['user_key'] == $_COOKIE['User_Key']){
                        $user_id = $row['id'];
                        $user_name = $row['user_name'];
				if($row['img_path'] == null){
				 	$icon_path = 'sample.jpg';
				}else{	
					$icon_path = $row['img_path'];
				}
                }
	endforeach;
	$user_error_flag = false;
}
?>



<head>
<style type ="text/css">
.icon_image{
	background-image: url("/static/icon/<?php echo $icon_path ?>");
	background-size: cover;
	width: 40px;
	height: 40px;
	margin-top:none;
	margin-bottom:none;
	border-radius: 50%;
	background-position: center;
	display: inline-block;

}
p{
	margin: none;
}
.test{
	width: 40px;
	height: 40px;
	border-radius:50%;
	display:inline-block;
}
</style>
</head>

<?php
if($user_error_flag){
        echo "<form action=\"./login_SNS.php\">";
        echo "<button>Login</button>";
        echo "</form>";

        echo "<form action = \"./create_user.php\">";
        echo "<button>新規登録</button>";
        echo "</form>";
}else{
	echo "<form action = \"./edit_user_SNS.php\">";
	echo "<button>プロフィール編集</button>";
	echo "</form>";
	echo "<form action=\"./logout.php\">";
        echo "<button>Logout</button>";
        echo "</form>";
}
?>
<?php
// 行の中身を取る
$select_sth = $dbh->prepare('SELECT * FROM contents JOIN users ON contents.user_id = users.id WHERE user_id = ? ORDER BY contents.id ASC');
$select_sth->execute(array($user_id));
$rows = $select_sth->fetchAll();


foreach ($rows as $row) : ?>
<hr>
<div>
    <span><?php if($row['img_path'] == null){ $temp = "sample.jpg";}else{$temp = $row['img_path'];}?><img src="/static/icon/<?php echo $temp;?>" class="test"><?php echo $row['user_name']; ?>さんの投稿 (投稿日: <?php echo $row['created_time']; ?>)</span>
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


<form method="POST" action="./write_SNS.php" enctype="multipart/form-data">
    <div>
        <p class = "icon_image"></p>名前: <?php echo $user_name; ?> 
        <input type="file" name="image">
    </div>
    <div>
        <textarea name="body" rows="5" cols="100" required></textarea>
    </div>
<?php
if($_COOKIE['User_Key']){ echo "<input type=\"submit\">";}else{echo "Loginしてください";}
?>
</form>

