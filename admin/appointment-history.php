<?php
include('../config/db.php');
if(!isset($_SESSION['admin_id'])) { header('location:login.php'); exit(); }

$msg = "";
$error = "";

// Handle Quick Approve Action
if (isset($_GET['approve'])) {
    $app_id = intval($_GET['approve']);
    if (mysqli_query($conn, "UPDATE appointments SET status = 'Approved' WHERE id = $app_id")) {
        $msg = "Appointment #AP-" . sprintf('%03d', $app_id) . " approved successfully!";
    }
}

// Handle Admin Reschedule / Status / Cancel Submission
if (isset($_POST['update_appointment'])) {
    $app_id = intval($_POST['app_id']);
    $new_date = mysqli_real_escape_string($conn, $_POST['app_date']);
    $new_time = mysqli_real_escape_string($conn, $_POST['app_time']);
    $new_doc  = mysqli_real_escape_string($conn, $_POST['doc_name']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);

    if ($new_status !== 'Cancelled') {
        $check_conflict = mysqli_query($conn, "SELECT id, patient_name FROM appointments WHERE doc_name = '$new_doc' AND app_date = '$new_date' AND app_time = '$new_time' AND (status IS NULL OR status != 'Cancelled') AND id != $app_id");
        if (mysqli_num_rows($check_conflict) > 0) {
            $conflict = mysqli_fetch_assoc($check_conflict);
            $error = "<strong>Double Booking Conflict:</strong> Dr. " . htmlspecialchars($new_doc) . " is already booked for " . date('h:i A', strtotime($new_time)) . " on " . htmlspecialchars($new_date) . " (Patient: " . htmlspecialchars($conflict['patient_name']) . ").";
        } else {
            $update_sql = "UPDATE appointments SET app_date = '$new_date', app_time = '$new_time', doc_name = '$new_doc', status = '$new_status' WHERE id = $app_id";
            if (mysqli_query($conn, $update_sql)) {
                $msg = "Appointment #AP-" . sprintf('%03d', $app_id) . " updated successfully! Status set to: $new_status.";
            } else {
                $error = "Error updating appointment. Please try again.";
            }
        }
    } else {
        $update_sql = "UPDATE appointments SET app_date = '$new_date', app_time = '$new_time', doc_name = '$new_doc', status = '$new_status' WHERE id = $app_id";
        if (mysqli_query($conn, $update_sql)) {
            $msg = "Appointment #AP-" . sprintf('%03d', $app_id) . " updated successfully! Status set to: Cancelled.";
        } else {
            $error = "Error updating appointment. Please try again.";
        }
    }
}

$appointments = mysqli_query($conn, "SELECT * FROM appointments ORDER BY id DESC");
$doctors_list = mysqli_query($conn, "SELECT doc_name, specialization FROM doctors ORDER BY doc_name ASC");

include('../includes/header.php');
include('../includes/admin_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <div>
            <h2>Global Appointment Master Record</h2>
            <p>Complete record of patient bookings. Reschedules and cancellations are managed exclusively by System Administration.</p>
        </div>
    </div>

    <?php if($msg) echo "<div class='success-msg'>$msg</div>"; ?>
    <?php if($error) echo "<div class='error-msg'>$error</div>"; ?>

    <div class="table-responsive">
        <div class="search-bar-wrapper">
            <div class="search-input-wrap">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <input type="text" id="apptSearch" class="table-search-input" placeholder="Search by patient, doctor, specialization, date or status...">
            </div>
            <span class="search-result-count" id="apptCount"></span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Appt ID</th>
                    <th>Patient Name</th>
                    <th>Attending Doctor</th>
                    <th>Specialization</th>
                    <th>Date &amp; Time</th>
                    <th>Status</th>
                    <th style="text-align: right;">Admin Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(mysqli_num_rows($appointments) > 0) {
                    while($a = mysqli_fetch_assoc($appointments)) { 
                        $status_str = isset($a['status']) && $a['status'] ? $a['status'] : 'Pending';
                        $status_badge = 'badge-pending';
                        if ($status_str === 'Approved') $status_badge = 'badge-success';
                        if ($status_str === 'Cancelled') $status_badge = 'badge-danger';
                ?>
                <tr>
                    <td><strong>#AP-<?php echo sprintf('%03d', $a['id']); ?></strong></td>
                    <td>
                        <div class="table-user-cell">
                            <?php echo avatar_circle($a['patient_name'], 36); ?>
                            <span class="name"><?php echo htmlspecialchars($a['patient_name']); ?></span>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 600; font-size: 13px; color: var(--text-primary);"><?php echo htmlspecialchars($a['doc_name']); ?></div>
                    </td>
                    <td><span class="badge badge-info"><?php echo htmlspecialchars($a['specialization']); ?></span></td>
                    <td>
                        <div style="font-size: 13px; font-weight: 600; color: var(--text-primary);"><?php echo htmlspecialchars($a['app_date']); ?></div>
                        <div style="font-size: 12px; color: var(--text-muted);"><?php echo date('h:i A', strtotime($a['app_time'])); ?></div>
                    </td>
                    <td>
                        <span class="badge <?php echo $status_badge; ?>">
                            <?php echo htmlspecialchars($status_str); ?>
                        </span>
                    </td>
                    <td style="text-align: right; display: flex; justify-content: flex-end; gap: 6px; align-items: center;">
                        <?php if ($status_str === 'Pending'): ?>
                        <a href="appointment-history.php?approve=<?php echo $a['id']; ?>" 
                           class="btn btn-sm" 
                           style="background: #10b981; color: #ffffff; border-color: #10b981; font-size: 12px; padding: 6px 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;"
                           onclick="return confirm('Are you sure you want to approve this appointment (#AP-<?php echo sprintf('%03d', $a['id']); ?>)?');">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            Approve
                        </a>
                        <?php endif; ?>
                        <button type="button" 
                                class="btn btn-sm open-reschedule-btn"
                                data-id="<?php echo $a['id']; ?>"
                                data-patient="<?php echo htmlspecialchars($a['patient_name'], ENT_QUOTES); ?>"
                                data-doctor="<?php echo htmlspecialchars($a['doc_name'], ENT_QUOTES); ?>"
                                data-spec="<?php echo htmlspecialchars($a['specialization'], ENT_QUOTES); ?>"
                                data-date="<?php echo $a['app_date']; ?>"
                                data-time="<?php echo $a['app_time']; ?>"
                                data-status="<?php echo $status_str; ?>"
                                style="font-size: 12px; padding: 6px 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                            Reschedule / Cancel
                        </button>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center; padding: 24px; color: var(--text-muted);'>No appointment records found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Reschedule & Cancel Modal for Admin -->
<div class="query-reply-modal" id="rescheduleModal">
    <div class="query-modal-card">
        <div class="query-modal-header">
            <h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                Admin Reschedule / Cancel Appointment
            </h3>
            <button type="button" class="query-modal-close" id="closeRescheduleModal" aria-label="Close modal">&times;</button>
        </div>
        <form action="appointment-history.php" method="POST">
            <div class="query-modal-body">
                <input type="hidden" name="app_id" id="modalAppId">
                
                <div style="background: var(--bg-subtle); padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Patient Name</div>
                        <div style="font-size: 14px; font-weight: 800; color: var(--text-primary);" id="modalPatientName">John Doe</div>
                    </div>
                    <div>
                        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; text-align: right;">Appointment ID</div>
                        <div style="font-size: 14px; font-weight: 800; color: var(--primary); text-align: right;" id="modalAppIdBadge">#AP-001</div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Attending Doctor</label>
                    <select name="doc_name" id="modalDocName" required>
                        <?php 
                        mysqli_data_seek($doctors_list, 0);
                        while($d = mysqli_fetch_assoc($doctors_list)) {
                            echo "<option value='".htmlspecialchars($d['doc_name'])."'>".htmlspecialchars($d['doc_name'])." (".htmlspecialchars($d['specialization']).")</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label>New Appointment Date</label>
                        <input type="date" name="app_date" id="modalAppDate" required>
                    </div>

                    <div class="form-group">
                        <label>New Appointment Time</label>
                        <input type="time" name="app_time" id="modalAppTime" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label>Appointment Status / Action</label>
                    <select name="status" id="modalAppStatus" required>
                        <option value="Approved">Approved (Confirmed)</option>
                        <option value="Pending">Pending Review</option>
                        <option value="Cancelled">Cancelled (Cancel Appointment)</option>
                    </select>
                </div>
            </div>
            <div style="padding: 16px 24px; background: var(--bg-subtle); border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="btn btn-secondary btn-sm" id="cancelRescheduleBtn">Dismiss</button>
                <button type="submit" name="update_appointment" class="btn btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Save Changes &amp; Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search Filtering
    var input = document.getElementById('apptSearch');
    var tbody = document.querySelector('table tbody');
    var countEl = document.getElementById('apptCount');
    if (input && tbody) {
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
    }

    // Modal logic
    const modal = document.getElementById('rescheduleModal');
    const closeBtn = document.getElementById('closeRescheduleModal');
    const cancelBtn = document.getElementById('cancelRescheduleBtn');
    const btns = document.querySelectorAll('.open-reschedule-btn');

    const modalId = document.getElementById('modalAppId');
    const modalIdBadge = document.getElementById('modalAppIdBadge');
    const modalPatient = document.getElementById('modalPatientName');
    const modalDoc = document.getElementById('modalDocName');
    const modalDate = document.getElementById('modalAppDate');
    const modalTime = document.getElementById('modalAppTime');
    const modalStatus = document.getElementById('modalAppStatus');

    btns.forEach(btn => {
        btn.addEventListener('click', function() {
            modalId.value = this.dataset.id;
            modalIdBadge.textContent = '#AP-' + String(this.dataset.id).padStart(3, '0');
            modalPatient.textContent = this.dataset.patient;
            if (modalDoc) modalDoc.value = this.dataset.doctor;
            modalDate.value = this.dataset.date;
            modalTime.value = this.dataset.time;
            modalStatus.value = this.dataset.status;

            modal.classList.add('is-active');
        });
    });

    function closeModal() {
        modal.classList.remove('is-active');
    }

    if(closeBtn) closeBtn.addEventListener('click', closeModal);
    if(cancelBtn) cancelBtn.addEventListener('click', closeModal);
    if(modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });
    }
});
</script>
<?php include('../includes/footer.php'); ?>
