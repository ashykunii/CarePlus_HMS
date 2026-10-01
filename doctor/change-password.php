<?php
include('../config/db.php');
if(!isset($_SESSION['doc_id'])) { header('location:login.php'); exit(); }

$msg = "";
$error = "";

if(isset($_POST['change_key'])) {
    $did = $_SESSION['doc_id'];
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    $conf = mysqli_real_escape_string($conn, $_POST['conf_password']);
    
    if($pass !== $conf) {
        $error = "Passwords do not match!";
    } else {
        $hashed_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        if(mysqli_query($conn, "UPDATE doctors SET password='$hashed_pass' WHERE id=$did")) { 
            $msg = "Doctor account password updated successfully!"; 
        } else {
            $error = "Failed to update password.";
        }
    }
}

include('../includes/header.php');
include('../includes/doctor_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <div>
            <h2>Security &amp; Credentials</h2>
            <p>Update your doctor portal consultation access password.</p>
        </div>
    </div>

    <?php if($msg) echo "<div class='success-msg'>$msg</div>"; ?>
    <?php if($error) echo "<div class='error-msg'>$error</div>"; ?>

    <div class="dashboard-two-col" style="align-items: flex-start;">
        <!-- Left: Form Card -->
        <div class="data-card">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;color:var(--primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                Change Staff Password
            </h3>
            <form action="change-password.php" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="password" placeholder="Enter new password" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="conf_password" placeholder="Confirm new password" required>
                </div>
                <button type="submit" name="change_key" class="btn" style="margin-top: 8px;">Update Password</button>
            </form>
        </div>

        <!-- Right: Doctor Security Policy Card -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div class="data-card" style="background: var(--navy-primary); color: #ffffff; border: 1px solid var(--navy-light);">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                    <div style="width: 36px; height: 36px; background: rgba(56, 189, 248, 0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #38bdf8;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <div>
                        <h4 style="font-size: 15px; font-weight: 800; color: #ffffff;">Clinical Account Security</h4>
                        <div style="font-size: 12px; color: #94a3b8;">EHR Data Protection Policy</div>
                    </div>
                </div>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 10px; font-size: 13px; color: #cbd5e1;">
                    <li style="display: flex; align-items: flex-start; gap: 8px;">
                        <span style="color: #34d399; font-weight: 800;">✓</span>
                        Ensure your clinical password remains private to protect EHR records.
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 8px;">
                        <span style="color: #34d399; font-weight: 800;">✓</span>
                        Log out of active station sessions when leaving consultation rooms.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</main>
<?php include('../includes/footer.php'); ?>
