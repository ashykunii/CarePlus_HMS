<?php
include('../config/db.php');
$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    $query = "SELECT * FROM admin WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($_POST['password'], $row['password']) || $password === $row['password']) {
            if ($password === $row['password']) {
                $new_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE admin SET password='$new_hash' WHERE id=" . $row['id']);
            }
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_user'] = $row['username'];
            header('location:dashboard.php');
            exit();
        } else {
            $error = "Invalid username or password credentials.";
        }
    } else {
        $error = "Invalid username or password credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CarePlus HMS</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="login-body">
    <div class="login-box">
        <div class="login-header-logo">
            <div class="icon-circle">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h97.5M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 18H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 12h11.25" />
                </svg>
            </div>
            <h2>Admin Authentication</h2>
            <p class="subtitle">System Administrator Management Access</p>
        </div>
        
        <?php if($error) echo "<div class='error-msg'><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:18px;height:18px;'><path stroke-linecap='round' stroke-linejoin='round' d='M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z'/></svg> $error</div>"; ?>
        
        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter admin username" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit" name="login" class="btn" style="width: 100%; margin-top: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                </svg>
                Sign In to Console
            </button>
        </form>
        <p style="text-align: center; margin-top: 24px; font-size: 14px;"><a href="../index.php">&larr; Back to Portal Directory</a></p>
    </div>
</body>
</html>
