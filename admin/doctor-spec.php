<?php
include('../config/db.php');
if(!isset($_SESSION['admin_id'])) { header('location:login.php'); exit(); }

$msg = "";
if (isset($_POST['add_spec'])) {
    $spec = mysqli_real_escape_string($conn, $_POST['specialization']);
    $query = "INSERT INTO specializations (spec_name) VALUES ('$spec')";
    if(mysqli_query($conn, $query)) { 
        $msg = "Specialization added successfully!"; 
    }
}

$result = mysqli_query($conn, "SELECT * FROM specializations ORDER BY id DESC");
include('../includes/header.php');
include('../includes/admin_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <h2>Doctor Specializations</h2>
        <p>Add and manage medical clinical specializations.</p>
    </div>

    <?php if($msg) echo "<div class='success-msg'>$msg</div>"; ?>

    <div class="dashboard-two-col" style="align-items: flex-start; margin-bottom: 24px;">
        <!-- Left: Form Card -->
        <div class="data-card" style="margin-bottom: 0;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;color:var(--primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Add New Specialization
            </h3>
            <form action="doctor-spec.php" method="POST" class="inline-form">
                <div class="form-group" style="flex: 1;">
                    <label>Specialization Name</label>
                    <input type="text" name="specialization" placeholder="e.g., Cardiology, Pediatrics, Neurology" required>
                </div>
                <button type="submit" name="add_spec" class="btn" style="margin-top: 24px;">Add Specialization</button>
            </form>
        </div>

        <!-- Right: Department Info Tile -->
        <div class="data-card" style="margin-bottom: 0; background: var(--surface-card);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
                <div style="width: 40px; height: 40px; background: rgba(2, 132, 199, 0.12); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--teal-accent);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:22px;height:22px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" /></svg>
                </div>
                <div>
                    <h4 style="font-size: 15px; font-weight: 700; color: var(--text-primary);">Department Registry</h4>
                    <div style="font-size: 12px; color: var(--text-muted);"><?php echo mysqli_num_rows($result); ?> Specializations Enrolled</div>
                </div>
            </div>
            <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.5;">
                Adding new clinical specializations enables automated doctor-patient matchmaking during online appointment bookings.
            </p>
        </div>
    </div>

    <div class="table-responsive">
        <div class="search-bar-wrapper">
            <div class="search-input-wrap">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <input type="text" id="specSearch" class="table-search-input" placeholder="Search specializations...">
            </div>
            <span class="search-result-count" id="specCount"></span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Specialization Name</th>
                    <th>Date Added</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) { 
                ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td><span style="font-weight: 600; color: var(--text-primary);"><?php echo htmlspecialchars($row['spec_name']); ?></span></td>
                    <td><?php echo isset($row['creation_date']) ? $row['creation_date'] : 'N/A'; ?></td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='3' style='text-align:center; color: var(--text-muted);'>No specializations found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>
<script>
(function() {
    var input = document.getElementById('specSearch');
    var tbody = document.querySelector('table tbody');
    var countEl = document.getElementById('specCount');
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
