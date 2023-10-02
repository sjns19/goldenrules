<?php if (!defined(basename(__FILE__) . '-included')) return http_response_code(404); ?>
	<div class="wrapper p-3">
		<div class="card card-sm card-dark-grey card-centered card-gold-bordered p-2">
<?php if ($this->continue_msg): ?>
			<div class="txt-align-center m-1 mb-2">
				<h3 class="txt-gold txt-uppercase">please login first</h3>
				<p class="txt-dark-grey txt-mds"><?= $this->continue_msg ?></p>
			</div>
<?php endif; ?>
			<div class="card-header txt-align-center">
				<img src="<?= ICONS_PATH ?>logo.png" alt="<?= SITE_NAME ?>" height="60" width="200" />
				<hr class="hr-gradient-gold">
				<p class="txt-dark-grey txt-uppercase mt-1">membership login</p>
			</div>
			<div class="card-body">
				<form id="login-form" action="/user/scripts/user_login.php" method="POST">
					<div id="login-alert"></div>
					<div class="input-group">
						<input type="test" class="input" id="login-email-username" title="Your username or email" placeholder="Email or Username" autocapitalize="off" autocomplete="off" required />
						<span class="input-sub-label"></span>
					</div>
					<div class="input-group input-group-labeled">
						<input type="password" class="input" id="login-password" title="Your password" placeholder="Password" autocomplete="off" required />
						<span class="input-sub-label">
							<a href="/user/password-recover" class="link-grey link-no-underline" title="Forgot your password?">Forgot Password?</a>
						</span>
					</div>
					<div class="align-center">
						<input type="submit" class="btn btn-gold btn-lg btn-block txt-uppercase" value="Login" id="login-button" disabled />
					</div>
				</form>
			</div>
			<div class="card-footer txt-align-center">
				<p class="txt-dark-grey">Not a member? <a href="/signup" class="link-gold link-no-underline" title="Sign up for GoldenRules membership">Sign Up!</a></p>
			</div>
		</div>
		<div class="txt-align-center mt-2">
			<small class="txt-dark-grey">&copy <?= date('Y') ?> <a href="/" class="link-grey link-no-underline" title="Back to Home"><?= SITE_NAME ?></a> - All Rights Reserved.</small>
		</div>
	</div>