<?php if (!defined(basename(__FILE__) . '-included')) return http_response_code(404); ?>
	<div class="wrapper p-3">
		<div class="card card-md card-grey card-centered card-gold-bordered p-2">
			<div class="card-header txt-align-center">
				<p class="txt-gold txt-uppercase mt-1">under maintenance</p>
        <hr class="hr-gradient-gold" />
			</div>
			<div class="card-body">
        <div class="txt-align-center">
				  <h4 class="txt-white">Dear <?= SITE_NAME ?> members,</h4>
          <p class="txt-grey mb-1">The website is currently under
             maintenance. We will be back soon as possible, please be patient.</p>
          <p class="txt-grey txt-sm">We hope you understand. Have a nice day!</p>
        </div>
			</div>
		</div>
	</div>