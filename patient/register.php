<?php
include('../config/db.php');
$msg = "";
$error = "";

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';

    if ($_POST['password'] !== $password_confirm) {
        $error = "Passwords do not match. Please try again.";
    } else {
    // Check if email exists
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0) {
        $error = "An account with this email address already exists.";
    } else {
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query = "INSERT INTO users (fullname, email, password) VALUES ('$name', '$email', '$hashed_password')";
        if(mysqli_query($conn, $query)) {
            $msg = "Account created successfully! You can now sign in.";
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration - CarePlus HMS</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="login-body">
    <div class="login-box">
        <div class="login-header-logo">
            <div class="icon-circle">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.765Z" />
                </svg>
            </div>
            <h2>Create Patient Account</h2>
            <p class="subtitle">Join CarePlus Digital Health System</p>
        </div>
        
        <?php if($msg) echo "<div class='success-msg'><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:18px;height:18px;'><path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'/></svg> $msg</div>"; ?>
        <?php if($error) echo "<div class='error-msg'><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:18px;height:18px;'><path stroke-linecap='round' stroke-linejoin='round' d='M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z'/></svg> $error</div>"; ?>
        
        <form action="register.php" method="POST">
            <div class="form-group">
                <label>Full Legal Name</label>
                <input type="text" name="name" placeholder="John Doe" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="patient@domain.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" id="reg_password" placeholder="Create a secure password" minlength="6" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirm" id="reg_password_confirm" placeholder="Re-enter your password" minlength="6" required>
                <span id="pwMatchMsg" style="font-size: 12px; font-weight: 600;"></span>
            </div>
            <button type="submit" name="register" class="btn" style="width: 100%; margin-top: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.765Z" />
                </svg>
                Register Account
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 24px; font-size: 14px;">Already registered? <a href="login.php" style="font-weight: 700;">Sign In Here</a></p>
    </div>
    <script src="../assets/main.js"></script>
</body>
</html>

