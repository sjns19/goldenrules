<?php if ($this->web_stats_count): ?>
  <strong class="txt-grey mb-1 pb-1">Site Statistics</strong>
  <div class="flex flex-justify-space-between mt-2 mb-4 pb-1">
    <div class="block flex align-items-center flex-no-break p-1">
      <i class="fa fa-newspaper-o txt-lg txt-gold"></i>
      <div class="ml-2">
        <h3><?= number_format($this->web_stats_count['total_news']) ?></h3>
        <p class="txt-grey txt-uppercase txt-sm">news posted</p>
      </div>
    </div>
    <div class="block flex align-items-center flex-no-break p-1">
      <i class="fa fa-users txt-lg txt-gold"></i>
      <div class="ml-2">
        <h3><?= number_format($this->web_stats_count['total_users']) ?></h3>
        <p class="txt-grey txt-uppercase txt-sm">verified users</p>
      </div>
    </div>
    <div class="block flex align-items-center flex-no-break p-1">
      <i class="fa fa-bar-chart txt-lg txt-gold"></i>
      <div class="ml-2">
        <h3><?= number_format($this->web_stats_count['total_trading_analysis']) ?></h3>
        <p class="txt-grey txt-uppercase txt-sm">trading analysis posted</p>
      </div>
    </div>
  </div>
<?php endif; ?>
<strong class="txt-grey mb-1 pb-1">Your Profile Overview</strong>
<div class="txt-grey mt-1 pt-1">
  <div class="mb-2">
    <div class="flex align-items-center flex-no-break">
      <i class="fa fa-user-circle txt-gold txt-mdl" style="width:20px"></i>
      <div class="ml-2">
        <p><strong>Username</strong></p>
        <small>@<?= $username ?></small>
      </div>
    </div>
  </div>
  <div class="mb-2">
    <div class="flex align-items-center flex-no-break">
      <i class="fa fa-envelope txt-gold txt-mdl" style="width:20px"></i>
      <div class="ml-2">
        <p><strong>Email</strong></p>
        <small><?= $email ?> <?php if (!$email_verified): ?> <span class="txt-danger">(not verified)</span> <?php endif; ?></small>
      </div>
    </div>
  </div>
  <div class="mb-2">
    <div class="flex align-items-center flex-no-break">
      <i class="fa fa-calendar txt-gold txt-mdl" style="width:20px"></i>
      <div class="ml-2">
        <p><strong>Joined</strong></p>
        <small><?= date('M j, Y', $joined_date_unix) . ', ' . App::getAgoFromUnix($joined_date_unix) ?></small>
      </div>
    </div>
  </div>
  <div class="mb-2">
    <div class="flex align-items-center flex-no-break">
      <i class="fa fa-user-plus txt-gold txt-mdl" style="width:20px"></i>
      <div class="ml-2">
        <p><strong>Paid Member</strong></p>
        <small><?= (!$paid_membership) ? 'No (<a href="/user/paid-membership/checkout" class="link-gold no-underline" title="Click to start your payment process for GoldenRules paid membership.">become a paid member now</a>)' : 'Yes' ?></small>
      </div>
    </div>
  </div>
</div>