<?php if (!defined(basename(__FILE__) . '-included')) return http_response_code(404); ?>
	<div class="wrapper p-3">
		<div class="card card-md card-dark-grey card-centered card-gold-bordered p-2">
			<div class="card-header txt-align-center">
				<img src="<?= ICONS_PATH ?>logo.png" alt="<?= SITE_NAME ?>" height="60" width="200" />
				<hr class="hr-gradient-gold">
				<p class="txt-grey txt-uppercase mt-1">membership registration</p>
			</div>
			<div class="card-body">
				<form id="reg-form" action="/user/scripts/user_register.php" method="POST">
					<div class="input-group input-group-inlined">
						<div class="input-inlined">
							<input type="text" class="input" id="reg-firstname" title="Your first name" placeholder="First Name*"  autocomplete="off" required />
							<span class="input-sub-label"></span>
						</div>
						<div class="input-inlined">
							<input type="text" class="input" id="reg-lastname" title="Your last name" placeholder="Last Name*" autocomplete="off" required />
							<span class="input-sub-label"></span>
						</div>
					</div>
					<div class="input-group">
						<input type="email" class="input" id="reg-email" title="Your Email" placeholder="Email* (you@example.com)" autocapitalize="off" autocomplete="off" required />
						<span class="input-sub-label"></span>
					</div>
					<div class="input-group input-group-inlined">
						<div class="input-inlined">
							<input type="password" class="input" id="reg-password" title="Your password" placeholder="Password*" autocapitalize="off" autocomplete="off" required />
							<span class="input-sub-label"></span>
						</div>
						<div class="input-inlined">
							<input type="password" class="input" id="reg-password-retype" title="Confirm your password" placeholder="Confirm Password" autocapitalize="off" autocomplete="off" disabled />
							<span class="input-sub-label"></span>
						</div>
					</div>
					<div class="input-group">
						<input type="text" class="input" id="reg-username" title="Type your username" placeholder="Username*" autocapitalize="off" autocomplete="off" required />
						<span class="input-sub-label"></span>
					</div>
					<div id="reg-alert"></div>
					<div class="align-center">
						<input type="submit" class="btn btn-gold btn-lg btn-block txt-uppercase" value="register" id="reg-button" disabled />
					</div>
				</form>
			</div>
			<div class="card-footer txt-align-center">
				<p class="txt-grey" id="login-question">Already a member? <a href="/login" class="link-gold link-no-underline" title="Login to your GoldenRules account">Login!</a></p>
			</div>
		</div>
		<div class="txt-align-center mt-2">
			<small class="txt-grey">By clicking 'Register', you agree to our<br /> 
				<a href="/legal/privacy-policy" class="link-grey" target="_blank">Privacy Policy</a>, 
				<a href="/legal/terms-and-conditions" class="link-grey" target="_blank">Terms of Service</a> and 
				<a href="/legal/privacy-policy/#cookies" class="link-grey" target="_blank">Cookie Policy</a>.<br />
			<p class="mt-1">&copy <?= date('Y') ?> <a href="/" class="link-grey link-no-underline" title="Back to Home"><?= SITE_NAME ?></a> - All Rights Reserved.</p></small>
		</div>
	</div>