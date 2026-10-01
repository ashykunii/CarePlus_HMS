<?php
include('../config/db.php');
if(!isset($_SESSION['admin_id'])) { header('location:login.php'); exit(); }

$msg = "";
$error = "";

if(isset($_POST['change_password'])) {
    $uid = $_SESSION['admin_id'];
    $newpass = mysqli_real_escape_string($conn, $_POST['newpass']);
    $confpass = mysqli_real_escape_string($conn, $_POST['confpass']);
    
    if($newpass !== $confpass) {
        $error = "New passwords do not match!";
    } else {
        $hashed_pass = password_hash($_POST['newpass'], PASSWORD_DEFAULT);
        if(mysqli_query($conn, "UPDATE admin SET password='$hashed_pass' WHERE id=$uid")) {
            $msg = "Admin credentials updated successfully!";
        } else {
            $error = "Failed to update password.";
        }
    }
}

include('../includes/header.php');
include('../includes/admin_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <div>
            <h2>Admin Security Settings</h2>
            <p>Update administrator login credentials and account password.</p>
        </div>
    </div>

    <?php if($msg) echo "<div class='success-msg'>$msg</div>"; ?>
    <?php if($error) echo "<div class='error-msg'>$error</div>"; ?>

    <div class="dashboard-two-col" style="align-items: flex-start;">
        <!-- Left: Form Card -->
        <div class="data-card">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;color:var(--primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                Change Administrator Password
            </h3>
            <form action="change-password.php" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="newpass" placeholder="Enter new password" required>
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confpass" placeholder="Confirm new password" required>
                </div>
                <button type="submit" name="change_password" class="btn" style="margin-top: 8px;">Update Password</button>
            </form>
        </div>

        <!-- Right: Admin Security Audit Tile -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div class="data-card" style="background: var(--navy-primary); color: #ffffff; border: 1px solid var(--navy-light);">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                    <div style="width: 36px; height: 36px; background: rgba(239, 68, 68, 0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #fca5a5;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <div>
                        <h4 style="font-size: 15px; font-weight: 800; color: #ffffff;">System Administration Security</h4>
                        <div style="font-size: 12px; color: #94a3b8;">High Security Access Console</div>
                    </div>
                </div>
                <p style="font-size: 13px; color: #cbd5e1; line-height: 1.5; margin-bottom: 12px;">
                    Administrative credential changes affect system-wide root control. All login attempts and credential modifications are logged into the system audit registry.
                </p>
            </div>
        </div>
    </div>
</main>
<?php include('../includes/footer.php'); ?>
