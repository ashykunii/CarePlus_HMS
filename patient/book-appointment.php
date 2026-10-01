<?php
include('../config/db.php');
if(!isset($_SESSION['user_id'])) { header('location:login.php'); exit(); }

$msg = "";
$error = "";

if(isset($_POST['book'])) { 
    $pname = $_SESSION['user_name'];
    $spec = mysqli_real_escape_string($conn, $_POST['specialization']);
    $doc = mysqli_real_escape_string($conn, $_POST['doctor']);
    $date = mysqli_real_escape_string($conn, $_POST['app_date']);
    $time = mysqli_real_escape_string($conn, $_POST['app_time']);
    
    // Check if doctor is already booked
    $check_doc = mysqli_query($conn, "SELECT id, patient_name FROM appointments WHERE doc_name = '$doc' AND app_date = '$date' AND app_time = '$time' AND (status IS NULL OR status != 'Cancelled')");

    if (mysqli_num_rows($check_doc) > 0) {
        $error = "<strong>Slot Unavailable:</strong> Dr. " . htmlspecialchars($doc) . " is already booked at " . date('h:i A', strtotime($time)) . " on " . htmlspecialchars($date) . ". Please select a different time slot or doctor.";
    } else {
        // Check if patient has schedule conflict
        $check_patient = mysqli_query($conn, "SELECT id FROM appointments WHERE patient_name = '$pname' AND app_date = '$date' AND app_time = '$time' AND (status IS NULL OR status != 'Cancelled')");
        if (mysqli_num_rows($check_patient) > 0) {
            $error = "<strong>Schedule Conflict:</strong> You already have another appointment scheduled at " . date('h:i A', strtotime($time)) . " on " . htmlspecialchars($date) . ".";
        } else {
            $q = "INSERT INTO appointments (patient_name, doc_name, specialization, app_date, app_time, status) VALUES ('$pname', '$doc', '$spec', '$date', '$time', 'Pending')";
            if(mysqli_query($conn, $q)) { 
                $msg = "Appointment request submitted successfully for Dr. " . htmlspecialchars($doc) . " on " . htmlspecialchars($date) . " at " . date('h:i A', strtotime($time)) . ". Your status is <strong>PENDING administrator approval</strong>."; 
            } else {
                $error = "Failed to book appointment. Please try again.";
            }
        }
    }
}

$specs = mysqli_query($conn, "SELECT * FROM specializations");
$docs = mysqli_query($conn, "SELECT * FROM doctors");

// Get booked slots for JS validation
$booked_query = mysqli_query($conn, "SELECT doc_name, app_date, app_time FROM appointments WHERE status IS NULL OR status != 'Cancelled'");
$booked_slots = [];
while($b = mysqli_fetch_assoc($booked_query)) {
    $booked_slots[] = [
        'doc' => $b['doc_name'],
        'date' => $b['app_date'],
        'time' => date('H:i', strtotime($b['app_time']))
    ];
}

include('../includes/header.php');
include('../includes/patient_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <div>
            <h2>Book Healthcare Appointment</h2>
            <p style="color: var(--text-secondary); margin-top: 4px;">Schedule a consultation with our medical specialists.</p>
        </div>
    </div>

    <?php if($msg) echo "<div class='success-msg'>$msg</div>"; ?>
    <?php if($error) echo "<div class='error-msg'>$error</div>"; ?>

    <div class="dashboard-two-col" style="align-items: flex-start;">
        <!-- Left Column: Form & FCFS Policy -->
        <div>
            <!-- First Come First Serve Notice -->
            <div style="background: #f8fafc; border: 1px solid var(--border-color); border-left: 4px solid var(--navy-primary); border-radius: var(--radius-sm); padding: 14px 18px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px;color:var(--navy-primary);flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <div style="font-size: 13px; color: var(--text-secondary); line-height: 1.4;">
                    <strong>First-Come, First-Served Policy:</strong> Consultation slots are allocated dynamically upon booking. Double-booking a doctor for the same time window is prevented automatically.
                </div>
            </div>

            <div class="data-card">
                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;color:var(--primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                    Appointment Booking Form
                </h3>
                <form action="book-appointment.php" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
                    <div class="form-group">
                        <label>Medical Specialization</label>
                        <select name="specialization" id="specSelect" required>
                            <option value="">Select Specialty</option>
                            <?php while($s = mysqli_fetch_assoc($specs)) { echo "<option value='".htmlspecialchars($s['spec_name'])."'>".htmlspecialchars($s['spec_name'])."</option>"; } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Select Doctor</label>
                        <select name="doctor" id="docSelect" required>
                            <option value="">Select Doctor</option>
                            <?php while($d = mysqli_fetch_assoc($docs)) { echo "<option value='".htmlspecialchars($d['doc_name'])."' data-spec='".htmlspecialchars($d['specialization'])."'>".htmlspecialchars($d['doc_name'])." (".htmlspecialchars($d['specialization']).")</option>"; } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Appointment Date</label>
                        <input type="date" name="app_date" id="dateInput" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Appointment Time</label>
                        <input type="time" name="app_time" id="timeInput" required>
                    </div>

                    <div id="slotConflictAlert" style="display:none; padding: 12px 16px; background: #fef2f2; border: 1px solid #fca5a5; border-radius: var(--radius-sm); color: #991b1b; font-size: 13px; font-weight: 600; line-height: 1.4;">
                        ⚠️ <strong>Slot Unavailable (Taken):</strong> Dr. <span id="conflictDocName"></span> is already booked at <span id="conflictTimeStr"></span> on <span id="conflictDateStr"></span>. Please pick a different time slot or doctor.
                    </div>

                    <button type="submit" name="book" id="bookSubmitBtn" class="btn" style="margin-top: 8px;">Book Appointment Now</button>
                </form>
            </div>
        </div>

        <!-- Right Column: Live Booking Summary & Guidance Features -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <!-- Live Preview Card -->
            <div class="data-card" style="background: var(--navy-primary); color: #ffffff; border: 1px solid var(--navy-light);">
                <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #38bdf8; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between;">
                    <span>Live Booking Preview</span>
                    <span class="badge badge-info" style="font-size:10px;">FCFS Protected</span>
                </div>
                
                <h4 id="previewDocName" style="font-size: 16px; font-weight: 800; color: #ffffff; margin-bottom: 4px;">Select Specialist</h4>
                <div id="previewSpecName" style="font-size: 13px; color: #94a3b8; margin-bottom: 16px;">Specialty Pending Selection</div>

                <div style="display: flex; flex-direction: column; gap: 10px; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.1); font-size: 13px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #94a3b8;">Target Date:</span>
                        <span id="previewDate" style="font-weight: 700; color: #ffffff;">Not selected</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #94a3b8;">Time Window:</span>
                        <span id="previewTime" style="font-weight: 700; color: #ffffff;">Not selected</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #94a3b8;">Slot Status:</span>
                        <span id="previewStatus" style="color: #94a3b8; font-weight: 700;">● Select Details</span>
                    </div>
                </div>
            </div>

            <!-- Consultation Preparation Checklist -->
            <div class="data-card">
                <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 12px; color: var(--text-primary); display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;color:#059669;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Consultation Guidelines
                </h3>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 10px; font-size: 13px; color: var(--text-secondary);">
                    <li style="display: flex; align-items: flex-start; gap: 10px;">
                        <span style="color: var(--primary); font-weight: 800;">✓</span>
                        Arrive 15 minutes before your scheduled appointment time.
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 10px;">
                        <span style="color: var(--primary); font-weight: 800;">✓</span>
                        Bring valid photo identification and current prescription history.
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 10px;">
                        <span style="color: var(--primary); font-weight: 800;">✓</span>
                        Rescheduling is available anytime via Hospital Admin Desk.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var bookedSlots = <?php echo json_encode($booked_slots); ?>;

    var specSelect = document.getElementById('specSelect');
    var docSelect = document.getElementById('docSelect');
    var dateInput = document.getElementById('dateInput');
    var timeInput = document.getElementById('timeInput');

    var pDoc = document.getElementById('previewDocName');
    var pSpec = document.getElementById('previewSpecName');
    var pDate = document.getElementById('previewDate');
    var pTime = document.getElementById('previewTime');
    var pStatus = document.getElementById('previewStatus');

    var alertBox = document.getElementById('slotConflictAlert');
    var conflictDoc = document.getElementById('conflictDocName');
    var conflictDate = document.getElementById('conflictDateStr');
    var conflictTime = document.getElementById('conflictTimeStr');
    var submitBtn = document.getElementById('bookSubmitBtn');

    if (!specSelect || !docSelect) return;

    var allDocOptions = Array.from(docSelect.querySelectorAll('option')).slice(1);

    function formatTimeDisplay(timeStr) {
        if (!timeStr) return "Not selected";
        var parts = timeStr.split(':');
        var h = parseInt(parts[0], 10);
        var m = parts[1];
        if (isNaN(h)) return timeStr;
        var ampm = h >= 12 ? 'PM' : 'AM';
        h = h % 12;
        h = h ? h : 12;
        return (h < 10 ? '0' + h : h) + ':' + m + ' ' + ampm;
    }

    function updatePreview() {
        var selectedDoc = docSelect.value;
        var selectedSpec = specSelect.value;
        var selectedDate = dateInput.value;
        var selectedTime = timeInput.value;

        if (pDoc && selectedDoc) pDoc.textContent = "Dr. " + selectedDoc;
        else if (pDoc) pDoc.textContent = "Select Specialist";

        if (pSpec && selectedSpec) pSpec.textContent = selectedSpec;
        else if (pSpec) pSpec.textContent = "Specialty Pending Selection";

        if (pDate && selectedDate) pDate.textContent = selectedDate;
        else if (pDate) pDate.textContent = "Not selected";

        if (pTime && selectedTime) pTime.textContent = formatTimeDisplay(selectedTime);
        else if (pTime) pTime.textContent = "Not selected";

        // Check slot availability
        if (!selectedDoc || !selectedDate || !selectedTime) {
            if (pStatus) {
                pStatus.style.color = '#94a3b8';
                pStatus.textContent = '● Select Details';
            }
            if (alertBox) alertBox.style.display = 'none';
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            }
            return;
        }

        var isTaken = bookedSlots.some(function(s) {
            return s.doc === selectedDoc && s.date === selectedDate && s.time === selectedTime;
        });

        if (isTaken) {
            if (pStatus) {
                pStatus.style.color = '#f87171'; // Red highlight
                pStatus.textContent = '● Slot Unavailable (Taken)';
            }
            if (alertBox) {
                if (conflictDoc) conflictDoc.textContent = selectedDoc;
                if (conflictDate) conflictDate.textContent = selectedDate;
                if (conflictTime) conflictTime.textContent = formatTimeDisplay(selectedTime);
                alertBox.style.display = 'block';
            }
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
                submitBtn.style.cursor = 'not-allowed';
            }
        } else {
            if (pStatus) {
                pStatus.style.color = '#34d399'; // Bright green
                pStatus.textContent = '● Available Now';
            }
            if (alertBox) alertBox.style.display = 'none';
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            }
        }
    }

    specSelect.addEventListener('change', function() {
        var selectedSpec = this.value;
        docSelect.value = '';
        allDocOptions.forEach(function(opt) {
            if (!selectedSpec || opt.getAttribute('data-spec') === selectedSpec) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        });
        updatePreview();
    });

    docSelect.addEventListener('change', updatePreview);
    dateInput.addEventListener('change', updatePreview);
    timeInput.addEventListener('change', updatePreview);
});
</script>
<?php include('../includes/footer.php'); ?>
