<?php
include('../config/db.php');
if(!isset($_SESSION['doc_id'])) { header('location:login.php'); exit(); }

$msg = "";
if(isset($_POST['save_patient'])) {
    $pname = mysqli_real_escape_string($conn, $_POST['pname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $age = intval($_POST['age']);
    $history = mysqli_real_escape_string($conn, $_POST['history']);
    
    if(!empty($_POST['pat_id'])) {
        $id = intval($_POST['pat_id']);
        $q = "UPDATE patients SET patient_name='$pname', contact_no='$phone', gender='$gender', age=$age, med_history='$history' WHERE id=$id";
        $msg = "Patient clinical profile updated!";
    } else {
        $q = "INSERT INTO patients (patient_name, contact_no, gender, age, med_history) VALUES ('$pname', '$phone', '$gender', $age, '$history')";
        $msg = "New patient clinical record created!";
    }
    mysqli_query($conn, $q);
}

$patients = mysqli_query($conn, "SELECT * FROM patients ORDER BY id DESC");
include('../includes/header.php');
include('../includes/doctor_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <div>
            <h2>Manage Patient Records</h2>
            <p>Add and update patient clinical history profiles and diagnostic notes.</p>
        </div>
    </div>

    <?php if($msg) echo "<div class='success-msg'><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:20px;height:20px;'><path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'/></svg> $msg</div>"; ?>

    <div class="data-card">
        <div class="data-card-header">
            <h3 id="form_title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:22px;height:22px;color:var(--primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                Add / Edit Patient Clinical Record
            </h3>
        </div>
        
        <form action="manage-patient.php" method="POST">
            <input type="hidden" name="pat_id" id="pat_id">
            
            <div class="form-flex-layout">
                <!-- Patient Photo Avatar Placeholder Card -->
                <div class="photo-upload-card" style="width: 190px; flex-shrink: 0;">
                    <div class="photo-upload-preview">
                        <span class="avatar-circle-icon" style="width:110px;height:110px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:48px;height:48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                        </span>
                    </div>
                    <span style="font-size: 12px; font-weight: 700; color: var(--text-secondary);">Patient Photo</span>
                    <span style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Identity Avatar</span>
                </div>

                <!-- Patient Form Fields -->
                <div class="form-fields-col form-grid-2">
                    <div class="form-group">
                        <label>Patient Full Name</label>
                        <input type="text" name="pname" id="pat_name" placeholder="Jane Doe" required>
                    </div>

                    <div class="form-group">
                        <label>Contact Phone</label>
                        <input type="text" name="phone" id="pat_phone" placeholder="+1 (555) 000-0000" required>
                    </div>

                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" id="pat_gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Age</label>
                        <input type="number" name="age" id="pat_age" placeholder="e.g., 35" required>
                    </div>

                    <div class="form-group full-width">
                        <label>Clinical Notes / Medical History</label>
                        <textarea name="history" id="pat_history" rows="4" placeholder="Enter clinical diagnosis, prescriptions, or history notes..." required></textarea>
                    </div>

                    <div class="form-group full-width">
                        <div class="form-action-row">
                            <button type="submit" name="save_patient" class="btn">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                Save Clinical Record
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
                <input type="text" id="patSearch" class="table-search-input" placeholder="Search patients by name, phone, gender or age...">
            </div>
            <span class="search-result-count" id="patCount"></span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Patient Record</th>
                    <th>Phone</th>
                    <th>Gender & Age</th>
                    <th>Diagnosis / Medical Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(mysqli_num_rows($patients) > 0) {
                    while($p = mysqli_fetch_assoc($patients)) { 
                ?>
                <tr>
                    <td>
                        <div class="table-user-cell">
                            <?php echo avatar_circle($p['patient_name'], 36); ?>
                            <div class="user-meta">
                                <span class="name"><?php echo htmlspecialchars($p['patient_name']); ?></span>
                                <span class="subtext">#PAT-<?php echo sprintf('%03d', $p['id']); ?></span>
                            </div>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($p['contact_no']); ?></td>
                    <td><span class="badge badge-info"><?php echo htmlspecialchars($p['gender']); ?>, <?php echo htmlspecialchars($p['age']); ?> yrs</span></td>
                    <td><?php echo nl2br(htmlspecialchars($p['med_history'])); ?></td>
                    <td>
                        <button type="button" class="btn btn-secondary btn-sm" onclick='editPat(<?php echo json_encode($p); ?>)'>Edit</button>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center; padding: 24px; color: var(--text-muted);'>No patient records published yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>

<script>
(function() {
    var input = document.getElementById('patSearch');
    var tbody = document.querySelector('table tbody');
    var countEl = document.getElementById('patCount');
    if (!input || !tbody) return;
    input.addEventListener('input', function() {
        var q = this.value.toLowerCase();
        var rows = tbody.querySelectorAll('tr');
        var visible = 0;
        rows.forEach(function(row) {
            var match = !q || row.textContent.toLowerCase().includes(q);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        countEl.textContent = q ? visible + ' result' + (visible !== 1 ? 's' : '') + ' found' : '';
    });
})();

function editPat(d) {
    document.getElementById('form_title').innerText = 'Edit Patient Clinical Record';
    document.getElementById('pat_id').value = d.id;
    document.getElementById('pat_name').value = d.patient_name;
    document.getElementById('pat_phone').value = d.contact_no;
    document.getElementById('pat_gender').value = d.gender;
    document.getElementById('pat_age').value = d.age;
    document.getElementById('pat_history').value = d.med_history;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetForm() {
    document.getElementById('form_title').innerText = 'Add New Patient Clinical Record';
    document.getElementById('pat_id').value = '';
    document.getElementById('pat_name').value = '';
    document.getElementById('pat_phone').value = '';
    document.getElementById('pat_gender').value = 'Male';
    document.getElementById('pat_age').value = '';
    document.getElementById('pat_history').value = '';
}
</script>
<?php include('../includes/footer.php'); ?>
