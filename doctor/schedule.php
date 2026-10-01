<?php
include('../config/db.php');
if(!isset($_SESSION['doc_id'])) { header('location:login.php'); exit(); }

$doc_name = $_SESSION['doc_name'];

// Determine Selected Month & Year
$month = isset($_GET['month']) ? intval($_GET['month']) : intval(date('m'));
$year  = isset($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));

if ($month < 1) { $month = 12; $year--; }
if ($month > 12) { $month = 1; $year++; }

// Filter Params
$selected_status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

// Build Query for this Doctor
$where_clauses = ["doc_name = '$doc_name'", "YEAR(app_date) = $year", "MONTH(app_date) = $month"];
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

// Calendar Dates Calculation
$first_day_timestamp = strtotime("$year-$month-01");
$days_in_month = date('t', $first_day_timestamp);
$month_name    = date('F', $first_day_timestamp);
$start_day_of_week = date('w', $first_day_timestamp); // 0 = Sunday

// Today info
$today_date = date('Y-m-d');
$today_appts_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM appointments WHERE doc_name = '$doc_name' AND app_date = '$today_date'"));

// Selected Inspection Date
$inspect_date = isset($_GET['inspect']) ? mysqli_real_escape_string($conn, $_GET['inspect']) : ($today_date);

include('../includes/header.php');
include('../includes/doctor_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <div>
            <h2>My Shift Schedule &amp; Patient Calendar</h2>
            <p>Dr. <?php echo htmlspecialchars($doc_name); ?> · View consultation slots and manage appointment schedule.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="schedule.php" class="btn btn-secondary btn-sm">Jump to Today</a>
        </div>
    </div>

    <!-- KPI Summary Row -->
    <div class="stats-grid" style="margin-bottom: 24px;">
        <div class="stat-card">
            <div class="stat-info">
                <h3>My Appointments This Month</h3>
                <p><?php echo $total_month_appts; ?></p>
                <div class="stat-trend flat"><?php echo $month_name . ' ' . $year; ?></div>
            </div>
            <div class="stat-icon stat-blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Today's Patient Queue</h3>
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
                $filter_qs = "&status=" . urlencode($selected_status);
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
                <div style="min-width: 160px;">
                    <select name="status" onchange="this.form.submit()" style="padding: 8px 12px; font-size: 13px;">
                        <option value="">-- All Statuses --</option>
                        <option value="Approved" <?php echo ($selected_status === 'Approved') ? 'selected' : ''; ?>>Approved</option>
                        <option value="Pending" <?php echo ($selected_status === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="Cancelled" <?php echo ($selected_status === 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>

                <?php if(!empty($selected_status)) { ?>
                    <a href="schedule.php?month=<?php echo $month; ?>&year=<?php echo $year; ?>" class="btn btn-secondary btn-sm" style="padding: 6px 12px;">Reset Filter</a>
                <?php } ?>
            </div>

            <div style="font-size: 13px; font-weight: 700; color: var(--text-muted);">
                Showing <?php echo $total_month_appts; ?> booked slots for Dr. <?php echo htmlspecialchars($doc_name); ?>
            </div>
        </form>

        <!-- Calendar Grid Header -->
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
                        echo '<div class="calendar-slot-pill '.$status_class.'" title="'.htmlspecialchars($a['patient_name']).'">';
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

            // Next month padding cells
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
                My Consultations for <?php echo date('F j, Y', strtotime($inspect_date)); ?>
            </h3>
            <span class="badge badge-info"><?php echo isset($appts_by_date[$inspect_date]) ? count($appts_by_date[$inspect_date]) : 0; ?> Patient Slots</span>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 120px;">Time Window</th>
                        <th>Patient Name</th>
                        <th>Specialization</th>
                        <th>Status</th>
                        <th style="width: 140px; text-align: right;">Action</th>
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
                            <span class="badge badge-info"><?php echo htmlspecialchars($a['specialization']); ?></span>
                        </td>
                        <td>
                            <span class="badge <?php echo $st_badge; ?>"><?php echo htmlspecialchars($st); ?></span>
                        </td>
                        <td style="text-align: right;">
                            <a href="manage-patient.php?name=<?php echo urlencode($a['patient_name']); ?>" class="btn btn-sm" style="font-size: 12px; padding: 5px 12px;">
                                View Record &rarr;
                            </a>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center; padding: 28px; color: var(--text-muted);'>No patient consultations scheduled for this date.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php include('../includes/footer.php'); ?>
