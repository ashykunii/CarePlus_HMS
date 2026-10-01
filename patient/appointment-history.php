<?php
include('../config/db.php');
if(!isset($_SESSION['user_id'])) { header('location:login.php'); exit(); }

$pname = $_SESSION['user_name'];
$history_result = mysqli_query($conn, "SELECT * FROM appointments WHERE patient_name='$pname' ORDER BY id DESC");
$history_arr = [];
while($h = mysqli_fetch_assoc($history_result)) { $history_arr[] = $h; }

include('../includes/header.php');
include('../includes/patient_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <div>
            <h2>My Scheduled Appointments</h2>
            <p>Complete history of your appointments and consultation schedules.</p>
        </div>
        <div>
            <a href="book-appointment.php" class="btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Book New Appointment
            </a>
        </div>
    </div>

    <!-- Admin Reschedule/Cancellation Policy Callout -->
    <div style="background: #f8fafc; border: 1px solid var(--border-color); border-left: 4px solid var(--navy-primary); border-radius: var(--radius-sm); padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: flex-start; gap: 14px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:22px;height:22px;color:var(--navy-primary);flex-shrink:0;margin-top:2px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
        </svg>
        <div>
            <div style="font-size: 14px; font-weight: 800; color: var(--navy-primary); margin-bottom: 2px;">Reschedule &amp; Cancellation Policy Notice</div>
            <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.5; margin: 0;">
                All appointment reschedules and cancellations are processed exclusively by <strong>Hospital Administration</strong>. If you need to change your consultation date or cancel a booking, please contact our administrative desk at <strong>support@careplus.com</strong> or call <strong>+1 (800) 555-CARE</strong>.
            </p>
        </div>
    </div>

    <div class="table-responsive">
        <div class="search-bar-wrapper">
            <div class="search-input-wrap">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <input type="text" id="myApptSearch" class="table-search-input" placeholder="Search by doctor, specialization, date or status...">
            </div>
            <span class="search-result-count" id="myApptCount"></span>
        </div>
        <p style="font-size:12px; color:var(--text-muted); margin: -8px 0 12px; padding: 0 4px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:13px;height:13px;vertical-align:-2px;margin-right:3px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672 13.684 16.6m0 0-2.51 2.225.569-9.47 5.227 7.917-3.286-.672Zm-7.518-.267A8.25 8.25 0 1 1 20.25 10.5M8.288 14.212A5.25 5.25 0 1 1 17.25 10.5" /></svg>
            Click any row to view appointment details
        </p>
        <table>
            <thead>
                <tr>
                    <th>Appt ID</th>
                    <th>Doctor Name</th>
                    <th>Specialization</th>
                    <th>Date &amp; Time</th>
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
                <tr class="history-row" data-idx="<?php echo $idx; ?>" style="cursor:pointer;" title="Click to view details">
                    <td><strong>#AP-<?php echo sprintf('%03d', $h['id']); ?></strong></td>
                    <td style="font-weight: 600;"><?php echo htmlspecialchars($h['doc_name']); ?></td>
                    <td><span class="badge badge-info"><?php echo htmlspecialchars($h['specialization']); ?></span></td>
                    <td>
                        <div style="font-size: 13px; font-weight: 600; color: var(--text-primary);"><?php echo htmlspecialchars($h['app_date']); ?></div>
                        <div style="font-size: 12px; color: var(--text-muted);"><?php echo date('h:i A', strtotime($h['app_time'])); ?></div>
                    </td>
                    <td><span class="badge <?php echo $st_badge; ?>"><?php echo htmlspecialchars($st); ?></span></td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center; color: var(--text-muted); padding: 28px;'>No appointment history found. <a href='book-appointment.php'>Book your first appointment</a></td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Appointment Detail Popup Modal -->
<div id="historyDetailModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; box-shadow:0 24px 64px rgba(0,0,0,0.24); width:100%; max-width:500px; margin:20px; overflow:hidden; animation: slideUp 0.22s ease;">
        <!-- Header -->
        <div style="background: var(--navy-primary); padding:20px 24px; display:flex; align-items:center; justify-content:space-between;">
            <div>
                <div style="font-size:11px; font-weight:700; color:rgba(255,255,255,0.6); text-transform:uppercase; letter-spacing:0.8px; margin-bottom:4px;">Appointment Record</div>
                <div id="hModalApptId" style="font-size:20px; font-weight:800; color:#fff;">#AP-001</div>
            </div>
            <button id="closeHistoryModal" style="background:rgba(255,255,255,0.15); border:none; border-radius:8px; color:#fff; cursor:pointer; padding:8px; display:flex; align-items:center; justify-content:center;" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <!-- Info Grid -->
        <div style="padding:20px 24px;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
                <div style="background:var(--bg-subtle); border-radius:10px; padding:12px 14px;">
                    <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Doctor</div>
                    <div id="hModalDoc" style="font-size:14px; font-weight:700; color:var(--text-primary);">—</div>
                </div>
                <div style="background:var(--bg-subtle); border-radius:10px; padding:12px 14px;">
                    <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Specialization</div>
                    <div id="hModalSpec" style="font-size:14px; font-weight:700; color:var(--text-primary);">—</div>
                </div>
                <div style="background:var(--bg-subtle); border-radius:10px; padding:12px 14px;">
                    <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Date</div>
                    <div id="hModalDate" style="font-size:14px; font-weight:700; color:var(--text-primary);">—</div>
                </div>
                <div style="background:var(--bg-subtle); border-radius:10px; padding:12px 14px;">
                    <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Time</div>
                    <div id="hModalTime" style="font-size:14px; font-weight:700; color:var(--text-primary);">—</div>
                </div>
            </div>
            <!-- Status -->
            <div style="background:var(--bg-subtle); border-radius:10px; padding:12px 14px; display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
                <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Status</div>
                <span id="hModalStatusBadge" class="badge badge-pending">Pending</span>
            </div>
            <!-- Timeline mini -->
            <div id="hModalTimeline" style="display:flex; gap:0; margin-bottom:14px;">
                <!-- rendered by JS -->
            </div>
            <div id="hModalMsg" style="font-size:13px; color:var(--text-secondary); line-height:1.5; padding:10px 14px; border-radius:8px; border-left:3px solid var(--primary); background: var(--bg-subtle);"></div>
        </div>
        <div style="padding:0 24px 20px; display:flex; gap:10px;">
            <button id="closeHistoryModal2" class="btn btn-secondary" style="flex:1; justify-content:center;">Close</button>
            <a href="book-appointment.php" class="btn" style="flex:1; justify-content:center; text-decoration:none;">Book New +</a>
        </div>
    </div>
</div>



<script>
var historyData = <?php echo json_encode($history_arr); ?>;
var histModal = document.getElementById('historyDetailModal');

function fmtTime(t) {
    if (!t) return '—';
    var p = t.split(':'); var h = parseInt(p[0],10); var m = p[1]||'00';
    var ap = h>=12?'PM':'AM'; h=h%12; h=h?h:12;
    return (h<10?'0'+h:h)+':'+m+' '+ap;
}

function openHistModal(appt) {
    var st = appt.status || 'Pending';
    var badgeClass = st==='Approved'?'badge-success':(st==='Cancelled'?'badge-danger':'badge-pending');
    var msgs = {
        'Pending':  'This appointment is awaiting administrator review and approval.',
        'Approved': 'Appointment confirmed! Please arrive 15 minutes early with valid ID.',
        'Cancelled':'This appointment has been cancelled. Contact admin to rebook.'
    };

    document.getElementById('hModalApptId').textContent = '#AP-' + String(appt.id).padStart(3,'0');
    document.getElementById('hModalDoc').textContent = appt.doc_name || '—';
    document.getElementById('hModalSpec').textContent = appt.specialization || '—';
    document.getElementById('hModalDate').textContent = appt.app_date || '—';
    document.getElementById('hModalTime').textContent = fmtTime(appt.app_time);
    var b = document.getElementById('hModalStatusBadge');
    b.textContent = st; b.className = 'badge ' + badgeClass;
    document.getElementById('hModalMsg').textContent = msgs[st] || '';

    // Mini timeline
    var tl = document.getElementById('hModalTimeline');
    var isCan = st==='Cancelled', isPen = st==='Pending', isApp = st==='Approved';
    tl.innerHTML =
        '<div class="mini-tl-step done">' +
            '<div class="mini-tl-dot">✓</div><div class="mini-tl-label">Submitted</div>' +
        '</div>' +
        '<div class="mini-tl-step ' + (isCan?'cancelled':(isPen?'active':'done')) + '">' +
            '<div class="mini-tl-dot">' + (isCan?'✕':(isPen?'…':'✓')) + '</div>' +
            '<div class="mini-tl-label">' + (isCan?'Cancelled':(isPen?'Pending':'Approved')) + '</div>' +
        '</div>' +
        '<div class="mini-tl-step ' + (isApp?'active':'') + '">' +
            '<div class="mini-tl-dot">♥</div><div class="mini-tl-label">Consultation</div>' +
        '</div>';

    histModal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeHistModal() {
    histModal.style.display = 'none';
    document.body.style.overflow = '';
}

document.querySelectorAll('.history-row').forEach(function(row) {
    row.addEventListener('click', function() {
        openHistModal(historyData[parseInt(this.dataset.idx,10)]);
    });
});
document.getElementById('closeHistoryModal').addEventListener('click', closeHistModal);
document.getElementById('closeHistoryModal2').addEventListener('click', closeHistModal);
histModal.addEventListener('click', function(e) { if(e.target===histModal) closeHistModal(); });
document.addEventListener('keydown', function(e) { if(e.key==='Escape') closeHistModal(); });

// Table search
(function() {
    var input = document.getElementById('myApptSearch');
    var tbody = document.querySelector('table tbody');
    var countEl = document.getElementById('myApptCount');
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
