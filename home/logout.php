<?php

setcookie('User_Key','', time() - 1800);

header("HTTP/1.1 303 See Other");
header("Location: ./login_SNS.php");
return;

