<?php if (!defined(basename(__FILE__) . '-included')) return http_response_code(404); ?>
		<div class="wrapper p-3">
			<div class="card card-md card-dark-grey card-centered card-gold-bordered p-2">
				<div class="card-header">
					<h3 class="txt-gold txt-uppercase txt-grad-gold">forgot password?</h3>
				</div>
				<div class="card-body">
					<p class="txt-grey">Enter the Email address associated with a <?= SITE_NAME ?> account and we will send a recovery link to it.</p>
					<form class="mt-2" id="password-recover-form" method="POST">
						<div class="input-group">
							<input type="email" class="input" id="password-recover-email" title="Enter your Email address" placeholder="Email Address"  autocomplete="off" required />
							<span class="input-sub-label"></span>
						</div>
						<input type="submit" class="btn btn-gold btn-lg btn-block txt-uppercase" id="password-recover-btn" disabled>
					</form>
				</div>
				<div class="card-footer">
					<p class="txt-grey txt-sm"><strong>Note:</strong> Make sure the Email address is valid and belongs to you and your <?= SITE_NAME ?> account before proceeding.</p>
				</div>
			</div>
			<div class="txt-align-center mt-2">
        <small class="txt-grey">&copy <?= date('Y') ?> <a href="/" class="link-grey link-no-underline" title="Back to Home"><?= SITE_NAME ?></a> - All Rights Reserved.</small>
      </div>
		</div>