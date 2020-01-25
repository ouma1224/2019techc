<?php
session_start();

$user_name = $_SESSION["user_name"];
$error_user_mes = $_SESSION["error_user_mes"];
$error_pass_mes = $_SESSION["error_pass_mes"];

session_unset();
?>

<form method = "POST" action = "./check_SNS.php">
	<p><?php echo $error_user_mes ?></p>
	<div>
	username : <input type = "text" name = "user_name" value = <?php echo $user_name?>>
	</div>

	<p><?php echo $error_pass_mes ?></p>
	<div>
		passwrod : <input type = "password" name = "pass">
	</div>
	<div>
		<button>送信</button>
	</div>
</form>
<form action = "./create_user.php">
	<button>新規登録</button>
</form>
