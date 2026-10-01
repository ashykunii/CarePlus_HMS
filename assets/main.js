/**
 * CarePlus HMS — shared front-end behaviour.
 * Loaded on every page (dashboards via includes/footer.php, public pages inline).
 */
(function () {
  'use strict';

 
  var toggle  = document.getElementById('sidebarToggle');
  var sidebar = document.getElementById('mainSidebar');
  var scrim   = document.getElementById('sidebarScrim');

  function openSidebar() {
    if (!sidebar) return;
    sidebar.classList.add('is-open');
    if (scrim)  scrim.classList.add('is-visible');
    if (toggle) toggle.setAttribute('aria-expanded', 'true');
    document.body.classList.add('no-scroll');
  }

  function closeSidebar() {
    if (!sidebar) return;
    sidebar.classList.remove('is-open');
    if (scrim)  scrim.classList.remove('is-visible');
    if (toggle) toggle.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('no-scroll');
  }

  if (toggle && sidebar) {
    toggle.addEventListener('click', function () {
      sidebar.classList.contains('is-open') ? closeSidebar() : openSidebar();
    });
  }
  if (scrim) scrim.addEventListener('click', closeSidebar);

  window.addEventListener('resize', function () {
    if (window.innerWidth > 902) closeSidebar();
  });

  /* ─────────────────────────────────────────────
   * Auto-dismissing flash messages
   * ───────────────────────────────────────────── */
  document.querySelectorAll('.success-msg, .error-msg, .info-msg').forEach(function (msg) {
    if (msg.dataset.persist === 'true') return;

    var closeBtn = document.createElement('button');
    closeBtn.type = 'button';
    closeBtn.className = 'alert-close-btn';
    closeBtn.setAttribute('aria-label', 'Dismiss message');
    closeBtn.innerHTML = '&times;';
    closeBtn.addEventListener('click', function () { msg.style.display = 'none'; });
    msg.appendChild(closeBtn);

    if (msg.classList.contains('success-msg') || msg.classList.contains('info-msg')) {
      setTimeout(function () {
        msg.classList.add('is-fading');
        setTimeout(function () { msg.style.display = 'none'; }, 400);
      }, 6000);
    }
  });

  /* ─────────────────────────────────────────────
   * Client-side photo preview
   * ───────────────────────────────────────────── */
  document.querySelectorAll('[data-preview]').forEach(function (box) {
    var input       = box.querySelector('.preview-input');
    var img         = box.querySelector('.preview-img');
    var placeholder = box.querySelector('.preview-placeholder');
    if (!input || !img) return;

    input.addEventListener('change', function (e) {
      var file = e.target.files && e.target.files[0];
      if (!file || !file.type.startsWith('image/')) return;

      var reader = new FileReader();
      reader.onload = function (ev) {
        img.src = ev.target.result;
        img.style.display = 'block';
        if (placeholder) placeholder.style.display = 'none';
      };
      reader.readAsDataURL(file);
    });
  });

  /* ─────────────────────────────────────────────
   * Generic live table-search
   * Activates on any .table-search-input that has
   * a data-table-body="#id" and data-count="#id".
   * ───────────────────────────────────────────── */
  document.querySelectorAll('.table-search-input[data-table-body]').forEach(function (input) {
    var bodyId   = input.getAttribute('data-table-body');
    var countId  = input.getAttribute('data-count');
    var tbody    = bodyId  ? document.querySelector(bodyId)  : null;
    var countEl  = countId ? document.getElementById(countId.replace('#','')) : null;

    if (!tbody) return;

    input.addEventListener('input', function () {
      var q       = this.value.toLowerCase();
      var rows    = tbody.querySelectorAll('tr');
      var visible = 0;
      rows.forEach(function (row) {
        var match = !q || row.textContent.toLowerCase().includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
      });
      if (countEl) {
        countEl.textContent = q
          ? visible + ' result' + (visible !== 1 ? 's' : '') + ' found'
          : '';
      }
    });
  });

  /* ─────────────────────────────────────────────
   * Hero section carousel (index.php)
   * Cycles through .hero-card-item every 3.2 s.
   * ───────────────────────────────────────────── */
  var heroCards = document.querySelectorAll('.hero-card-item');
  if (heroCards.length > 0) {
    var activeIdx = 0;

    function setActive(idx) {
      heroCards.forEach(function (card, i) {
        var fill = card.querySelector('.hero-pulse-bar-fill');
        if (i === idx) {
          card.classList.add('is-active');
          if (fill) fill.style.width = '100%';
        } else {
          card.classList.remove('is-active');
          if (fill) fill.style.width = '35%';
        }
      });
    }

    setActive(0);

    var heroTimer = setInterval(function () {
      activeIdx = (activeIdx + 1) % heroCards.length;
      setActive(activeIdx);
    }, 3200);

    heroCards.forEach(function (card, idx) {
      card.addEventListener('click', function () {
        clearInterval(heroTimer);
        activeIdx = idx;
        setActive(activeIdx);
        // Restart auto-cycle after manual selection
        heroTimer = setInterval(function () {
          activeIdx = (activeIdx + 1) % heroCards.length;
          setActive(activeIdx);
        }, 3200);
      });
    });
  }

  /* ─────────────────────────────────────────────
   * Password match validator (patient/register.php)
   * ───────────────────────────────────────────── */
  var pw        = document.getElementById('reg_password');
  var pwConfirm = document.getElementById('reg_password_confirm');
  var pwMsg     = document.getElementById('pwMatchMsg');

  if (pw && pwConfirm && pwMsg) {
    var regForm = pwConfirm.closest('form');

    function checkMatch() {
      if (!pwConfirm.value) { pwMsg.textContent = ''; return true; }
      if (pw.value === pwConfirm.value) {
        pwMsg.textContent = 'Passwords match.';
        pwMsg.style.color = 'var(--success)';
        return true;
      }
      pwMsg.textContent = 'Passwords do not match.';
      pwMsg.style.color = 'var(--danger)';
      return false;
    }

    pw.addEventListener('input', checkMatch);
    pwConfirm.addEventListener('input', checkMatch);
    if (regForm) {
      regForm.addEventListener('submit', function (e) {
        if (!checkMatch()) { e.preventDefault(); pwConfirm.focus(); }
      });
    }
  }

  /* ─────────────────────────────────────────────
   * Doctor form helpers (admin/manage-doctor.php)
   * ───────────────────────────────────────────── */
  window.resetPhotoPreview = function () {
    var img         = document.getElementById('doctor_photo_preview');
    var placeholder = document.getElementById('doctor_photo_placeholder');
    if (img)         { img.style.display = 'none'; img.removeAttribute('src'); }
    if (placeholder) { placeholder.style.display = 'flex'; }
  };

  window.editDoc = function (data) {
    document.getElementById('form_title').innerText    = 'Edit Doctor Profile';
    document.getElementById('doc_id').value            = data.id;
    document.getElementById('doc_name').value          = data.doc_name;
    document.getElementById('doc_spec').value          = data.specialization;
    document.getElementById('doc_phone').value         = data.contact_no;
    document.getElementById('doc_email').value         = data.email;
    document.getElementById('doc_password').placeholder = 'Leave blank to keep unchanged';
    window.resetPhotoPreview();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  window.resetForm = function () {
    document.getElementById('form_title').innerText    = 'Add New Doctor Profile';
    document.getElementById('doc_id').value            = '';
    document.getElementById('doc_name').value          = '';
    document.getElementById('doc_spec').value          = '';
    document.getElementById('doc_phone').value         = '';
    document.getElementById('doc_email').value         = '';
    document.getElementById('doc_password').placeholder = 'Create secure password';
    window.resetPhotoPreview();
  };

  /* Note: resetForm() is also used by manage-patient.php — both pages define the
     same global name but only one is loaded at a time, so there is no conflict.  */
  window.editPat = function (d) {
    document.getElementById('form_title').innerText = 'Edit Patient Clinical Record';
    document.getElementById('pat_id').value         = d.id;
    document.getElementById('pat_name').value       = d.patient_name;
    document.getElementById('pat_phone').value      = d.contact_no;
    document.getElementById('pat_gender').value     = d.gender;
    document.getElementById('pat_age').value        = d.age;
    document.getElementById('pat_history').value    = d.med_history;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  window.resetPatForm = function () {
    document.getElementById('form_title').innerText = 'Add New Patient Clinical Record';
    document.getElementById('pat_id').value         = '';
    document.getElementById('pat_name').value       = '';
    document.getElementById('pat_phone').value      = '';
    document.getElementById('pat_gender').value     = 'Male';
    document.getElementById('pat_age').value        = '';
    document.getElementById('pat_history').value    = '';
  };

  /* ─────────────────────────────────────────────
   * Generic reschedule / reply modal opener
   * (admin/schedule.php, admin/appointment-history.php,
   *  admin/query.php)
   * ───────────────────────────────────────────── */
  function initRescheduleModal() {
    var modal     = document.getElementById('rescheduleModal');
    if (!modal) return;

    var closeBtn  = document.getElementById('closeRescheduleModal');
    var cancelBtn = document.getElementById('cancelRescheduleBtn');
    var btns      = document.querySelectorAll('.open-reschedule-btn');

    var modalId      = document.getElementById('modalAppId');
    var modalIdBadge = document.getElementById('modalAppIdBadge');
    var modalPatient = document.getElementById('modalPatientName');
    var modalDoc     = document.getElementById('modalDocName');
    var modalDate    = document.getElementById('modalAppDate');
    var modalTime    = document.getElementById('modalAppTime');
    var modalStatus  = document.getElementById('modalAppStatus');

    btns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        if (modalId)      modalId.value           = this.dataset.id;
        if (modalIdBadge) modalIdBadge.textContent = '#AP-' + String(this.dataset.id).padStart(3, '0');
        if (modalPatient) modalPatient.textContent = this.dataset.patient;
        if (modalDoc)     modalDoc.value           = this.dataset.doctor;
        if (modalDate)    modalDate.value           = this.dataset.date;
        if (modalTime)    modalTime.value           = this.dataset.time;
        if (modalStatus)  modalStatus.value         = this.dataset.status;
        modal.classList.add('is-active');
      });
    });

    function closeModal() { modal.classList.remove('is-active'); }
    if (closeBtn)  closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });
  }

  function initReplyModal() {
    var modal = document.getElementById('replyModal');
    if (!modal) return;

    var closeBtn   = document.getElementById('closeReplyModal');
    var cancelBtn  = document.getElementById('cancelReplyBtn');
    var replyBtns  = document.querySelectorAll('.open-reply-btn');

    var modalId     = document.getElementById('modalQueryId');
    var modalName   = document.getElementById('modalRecipientName');
    var modalEmail  = document.getElementById('modalRecipientEmail');
    var modalMsg    = document.getElementById('modalOriginalMessage');
    var modalRemark = document.getElementById('modalAdminRemark');

    replyBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        if (modalId)     modalId.value       = this.dataset.id;
        if (modalName)   modalName.textContent  = this.dataset.name;
        if (modalEmail)  modalEmail.textContent = this.dataset.email;
        if (modalMsg)    modalMsg.textContent   = '"' + this.dataset.message + '"';
        if (modalRemark) modalRemark.value      = this.dataset.reply || '';
        modal.classList.add('is-active');
        setTimeout(function () { if (modalRemark) modalRemark.focus(); }, 100);
      });
    });

    function closeModal() { modal.classList.remove('is-active'); }
    if (closeBtn)  closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initRescheduleModal();
    initReplyModal();
  });

})();
