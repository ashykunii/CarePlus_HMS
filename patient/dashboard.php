<?php
include('../config/db.php');
if(!isset($_SESSION['user_id'])) { header('location:login.php'); exit(); }

$pname = $_SESSION['user_name'];
$total_appts = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE patient_name='$pname'"));
$approved_appts = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE patient_name='$pname' AND (status IS NULL OR status != 'Cancelled')"));
$recent_appts = mysqli_query($conn, "SELECT * FROM appointments WHERE patient_name='$pname' ORDER BY id DESC LIMIT 5");

// Get recent appointments
$recent_appts_arr = [];
$recent_appts_result = mysqli_query($conn, "SELECT * FROM appointments WHERE patient_name='$pname' ORDER BY id DESC LIMIT 5");
while($ra = mysqli_fetch_assoc($recent_appts_result)) {
    $recent_appts_arr[] = $ra;
}

// Get latest appointment
$latest_appt = $recent_appts_arr[0] ?? null;

include('../includes/header.php');
include('../includes/patient_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <div>
            <h2>Patient Command Center</h2>
            <p>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! Track your consultations, health records, and upcoming schedule.</p>
        </div>
        <div>
            <a href="book-appointment.php" class="btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Book Appointment
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Bookings</h3>
                <p><?php echo $total_appts; ?></p>
            </div>
            <div class="stat-icon stat-blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Active Consultations</h3>
                <p><?php echo $approved_appts; ?></p>
            </div>
            <div class="stat-icon stat-teal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Health Records</h3>
                <p>Available</p>
            </div>
            <div class="stat-icon stat-purple">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
            </div>
        </div>
    </div>

    <!-- Appointment Status Timeline Card -->
    <div class="timeline-card" id="timelineCard">
        <div class="timeline-card-header">
            <span style="display:flex; align-items:center; gap:8px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px;color:var(--primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                Appointment Status Timeline Tracker
            </span>
            <span id="timelineBadgeDate" class="badge badge-info"><?php echo $latest_appt ? htmlspecialchars($latest_appt['app_date']) : 'No appointments'; ?></span>
        </div>

        <!-- Timeline appointment info bar -->
        <div id="timelineInfoBar" style="background: var(--bg-subtle); border-radius: var(--radius-sm); padding: 10px 16px; margin-bottom: 14px; display: flex; gap: 20px; flex-wrap: wrap; font-size: 13px; color: var(--text-secondary);">
            <span><strong style="color:var(--text-primary);">ID:</strong> <span id="tlApptId"><?php echo $latest_appt ? '#AP-'.sprintf('%03d', $latest_appt['id']) : '—'; ?></span></span>
            <span><strong style="color:var(--text-primary);">Doctor:</strong> <span id="tlDocName"><?php echo $latest_appt ? htmlspecialchars($latest_appt['doc_name']) : '—'; ?></span></span>
            <span><strong style="color:var(--text-primary);">Specialty:</strong> <span id="tlSpec"><?php echo $latest_appt ? htmlspecialchars($latest_appt['specialization']) : '—'; ?></span></span>
            <span><strong style="color:var(--text-primary);">Time:</strong> <span id="tlTime"><?php echo $latest_appt ? date('h:i A', strtotime($latest_appt['app_time'])) : '—'; ?></span></span>
            <span id="tlHint" style="margin-left:auto; color: var(--text-muted); font-style: italic; font-size:12px;">Click any row below to update tracker ↓</span>
        </div>

        <div class="status-timeline" id="statusTimeline">
            <!-- Steps rendered by JS initially and on click -->
        </div>
    </div>

    <div class="dashboard-two-col">
        <!-- Left: Appointments Queue Table -->
        <div class="data-card" style="margin-bottom:0;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary);">My Recent Appointments</h3>
                <a href="appointment-history.php" style="font-size:13px; font-weight:700;">View All &rarr;</a>
            </div>
            <div class="table-responsive">
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
                        if(count($recent_appts_arr) > 0) {
                            foreach($recent_appts_arr as $idx => $a) { 
                                $status = isset($a['status']) && $a['status'] ? $a['status'] : 'Pending';
                                $st_badge = ($status === 'Cancelled') ? 'badge-danger' : (($status === 'Approved') ? 'badge-success' : 'badge-pending');
                        ?>
                        <tr class="appt-row<?php echo $idx === 0 ? ' selected-appt-row' : ''; ?>" 
                            data-idx="<?php echo $idx; ?>"
                            style="cursor:pointer; <?php echo $idx === 0 ? 'background: var(--bg-subtle);' : ''; ?>"
                            title="Click to view in timeline">
                            <td><strong>#AP-<?php echo sprintf('%03d', $a['id']); ?></strong></td>
                            <td style="font-weight: 600;"><?php echo htmlspecialchars($a['doc_name']); ?></td>
                            <td><span class="badge badge-info"><?php echo htmlspecialchars($a['specialization']); ?></span></td>
                            <td>
                                <div style="font-size: 13px; font-weight: 600; color: var(--text-primary);"><?php echo htmlspecialchars($a['app_date']); ?></div>
                                <div style="font-size: 12px; color: var(--text-muted);"><?php echo date('h:i A', strtotime($a['app_time'])); ?></div>
                            </td>
                            <td><span class="badge <?php echo $st_badge; ?>"><?php echo htmlspecialchars($status); ?></span></td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center; color: var(--text-muted); padding:24px;'>No appointments booked yet. <a href='book-appointment.php'>Book your first appointment</a></td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Patient Care Support Sidebar -->
        <div style="display:flex; flex-direction:column; gap:20px;">
            <div class="data-card" style="background:var(--navy-primary); color:#ffffff; border:1px solid var(--navy-light);">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px;">
                    <div style="width:40px; height:40px; background:rgba(255,255,255,0.1); border-radius:10px; display:flex; align-items:center; justify-content:center; color:#38bdf8;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:22px;height:22px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3" /></svg>
                    </div>
                    <div>
                        <h4 style="font-size:15px; font-weight:700; color:#ffffff;">CarePlus Helpline</h4>
                        <div style="font-size:12px; color:#94a3b8;">24/7 Medical Assistance</div>
                    </div>
                </div>
                <p style="font-size:13px; color:#cbd5e1; line-height:1.5; margin-bottom:16px;">Need urgent consultation changes or medical support? Contact our clinical staff directly.</p>
                <div style="font-size:14px; font-weight:800; color:#38bdf8; display:flex; align-items:center; gap:8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.826-1.47-5.114-3.758-6.584-6.584l1.293-.97c.362-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" /></svg>
                    +1 (555) 999-0000
                </div>
            </div>

            <div class="data-card">
                <h3 style="font-size:15px; font-weight:700; margin-bottom:12px; color:var(--text-primary);">Quick Actions</h3>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <a href="book-appointment.php" class="btn" style="justify-content:flex-start;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Book New Appointment
                    </a>
                    <a href="appointment-history.php" class="btn btn-secondary" style="justify-content:flex-start;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                        View Appointment Records
                    </a>
                    <a href="medical-record.php" class="btn btn-secondary" style="justify-content:flex-start;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                        Personal Medical History
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Appointment Detail Popup Modal -->
<div id="apptDetailModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; box-shadow:0 24px 64px rgba(0,0,0,0.24); width:100%; max-width:480px; margin:20px; overflow:hidden; animation: slideUp 0.22s ease;">
        <div style="background: var(--navy-primary); padding:20px 24px; display:flex; align-items:center; justify-content:space-between;">
            <div>
                <div style="font-size:11px; font-weight:700; color:rgba(255,255,255,0.6); text-transform:uppercase; letter-spacing:0.8px; margin-bottom:4px;">Appointment Details</div>
                <div id="modalApptIdBadge" style="font-size:20px; font-weight:800; color:#fff;">#AP-001</div>
            </div>
            <button id="closeApptModal" style="background:rgba(255,255,255,0.15); border:none; border-radius:8px; color:#fff; cursor:pointer; padding:8px; display:flex; align-items:center; justify-content:center;" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div style="padding:20px 24px; display:flex; flex-direction:column; gap:12px;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div style="background:var(--bg-subtle); border-radius:10px; padding:12px 14px;">
                    <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Doctor</div>
                    <div id="modalDocNameVal" style="font-size:14px; font-weight:700; color:var(--text-primary);">—</div>
                </div>
                <div style="background:var(--bg-subtle); border-radius:10px; padding:12px 14px;">
                    <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Specialization</div>
                    <div id="modalSpecVal" style="font-size:14px; font-weight:700; color:var(--text-primary);">—</div>
                </div>
                <div style="background:var(--bg-subtle); border-radius:10px; padding:12px 14px;">
                    <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Date</div>
                    <div id="modalDateVal" style="font-size:14px; font-weight:700; color:var(--text-primary);">—</div>
                </div>
                <div style="background:var(--bg-subtle); border-radius:10px; padding:12px 14px;">
                    <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:4px;">Time</div>
                    <div id="modalTimeVal" style="font-size:14px; font-weight:700; color:var(--text-primary);">—</div>
                </div>
            </div>
            <div style="background:var(--bg-subtle); border-radius:10px; padding:12px 14px; display:flex; align-items:center; justify-content:space-between;">
                <div style="font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Status</div>
                <span id="modalStatusBadge" class="badge badge-pending">Pending</span>
            </div>
            <div id="modalStatusMsg" style="font-size:13px; color:var(--text-secondary); line-height:1.5; padding:10px 14px; border-radius:8px; border-left:3px solid var(--primary); background: var(--bg-subtle);">
                Your appointment is pending administrator approval.
            </div>
        </div>
        <div style="padding:0 24px 20px; display:flex; gap:10px;">
            <button id="closeApptModal2" class="btn btn-secondary" style="flex:1; justify-content:center;">Close</button>
            <a href="appointment-history.php" class="btn" style="flex:1; justify-content:center; text-decoration:none;">View All &rarr;</a>
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    var appointments = <?php echo json_encode($recent_appts_arr); ?>;
    var timeline = document.getElementById('statusTimeline');
    var modal = document.getElementById('apptDetailModal');

    function iconCheck() {
        return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>';
    }
    function iconX() {
        return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>';
    }
    function iconClock() {
        return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>';
    }
    function iconConsult() {
        return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>';
    }

    function renderTimeline(appt) {
        if (!appt) {
            timeline.innerHTML = '<div style="color:var(--text-muted); font-size:13px; padding:12px;">No appointments yet.</div>';
            return;
        }
        var st = appt.status || 'Pending';
        var isPending   = (st === 'Pending');
        var isApproved  = (st === 'Approved');
        var isCancelled = (st === 'Cancelled');

        // Info bar
        document.getElementById('timelineBadgeDate').textContent = appt.app_date || '—';
        document.getElementById('tlApptId').textContent = '#AP-' + String(appt.id).padStart(3,'0');
        document.getElementById('tlDocName').textContent = appt.doc_name || '—';
        document.getElementById('tlSpec').textContent = appt.specialization || '—';

        // Time format
        var tParts = (appt.app_time || '').split(':');
        var tH = parseInt(tParts[0] || 0, 10);
        var tM = tParts[1] || '00';
        var tAmpm = tH >= 12 ? 'PM' : 'AM';
        tH = tH % 12; tH = tH ? tH : 12;
        document.getElementById('tlTime').textContent = (tH < 10 ? '0'+tH : tH) + ':' + tM + ' ' + tAmpm;

        // Step 1
        var step1Class = 'is-done';
        var step1Icon  = iconCheck();
        var step1Label = '1. Booking Submitted';
        var step1Sub   = 'FCFS Queue Locked';

        // Step 2
        var step2Class, step2Icon, step2Label, step2Sub;
        if (isCancelled) {
            step2Class = 'is-cancelled'; step2Icon = iconX();
            step2Label = '2. Cancelled'; step2Sub = 'Appointment was cancelled';
        } else if (isPending) {
            step2Class = 'is-active'; step2Icon = iconClock();
            step2Label = '2. Awaiting Approval'; step2Sub = 'Pending admin review';
        } else {
            step2Class = 'is-done'; step2Icon = iconCheck();
            step2Label = '2. Doctor Confirmed'; step2Sub = 'Dr. ' + appt.doc_name;
        }

        // Step 3
        var step3Class, step3Icon, step3Label, step3Sub;
        if (isCancelled) {
            step3Class = ''; step3Icon = iconConsult();
            step3Label = '3. Care Consultation'; step3Sub = 'N/A — Appointment Cancelled';
        } else if (isPending) {
            step3Class = ''; step3Icon = iconConsult();
            step3Label = '3. Care Consultation'; step3Sub = 'Pending approval first';
        } else {
            step3Class = 'is-active'; step3Icon = iconConsult();
            step3Label = '3. Care Consultation'; step3Sub = 'Notes & Records Updated';
        }

        timeline.innerHTML =
            '<div class="timeline-step ' + step1Class + '">' +
                '<div class="timeline-icon">' + step1Icon + '</div>' +
                '<div class="timeline-label">' + step1Label + '</div>' +
                '<div class="timeline-sub">' + step1Sub + '</div>' +
            '</div>' +
            '<div class="timeline-step ' + step2Class + '">' +
                '<div class="timeline-icon">' + step2Icon + '</div>' +
                '<div class="timeline-label">' + step2Label + '</div>' +
                '<div class="timeline-sub">' + step2Sub + '</div>' +
            '</div>' +
            '<div class="timeline-step ' + step3Class + '">' +
                '<div class="timeline-icon">' + step3Icon + '</div>' +
                '<div class="timeline-label">' + step3Label + '</div>' +
                '<div class="timeline-sub">' + step3Sub + '</div>' +
            '</div>';
    }

    // Initial load
    if (appointments.length > 0) renderTimeline(appointments[0]);
    else renderTimeline(null);

    // Row click
    document.querySelectorAll('.appt-row').forEach(function(row) {
        row.addEventListener('click', function(e) {
            var idx = parseInt(this.getAttribute('data-idx'), 10);
            var appt = appointments[idx];
            document.querySelectorAll('.appt-row').forEach(function(r) {
                r.classList.remove('selected-appt-row');
                r.style.borderLeft = '';
            });
            this.classList.add('selected-appt-row');
            renderTimeline(appt);
            document.getElementById('timelineCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    // Modal view
    function openModal(appt) {
        var st = appt.status || 'Pending';
        var badgeClass = st === 'Approved' ? 'badge-success' : (st === 'Cancelled' ? 'badge-danger' : 'badge-pending');
        var msgs = {
            'Pending':   'Your appointment is pending administrator review. You will be notified once confirmed.',
            'Approved':  'Your appointment has been confirmed! Please arrive 15 minutes early with your ID.',
            'Cancelled': 'This appointment has been cancelled. Please contact the admin desk to rebook.'
        };
        var tParts = (appt.app_time || '').split(':');
        var tH = parseInt(tParts[0] || 0, 10);
        var tM = tParts[1] || '00';
        var tAmpm = tH >= 12 ? 'PM' : 'AM';
        tH = tH % 12; tH = tH ? tH : 12;
        var fmtTime = (tH < 10 ? '0'+tH : tH) + ':' + tM + ' ' + tAmpm;

        document.getElementById('modalApptIdBadge').textContent = '#AP-' + String(appt.id).padStart(3,'0');
        document.getElementById('modalDocNameVal').textContent = appt.doc_name || '—';
        document.getElementById('modalSpecVal').textContent = appt.specialization || '—';
        document.getElementById('modalDateVal').textContent = appt.app_date || '—';
        document.getElementById('modalTimeVal').textContent = fmtTime;
        var badge = document.getElementById('modalStatusBadge');
        badge.textContent = st;
        badge.className = 'badge ' + badgeClass;
        document.getElementById('modalStatusMsg').textContent = msgs[st] || '';
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    // Double click row
    document.querySelectorAll('.appt-row').forEach(function(row) {
        row.addEventListener('dblclick', function() {
            var idx = parseInt(this.getAttribute('data-idx'), 10);
            openModal(appointments[idx]);
        });
        row.setAttribute('title', 'Click to view timeline | Double click for details');
    });
    document.getElementById('closeApptModal').addEventListener('click', closeModal);
    document.getElementById('closeApptModal2').addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeModal(); });
});
</script>
<?php include('../includes/footer.php'); ?>
