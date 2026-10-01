<?php
include('../config/db.php');
if(!isset($_SESSION['admin_id'])) { header('location:login.php'); exit(); }

$msg = "";
if(isset($_POST['save_doctor'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $spec = mysqli_real_escape_string($conn, $_POST['specialization']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    
    if(!empty($_POST['doc_id'])) {
        $id = intval($_POST['doc_id']);
        if(!empty($_POST['password'])) {
            $hashed_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $q = "UPDATE doctors SET doc_name='$name', specialization='$spec', contact_no='$phone', email='$email', password='$hashed_pass' WHERE id=$id";
        } else {
            $q = "UPDATE doctors SET doc_name='$name', specialization='$spec', contact_no='$phone', email='$email' WHERE id=$id";
        }
        $msg = "Doctor record updated successfully!";
    } else {
        $hashed_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $q = "INSERT INTO doctors (doc_name, specialization, contact_no, email, password) VALUES ('$name', '$spec', '$phone', '$email', '$hashed_pass')";
        $msg = "New doctor added successfully!";
    }
    mysqli_query($conn, $q);
}

// Delete doctor
if(isset($_GET['del'])) {
    $did = intval($_GET['del']);
    mysqli_query($conn, "DELETE FROM doctors WHERE id=$did");
    header('location:manage-doctor.php');
    exit();
}

$specs = mysqli_query($conn, "SELECT * FROM specializations");
$doctors = mysqli_query($conn, "SELECT * FROM doctors ORDER BY id DESC");
include('../includes/header.php');
include('../includes/admin_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <div>
            <h2>Manage Doctors</h2>
            <p>Register new medical practitioners and manage existing staff profiles.</p>
        </div>
    </div>

    <?php if($msg) echo "<div class='success-msg'><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:20px;height:20px;'><path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'/></svg> $msg</div>"; ?>

    <div class="data-card">
        <div class="data-card-header">
            <h3 id="form_title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:22px;height:22px;color:var(--primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.765Z" /></svg>
                Add / Edit Doctor Profile
            </h3>
        </div>
        
        <form action="manage-doctor.php" method="POST">
            <input type="hidden" name="doc_id" id="doc_id">
            
            <div class="form-flex-layout">
                <!-- Doctor Photo Placeholder Card -->
                <div class="photo-upload-card" style="width: 190px; flex-shrink: 0;">
                    <div class="photo-upload-preview" data-preview>
                        <span class="avatar-circle-icon preview-placeholder" id="doctor_photo_placeholder" style="width:110px;height:110px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                        </span>
                        <img id="doctor_photo_preview" class="preview-img" alt="Doctor Photo Preview" style="display:none;">
                        <label class="photo-upload-badge" title="Change Photo">
                            <input type="file" accept="image/*" class="preview-input" hidden>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" /></svg>
                        </label>
                    </div>
                    <span style="font-size: 12px; font-weight: 700; color: var(--text-secondary);">Doctor Photo</span>
                    <span style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">JPG, PNG &mdash; preview only</span>
                </div>

                <!-- Doctor Input Form Fields -->
                <div class="form-fields-col form-grid-2">
                    <div class="form-group">
                        <label>Doctor Full Name</label>
                        <input type="text" name="name" id="doc_name" placeholder="Dr. Sarah Jenkins" required>
                    </div>

                    <div class="form-group">
                        <label>Specialization</label>
                        <select name="specialization" id="doc_spec" required>
                            <option value="">Select Specialization</option>
                            <?php while($s = mysqli_fetch_assoc($specs)) { echo "<option value='".htmlspecialchars($s['spec_name'])."'>".htmlspecialchars($s['spec_name'])."</option>"; } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Contact Phone Number</label>
                        <input type="text" name="phone" id="doc_phone" placeholder="+1 (555) 000-0000" required>
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" id="doc_email" placeholder="doctor@careplus.com" required>
                    </div>

                    <div class="form-group full-width">
                        <label>Account Password</label>
                        <input type="password" name="password" id="doc_password" placeholder="Create secure password">
                    </div>

                    <div class="form-group full-width">
                        <div class="form-action-row">
                            <button type="submit" name="save_doctor" class="btn">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                Save Doctor Profile
                            </button>
                            <button type="button" onclick="resetForm()" class="btn btn-secondary">Reset Form</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <div class="search-bar-wrapper">
            <div class="search-input-wrap">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <input type="text" id="doctorSearch" class="table-search-input" placeholder="Search doctors by name, specialization, phone or email..." data-table-body="table tbody" data-count="doctorCount">
            </div>
            <span class="search-result-count" id="doctorCount"></span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Doctor Profile</th>
                    <th>Specialization</th>
                    <th>Contact Phone</th>
                    <th>Email Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(mysqli_num_rows($doctors) > 0) {
                    while($d = mysqli_fetch_assoc($doctors)) { 
                ?>
                <tr>
                    <td>
                        <div class="table-user-cell">
                            <?php echo avatar_circle($d['doc_name'], 36); ?>
                            <div class="user-meta">
                                <span class="name"><?php echo htmlspecialchars($d['doc_name']); ?></span>
                                <span class="subtext">ID: #DOC-<?php echo sprintf('%03d', $d['id']); ?></span>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-info"><?php echo htmlspecialchars($d['specialization']); ?></span></td>
                    <td><?php echo htmlspecialchars($d['contact_no']); ?></td>
                    <td><?php echo htmlspecialchars($d['email']); ?></td>
                    <td>
                        <button type="button" class="btn btn-secondary btn-sm" onclick='editDoc(<?php echo json_encode($d); ?>)'>Edit</button>
                        <a href="manage-doctor.php?del=<?php echo $d['id']; ?>" type="button" class="danger-btn" onclick="return confirm('Are you sure you want to remove this doctor record?')">Delete</a>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center; padding: 24px; color: var(--text-muted);'>No doctor records found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>

<?php include('../includes/footer.php'); ?>
