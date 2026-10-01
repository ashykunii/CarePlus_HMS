<?php
include('../config/db.php');
if(!isset($_SESSION['user_id'])) { header('location:login.php'); exit(); }

$pname = $_SESSION['user_name'];
$med_records = mysqli_query($conn, "SELECT * FROM patients WHERE patient_name='$pname' ORDER BY id DESC");

include('../includes/header.php');
include('../includes/patient_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <h2>My Medical Records</h2>
        <p>Electronic health history and clinical diagnosis notes published by doctors.</p>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Contact Phone</th>
                    <th>Age</th>
                    <th>Clinical History & Diagnosis Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(mysqli_num_rows($med_records) > 0) {
                    while($m = mysqli_fetch_assoc($med_records)) { 
                ?>
                <tr>
                    <td style="font-weight: 600;"><?php echo htmlspecialchars($m['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($m['contact_no']); ?></td>
                    <td><?php echo htmlspecialchars($m['age']); ?> yrs</td>
                    <td><?php echo nl2br(htmlspecialchars($m['med_history'])); ?></td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center; color: var(--text-muted);'>No electronic health history records published yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>
<?php include('../includes/footer.php'); ?>
