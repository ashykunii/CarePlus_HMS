<?php
include('../config/db.php');
if(!isset($_SESSION['admin_id'])) { header('location:login.php'); exit(); }

$msg = "";
if(isset($_GET['del'])) {
    $id = intval($_GET['del']);
    if(mysqli_query($conn, "DELETE FROM users WHERE id=$id")) {
        $msg = "User account removed successfully.";
    }
}

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
include('../includes/header.php');
include('../includes/admin_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <div>
            <h2>Manage Registered Users</h2>
            <p>Overview of patient user accounts registered in the portal.</p>
        </div>
    </div>

    <?php if($msg) echo "<div class='success-msg'><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:20px;height:20px;'><path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'/></svg> $msg</div>"; ?>

    <div class="table-responsive">
        <div class="search-bar-wrapper">
            <div class="search-input-wrap">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <input type="text" id="userSearch" class="table-search-input" placeholder="Search users by name or email..." data-table-body="table tbody" data-count="userCount">
            </div>
            <span class="search-result-count" id="userCount"></span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>User Profile</th>
                    <th>Email Address</th>
                    <th>Registration Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(mysqli_num_rows($users) > 0) {
                    while($u = mysqli_fetch_assoc($users)) { 
                ?>
                <tr>
                    <td>
                        <div class="table-user-cell">
                            <?php echo avatar_circle($u['fullname'], 36); ?>
                            <div class="user-meta">
                                <span class="name"><?php echo htmlspecialchars($u['fullname']); ?></span>
                                <span class="subtext">User #USR-<?php echo sprintf('%03d', $u['id']); ?></span>
                            </div>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td><?php echo isset($u['reg_date']) ? $u['reg_date'] : 'N/A'; ?></td>
                    <td>
                        <a href="manage-user.php?del=<?php echo $u['id']; ?>" class="danger-btn" onclick="return confirm('Are you sure you want to delete this user account?')">Delete Account</a>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center; padding: 24px; color: var(--text-muted);'>No registered users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>
<?php include('../includes/footer.php'); ?>
