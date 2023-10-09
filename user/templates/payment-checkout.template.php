<?php if (!defined(basename(__FILE__) . '-included')) return http_response_code(404); ?>

<div class="wrapper p-3">
	<div class="card card-dark-grey card-md card-centered card-gold-bordered p-2">
		<div class="card-header txt-align-center">
			<div class="align-center">
				<img src="<?= ICONS_PATH ?>logo.png" alt="<?= SITE_NAME ?>" height="60" width="200" />

		<?php if ($this->user['email_verified'] || !$this->has_already_paid && !$this->user['paid_membership']): ?>
				<hr class="hr-gradient-gold">
				<p class="txt-dark-grey txt-uppercase">paid membership</p>
		<?php endif; ?>

			</div>
		</div>
		<div class="card-body">
<?php if (!$this->has_already_paid && !$this->user['paid_membership']): ?>
	<?php if ($this->user['email_verified']): ?>
			<div class="mb-1">
				<p class="txt-dark-grey txt-mds">You are about to make a payment for GoldenRules paid membership with the following account details</p>
				<div class="my-2 txt-sm">
					<div class="txt-dark-grey">
						<strong class="mt-1">Name</strong>
						<p class="mb-1"><?= $this->user['name'] ?></p>
						<strong class="mt-1">Email</strong>
						<p class="mb-1"><?= $this->user['email'] ?></p>
						<strong class="mt-1">Amount</strong>
						<p class="mb-1">$<?= Payment::getPaymentAmount() ?> USD</p>
					</div>
				</div>
			</div>
			<form action="/user/scripts/payment_charge.php" method="post" id="payment-form">
				<div class="form-row">
					<label class="txt-dark-grey txt-mds" for="card-element">Enter Credit or Debit card number</label>
					<div id="card-element">
					<!-- A Stripe Element will be inserted here. -->
					</div>
					<!-- Used to display form errors. -->
					<div id="card-errors" role="alert"></div>
				</div>
				<div id="payment-processor"></div>
				<button class="btn btn-gold btn-lg btn-block txt-uppercase mt-1" disabled>Pay Now</button>
			</form>
			<div class="my-1">
				<small class="txt-dark-grey">
					<h3 class="mt-2 mb-1 txt-uppercase">important</h3>
					<p class="txt-dark-grey">Make sure you have read our <a href="/legal/privacy-policy" class="link-grey" target="_blank">Privacy Policy</a>, 
					<a href="/legal/terms-and-conditions" class="link-grey" target="_blank">Terms of Service</a> and 
					<a href="/legal/refund-policy" class="link-grey" target="_blank">Refund Policy</a> properly before proceeding to make a payment.</p>
					<p class="mt-1">We highly value your privacy, therefore, we do not store your credit or debit card information in our database. Once you make a purchase, only
					the transaction details are stored so that our administrators can identify and process your purchase precisely.</p>
				</small>
				<div class="my-1">
					<img src="<?= ICONS_PATH ?>bank-cards.png" alt="MasterCard and Visa icons" height="30" width="100" title="Accepted cards - VISA and MasterCard" />
				</div>
			</div>
	<?php else: ?>
			<div class="alert alert-default alert-icon">
				<i class="fa fa-exclamation-triangle"></i>
				<h1>email verification required</h1>
				<p>You must verify your email address before you can proceed to pay.</p>
			</div>
	<?php endif; ?>
	<?php else: ?>
			<div class="alert alert-default alert-icon">
				<i class="fa fa-exclamation-triangle"></i>
				<h1>Sorry, you cannot make a payment at this moment</h1>
				<p>It appears that you have already made a payment to GoldenRules that is either pending or has already been accepted by an administator.</p>
				<p class="mt-1">We only allow a one-time payment for the paid membership. Thus, once you make a payment and it's in the pending or accepted state, you can no longer make another one,</p>
				<p class="mt-1 txt-uppercase txt-bold">unless:</p>
				<p class="mt-1">Your payment gets denied by an administrator for any sort of reasons or after your refund request gets accepted.</p>
				<p class="mt-1">That's all.</p>
			</div>
	<?php endif; ?>
		</div>
	</div>
	<div class="txt-align-center mt-2">
		<small class="txt-dark-grey">&copy <?= date('Y') ?> <a href="/" class="link-grey link-no-underline" title="Back to Home"><?= SITE_NAME ?></a> - All Rights Reserved.</small>
	</div>
</div>