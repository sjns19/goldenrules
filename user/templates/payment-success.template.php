<?php if (!defined(basename(__FILE__) . '-included')) return http_response_code(404); ?>

    <div class="wrapper p-3">
      <div class="card card-lg card-dark-grey card-centered card-gold-bordered p-2">
        <div class="card-header txt-align-center">
          <h1 class="txt-gold txt-uppercase txt-grad-gold">thank you!</h1>
        </div>
        <div class="card-body">
          <div class="mb-1">
            <p class="txt-grey">Your payment to GoldenRules to become a paid member was successful and is currently pending. An administrator will take action on it as soon as they become aware of this payment.</p>
            <div class="my-2">
              <h3>Your payment details</h3>
              <div class="txt-grey">
              <h3 class="mt-1">Transaction ID</h3>
                <p class="mb-1"><?= $this->user_payment_data['transaction_id'] ?></p>
                <h3 class="mt-1">Name</h3>
                <p class="mb-1"><?= $this->user_payment_data['payer_name'] ?></p>
                <h3 class="mt-1">Email</h3>
                <p class="mb-1"><?= $this->user_payment_data['payer_email'] ?></p>
                <h3 class="mt-1">Amount</h3>
                <p class="mb-1">$<?= $this->user_payment_data['amount'] ?> USD</p>
                <h3 class="mt-1">Date</h3>
                <p class="mb-1"><?= $this->user_payment_data['paid_date'] ?></p>
              </div>
            </div>
            
            <div class="my-2">
              <h3>What's next?</h3>
              <p class="txt-grey mt-1">You will gain access to your paid membership only after an administrator reviews and approves your payment. Be patient while they take an action.</p>
              <p class="txt-grey mt-1">You will be notified whether your payment has been approved or denied via the email address associated with your account.</p>
              <p class="txt-grey mt-1">The estimated time for an administrator to review your payment depends on how soon they are being aware of the payment. If none of the actions have been taken within 24 hours, please contact us at <a href="mailto:<?= SITE_SUPPORT_EMAIL ?>" class="link-gold"><?= SITE_SUPPORT_EMAIL ?></a>.</p>
            </div>
          </div>
          <a href="/" class="link-gold link-no-underline txt-sm txt-uppercase" title="Go back to home page">back to home</a>
        </div>
      </div>
      <div class="txt-align-center mt-2">
        <small class="txt-grey">&copy <?= date('Y') ?> <a href="/" class="link-grey link-no-underline" title="Back to Home"><?= SITE_NAME ?></a> - All Rights Reserved.</small>
      </div>
    </div>