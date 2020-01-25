<?php
session_start();
$user_name = $_SESSION["user_name"];
$error_mes_id = "";
if($_GET['error'] == 2){
	$error_mes_id = "既に使われているuser_idです";
}else if($_GET['error'] == 1){
	$error_mes_id = "id,passのいずれかが文字数error";
}
session_unset();

?>
<h1>新規登録</h1>
<form action = "./create_exec.php" method = "post">
	<div>
		<p><?php echo $error_mes_id?></p>
		username : <input type = "text" id ="user_name" name = "user_name" value = "<?php echo $user_name?>" minlength = "3" maxlength = "20">
	</div>
	<div>
		password : <input type = "password" id = "pass" name = "pass" minlength = "6" maxlength = "100">
	</div>
	<div>
		Repassword : <input type = "password" id = "repass" name = "repass">
	</div>
	<div>
		<button>送信</button>
	</div>
</form>
