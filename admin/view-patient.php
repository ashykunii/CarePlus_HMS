<?php
include('../config/db.php');
if(!isset($_SESSION['admin_id'])) { header('location:login.php'); exit(); }

$patients = mysqli_query($conn, "SELECT * FROM patients ORDER BY id DESC");
include('../includes/header.php');
include('../includes/admin_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <div>
            <h2>Registered Patient Medical Profiles</h2>
            <p>Comprehensive patient health profiles and clinical history notes.</p>
        </div>
    </div>

    <div class="table-responsive">
        <div class="search-bar-wrapper">
            <div class="search-input-wrap">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <input type="text" id="patientSearch" class="table-search-input" placeholder="Search patients by name, phone, gender, or age...">
            </div>
            <span class="search-result-count" id="patientCount"></span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Patient Record</th>
                    <th>Contact Phone</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Medical History Notes</th>
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
                                <span class="subtext">Record #PAT-<?php echo sprintf('%03d', $p['id']); ?></span>
                            </div>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($p['contact_no']); ?></td>
                    <td><span class="badge badge-info"><?php echo htmlspecialchars($p['gender']); ?></span></td>
                    <td><strong><?php echo htmlspecialchars($p['age']); ?></strong> yrs</td>
                    <td><?php echo nl2br(htmlspecialchars($p['med_history'])); ?></td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center; padding: 24px; color: var(--text-muted);'>No patient profiles found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>
<script>
(function() {
    var input = document.getElementById('patientSearch');
    var tbody = document.querySelector('table tbody');
    var countEl = document.getElementById('patientCount');
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
</script>
<?php include('../includes/footer.php'); ?>
