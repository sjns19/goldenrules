<?php if (!defined(basename(__FILE__) . '-included')) return http_response_code(404); ?>

		<div class="wrapper p-3">
			<div class="card card-md card-dark-grey card-centered card-gold-bordered p-2">
				<div class="card-header">
					<h3 class="txt-gold txt-uppercase txt-grad-gold">reset your password</h3>
				</div>
				<div class="card-body">
          <p class="txt-grey">Enter a new password below to reset.</p>
					<form class="form mt-2 mb-2" data-rft="<?= $this->reset_password_data['from_token'] ?>" data-rt="<?= $this->reset_password_data['page_token'] ?>" data-ts="<?= $this->reset_password_data['timestamp'] ?>" id="reset-password-form" method="POST">
						<div class="input-group">
							<input type="password" class="input" id="new-password" title="Enter your new password" placeholder="New Password"  autocomplete="off" required />
							<span class="input-sub-label"></span>
						</div>
						<div class="input-group">
							<input type="password" class="input" id="confirm-new-password" title="Confirm your new password" placeholder="Confirm Password"  autocomplete="off" required disabled />
							<span class="input-sub-label"></span>
						</div>
						<input type="submit" class="btn btn-gold btn-lg btn-block txt-uppercase" value="Change" id="reset-password-btn" disabled>
					</form>
				</div>
			</div>
			<div class="txt-align-center mt-2">
        <small class="txt-grey">&copy <?= date('Y') ?> <a href="/" class="link-grey link-no-underline" title="Back to Home"><?= SITE_NAME ?></a> - All Rights Reserved.</small>
      </div>
		</div>