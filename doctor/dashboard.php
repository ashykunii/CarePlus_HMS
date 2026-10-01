<?php
include('../config/db.php');
if(!isset($_SESSION['doc_id'])) { header('location:login.php'); exit(); }

$dname = $_SESSION['doc_name'];
$my_appointments_result = mysqli_query($conn, "SELECT * FROM appointments WHERE doc_name='$dname' ORDER BY id DESC LIMIT 5");
$my_appointments_arr = [];
while($ma = mysqli_fetch_assoc($my_appointments_result)) { $my_appointments_arr[] = $ma; }
$total_appts = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE doc_name='$dname'"));
$total_patients = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM patients"));

include('../includes/header.php');
include('../includes/doctor_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <div>
            <h2>Doctor Command Center</h2>
            <p>Welcome back, Dr. <?php echo htmlspecialchars($_SESSION['doc_name']); ?>. Here is your patient queue summary.</p>
        </div>
        <div>
            <a href="schedule.php" class="btn btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                My Schedule Calendar
            </a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <h3>My Appointments</h3>
                <p><?php echo $total_appts; ?></p>
            </div>
            <div class="stat-icon stat-blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Patients</h3>
                <p><?php echo $total_patients; ?></p>
            </div>
            <div class="stat-icon stat-teal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
            </div>
        </div>
    </div>

    <div class="data-card" style="padding: 0; overflow: hidden; border: none; background: transparent;">
        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 16px; color: var(--text-primary);">Upcoming Appointments Queue</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Patient Profile</th>
                        <th>Specialization</th>
                        <th>Target Date</th>
                        <th>Time Window</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(count($my_appointments_arr) > 0) {
                        foreach($my_appointments_arr as $idx => $a) { 
                            $st = isset($a['status']) && $a['status'] ? $a['status'] : 'Pending';
                            $st_badge = 'badge-pending';
                            if ($st === 'Approved') $st_badge = 'badge-success';
                            if ($st === 'Cancelled') $st_badge = 'badge-danger';
                    ?>
                    <tr class="doc-dash-row" data-idx="<?php echo $idx; ?>" style="cursor:pointer;" title="Click to view details">
                        <td>
                            <div class="table-user-cell">
                                <?php echo avatar_circle($a['patient_name'], 36); ?>
                                <span class="name"><?php echo htmlspecialchars($a['patient_name']); ?></span>
                            </div>
                        </td>
                        <td><span class="badge badge-info"><?php echo htmlspecialchars($a['specialization']); ?></span></td>
                        <td><?php echo htmlspecialchars($a['app_date']); ?></td>
                        <td><?php echo date('h:i A', strtotime($a['app_time'])); ?></td>
                        <td>
                            <span class="badge <?php echo $st_badge; ?>"><?php echo htmlspecialchars($st); ?></span>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center; padding: 24px; color: var(--text-muted);'>No upcoming appointments assigned.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Doctor Appointment Detail Popup -->
<div id="docDashModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; box-shadow:0 24px 64px rgba(0,0,0,0.24); width:100%; max-width:460px; margin:20px; overflow:hidden; animation: slideUp 0.22s ease;">
        <div style="background: var(--navy-primary); padding:18px 22px; display:flex; align-items:center; justify-content:space-between;">
            <div>
                <div style="font-size:11px; font-weight:700; color:rgba(255,255,255,0.6); text-transform:uppercase; letter-spacing:0.8px; margin-bottom:3px;">Patient Appointment</div>
                <div id="ddModalId" style="font-size:19px; font-weight:800; color:#fff;">#AP-001</div>
            </div>
            <button id="closeDdModal" style="background:rgba(255,255,255,0.15); border:none; border-radius:8px; color:#fff; cursor:pointer; padding:8px; display:flex; align-items:center; justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div style="padding:18px 22px;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
                <div style="background:var(--bg-subtle); border-radius:10px; padding:10px 13px;"><div style="font-size:10px; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:3px;">Patient</div><div id="ddModalPatient" style="font-size:13px; font-weight:700; color:var(--text-primary);">—</div></div>
                <div style="background:var(--bg-subtle); border-radius:10px; padding:10px 13px;"><div style="font-size:10px; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:3px;">Specialization</div><div id="ddModalSpec" style="font-size:13px; font-weight:700; color:var(--text-primary);">—</div></div>
                <div style="background:var(--bg-subtle); border-radius:10px; padding:10px 13px;"><div style="font-size:10px; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:3px;">Date</div><div id="ddModalDate" style="font-size:13px; font-weight:700; color:var(--text-primary);">—</div></div>
                <div style="background:var(--bg-subtle); border-radius:10px; padding:10px 13px;"><div style="font-size:10px; font-weight:700; color:var(--text-muted); text-transform:uppercase; margin-bottom:3px;">Time</div><div id="ddModalTime" style="font-size:13px; font-weight:700; color:var(--text-primary);">—</div></div>
            </div>
            <div style="background:var(--bg-subtle); border-radius:10px; padding:10px 13px; display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;"><div style="font-size:10px; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Status</div><span id="ddModalStatus" class="badge badge-pending">Pending</span></div>
            <div id="ddModalMsg" style="font-size:13px; color:var(--text-secondary); line-height:1.5; padding:10px 13px; border-radius:8px; border-left:3px solid var(--primary); background:var(--bg-subtle);"></div>
        </div>
        <div style="padding:0 22px 18px; display:flex; gap:10px;">
            <button id="closeDdModal2" class="btn btn-secondary" style="flex:1; justify-content:center;">Close</button>
            <a href="appointment-history.php" class="btn" style="flex:1; justify-content:center; text-decoration:none;">View All →</a>
        </div>
    </div>
</div>

<script>
var ddData = <?php echo json_encode($my_appointments_arr); ?>;
var ddModal = document.getElementById('docDashModal');
function fmtT(t){if(!t)return '—';var p=t.split(':');var h=parseInt(p[0],10);var m=p[1]||'00';var ap=h>=12?'PM':'AM';h=h%12;h=h?h:12;return(h<10?'0'+h:h)+':'+m+' '+ap;}
function openDdModal(a){
    var st=a.status||'Pending';
    var bc=st==='Approved'?'badge-success':(st==='Cancelled'?'badge-danger':'badge-pending');
    var msgs={'Pending':'Awaiting admin confirmation. Prepare for potential consultation.','Approved':'Confirmed. Patient should arrive 15 minutes early.','Cancelled':'Appointment cancelled by administration.'};
    document.getElementById('ddModalId').textContent='#AP-'+String(a.id).padStart(3,'0');
    document.getElementById('ddModalPatient').textContent=a.patient_name||'—';
    document.getElementById('ddModalSpec').textContent=a.specialization||'—';
    document.getElementById('ddModalDate').textContent=a.app_date||'—';
    document.getElementById('ddModalTime').textContent=fmtT(a.app_time);
    var b=document.getElementById('ddModalStatus');b.textContent=st;b.className='badge '+bc;
    document.getElementById('ddModalMsg').textContent=msgs[st]||'';
    ddModal.style.display='flex';document.body.style.overflow='hidden';
}
function closeDdModal(){ddModal.style.display='none';document.body.style.overflow='';}
document.querySelectorAll('.doc-dash-row').forEach(function(row){row.addEventListener('click',function(){openDdModal(ddData[parseInt(this.dataset.idx,10)]);});});
document.getElementById('closeDdModal').addEventListener('click',closeDdModal);
document.getElementById('closeDdModal2').addEventListener('click',closeDdModal);
ddModal.addEventListener('click',function(e){if(e.target===ddModal)closeDdModal();});
document.addEventListener('keydown',function(e){if(e.key==='Escape')closeDdModal();});
</script>

<?php include('../includes/footer.php'); ?>
