<?php
include('../config/db.php');
if(!isset($_SESSION['admin_id'])) { header('location:login.php'); exit(); }

$logs = mysqli_query($conn, "SELECT * FROM doctor_logs ORDER BY id DESC");
include('../includes/header.php');
include('../includes/admin_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <h2>Doctor Session Security Logs</h2>
        <p>Authentication audit log recording medical staff login and logout sessions.</p>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>Doctor Email</th>
                    <th>Login Timestamp</th>
                    <th>Logout Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(mysqli_num_rows($logs) > 0) {
                    while($l = mysqli_fetch_assoc($logs)) { 
                ?>
                <tr>
                    <td>#<?php echo $l['id']; ?></td>
                    <td style="font-weight: 600;"><?php echo htmlspecialchars($l['doc_email']); ?></td>
                    <td><span class="badge badge-success"><?php echo $l['login_time']; ?></span></td>
                    <td>
                        <?php 
                        if($l['logout_time']) {
                            echo "<span class='badge badge-info'>{$l['logout_time']}</span>";
                        } else {
                            echo "<span class='badge badge-pending'>Session Active</span>";
                        }
                        ?>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center; color: var(--text-muted);'>No doctor session logs recorded yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>
<?php include('../includes/footer.php'); ?>
