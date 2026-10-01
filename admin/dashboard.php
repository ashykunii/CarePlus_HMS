<?php
include('../config/db.php');
if(!isset($_SESSION['admin_id'])) { header('location:login.php'); exit(); }

// Get totals
$patients_count     = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users"));
$doctors_count      = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM doctors"));
$appointments_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments"));
$queries_count      = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM queries"));

// Get recent appointments
$recent_appts_result = mysqli_query($conn, "SELECT * FROM appointments ORDER BY id DESC LIMIT 6");
$recent_appts_arr = [];
while($ra = mysqli_fetch_assoc($recent_appts_result)) { $recent_appts_arr[] = $ra; }

// Get recent doctors
$recent_docs = mysqli_query($conn, "SELECT * FROM doctors ORDER BY id DESC LIMIT 3");

// Today's appointments
$today = date('Y-m-d');
$today_appts = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE app_date='$today'"));

include('../includes/header.php');
include('../includes/admin_sidebar.php');
?>
<main class="content-area">

    <!-- Welcome Banner -->
    <div class="db-welcome-banner">
        <div class="db-welcome-text">
            <h2>Welcome back, <?php echo htmlspecialchars(isset($_SESSION['admin_user']) ? $_SESSION['admin_user'] : 'Administrator'); ?>.</h2>
            <p>Here is your system overview for today. All services are operational.</p>
        </div>
        <div class="db-welcome-meta">
            <div class="date-pill">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                <?php echo date('l, F j, Y'); ?>
            </div>
            <div class="status-pill">All Systems Operational</div>
        </div>
    </div>

    <!-- KPI Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <h3>Registered Users</h3>
                <p><?php echo $patients_count; ?></p>
                <div class="stat-trend up">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.281-2.28 5.941" /></svg>
                    Active Patient Accounts
                </div>
            </div>
            <div class="stat-icon stat-blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <h3>Medical Doctors</h3>
                <p><?php echo $doctors_count; ?></p>
                <div class="stat-trend up">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.281-2.28 5.941" /></svg>
                    Registered Practitioners
                </div>
            </div>
            <div class="stat-icon stat-teal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" /></svg>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Appointments</h3>
                <p><?php echo $appointments_count; ?></p>
                <div class="stat-trend flat">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                    <?php echo $today_appts; ?> scheduled today
                </div>
            </div>
            <div class="stat-icon stat-purple">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <h3>Contact Queries</h3>
                <p><?php echo $queries_count; ?></p>
                <div class="stat-trend flat">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                    Pending review
                </div>
            </div>
            <div class="stat-icon stat-amber">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
            </div>
        </div>
    </div>

    <!-- Two-column layout -->
    <div class="dashboard-two-col">

        <!-- Left: Recent Appointments Table -->
        <div class="panel-card">
            <div class="panel-card-header">
                <h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                    Recent Appointments
                    <?php if($today_appts > 0): ?>
                    <span class="today-badge"><?php echo $today_appts; ?> today</span>
                    <?php endif; ?>
                </h3>
                <a href="appointment-history.php">View All &rarr;</a>
            </div>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Specialization</th>
                            <th>Date &amp; Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(count($recent_appts_arr) > 0):
                        foreach($recent_appts_arr as $idx => $a): 
                            $ast = isset($a['status']) && $a['status'] ? $a['status'] : 'Pending';
                            $abadge = $ast==='Approved'?'badge-success':($ast==='Cancelled'?'badge-danger':'badge-pending');
                    ?>
                        <tr class="admin-dash-row" data-idx="<?php echo $idx; ?>" style="cursor:pointer;" title="Click to view details">
                            <td>
                                <div class="table-user-cell">
                                    <?php echo avatar_circle($a['patient_name'], 32); ?>
                                    <span style="font-weight:600;font-size:13px;"><?php echo htmlspecialchars($a['patient_name']); ?></span>
                                </div>
                            </td>
                            <td style="font-size:13px;"><?php echo htmlspecialchars($a['doc_name']); ?></td>
                            <td><span class="badge badge-info"><?php echo htmlspecialchars($a['specialization']); ?></span></td>
                            <td style="font-size:13px;white-space:nowrap;"><?php echo htmlspecialchars($a['app_date']); ?> <span style="color:var(--text-muted);"><?php echo date('h:i A', strtotime($a['app_time'])); ?></span></td>
                            <td><span class="badge <?php echo $abadge; ?>"><?php echo htmlspecialchars($ast); ?></span></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5" style="text-align:center;padding:28px;color:var(--text-muted);">No appointment records yet.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right column -->
        <div style="display:flex;flex-direction:column;gap:20px;">

            <!-- Quick Actions -->
            <div class="panel-card">
                <div class="panel-card-header">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                        Quick Actions
                    </h3>
                </div>
                <div class="panel-card-body" style="padding:14px 16px;">
                    <a href="manage-doctor.php" class="quick-action-tile">
                        <div class="qa-icon" style="background:#e0f2fe;color:#0284c7;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.765Z" /></svg>
                        </div>
                        <div class="qa-text"><div class="qa-title">Manage Doctors</div><div class="qa-sub">Add or edit practitioners</div></div>
                        <div class="qa-arrow"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg></div>
                    </a>
                    <a href="manage-user.php" class="quick-action-tile">
                        <div class="qa-icon" style="background:#ccfbf1;color:#0d9488;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a6.002 6.002 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg>
                        </div>
                        <div class="qa-text"><div class="qa-title">Manage Users</div><div class="qa-sub">View patient accounts</div></div>
                        <div class="qa-arrow"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg></div>
                    </a>
                    <a href="doctor-spec.php" class="quick-action-tile">
                        <div class="qa-icon" style="background:#f3e8ff;color:#9333ea;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h3.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.796 3.111a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.798 3.111a1.125 1.125 0 0 1-1.37.49l-1.216-.456c-.356-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-3.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.797-3.111a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.797-3.111a1.125 1.125 0 0 1 1.37-.49l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" /></svg>
                        </div>
                        <div class="qa-text"><div class="qa-title">Specializations</div><div class="qa-sub">Manage medical departments</div></div>
                        <div class="qa-arrow"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg></div>
                    </a>
                    <a href="query.php" class="quick-action-tile">
                        <div class="qa-icon" style="background:#fee2e2;color:#dc2626;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                        </div>
                        <div class="qa-text"><div class="qa-title">Reply Contact Queries</div><div class="qa-sub">Respond to patient inquiries</div></div>
                        <div class="qa-arrow"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg></div>
                    </a>
                </div>
            </div>

            <!-- System Health -->
            <div class="panel-card">
                <div class="panel-card-header">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 12.728M5.636 5.636a9 9 0 1 1 12.728 12.728M5.636 5.636 12 12m6.364-6.364L12 12" /></svg>
                        System Health
                    </h3>
                </div>
                <div class="panel-card-body">
                    <div class="sys-health-row">
                        <div class="sys-health-item"><span class="label">Database Connection</span><span class="sys-health-pill ok">● Online</span></div>
                        <div class="sys-health-item"><span class="label">Application Server</span><span class="sys-health-pill ok">● Running</span></div>
                        <div class="sys-health-item"><span class="label">Session Manager</span><span class="sys-health-pill ok">● Active</span></div>
                        <div class="sys-health-item"><span class="label">Patient Portal</span><span class="sys-health-pill ok">● Accessible</span></div>
                        <div class="sys-health-item"><span class="label">Doctor Portal</span><span class="sys-health-pill ok">● Accessible</span></div>
                    </div>
                </div>
            </div>

            <!-- Recently Added Doctors -->
            <div class="panel-card">
                <div class="panel-card-header">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                        Recent Doctors
                    </h3>
                    <a href="manage-doctor.php">Manage &rarr;</a>
                </div>
                <div class="panel-card-body">
                    <?php if(mysqli_num_rows($recent_docs) > 0):
                        while($d = mysqli_fetch_assoc($recent_docs)): ?>
                    <div class="mini-record-card">
                        <?php echo avatar_circle($d['doc_name'], 38); ?>
                        <div class="mini-record-info">
                            <div class="name"><?php echo htmlspecialchars($d['doc_name']); ?></div>
                            <div class="sub"><span class="badge badge-info" style="font-size:11px;padding:2px 8px;"><?php echo htmlspecialchars($d['specialization']); ?></span></div>
                        </div>
                    </div>
                    <?php endwhile; else: ?>
                        <p style="color:var(--text-muted);font-size:13px;text-align:center;">No doctors registered yet.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div><!-- /right col -->
    </div><!-- /two-col -->

</main>

<!-- Admin Appointment Detail Popup -->
<div id="adminApptModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; box-shadow:0 24px 64px rgba(0,0,0,0.24); width:100%; max-width:480px; margin:20px; overflow:hidden; animation:slideUp 0.22s ease;">
        <div style="background: var(--navy-primary);padding:18px 22px;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <div style="font-size:11px;font-weight:700;color:rgba(255,255,255,0.6);text-transform:uppercase;letter-spacing:0.8px;margin-bottom:3px;">Appointment Record</div>
                <div id="admModalId" style="font-size:19px;font-weight:800;color:#fff;">#AP-001</div>
            </div>
            <button id="closeAdmModal" style="background:rgba(255,255,255,0.15);border:none;border-radius:8px;color:#fff;cursor:pointer;padding:8px;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div style="padding:18px 22px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
                <div style="background:var(--bg-subtle);border-radius:10px;padding:10px 13px;"><div style="font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;margin-bottom:3px;">Patient</div><div id="admModalPatient" style="font-size:13px;font-weight:700;color:var(--text-primary);">—</div></div>
                <div style="background:var(--bg-subtle);border-radius:10px;padding:10px 13px;"><div style="font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;margin-bottom:3px;">Doctor</div><div id="admModalDoc" style="font-size:13px;font-weight:700;color:var(--text-primary);">—</div></div>
                <div style="background:var(--bg-subtle);border-radius:10px;padding:10px 13px;"><div style="font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;margin-bottom:3px;">Date</div><div id="admModalDate" style="font-size:13px;font-weight:700;color:var(--text-primary);">—</div></div>
                <div style="background:var(--bg-subtle);border-radius:10px;padding:10px 13px;"><div style="font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;margin-bottom:3px;">Time</div><div id="admModalTime" style="font-size:13px;font-weight:700;color:var(--text-primary);">—</div></div>
            </div>
            <div style="background:var(--bg-subtle);border-radius:10px;padding:10px 13px;display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;"><div style="font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Status</div><span id="admModalStatus" class="badge badge-pending">Pending</span></div>
            <div id="admModalMsg" style="font-size:13px;color:var(--text-secondary);line-height:1.5;padding:10px 13px;border-radius:8px;border-left:3px solid var(--primary);background:var(--bg-subtle);"></div>
        </div>
        <div style="padding:0 22px 18px;display:flex;gap:10px;">
            <button id="closeAdmModal2" class="btn btn-secondary" style="flex:1;justify-content:center;">Close</button>
            <a href="appointment-history.php" class="btn" style="flex:1;justify-content:center;text-decoration:none;">Manage Appointments →</a>
        </div>
    </div>
</div>


<script>
var admData = <?php echo json_encode($recent_appts_arr); ?>;
var admModal = document.getElementById('adminApptModal');
function fmtTA(t){if(!t)return '—';var p=t.split(':');var h=parseInt(p[0],10);var m=p[1]||'00';var ap=h>=12?'PM':'AM';h=h%12;h=h?h:12;return(h<10?'0'+h:h)+':'+m+' '+ap;}
function openAdmModal(a){
    var st=a.status||'Pending';
    var bc=st==='Approved'?'badge-success':(st==='Cancelled'?'badge-danger':'badge-pending');
    var msgs={'Pending':'Awaiting approval. Use appointment history to approve or manage.','Approved':'Appointment confirmed. Patient and doctor notified.','Cancelled':'Appointment cancelled.'};
    document.getElementById('admModalId').textContent='#AP-'+String(a.id).padStart(3,'0');
    document.getElementById('admModalPatient').textContent=a.patient_name||'—';
    document.getElementById('admModalDoc').textContent=a.doc_name||'—';
    document.getElementById('admModalDate').textContent=a.app_date||'—';
    document.getElementById('admModalTime').textContent=fmtTA(a.app_time);
    var b=document.getElementById('admModalStatus');b.textContent=st;b.className='badge '+bc;
    document.getElementById('admModalMsg').textContent=msgs[st]||'';
    admModal.style.display='flex';document.body.style.overflow='hidden';
}
function closeAdmModal(){admModal.style.display='none';document.body.style.overflow='';}
document.querySelectorAll('.admin-dash-row').forEach(function(row){row.addEventListener('click',function(){openAdmModal(admData[parseInt(this.dataset.idx,10)]);});});
document.getElementById('closeAdmModal').addEventListener('click',closeAdmModal);
document.getElementById('closeAdmModal2').addEventListener('click',closeAdmModal);
admModal.addEventListener('click',function(e){if(e.target===admModal)closeAdmModal();});
document.addEventListener('keydown',function(e){if(e.key==='Escape')closeAdmModal();});
</script>
<?php include('../includes/footer.php'); ?>
