<?php
include('../config/db.php');
if(!isset($_SESSION['user_id'])) { header('location:login.php'); exit(); }

$uid = $_SESSION['user_id'];
$msg = "";
$error = "";

if(isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    if(mysqli_query($conn, "UPDATE users SET fullname='$name' WHERE id=$uid")) {
        $_SESSION['user_name'] = $name;
        $msg = "Profile updated successfully!";
    } else {
        $error = "Failed to update profile info.";
    }
}

$u = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$uid"));
include('../includes/header.php');
include('../includes/patient_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <div>
            <h2>My Account Profile</h2>
            <p>Manage your account name, credentials, and profile details.</p>
        </div>
    </div>

    <?php if($msg) echo "<div class='success-msg'>$msg</div>"; ?>
    <?php if($error) echo "<div class='error-msg'>$error</div>"; ?>

    <div class="dashboard-two-col" style="align-items: flex-start;">
        <!-- Left: Form Card -->
        <div class="data-card">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;color:var(--primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                Update Personal Details
            </h3>
            <form action="profile.php" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($u['fullname']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Email Address (Read Only System ID)</label>
                    <input type="email" value="<?php echo htmlspecialchars($u['email']); ?>" disabled style="background-color: var(--bg-app); color: var(--text-muted);">
                </div>

                <button type="submit" name="update_profile" class="btn" style="margin-top: 8px;">Save Profile Modifications</button>
            </form>
        </div>

        <!-- Right: Patient Account Health Badge Card -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div class="data-card" style="text-align: center; background: var(--surface-card);">
                <div style="margin-bottom: 14px;">
                    <?php echo avatar_circle($u['fullname'], 64, 'lg'); ?>
                </div>
                <h4 style="font-size: 17px; font-weight: 800; color: var(--text-primary); margin-bottom: 2px;"><?php echo htmlspecialchars($u['fullname']); ?></h4>
                <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 12px;"><?php echo htmlspecialchars($u['email']); ?></div>
                <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background: rgba(5, 150, 105, 0.12); color: #059669; border-radius: 20px; font-size: 12px; font-weight: 700;">
                    ● Active Patient ID #PAT-<?php echo sprintf('%04d', $u['id']); ?>
                </div>
            </div>

            <div class="data-card">
                <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 10px; color: var(--text-primary); display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;color:var(--navy-primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Data Privacy Notice
                </h3>
                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.5;">
                    Your profile information is strictly confidential and protected under CarePlus HMS health data privacy standards. Email addresses serve as immutable system identifiers.
                </p>
            </div>
        </div>
    </div>
</main>
<?php include('../includes/footer.php'); ?>
