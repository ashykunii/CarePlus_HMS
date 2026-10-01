<?php
include('../config/db.php');
if(!isset($_SESSION['doc_id'])) { header('location:login.php'); exit(); }

$dname = $_SESSION['doc_name'];
$history_result = mysqli_query($conn, "SELECT * FROM appointments WHERE doc_name='$dname' ORDER BY id DESC");
$history_arr = [];
while($h = mysqli_fetch_assoc($history_result)) { $history_arr[] = $h; }

include('../includes/header.php');
include('../includes/doctor_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <div>
            <h2>My Consultation Appointments</h2>
            <p>Complete historical list of appointments assigned to Dr. <?php echo htmlspecialchars($dname); ?>.</p>
        </div>
    </div>

    <!-- Admin Reschedule/Cancellation Policy Notice for Doctor -->
    <div style="background: #f8fafc; border: 1px solid var(--border-color); border-left: 4px solid var(--navy-primary); border-radius: var(--radius-sm); padding: 14px 18px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px;color:var(--navy-primary);flex-shrink:0;">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
        </svg>
        <span style="font-size: 13px; color: var(--text-secondary);">
            <strong>System Administration Policy:</strong> Appointment rescheduling and cancellation controls are managed exclusively by System Administration. Please contact the admin desk for shift or appointment adjustments.
        </span>
    </div>

    <div class="table-responsive">
        <div class="search-bar-wrapper">
            <div class="search-input-wrap">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <input type="text" id="dApptSearch" class="table-search-input" placeholder="Search by patient, specialization, date or status...">
            </div>
            <span class="search-result-count" id="dApptCount"></span>
        </div>
        <p style="font-size:12px; color:var(--text-muted); margin: -8px 0 12px; padding: 0 4px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:13px;height:13px;vertical-align:-2px;margin-right:3px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672 13.684 16.6m0 0-2.51 2.225.569-9.47 5.227 7.917-3.286-.672Zm-7.518-.267A8.25 8.25 0 1 1 20.25 10.5M8.288 14.212A5.25 5.25 0 1 1 17.25 10.5" /></svg>
            Click any row to view full appointment details
        </p>
        <table>
            <thead>
                <tr>
                    <th>Appt ID</th>
                    <th>Patient Name</th>
                    <th>Specialization</th>
                    <th>Scheduled Date &amp; Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(count($history_arr) > 0) {
                    foreach($history_arr as $idx => $h) { 
                        $st = isset($h['status']) && $h['status'] ? $h['status'] : 'Pending';
                        $st_badge = 'badge-pending';
                        if ($st === 'Approved') $st_badge = 'badge-success';
                        if ($st === 'Cancelled') $st_badge = 'badge-danger';
                ?>
                <tr class="doc-appt-row" data-idx="<?php echo $idx; ?>" style="cursor:pointer;" title="Click to view details">
                    <td><strong>#AP-<?php echo sprintf('%03d', $h['id']); ?></strong></td>
                    <td>
                        <div class="table-user-cell">
                            <?php echo avatar_circle($h['patient_name'], 36); ?>
                            <span class="name"><?php echo htmlspecialchars($h['patient_name']); ?></span>
                        </div>
                    </td>
                    <td><span class="badge badge-info"><?php echo htmlspecialchars($h['specialization']); ?></span></td>
                    <td>
                        <div style="font-size: 13px; font-weight: 600; color: var(--text-primary);"><?php echo htmlspecialchars($h['app_date']); ?></div>
                        <div style="font-size: 12px; color: var(--text-muted);"><?php echo date('h:i A', strtotime($h['app_time'])); ?></div>
                    </td>
                    <td>
                        <span class="badge <?php echo $st_badge; ?>">
                            <?php echo htmlspecialchars($st); ?>
                        </span>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center; padding: 24px; color: var(--text-muted);'>No consultation appointments found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Appointment Detail Popup -->
<div id="docApptModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; box-shadow:0 24px 64px rgba(0,0,0,0.24); width:100%; max-width:500px; margin:20px; overflow:hidden; animation: slideUp 0.22s ease;">
        <div style="background: var(--navy-primary); padding:20px 24px; display:flex; align-items:center; justify-content:space-between;">
            <div>
                <div style="font-size:11px; font-weight:700; color:rgba(255,255,255,0.6); text-transform:uppercase; letter-spacing:0.8px; margin-bottom:4px;">Patient Appointment</div>
                <div id="dModalApptId" style="font-size:20px; font-weight:800; color:#fff;">#AP-001</div>
            </div>
            <button id="closeDModal" style="background:rgba(255,255,255,0.15); border:none; border-radius:8px; color:#fff; cursor:pointer; padding:8px; display:flex; align-items:center; justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div style="padding:20px 24px;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
                <div style="background:var(--bg-subtle); border-radius:10px; padding:12px 14px;">
                    <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Patient</div>
                    <div id="dModalPatient" style="font-size:14px; font-weight:700; color:var(--text-primary);">—</div>
                </div>
                <div style="background:var(--bg-subtle); border-radius:10px; padding:12px 14px;">
                    <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Specialization</div>
                    <div id="dModalSpec" style="font-size:14px; font-weight:700; color:var(--text-primary);">—</div>
                </div>
                <div style="background:var(--bg-subtle); border-radius:10px; padding:12px 14px;">
                    <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Date</div>
                    <div id="dModalDate" style="font-size:14px; font-weight:700; color:var(--text-primary);">—</div>
                </div>
                <div style="background:var(--bg-subtle); border-radius:10px; padding:12px 14px;">
                    <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Time</div>
                    <div id="dModalTime" style="font-size:14px; font-weight:700; color:var(--text-primary);">—</div>
                </div>
            </div>
            <div style="background:var(--bg-subtle); border-radius:10px; padding:12px 14px; display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
                <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Status</div>
                <span id="dModalStatusBadge" class="badge badge-pending">Pending</span>
            </div>
            <!-- Mini timeline -->
            <div id="dModalTimeline" style="display:flex; margin-bottom:14px;"></div>
            <div id="dModalMsg" style="font-size:13px; color:var(--text-secondary); line-height:1.5; padding:10px 14px; border-radius:8px; border-left:3px solid var(--primary); background:var(--bg-subtle);"></div>
        </div>
        <div style="padding:0 24px 20px; display:flex; gap:10px;">
            <button id="closeDModal2" class="btn btn-secondary" style="flex:1; justify-content:center;">Close</button>
            <a href="schedule.php" class="btn" style="flex:1; justify-content:center; text-decoration:none;">My Schedule →</a>
        </div>
    </div>
</div>



<script>
var docHistory = <?php echo json_encode($history_arr); ?>;
var docModal = document.getElementById('docApptModal');

function fmtTime(t) {
    if (!t) return '—';
    var p=t.split(':'); var h=parseInt(p[0],10); var m=p[1]||'00';
    var ap=h>=12?'PM':'AM'; h=h%12; h=h?h:12;
    return (h<10?'0'+h:h)+':'+m+' '+ap;
}

function openDocModal(appt) {
    var st = appt.status || 'Pending';
    var bc = st==='Approved'?'badge-success':(st==='Cancelled'?'badge-danger':'badge-pending');
    var msgs = {
        'Pending':  'This patient appointment is pending admin confirmation. Prepare for potential consultation.',
        'Approved': 'Appointment confirmed. Patient should arrive 15 minutes early. Please review patient history.',
        'Cancelled':'This appointment has been cancelled by administration.'
    };
    document.getElementById('dModalApptId').textContent = '#AP-' + String(appt.id).padStart(3,'0');
    document.getElementById('dModalPatient').textContent = appt.patient_name || '—';
    document.getElementById('dModalSpec').textContent = appt.specialization || '—';
    document.getElementById('dModalDate').textContent = appt.app_date || '—';
    document.getElementById('dModalTime').textContent = fmtTime(appt.app_time);
    var b = document.getElementById('dModalStatusBadge');
    b.textContent = st; b.className = 'badge ' + bc;
    document.getElementById('dModalMsg').textContent = msgs[st] || '';
    var isCan = st==='Cancelled', isPen = st==='Pending', isApp = st==='Approved';
    document.getElementById('dModalTimeline').innerHTML =
        '<div class="mini-tl-step done"><div class="mini-tl-dot">✓</div><div class="mini-tl-label">Submitted</div></div>' +
        '<div class="mini-tl-step '+(isCan?'cancelled':(isPen?'active':'done'))+'"><div class="mini-tl-dot">'+(isCan?'✕':(isPen?'…':'✓'))+'</div><div class="mini-tl-label">'+(isCan?'Cancelled':(isPen?'Pending':'Approved'))+'</div></div>' +
        '<div class="mini-tl-step '+(isApp?'active':'')+'"><div class="mini-tl-dot">♥</div><div class="mini-tl-label">Consultation</div></div>';
    docModal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeDocModal() { docModal.style.display='none'; document.body.style.overflow=''; }

document.querySelectorAll('.doc-appt-row').forEach(function(row) {
    row.addEventListener('click', function() { openDocModal(docHistory[parseInt(this.dataset.idx,10)]); });
});
document.getElementById('closeDModal').addEventListener('click', closeDocModal);
document.getElementById('closeDModal2').addEventListener('click', closeDocModal);
docModal.addEventListener('click', function(e) { if(e.target===docModal) closeDocModal(); });
document.addEventListener('keydown', function(e) { if(e.key==='Escape') closeDocModal(); });

(function() {
    var input = document.getElementById('dApptSearch');
    var tbody = document.querySelector('table tbody');
    var countEl = document.getElementById('dApptCount');
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
