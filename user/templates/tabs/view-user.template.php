
<?php extract((array) $this->user_data); ?>
<div class="my-1">
  <div class="flex flex-centered">
    <div class="avatar avatar-md" style="background-color: <?= $avatar_color ?>;">
<?php if ($avatar_url != 'none'): ?>
      <img class="avatar-img" src="/media/admin-avatars/<?= $avatar_url ?>" alt="<?= $name ?>'s profile picture">
<?php else: ?>
      <div class="avatar-no-img" style="background-color:<?= $avatar_color ?>"><?= App::getFirstLetterFromName($name) ?></div>
<?php endif; ?>
    </div>
    <h3 class="ml-1 txt-gold"><?= $name ?></h3>
  </div>
  <div class="my-1 txt-grey">
    <table class="table-user-stats">
      <tr>
        <td><strong>Account ID</strong></td>
        <td>GRT-<?= $id ?></td>
      </tr>
      <tr>
        <td><strong>Username</strong></td>
        <td><?= $username ?></td>
      </tr>
      <tr>
        <td><strong>Email</strong></td>
        <td><?= $email ?></td>
      </tr>
      <tr>
        <td><strong>Joined Date</strong></td>
        <td><?= date('M j, Y', $joined_date_unix) ?> - <?= App::getAgoFromUnix($joined_date_unix) ?></td>
      </tr>
      <tr>
        <td><strong>Has Email Verified</strong></td>
        <td><?= $email_verified ? 'Yes' : 'No' ?></td>
      </tr>
      <tr>
        <td><strong>Is Paid Member</strong></td>
        <td><?= $paid_membership ? 'Yes' : 'No' ?></td>
      </tr>
      <tr>
        <td><strong>Is Administrator</strong></td>
        <td><?= $is_admin ? 'Yes' : 'No' ?></td>
      </tr>
      <tr>
        <td><strong>Location</strong></td>
        <td id="user-location" data-ip="<?= $ip ?>">
          <div class="spinner spinner-sm"></div>
        </td>
      </tr>
      <tr>
        <td><strong>IP Address</strong></td>
        <td><?= $ip ?></td>
      </tr>
      <tr>
        <td><strong>Last Recorded IP Addr.</strong></td>
        <td><?= $last_logged_ip ?></td>
      </tr>
      <tr>
        <td><strong>Last Active</strong></td>
        <td><?= date('M j, Y', $last_active_unix) ?></td>
      </tr>
    </table>
    <div class="alert alert-info alert-icon">
      <i class="fa fa-exclamation-circle"></i>
      <h1>heads up</h1>
      <p>The location of the user is fetched based on their <strong>IP Address</strong>, it is fetched when the user creates their account and is stored like that permanently.</o>
      <p>The <strong>Last Recorded IP Address</strong> gets updated every time the user logs in. These IP addresses may not be accurate sometimes, specially when the user is using VPN, thus, the location may not be 100% accurate.</p>
    </div>
  </div>
</div>