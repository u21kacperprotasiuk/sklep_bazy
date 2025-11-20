<?php
session_start();
session_unset();
session_destroy();
echo "You have logged out and will be redirected after 3 seconds...";
header('REFRESH:3; URL=index.php');
exit();
?>
