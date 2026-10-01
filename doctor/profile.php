<?php
include('../config/db.php');
if(!isset($_SESSION['doc_id'])) { header('location:login.php'); exit(); }

$did = $_SESSION['doc_id'];
$msg = "";
$error = "";

if(isset($_POST['update_profile'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $spec = mysqli_real_escape_string($conn, $_POST['specialization']);
    
    $q = "UPDATE doctors SET doc_name='$name', contact_no='$phone', specialization='$spec' WHERE id=$did";
    if(mysqli_query($conn, $q)) {
        $_SESSION['doc_name'] = $name;
        $msg = "Professional doctor profile updated successfully!";
    } else {
        $error = "Failed to update profile info.";
    }
}

$d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM doctors WHERE id=$did"));
$specs = mysqli_query($conn, "SELECT * FROM specializations");

include('../includes/header.php');
include('../includes/doctor_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <div>
            <h2>Doctor Profile Information</h2>
            <p>Manage your professional details, contact information, and medical specialization.</p>
        </div>
    </div>

    <?php if($msg) echo "<div class='success-msg'><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:20px;height:20px;'><path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'/></svg> $msg</div>"; ?>
    <?php if($error) echo "<div class='error-msg'><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:20px;height:20px;'><path stroke-linecap='round' stroke-linejoin='round' d='M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z'/></svg> $error</div>"; ?>

    <div class="data-card">
        <div class="data-card-header">
            <h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:22px;height:22px;color:var(--primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                Update Practitioner Profile & Photo
            </h3>
        </div>
        
        <form action="profile.php" method="POST">
            <div style="display: flex; gap: 32px; flex-wrap: wrap; align-items: flex-start;">
                <!-- Doctor Profile Photo Card -->
                <div class="photo-upload-card" style="width: 220px;">
                    <div class="photo-upload-preview" data-preview>
                        <?php echo avatar_circle($d['doc_name'], 110, 'preview-placeholder'); ?>
                        <img class="preview-img" alt="Doctor Profile Picture" style="display:none;">
                        <label class="photo-upload-badge" title="Upload New Photo">
                            <input type="file" accept="image/*" class="preview-input" hidden>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" /></svg>
                        </label>
                    </div>
                    <span style="font-size: 13px; font-weight: 700; color: var(--text-primary);"><?php echo htmlspecialchars($d['doc_name']); ?></span>
                    <span style="font-size: 12px; color: var(--text-muted); margin-top: 2px;"><?php echo htmlspecialchars($d['specialization']); ?></span>
                    <span style="font-size: 11px; color: var(--text-muted); margin-top: 10px;">Photo preview only &mdash; not yet saved to your account</span>
                </div>

                <!-- Doctor Details Form -->
                <div style="flex: 1; min-width: 280px;" class="form-grid-2">
                    <div class="form-group">
                        <label>Doctor Full Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($d['doc_name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Registered Staff Email (Read Only)</label>
                        <input type="email" value="<?php echo htmlspecialchars($d['email']); ?>" disabled style="background-color: var(--bg-app); color: var(--text-muted);">
                    </div>

                    <div class="form-group">
                        <label>Contact Phone Number</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($d['contact_no']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Specialization</label>
                        <select name="specialization" required>
                            <?php while($s = mysqli_fetch_assoc($specs)) { ?>
                                <option value="<?php echo htmlspecialchars($s['spec_name']); ?>" <?php if($s['spec_name'] == $d['specialization']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($s['spec_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group full-width" style="margin-top: 8px;">
                        <button type="submit" name="update_profile" class="btn" style="width: auto;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            Save Profile Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>
<?php include('../includes/footer.php'); ?>
