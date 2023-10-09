<?php if (!defined(basename(__FILE__) . '-included')) return http_response_code(404); ?>

<div class="wrapper p-3">
	<div class="card card-md card-dark-grey card-centered card-gold-bordered p-2">
<?php if ($this->verify_status): ?>
	<div class="card-header">
		<h3 class="txt-gold txt-uppercase txt-grad-gold">email verified</h3>
	</div>
	<div class="card-body">
		<p class="txt-grey">Your email address has been successfully verified. You can now continue to use your account.</p>
		<p class="txt-grey mt-1">Would you like to <a href="/login" class="link-gold">login</a>?</p>
	</div>
<?php else: ?>
	<div class="card-header">
		<h3 class="txt-gold txt-uppercase">could not verify email</h3>
	</div>
	<div class="card-body">
		<p class="txt-grey">An unknown error occured while trying to verify your account.</p>
		<p class="txt-grey mt-1">If you keep on seeing this error, please contact us at <a href="mailto:<?= SITE_SUPPORT_EMAIL ?>" class="link-gold no-underline"><?= SITE_SUPPORT_EMAIL ?></a></p>
	</div>
<?php endif; ?>
	</div>
	<div class="txt-align-center mt-2">
		<small class="txt-grey">&copy <?= date('Y') ?> <a href="/" class="link-grey link-no-underline" title="Back to Home"><?= SITE_NAME ?></a> - All Rights Reserved.</small>
	</div>
</div>