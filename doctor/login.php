<?php
include('../config/db.php');
$error = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    $query = "SELECT * FROM doctors WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($_POST['password'], $row['password']) || $password === $row['password']) {
            if ($password === $row['password']) {
                $new_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE doctors SET password='$new_hash' WHERE id=" . $row['id']);
            }
            $_SESSION['doc_id'] = $row['id'];
            $_SESSION['doc_email'] = $row['email'];
            $_SESSION['doc_name'] = $row['doc_name'];
            
            mysqli_query($conn, "INSERT INTO doctor_logs (doc_email, login_time) VALUES ('{$row['email']}', NOW())");
            $_SESSION['last_doc_log_id'] = mysqli_insert_id($conn);
            
            header('location:dashboard.php');
            exit();
        } else {
            $error = "Invalid doctor email or password credentials.";
        }
    } else {
        $error = "Invalid doctor email or password credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Login - CarePlus HMS</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="login-body">
    <div class="login-box">
        <div class="login-header-logo">
            <div class="icon-circle">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
            <h2>Doctor Portal Login</h2>
            <p class="subtitle">Medical Practitioner Authentication Gate</p>
        </div>
        
        <?php if($error) echo "<div class='error-msg'><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:18px;height:18px;'><path stroke-linecap='round' stroke-linejoin='round' d='M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z'/></svg> $error</div>"; ?>
        
        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Staff Email Address</label>
                <input type="email" name="email" placeholder="doctor@careplus.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit" name="login" class="btn" style="width: 100%; margin-top: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                </svg>
                Doctor Sign In
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 24px; font-size: 14px; display: flex; justify-content: space-between;">
            <a href="forget-password.php">Forgot Password?</a>
            <a href="../index.php">&larr; Back to Directory</a>
        </div>
    </div>
</body>
</html>
