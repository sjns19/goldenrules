<?php if (!$email_verified): ?>
    <div class="alert alert-danger alert-icon">
      <i class="fa fa-exclamation-triangle"></i>
      <h1>email verification required</h1>
      <p>You must verify your email address before you can select a mentor.</p>
    </div>
<?php else: ?>
  <div id="mentors-list" data-token="<?= $this->page_data['csrf_token'] ?>">
    <div class="spinner"></div>
    <div class="txt-align-center txt-grey">Loading...</div>
  </div>
  <?php if (!$mentor_id): ?>
    <div class="alert alert-info alert-icon">
      <i class="fa fa-exclamation-circle"></i>
      <h1>Hey!</h1>
      <p>Once you select a mentor, you will not be able to select another one until the mentor you have selected, ends the active session with you..</p>
      <p class="mt-1">If you, however want to cancel your session, please contact the respected mentor with a valid reason.</p>
    </div>
  <?php endif; ?>
<?php endif; ?>