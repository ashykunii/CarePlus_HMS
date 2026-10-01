<?php 
include('config/db.php'); 

$message = "";

if (isset($_POST['submit_query'])) {
    $name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $msg = mysqli_real_escape_string($conn, $_POST['message']);
    
    $query = "INSERT INTO queries (fullname, email, contact_no, message) VALUES ('$name', '$email', '$phone', '$msg')";
    
    if (mysqli_query($conn, $query)) {
        $message = "<div class='success-msg'><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:20px;height:20px;'><path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'/></svg> Your query has been submitted successfully! Our medical team will respond shortly.</div>";
    } else {
        $message = "<div class='error-msg'>Error: Could not send message. Please try again.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - CarePlus HMS</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22><rect width=%2224%22 height=%2224%22 rx=%226%22 fill=%22%230284c7%22/><path d=%22M12 5v14M5 12h14%22 stroke=%22white%22 stroke-width=%222.5%22 stroke-linecap=%22round%22/></svg>">
</head>
<body class="public-page">
    <header class="public-header">
        <div class="brand-logo">
            <div class="brand-icon-box">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </div>
            <h1>CarePlus HMS</h1>
        </div>
        <nav>
            <a href="index.php">Home</a>
            <a href="contact.php" class="active">Contact Us</a>
        </nav>
    </header>

    <main class="welcome-container" style="max-width: 1050px;">
        <div class="form-grid-2" style="gap: 32px; align-items: start;">
            <!-- Left: Contact Form -->
            <div class="form-container" style="margin-bottom: 0;">
                <h2 style="font-size: 24px; font-weight: 800; margin-bottom: 6px;">Contact Us / General Query</h2>
                <p style="color: var(--text-secondary); margin-bottom: 24px; font-size: 14px;">Have questions or need assistance? Send us a message and our support staff will assist you.</p>
                
                <?php echo $message; ?>
                
                <form action="contact.php" method="POST">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="fullname" placeholder="John Doe" required>
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" placeholder="john.doe@example.com" required>
                    </div>

                    <div class="form-group">
                        <label>Contact Phone Number</label>
                        <input type="text" name="phone" placeholder="+1 (555) 000-0000" required>
                    </div>

                    <div class="form-group">
                        <label>Your Message / Inquiry</label>
                        <textarea name="message" rows="4" placeholder="Type your inquiry or feedback here..." required></textarea>
                    </div>

                    <button type="submit" name="submit_query" class="btn" style="width: 100%; margin-top: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                        Send Message
                    </button>
                </form>
            </div>

            <!-- Right: Hospital Info Card & Photo -->
            <div>
                <div style="background: var(--surface-card); border-radius: var(--radius-md); border: 1px solid var(--border-color); overflow: hidden; box-shadow: var(--shadow-sm);">
                    <img src="assets/images/hospital.png " class="img-cover" alt="Hospital" style="width: 100%; height: 200px; object-fit: cover;">
                    <div style="padding: 24px;">
                        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 12px; color: var(--text-primary);">CarePlus Central Hospital</h3>
                        <p style="font-size: 14px; color: var(--text-secondary); margin-bottom: 16px;">123 Healthcare Boulevard, Medical District, Suite 400</p>
                        
                        <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13px; color: var(--text-secondary);">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;color:var(--primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.826-1.47-5.114-3.758-6.584-6.584l1.293-.97c.362-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" /></svg>
                                <span>Emergency Line: <strong>+1 (800) 555-CARE</strong></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;color:var(--primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                                <span>Email: <strong>support@careplus.com</strong></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;color:var(--primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                <span>Working Hours: <strong>24/7 Emergency & OPD</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> CarePlus Health Systems. All rights reserved.</p>
    </footer>
    <script src="assets/main.js"></script>
</body>
</html>
