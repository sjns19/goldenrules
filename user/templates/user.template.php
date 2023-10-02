<?php if (!defined(basename(__FILE__) . '-included')) return http_response_code(404); extract((array) $this->page_data); extract((array) $this->user); ?>  
    <nav class="nav" id="nav">
      <header class="nav-header">
<?php if ($avatar_url != 'none'): ?>
        <img class="nav-avatar-img" src="/media/avatars/<?= $avatar_url ?>" alt="User profile icon" />
<?php else: ?>
        <div class="nav-avatar-letter" style="background-color:<?= $avatar_color ?>"><?= App::getFirstLetterFromName($name) ?></div>
<?php endif; ?>
        <div class="nav-header-contents">
          <p class="txt-gold txt-bold"><?= $name ?></p>
          <small>@<?= $username; ?></small>
        </div>
      </header>
      <section class="nav-body">
        <ul class="nav-list">
          <li class="nav-list-item">
            <a class="nav-link <?= ($tab['name'] != ucfirst(TAB_DASHBOARD) && $tab['name'] != 'Profile') ?: 'active' ?>" href="<?= $this->base_url ?>" title="<?= $is_admin ? 'Dashboard' : 'Profile' ?>">
          <?php if ($is_admin): ?>
              <i class="fa fa-bar-chart"></i>
          <?php else: ?>
              <i class="fa fa-user"></i>
          <?php endif; ?>
              <p><?= $is_admin ? 'Dashboard' : 'Profile' ?></p>
            </a>
          </li>
        <?php if ($is_admin): ?>
          <li class="nav-list-item nl-collapse" title="News">
            <a class="nav-link <?= ($tab['name'] != TAB_NEWS && $tab['name'] != TAB_POST_NEWS && $tab['name'] !=  TAB_EDIT_NEWS) ?: 'active' ?>" href="javascript:" title="Dashboard">
              <i class="fa fa-newspaper-o"></i>
              <p>News</p>
              <i class="fa fa-angle-down nl-collapse-indicator"></i>
            </a>
            <ul class="nl-collapse-inner">
              <li class="nl-collapse-item">
                <a class="nl-collapse-link <?= ($tab['name'] != TAB_POST_NEWS) ?: 'active' ?>" href="<?= $this->base_url ?>news?action=post" title="Post a news">Post New</a>
              </li>
              <li class="nl-collapse-item">
                <a class="nl-collapse-link <?= ($tab['name'] != TAB_NEWS && $tab['name'] != TAB_EDIT_NEWS) ?: 'active' ?>" href="<?= $this->base_url ?>news" title="View all news">View List</a>
              </li>
            </ul>
          </li>
        <?php endif; ?>

          <li class="nav-list-item nl-collapse">
            <a class="nav-link <?= ($tab['name'] != TAB_TRADING_ANALYSIS && $tab['name'] != TAB_POST_ANALYSIS && $tab['name'] !=  TAB_EDIT_ANALYSIS) ?: 'active' ?>" href="javascript:" title="Trading Analysis">
              <i class="fa fa-line-chart"></i>
              <p>Analysis</p>
              <i class="fa fa-angle-down nl-collapse-indicator"></i>
            </a>
            <ul class="nl-collapse-inner">
              <li class="nl-collapse-item">
                <a class="nl-collapse-link <?= ($tab['name'] != TAB_POST_ANALYSIS) ?: 'active' ?>" href="<?= $this->base_url ?>trading-analysis?action=post" title="Post a trading analysis">Post New</a>
              </li>
              <li class="nl-collapse-item">
                <a class="nl-collapse-link <?= ($tab['name'] != TAB_TRADING_ANALYSIS && $tab['name'] != TAB_EDIT_ANALYSIS) ?: 'active' ?>" href="<?= $this->base_url ?>trading-analysis" title="View all trading analysis">View List</a>
              </li>
            </ul>
          </li>
        <?php if ($is_admin): ?>
          <li class="nav-list-item">
            <a class="nav-link <?= ($tab['name'] != TAB_QUOTES) ?: 'active' ?>" href="<?= $this->base_url ?>quotes" title="Quotes section">
              <i class="fa fa-quote-right"></i>
              <p>Quotes</p>
            </a>
          </li>
          <li class="nav-list-item">
            <a class="nav-link <?= ($tab['name'] != TAB_USERS && $tab['name'] != 'user info') ?: 'active' ?>" href="<?= $this->base_url ?>users" title="All registered users">
              <i class="fa fa-users"></i>
              <p>Users</p>
            </a>
          </li>
          <li class="nav-list-item nl-collapse">
            <a class="nav-link <?= ($tab['name'] != 'Admin Activity Logs' && $tab['name'] != 'User Activity Logs') ?: 'active' ?>" href="javascript:" title="Activity logs">
              <i class="fa fa-sticky-note-o"></i>
              <p>Logs</p>
              <i class="fa fa-angle-down nl-collapse-indicator"></i>
            </a>
            <ul class="nl-collapse-inner">
              <li class="nl-collapse-item">
                <a class="nl-collapse-link <?= ($tab['name'] != 'Admin Activity Logs') ?: 'active' ?>" href="<?= $this->base_url ?>logs?type=admin" title="Admin activity logs">Admin Logs</a>
              </li>
              <li class="nl-collapse-item">
                <a class="nl-collapse-link <?= ($tab['name'] != 'User Activity Logs') ?: 'active' ?>" href="<?= $this->base_url ?>logs?type=user" title="User activity logs">User Logs</a>
              </li>
            </ul>
          </li>
        <?php endif; if ($paid_membership && !$is_mentor): ?>
          <li class="nav-list-item">
            <a class="nav-link <?= ($tab['name'] != TAB_MENTORS) ?: 'active' ?>" href="<?= $this->base_url ?>mentors" title="Mentors">
              <i class="fa fa-graduation-cap"></i>
              <p>Mentors</p>
            </a>
          </li>
        <?php endif; ?>
          <li class="nav-list-item">
            <a class="nav-link <?= ($tab['name'] != TAB_SETTINGS) ?: 'active' ?>" href="<?= $this->base_url ?>settings" title="Settings">
              <i class="fa fa-cog"></i>
              <p>Settings</p>
            </a>
          </li>

        <?php if ($is_admin): ?>
          <li class="nav-list-item">
            <a class="nav-link <?= ($tab['name'] != TAB_PAYMENTS) ?: 'active' ?>" href="<?= $this->base_url ?>payments" title="User payments">
              <i class="fa fa-dollar"></i>
              <p>Payments</p>
      <?php if ($this->pending_payments && $this->pending_payments > 0): ?>
              <span class="nav-link-badge" title="There are <?= $this->pending_payments ?> pending payments"><?= $this->pending_payments ?></span>
      <?php endif; ?>
            </a>
          </li>
          <li class="nav-list-item">
            <a class="nav-link <?= ($tab['name'] != TAB_STUDENTS) ?: 'active' ?>" href="<?= $this->base_url ?>students" title="Your students">
              <i class="fa fa-graduation-cap"></i>
              <p>Students</p>
      <?php if ($this->count_students && $this->count_students > 0): ?>
              <span class="nav-link-badge" title="You currently have <?= $this->count_students ?> students"><?= $this->count_students ?></span>
      <?php endif; ?>
            </a>
          </li>
        <?php endif; ?>
        </ul>
      </section>
    </nav>
    <div class="nav-backdrop" id="nav-backdrop"></div>
    <div class="content-wrapper">
      <div class="panel">
        <div class="panel-header">
          <i class="fa fa-bars nav-toggler" id="nav-toggler"></i>
          <h1 class="txt-gold"><?= ($tab['name'] !== TAB_NOT_FOUND) ? str_replace('-', ' ', $tab['name']) : '' ?></h1>
        </div>
        <div class="panel-body">
        <?php if (!$email_verified): ?>
          <div class="mb-2">
            <div class="alert alert-warning alert-icon">
              <i class="fa fa-exclamation-circle"></i>
              <h3>your email is not verified yet</h3>
              <p>You must verify your email address before you can continue to use your account.</p>
              <p>Did not get verification email?</p>
              <button class="btn btn-md mt-1" id="resend-email-btn" title="Click to resend verification email">Resend Email</button>
              <p class="mt-1"><i class="fa fa-exclamation-triangle"></i> It is recommended to use this feature ONLY IF you did not receive the verification email. Please do not spam the feature.</p>
            </div>
          </div>
        <?php endif; ?>
          <?php include $tab['template'] ?>
        </div>
        <div class="panel-footer">
          <button class="floating-btn floating-btn-danger" id="logout-btn" title="Logout" data-continue="/user/logout?continue=<?= urlencode(SITE_URL) ?>"><i class="fa fa-sign-out"></i></button>
          <p class="mb-1">&copy <?= date('Y') ?> GoldenRules - All Rights Reserved</p>
          <a href="/" class="link-gold link-no-underline txt-sm txt-uppercase" title="Go back to the main website">back to the website</a>
        </div>
      </div>
    </div>