<?php
include('../config/db.php');
unset($_SESSION['admin_id']);
unset($_SESSION['admin_user']);
session_destroy();
header('location:login.php');
exit();
?>
