<?php
include('../config/db.php');
$msg = "";
$error = "";

if(isset($_POST['recover'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $res = mysqli_query($conn, "SELECT password FROM users WHERE email='$email'");
    if(mysqli_num_rows($res) > 0) {
        $r = mysqli_fetch_assoc($res);
        $msg = "Your account password trace result: <strong>" . htmlspecialchars($r['password']) . "</strong>";
    } else { 
        $error = "No patient account found with that email address."; 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover Account Password - CarePlus HMS</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="login-body">
    <div class="login-box">
        <div class="login-header-logo">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21 8.25Z" />
            </svg>
            <h2>Password Recovery</h2>
            <p class="subtitle">Retrieve Patient Credentials</p>
        </div>
        
        <?php if($msg) echo "<div class='info-msg'>$msg</div>"; ?>
        <?php if($error) echo "<div class='error-msg'>$error</div>"; ?>
        
        <form action="forgot-password.php" method="POST">
            <div class="form-group">
                <label>Registered Email Address</label>
                <input type="email" name="email" placeholder="patient@domain.com" required>
            </div>
            <button type="submit" name="recover" class="btn" style="width: 100%; margin-top: 10px;">Retrieve Credentials</button>
        </form>
        
        <p style="text-align: center; margin-top: 24px; font-size: 14px;"><a href="login.php">Back to Patient Sign In</a></p>
    </div>
</body>
</html>
