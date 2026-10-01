<?php
include('../config/db.php');
if(isset($_SESSION['last_doc_log_id'])) {
    $lid = $_SESSION['last_doc_log_id'];
    mysqli_query($conn, "UPDATE doctor_logs SET logout_time=NOW() WHERE id=$lid");
}
unset($_SESSION['doc_id']);
unset($_SESSION['doc_email']);
unset($_SESSION['doc_name']);
unset($_SESSION['last_doc_log_id']);
session_destroy();
header('location:login.php');
exit();
?>
