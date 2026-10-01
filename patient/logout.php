<?php
include('../config/db.php');
if(isset($_SESSION['last_user_log_id'])) {
    $lid = $_SESSION['last_user_log_id'];
    mysqli_query($conn, "UPDATE user_logs SET logout_time=NOW() WHERE id=$lid");
}
unset($_SESSION['user_id']);
unset($_SESSION['user_email']);
unset($_SESSION['user_name']);
unset($_SESSION['last_user_log_id']);
session_destroy();
header('location:login.php');
exit();
?>
