<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarePlus HMS — Smart Hospital Management System</title>
    <meta name="description" content="CarePlus HMS — book appointments, manage medical records, and coordinate hospital staff and patients in one intelligent platform.">
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
            <a href="index.php" class="active">Home</a>
            <a href="contact.php">Contact Us</a>
            <a href="patient/login.php">Patient Sign In</a>
        </nav>
    </header>

    <!-- Hero -->
    <section class="welcome-container">
        <div class="welcome-hero">
            <div class="hero-content">
                <div class="hero-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:15px;height:15px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Trusted Digital Health Platform
                </div>
                <h2>Modern &amp; Intelligent<br>Healthcare Management</h2>
                <p>CarePlus HMS unifies patient consultations, electronic medical records, clinical appointments, and administrative operations — all in one intelligent platform.</p>
                <div style="display:flex; gap:14px; flex-wrap:wrap;">
                    <a href="patient/register.php" class="btn" id="hero-book-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                        Book Appointment
                    </a>
                    <a href="contact.php" class="btn btn-secondary" id="hero-contact-btn">Get In Touch</a>
                </div>
            </div>

            <!-- Hero Visual -->
            <div class="hero-visual">
                <div class="hero-visual-top">
                    <div class="hero-card-item">
                        <div class="hero-card-icon" style="background:rgba(56,189,248,0.25);">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#38bdf8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                        </div>
                        <div class="hero-card-text">
                            <div class="title">Dr. Sarah Jenkins · Cardiology</div>
                            <div class="sub">Available Today · 3 slots remaining</div>
                            <div class="hero-pulse-bar"><div class="hero-pulse-bar-fill" style="width:72%;"></div></div>
                        </div>
                    </div>
                    <div class="hero-card-item">
                        <div class="hero-card-icon" style="background:rgba(45,212,191,0.25);">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#2dd4bf"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        </div>
                        <div class="hero-card-text">
                            <div class="title">Appointment Confirmed</div>
                            <div class="sub">Sept 24 · 09:30 AM · Neurology</div>
                            <div class="hero-pulse-bar"><div class="hero-pulse-bar-fill" style="width:100%;"></div></div>
                        </div>
                    </div>
                    <div class="hero-card-item">
                        <div class="hero-card-icon" style="background:rgba(249,115,22,0.25);">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#fb923c"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                        </div>
                        <div class="hero-card-text">
                            <div class="title">Medical Record Updated</div>
                            <div class="sub">Lab results attached · Reviewed</div>
                            <div class="hero-pulse-bar"><div class="hero-pulse-bar-fill" style="width:55%;background:var(--primary);"></div></div>
                        </div>
                    </div>
                </div>
                <div class="hero-visual-bottom">
                    <div class="hero-mini-stat"><div class="n">98%</div><div class="l">Patient Satisfaction</div></div>
                    <div class="hero-mini-stat"><div class="n">24/7</div><div class="l">System Availability</div></div>
                    <div class="hero-mini-stat"><div class="n">50+</div><div class="l">Departments</div></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <div class="stats-banner">
        <div class="stats-banner-inner">
            <div class="stat-item">
                <div class="num">12,400+</div>
                <div class="label">Patients Served</div>
            </div>
            <div class="stat-item">
                <div class="num">180+</div>
                <div class="label">Specialist Doctors</div>
            </div>
            <div class="stat-item">
                <div class="num">35+</div>
                <div class="label">Departments</div>
            </div>
            <div class="stat-item">
                <div class="num">99.9%</div>
                <div class="label">System Uptime</div>
            </div>
        </div>
    </div>

    <!-- Features -->
    <section class="lp-section">
        <div class="lp-section-inner">
            <div class="section-eyebrow">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z" /></svg>
                Platform Features
            </div>
            <h2 class="section-heading">Everything Your Hospital Needs</h2>
            <p class="section-sub">CarePlus HMS delivers a complete digital health management suite designed for modern clinical operations.</p>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#e0f2fe; color:#0284c7;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                    </div>
                    <h4>Smart Appointment Booking</h4>
                    <p>Patients can schedule consultations online in minutes. Doctors receive instant notifications and manage their daily schedules effortlessly.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:#ccfbf1; color:#0d9488;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    </div>
                    <h4>Electronic Medical Records</h4>
                    <p>Centralized, secure patient health records with full clinical history, diagnostic notes, and prescription tracking accessible anywhere.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:#f3e8ff; color:#9333ea;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                    </div>
                    <h4>Doctor Staff Management</h4>
                    <p>Manage physician rosters, specializations, schedules, and login credentials from a unified admin console with role-based access control.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:#fef3c7; color:#d97706;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" /></svg>
                    </div>
                    <h4>Analytics &amp; Reporting</h4>
                    <p>Comprehensive dashboards with live statistics on appointments, patient volumes, department activity, and system usage logs.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:#fee2e2; color:#dc2626;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                    </div>
                    <h4>Secure Role-Based Access</h4>
                    <p>Three distinct portals for admins, doctors, and patients — each with carefully scoped permissions and secure session management.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:#dbeafe; color:#2563eb;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                    </div>
                    <h4>Live Search &amp; Filtering</h4>
                    <p>Instantly search patients, appointments, and records across all modules. Results appear as you type — no page reload needed.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section class="lp-section hiw-section">
        <div class="lp-section-inner">
            <div style="text-align:center;">
                <div class="section-eyebrow">How It Works</div>
                <h2 class="section-heading">Get Started in 3 Simple Steps</h2>
                <p class="section-sub" style="margin: 0 auto;">From registration to consultation — everything is streamlined and intuitive for patients, doctors, and staff alike.</p>
            </div>
            <div class="hiw-steps">
                <div class="hiw-step">
                    <div class="hiw-step-num">1</div>
                    <h4>Create Your Account</h4>
                    <p>Patients register in seconds. Doctors and admins are onboarded through the admin portal with full credential management.</p>
                </div>
                <div class="hiw-step">
                    <div class="hiw-step-num">2</div>
                    <h4>Book an Appointment</h4>
                    <p>Choose your preferred specialist, pick a date and time, and confirm your appointment — all from your patient dashboard.</p>
                </div>
                <div class="hiw-step">
                    <div class="hiw-step-num">3</div>
                    <h4>Receive &amp; Review Care</h4>
                    <p>Doctors review your medical history, update clinical notes, and manage follow-ups — all in one secure digital environment.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits -->
    <section class="lp-section">
        <div class="lp-section-inner">
            <div style="text-align:center;">
                <div class="section-eyebrow">Key Advantages</div>
                <h2 class="section-heading">Why Choose CarePlus HMS?</h2>
                <p class="section-sub" style="margin: 0 auto;">Built to deliver maximum efficiency, transparency, and clinical excellence for all healthcare stakeholders.</p>
            </div>
            
            <div class="benefits-grid">
                <div class="benefit-card">
                    <span class="benefit-tag patient-tag">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                        Patient Advantage
                    </span>
                    <h4>24/7 FCFS Online Scheduling</h4>
                    <p>Book appointments anytime with immediate real-time slot validation. First-Come First-Served policy ensures transparent queue reservation with zero waiting room delays.</p>
                </div>
                
                <div class="benefit-card">
                    <span class="benefit-tag patient-tag">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                        Patient Advantage
                    </span>
                    <h4>Personal Medical Record Access</h4>
                    <p>Review comprehensive diagnostic history, consultation logs, and medical notes securely from any device with self-service account management.</p>
                </div>
                
                <div class="benefit-card">
                    <span class="benefit-tag doctor-tag">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" /></svg>
                        Clinical Advantage
                    </span>
                    <h4>Streamlined Roster &amp; Calendar</h4>
                    <p>Physicians view live daily consultation schedules, manage patient status updates, and access diagnostic files with one-click filtering.</p>
                </div>
                
                <div class="benefit-card">
                    <span class="benefit-tag doctor-tag">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Clinical Advantage
                    </span>
                    <h4>Specialty Matchmaking &amp; EHR</h4>
                    <p>Automatic routing ensures patients are matched with the right specialist while doctors maintain up-to-date Electronic Health Records effortlessly.</p>
                </div>
                
                <div class="benefit-card">
                    <span class="benefit-tag admin-tag">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0H3" /></svg>
                        Admin Advantage
                    </span>
                    <h4>Role-Based Access Control</h4>
                    <p>Strict permission boundaries between Admin, Doctor, and Patient portals protect confidential health data with full session security and audit logs.</p>
                </div>
                
                <div class="benefit-card">
                    <span class="benefit-tag system-tag">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        System Advantage
                    </span>
                    <h4>Zero Double-Booking Guarantee</h4>
                    <p>Database-enforced slot lock algorithm prevents overlapping appointments for doctors and double-bookings for patients automatically.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Portals -->
    <section class="lp-section">
        <div class="lp-section-inner">
            <div class="portal-section-heading">
                <div class="section-eyebrow" style="justify-content:center;">Access Portals</div>
                <h2 class="section-heading">Select Your Role to Sign In</h2>
            </div>
            <div class="portal-grid">
                <!-- Admin Portal -->
                <div class="portal-card">
                    <div class="portal-card-header" style="background:var(--navy-primary);">
                        <svg class="header-watermark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0H3" />
                        </svg>
                        <div class="portal-icon-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0H3" />
                            </svg>
                        </div>
                    </div>
                    <div class="portal-card-body">
                        <h3>Admin Console</h3>
                        <p>Full system control — manage doctor rosters, patient records, appointment data, audit logs, analytical reports, and system settings.</p>
                        <a href="admin/login.php" class="btn" id="admin-portal-btn" style="margin-top:auto;">Admin Login</a>
                    </div>
                </div>

                <!-- Doctor Portal -->
                <div class="portal-card">
                    <div class="portal-card-header header-teal">
                        <svg class="header-watermark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                        </svg>
                        <div class="portal-icon-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                            </svg>
                        </div>
                    </div>
                    <div class="portal-card-body">
                        <h3>Doctor Portal</h3>
                        <p>Manage daily consultations, review patient diagnostic history, update clinical notes, and track your assigned appointment schedule.</p>
                        <a href="doctor/login.php" class="btn" id="doctor-portal-btn" style="margin-top:auto;">Doctor Login</a>
                    </div>
                </div>

                <!-- Patient Portal -->
                <div class="portal-card">
                    <div class="portal-card-header header-amber">
                        <svg class="header-watermark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        <div class="portal-icon-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="portal-card-body">
                        <h3>Patient Portal</h3>
                        <p>Book appointments online, access your personal health records, view clinical notes, and keep your profile up to date with ease.</p>
                        <a href="patient/login.php" class="btn" id="patient-portal-btn" style="margin-top:auto;">Patient Sign In</a>
                        <p style="margin-top:14px; font-size:13px; text-align:center;">New patient? <a href="patient/register.php" style="font-weight:700;">Create an Account</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> CarePlus Health Systems &mdash; Intelligent Hospital Management. All rights reserved. &nbsp;|&nbsp; <a href="contact.php">Contact</a></p>
    </footer>

    <script src="assets/main.js"></script>
</body>
</html>
