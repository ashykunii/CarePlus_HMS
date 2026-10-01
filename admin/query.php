<?php
include('../config/db.php');
if(!isset($_SESSION['admin_id'])) { header('location:login.php'); exit(); }

// Add columns if missing
$chk_remark = mysqli_query($conn, "SHOW COLUMNS FROM queries LIKE 'admin_remark'");
if(mysqli_num_rows($chk_remark) == 0) {
    @mysqli_query($conn, "ALTER TABLE queries ADD COLUMN admin_remark TEXT NULL");
    @mysqli_query($conn, "ALTER TABLE queries ADD COLUMN status VARCHAR(50) DEFAULT 'Unread'");
    @mysqli_query($conn, "ALTER TABLE queries ADD COLUMN reply_date DATETIME NULL");
}

$message_alert = "";

// Save reply
if (isset($_POST['submit_reply'])) {
    $q_id = intval($_POST['query_id']);
    $reply_text = mysqli_real_escape_string($conn, trim($_POST['admin_remark']));
    
    if(!empty($reply_text)) {
        $update_query = "UPDATE queries SET admin_remark = '$reply_text', status = 'Replied', reply_date = NOW() WHERE id = $q_id";
        if(mysqli_query($conn, $update_query)) {
            $message_alert = "<div class='success-msg' style='margin-bottom: 20px;'><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:20px;height:20px;'><path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'/></svg> Response recorded successfully and sent to inquiry email!</div>";
        } else {
            $message_alert = "<div class='error-msg' style='margin-bottom: 20px;'>Error: Failed to save response. Please try again.</div>";
        }
    } else {
        $message_alert = "<div class='error-msg' style='margin-bottom: 20px;'>Please write a response message before submitting.</div>";
    }
}

// Get totals
$total_q = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM queries"))['cnt'];
$unread_q = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM queries WHERE status IS NULL OR status = 'Unread' OR status = ''"))['cnt'];
$replied_q = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM queries WHERE status = 'Replied'"))['cnt'];

$queries = mysqli_query($conn, "SELECT * FROM queries ORDER BY id DESC");
include('../includes/header.php');
include('../includes/admin_sidebar.php');
?>
<main class="content-area">
    <div class="page-title-bar">
        <div>
            <h2>Contact Queries & Messages Inbox</h2>
            <p>Review inquiries submitted by patients or visitors and respond directly.</p>
        </div>
    </div>

    <?php echo $message_alert; ?>

    <!-- Summary KPI Row -->
    <div class="stats-grid" style="margin-bottom: 24px;">
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Inquiries</h3>
                <p><?php echo $total_q; ?></p>
                <div class="stat-trend flat">Received from website</div>
            </div>
            <div class="stat-icon stat-blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Awaiting Response</h3>
                <p><?php echo $unread_q; ?></p>
                <div class="stat-trend <?php echo ($unread_q > 0) ? 'up' : 'flat'; ?>"><?php echo ($unread_q > 0) ? 'Requires action' : 'All clear'; ?></div>
            </div>
            <div class="stat-icon stat-amber">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Replied Queries</h3>
                <p><?php echo $replied_q; ?></p>
                <div class="stat-trend up">Responded & Resolved</div>
            </div>
            <div class="stat-icon stat-teal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="table-responsive">
        <div class="search-bar-wrapper">
            <div class="search-input-wrap">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input type="text" id="querySearchInput" class="table-search-input" placeholder="Search by sender, email, message or status..." autocomplete="off">
            </div>
            <div id="querySearchCount" class="search-result-count">Showing all queries</div>
        </div>

        <table id="queriesTable">
            <thead>
                <tr>
                    <th style="width: 110px;">Status</th>
                    <th>Sender Info</th>
                    <th>Contact Info</th>
                    <th>User Message Details</th>
                    <th>Admin Reply</th>
                    <th style="width: 140px;">Received</th>
                    <th style="width: 120px; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(mysqli_num_rows($queries) > 0) {
                    while($q = mysqli_fetch_assoc($queries)) { 
                        $is_replied = (isset($q['status']) && $q['status'] === 'Replied');
                        $sender_name = htmlspecialchars($q['fullname']);
                        $sender_email = htmlspecialchars($q['email']);
                        $sender_phone = htmlspecialchars($q['contact_no']);
                        $user_msg = htmlspecialchars($q['message']);
                        $admin_reply = isset($q['admin_remark']) ? htmlspecialchars($q['admin_remark']) : '';
                        $reply_date = isset($q['reply_date']) && !empty($q['reply_date']) ? date('M d, Y h:i A', strtotime($q['reply_date'])) : '';
                ?>
                <tr>
                    <td>
                        <?php if($is_replied) { ?>
                            <span class="badge badge-success">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                Replied
                            </span>
                        <?php } else { ?>
                            <span class="badge badge-pending">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5"/></svg>
                                Unread
                            </span>
                        <?php } ?>
                    </td>
                    <td>
                        <div class="table-user-cell">
                            <?php echo avatar_circle($q['fullname'], 36); ?>
                            <div class="user-meta">
                                <span class="name"><?php echo $sender_name; ?></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size: 13px; font-weight: 600; color: var(--text-primary);"><?php echo $sender_email; ?></div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;"><?php echo $sender_phone; ?></div>
                    </td>
                    <td>
                        <div style="max-width: 280px; font-size: 13px; color: var(--text-secondary); line-height: 1.5;">
                            <?php echo nl2br($user_msg); ?>
                        </div>
                    </td>
                    <td>
                        <?php if(!empty($admin_reply)) { ?>
                            <div class="reply-callout-box">
                                <div class="reply-callout-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                    Admin Response (<?php echo $reply_date; ?>)
                                </div>
                                <?php echo nl2br($admin_reply); ?>
                            </div>
                        <?php } else { ?>
                            <span style="font-size: 12px; color: var(--text-muted); font-style: italic;">No response yet</span>
                        <?php } ?>
                    </td>
                    <td>
                        <span style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">
                            <?php echo isset($q['posting_date']) ? date('M d, Y', strtotime($q['posting_date'])) : 'Recent'; ?>
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <button type="button" 
                                class="btn btn-sm open-reply-btn" 
                                data-id="<?php echo $q['id']; ?>"
                                data-name="<?php echo $sender_name; ?>"
                                data-email="<?php echo $sender_email; ?>"
                                data-message="<?php echo htmlspecialchars($q['message'], ENT_QUOTES); ?>"
                                data-reply="<?php echo htmlspecialchars($admin_reply, ENT_QUOTES); ?>"
                                style="font-size: 12px; padding: 6px 14px; <?php echo $is_replied ? 'background: #475569;' : ''; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                            </svg>
                            <?php echo $is_replied ? 'Edit Reply' : 'Reply'; ?>
                        </button>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center; padding: 32px; color: var(--text-muted);'>No contact queries found in database.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Reply Modal -->
<div class="query-reply-modal" id="replyModal">
    <div class="query-modal-card">
        <div class="query-modal-header">
            <h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
                Reply to Inquiry
            </h3>
            <button type="button" class="query-modal-close" id="closeReplyModal" aria-label="Close modal">&times;</button>
        </div>
        <form action="query.php" method="POST">
            <div class="query-modal-body">
                <input type="hidden" name="query_id" id="modalQueryId">
                
                <div style="margin-bottom: 16px;">
                    <div style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Recipient</div>
                    <div style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin-top: 2px;" id="modalRecipientName">John Doe</div>
                    <div style="font-size: 13px; color: var(--primary); font-weight: 600;" id="modalRecipientEmail">john@example.com</div>
                </div>

                <div class="query-quote-box">
                    <div class="quote-author">Original User Inquiry:</div>
                    <div id="modalOriginalMessage" style="font-style: italic;">"..."</div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-weight: 700; font-size: 13px; color: var(--text-primary);">Admin Response / Reply Message</label>
                    <textarea name="admin_remark" id="modalAdminRemark" rows="5" placeholder="Type your response to the user query here..." required style="width:100%; border-radius: var(--radius-sm); border: 1px solid var(--border-color); padding: 12px; font-size: 14px; font-family: inherit; outline: none; margin-top: 6px;"></textarea>
                </div>
            </div>
            <div style="padding: 16px 24px; background: var(--bg-subtle); border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="btn btn-secondary btn-sm" id="cancelReplyBtn">Cancel</button>
                <button type="submit" name="submit_reply" class="btn btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                    </svg>
                    Send & Save Reply
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search Filtering
    const searchInput = document.getElementById('querySearchInput');
    const table = document.getElementById('queriesTable');
    const countDisplay = document.getElementById('querySearchCount');
    
    if (searchInput && table) {
        const rows = table.querySelectorAll('tbody tr');
        const totalRows = rows.length;
        
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let visibleCount = 0;
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            if (countDisplay) {
                if (query === '') {
                    countDisplay.textContent = `Showing all ${totalRows} queries`;
                } else {
                    countDisplay.textContent = `Found ${visibleCount} of ${totalRows}`;
                }
            }
        });
    }

    // Modal logic
    const modal = document.getElementById('replyModal');
    const closeBtn = document.getElementById('closeReplyModal');
    const cancelBtn = document.getElementById('cancelReplyBtn');
    const replyBtns = document.querySelectorAll('.open-reply-btn');
    
    const modalId = document.getElementById('modalQueryId');
    const modalName = document.getElementById('modalRecipientName');
    const modalEmail = document.getElementById('modalRecipientEmail');
    const modalMsg = document.getElementById('modalOriginalMessage');
    const modalRemark = document.getElementById('modalAdminRemark');

    replyBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            modalId.value = this.dataset.id;
            modalName.textContent = this.dataset.name;
            modalEmail.textContent = this.dataset.email;
            modalMsg.textContent = '"' + this.dataset.message + '"';
            modalRemark.value = this.dataset.reply || '';
            
            modal.classList.add('is-active');
            setTimeout(() => modalRemark.focus(), 100);
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
