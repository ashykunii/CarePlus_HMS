<?php
include('../config/db.php');
if(!isset($_SESSION['admin_id'])) { header('location:login.php'); exit(); }

// Determine Selected Month & Year
$month = isset($_GET['month']) ? intval($_GET['month']) : intval(date('m'));
$year  = isset($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));

if ($month < 1) { $month = 12; $year--; }
if ($month > 12) { $month = 1; $year++; }

// Filter Params
$selected_doc  = isset($_GET['doc']) ? mysqli_real_escape_string($conn, $_GET['doc']) : '';
$selected_spec = isset($_GET['spec']) ? mysqli_real_escape_string($conn, $_GET['spec']) : '';
$selected_status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

$msg = "";
$error = "";

// Handle Admin Reschedule / Status / Cancel Submission from Schedule
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
                $msg = "Appointment #AP-" . sprintf('%03d', $app_id) . " updated successfully!";
            } else {
                $error = "Error updating appointment. Please try again.";
            }
        }
    } else {
        $update_sql = "UPDATE appointments SET app_date = '$new_date', app_time = '$new_time', doc_name = '$new_doc', status = '$new_status' WHERE id = $app_id";
        if (mysqli_query($conn, $update_sql)) {
            $msg = "Appointment #AP-" . sprintf('%03d', $app_id) . " set to Cancelled.";
        } else {
            $error = "Error updating appointment. Please try again.";
        }
    }
}

// Build Query
$where_clauses = ["YEAR(app_date) = $year", "MONTH(app_date) = $month"];
if(!empty($selected_doc)) { $where_clauses[] = "doc_name = '$selected_doc'"; }
if(!empty($selected_spec)) { $where_clauses[] = "specialization = '$selected_spec'"; }
if(!empty($selected_status)) { $where_clauses[] = "status = '$selected_status'"; }

$where_sql = implode(' AND ', $where_clauses);
$appointments_res = mysqli_query($conn, "SELECT * FROM appointments WHERE $where_sql ORDER BY app_time ASC");

// Index appointments by date 'Y-m-d'
$appts_by_date = [];
$total_month_appts = 0;
while($row = mysqli_fetch_assoc($appointments_res)) {
    $appts_by_date[$row['app_date']][] = $row;
    $total_month_appts++;
}

// Fetch lists for filter dropdowns
$doctors_list = mysqli_query($conn, "SELECT doc_name FROM doctors ORDER BY doc_name ASC");
$spec_list    = mysqli_query($conn, "SELECT spec_name FROM specializations ORDER BY spec_name ASC");

// Calendar Dates Calculation
$first_day_timestamp = strtotime("$year-$month-01");
$days_in_month = date('t', $first_day_timestamp);
$month_name    = date('F', $first_day_timestamp);
$start_day_of_week = date('w', $first_day_timestamp); // 0 = Sunday

// Today info
$today_date = date('Y-m-d');
$today_appts_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE app_date = '$today_date'"));

// Selected Inspection Date (default to today if in current month, or 1st of month)
$inspect_date = isset($_GET['inspect']) ? mysqli_real_escape_string($conn, $_GET['inspect']) : ($today_date);

include('../includes/header.php');
include('../includes/admin_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <div>
            <h2>Hospital Appointment Schedule &amp; Calendar</h2>
            <p>Master calendar of all doctor shifts, clinical appointments, and patient consultation slots.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="schedule.php" class="btn btn-secondary btn-sm">Jump to Today</a>
        </div>
    </div>

    <?php if($msg) echo "<div class='success-msg'>$msg</div>"; ?>
    <?php if($error) echo "<div class='error-msg'>$error</div>"; ?>

    <!-- KPI Summary Row -->
    <div class="stats-grid" style="margin-bottom: 24px;">
        <div class="stat-card">
            <div class="stat-info">
                <h3>Scheduled This Month</h3>
                <p><?php echo $total_month_appts; ?></p>
                <div class="stat-trend flat"><?php echo $month_name . ' ' . $year; ?></div>
            </div>
            <div class="stat-icon stat-blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Today's Booked Slots</h3>
                <p><?php echo $today_appts_count; ?></p>
                <div class="stat-trend <?php echo ($today_appts_count > 0) ? 'up' : 'flat'; ?>"><?php echo date('M d, Y'); ?></div>
            </div>
            <div class="stat-icon stat-teal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            </div>
        </div>
    </div>

    <!-- Calendar Container -->
    <div class="calendar-card">
        <!-- Header Bar -->
        <div class="calendar-header-bar">
            <div class="calendar-month-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:24px;height:24px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                <?php echo $month_name . ' ' . $year; ?>
            </div>

            <!-- Month Navigation Controls -->
            <div class="calendar-nav-btns">
                <?php 
                $prev_m = $month - 1; $prev_y = $year; if($prev_m < 1) { $prev_m = 12; $prev_y--; }
                $next_m = $month + 1; $next_y = $year; if($next_m > 12) { $next_m = 1; $next_y++; }
                $filter_qs = "&doc=" . urlencode($selected_doc) . "&spec=" . urlencode($selected_spec) . "&status=" . urlencode($selected_status);
                ?>
                <a href="schedule.php?month=<?php echo $prev_m; ?>&year=<?php echo $prev_y . $filter_qs; ?>" class="calendar-nav-btn">
                    &larr; Prev Month
                </a>
                <a href="schedule.php?month=<?php echo date('m'); ?>&year=<?php echo date('Y') . $filter_qs; ?>" class="calendar-nav-btn">
                    Current Month
                </a>
                <a href="schedule.php?month=<?php echo $next_m; ?>&year=<?php echo $next_y . $filter_qs; ?>" class="calendar-nav-btn">
                    Next Month &rarr;
                </a>
            </div>
        </div>

        <!-- Filter Controls Bar -->
        <form action="schedule.php" method="GET" class="calendar-filter-bar">
            <input type="hidden" name="month" value="<?php echo $month; ?>">
            <input type="hidden" name="year" value="<?php echo $year; ?>">
            
            <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center; flex: 1;">
                <div style="min-width: 180px;">
                    <select name="doc" onchange="this.form.submit()" style="padding: 8px 12px; font-size: 13px;">
                        <option value="">-- Filter All Doctors --</option>
                        <?php while($d = mysqli_fetch_assoc($doctors_list)) { 
                            $sel = ($selected_doc === $d['doc_name']) ? 'selected' : '';
                            echo "<option value='".htmlspecialchars($d['doc_name'])."' $sel>".htmlspecialchars($d['doc_name'])."</option>";
                        } ?>
                    </select>
                </div>

                <div style="min-width: 180px;">
                    <select name="spec" onchange="this.form.submit()" style="padding: 8px 12px; font-size: 13px;">
                        <option value="">-- All Specializations --</option>
                        <?php while($s = mysqli_fetch_assoc($spec_list)) { 
                            $sel = ($selected_spec === $s['spec_name']) ? 'selected' : '';
                            echo "<option value='".htmlspecialchars($s['spec_name'])."' $sel>".htmlspecialchars($s['spec_name'])."</option>";
                        } ?>
                    </select>
                </div>

                <div style="min-width: 140px;">
                    <select name="status" onchange="this.form.submit()" style="padding: 8px 12px; font-size: 13px;">
                        <option value="">-- All Statuses --</option>
                        <option value="Approved" <?php echo ($selected_status === 'Approved') ? 'selected' : ''; ?>>Approved</option>
                        <option value="Pending" <?php echo ($selected_status === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="Cancelled" <?php echo ($selected_status === 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>

                <?php if(!empty($selected_doc) || !empty($selected_spec) || !empty($selected_status)) { ?>
                    <a href="schedule.php?month=<?php echo $month; ?>&year=<?php echo $year; ?>" class="btn btn-secondary btn-sm" style="padding: 6px 12px;">Reset Filters</a>
                <?php } ?>
            </div>

            <div style="font-size: 13px; font-weight: 700; color: var(--text-muted);">
                Showing <?php echo $total_month_appts; ?> appointment slots
            </div>
        </form>

        <!-- Calendar Grid Header (Days of week) -->
        <div class="calendar-grid-header">
            <div>Sun</div>
            <div>Mon</div>
            <div>Tue</div>
            <div>Wed</div>
            <div>Thu</div>
            <div>Fri</div>
            <div>Sat</div>
        </div>

        <!-- Calendar Grid Body -->
        <div class="calendar-grid-body">
            <?php
            // Previous month padding cells
            for ($i = 0; $i < $start_day_of_week; $i++) {
                echo '<div class="calendar-day-cell is-other-month"></div>';
            }

            // Days of current month
            for ($day = 1; $day <= $days_in_month; $day++) {
                $formatted_day = str_pad($day, 2, '0', STR_PAD_LEFT);
                $formatted_month = str_pad($month, 2, '0', STR_PAD_LEFT);
                $current_cell_date = "$year-$formatted_month-$formatted_day";
                
                $is_today = ($current_cell_date === $today_date);
                $is_inspected = ($current_cell_date === $inspect_date);
                
                $cell_classes = 'calendar-day-cell';
                if ($is_today) $cell_classes .= ' is-today';
                if ($is_inspected) $cell_classes .= ' is-selected';

                $day_appts = isset($appts_by_date[$current_cell_date]) ? $appts_by_date[$current_cell_date] : [];
                $day_count = count($day_appts);
            ?>
                <div class="<?php echo $cell_classes; ?>" onclick="window.location.href='schedule.php?month=<?php echo $month; ?>&year=<?php echo $year . $filter_qs; ?>&inspect=<?php echo $current_cell_date; ?>#dayDetails'">
                    <div class="calendar-day-num">
                        <span><?php echo $day; ?></span>
                        <?php if ($is_today) { echo '<span class="num-badge">Today</span>'; } ?>
                    </div>

                    <?php 
                    $max_visible = 3;
                    $shown = 0;
                    foreach ($day_appts as $a) {
                        if ($shown >= $max_visible) break;
                        $status_class = 'status-' . strtolower(isset($a['status']) ? $a['status'] : 'approved');
                        $time_str = date('h:i A', strtotime($a['app_time']));
                        echo '<div class="calendar-slot-pill '.$status_class.'" title="'.htmlspecialchars($a['patient_name'] . ' with ' . $a['doc_name']).'">';
                        echo '<span>'.$time_str.'</span> · '.htmlspecialchars($a['patient_name']);
                        echo '</div>';
                        $shown++;
                    }

                    if ($day_count > $max_visible) {
                        $remaining = $day_count - $max_visible;
                        echo '<div class="calendar-more-count">+'.$remaining.' more slot'.($remaining > 1 ? 's' : '').'</div>';
                    }
                    ?>
                </div>
            <?php
            }

            // Next month padding cells to complete final week row
            $total_cells_so_far = $start_day_of_week + $days_in_month;
            $remaining_cells = (7 - ($total_cells_so_far % 7)) % 7;
            for ($i = 0; $i < $remaining_cells; $i++) {
                echo '<div class="calendar-day-cell is-other-month"></div>';
            }
            ?>
        </div>
    </div>

    <!-- Day Slots Inspector -->
    <div class="day-inspector-card" id="dayDetails">
        <div class="day-inspector-header">
            <h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Appointment Slots for <?php echo date('F j, Y', strtotime($inspect_date)); ?>
            </h3>
            <span class="badge badge-info"><?php echo isset($appts_by_date[$inspect_date]) ? count($appts_by_date[$inspect_date]) : 0; ?> Slots Booked</span>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 110px;">Time Window</th>
                        <th>Patient Name</th>
                        <th>Attending Doctor</th>
                        <th>Specialization</th>
                        <th>Status</th>
                        <th style="text-align: right;">Admin Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (isset($appts_by_date[$inspect_date]) && count($appts_by_date[$inspect_date]) > 0) {
                        foreach ($appts_by_date[$inspect_date] as $a) {
                            $st = isset($a['status']) ? $a['status'] : 'Approved';
                            $st_badge = 'badge-success';
                            if ($st === 'Pending') $st_badge = 'badge-pending';
                            if ($st === 'Cancelled') $st_badge = 'badge-danger';
                    ?>
                    <tr>
                        <td>
                            <strong style="color: var(--navy-primary); font-size: 13px;">
                                <?php echo date('h:i A', strtotime($a['app_time'])); ?>
                            </strong>
                        </td>
                        <td>
                            <div class="table-user-cell">
                                <?php echo avatar_circle($a['patient_name'], 32); ?>
                                <span style="font-weight: 700; font-size: 14px;"><?php echo htmlspecialchars($a['patient_name']); ?></span>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; font-size: 13px; color: var(--text-primary);"><?php echo htmlspecialchars($a['doc_name']); ?></div>
                        </td>
                        <td>
                            <span class="badge badge-info"><?php echo htmlspecialchars($a['specialization']); ?></span>
                        </td>
                        <td>
                            <span class="badge <?php echo $st_badge; ?>"><?php echo htmlspecialchars($st); ?></span>
                        </td>
                        <td style="text-align: right;">
                            <button type="button" 
                                    class="btn btn-sm open-reschedule-btn"
                                    data-id="<?php echo $a['id']; ?>"
                                    data-patient="<?php echo htmlspecialchars($a['patient_name'], ENT_QUOTES); ?>"
                                    data-doctor="<?php echo htmlspecialchars($a['doc_name'], ENT_QUOTES); ?>"
                                    data-spec="<?php echo htmlspecialchars($a['specialization'], ENT_QUOTES); ?>"
                                    data-date="<?php echo $a['app_date']; ?>"
                                    data-time="<?php echo $a['app_time']; ?>"
                                    data-status="<?php echo $st; ?>"
                                    style="font-size: 12px; padding: 5px 10px;">
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
                        echo "<tr><td colspan='6' style='text-align:center; padding: 28px; color: var(--text-muted);'>No appointments scheduled for this date.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
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
        <form action="schedule.php?month=<?php echo $month; ?>&year=<?php echo $year; ?>&inspect=<?php echo $inspect_date; ?>" method="POST">
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
                            echo "<option value='".htmlspecialchars($d['doc_name'])."'>".htmlspecialchars($d['doc_name'])."</option>";
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
