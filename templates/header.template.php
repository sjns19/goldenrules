<?php if (!defined(basename(__FILE__) . '-included')) return http_response_code(404); ?>

<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
    <title><?= $this->page_data['title'] ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="msapplication-TileColor" content="#222222">
    <meta name="msapplication-TileImage" content="<?= ICONS_PATH ?>ms-icon-144x144.png">
    <meta name="description" content="<?= $this->page_data['description'] ?>" />
    <meta name="keywords" content="<?= SITE_KEYWORDS ?>" />

    <link rel="icon" sizes="192x192" href="<?= ICONS_PATH ?>favicon.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= ICONS_PATH ?>apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?= ICONS_PATH ?>apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?= ICONS_PATH ?>apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= ICONS_PATH ?>apple-icon-180x180.png">
    
<!-- Global site tag (gtag.js) - Google Analytics -->
    <!--<script async src="https://www.googletagmanager.com/gtag/js?id=G-4R6DWYZWB7"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-4R6DWYZWB7');
    </script>-->

<!-- Facebook Open Graph tags -->
    <meta property="og:title" content="<?= $this->page_data['og']['title'] ?>" />
    <meta property="og:description" content="<?= $this->page_data['description'] ?>" />
    <meta property="og:image" content="<?= $this->page_data['og']['image'] ?>" />
    <meta property="og:site_name" content="<?= SITE_NAME ?>" />
    <meta property="og:url" content="<?= $this->page_data['og']['url'] ?>" />
    <meta property="og:type" content="<?= $this->page_data['og']['type'] ?>" />
<!-- -->
<!-- Twitter card tags -->
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:domain" content="goldenrulestrade.com"/>
    <meta name="twitter:title" itemprop="name" content="<?= $this->page_data['title'] ?>" />
    <meta name="twitter:description" property="og:description" itemprop="description" content="<?= $this->page_data['description'] ?>" />
<!-- -->
    <meta name="theme-color" content="#151515" />
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600|Source+Serif+Pro:300,400,800" rel="stylesheet">
    <link rel="manifest" href="<?= ICONS_PATH ?>manifest.json">
    <link rel="stylesheet" href="<?= CSS_PATH ?>icons.css" />
    <link rel="stylesheet" href="<?= CSS_PATH ?>layout.min.css?<?= time() ?>" />
    <noscript>
      <div class="sticky-banner align-center" style="display:block">
        <span class="txt-gold"><?= SITE_NAME ?></span> works better with JavaScript enabled browsers.
      </div>
    </noscript>
  </head>
  <body class="body">
<?php if (!defined('HIDE_HEADER_AND_FOOTER')): # Don't show navbar for the login, registration and some other pages ?>
    <nav class="navbar <?php if (isset($this->page_data['is_homepage'])) echo 'navbar-absolute' ?>">
      <div class="wrapper">
        <div class="navbar-container">
          <div class="navbar-inner">
            <div class="nav-toggler" id="navbar-toggler">
              <span class="nav-toggler-line"></span>
              <span class="nav-toggler-line"></span>
              <span class="nav-toggler-line"></span>
            </div>
            <div class="navbar-backdrop" id="navbar-backdrop"></div>
            <a href="/" class="navbar-brand" title="<?= SITE_NAME; ?>">
              <img src="<?= ICONS_PATH ?>logo.png" class="navbar-brand-icon" alt="<?= SITE_NAME ?>" />
            </a>
            <div class="navbar-nav" id="navbar">
              <a class="nav-link" href="/" title="Home">Home</a>
              <a class="nav-link" href="/news" title="News">News</a>
              <a class="nav-link" href="/about" title="About Us">About</a>
              <a class="nav-link" href="/trading-analysis" title="Trading Analysis">Analysis</a>
            </div>
          </div>
          <div class="nav-user">
<?php if (!$this->user_logged_in): # Don't show these buttons if the user is logged in ?>
            <div class="nav-user-buttons">
              <a href="/login" class="btn btn-gold btn-md txt-uppercase" title="Login to your <?= SITE_NAME ?> account">login</a>
              <a href="/signup" class="btn btn-gold-o btn-md txt-uppercase" title="Sign up to get started">sign up</a>
            </div>
            <div class="nav-profile-icon -m-visible-inline dropdown-toggle" data-target="#login-or-register" title="Login or register">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ccc" width="24px" height="24px">
                <path d="M0 0h34v24H0z" fill="none"/>
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
              </svg>
            </div>
            <div class="dropdown dropdown-right" id="login-or-register">
              <div class="dropdown-contents">
                <a href="/login" class="btn btn-gold btn-sm btn-block txt-uppercase" title="Login to your <?= SITE_NAME ?> account">login</a>
                <a href="/signup" class="btn btn-gold-o btn-sm btn-block txt-uppercase" title="Sign up to get started">sign up</a>
              </div>
            </div>
<?php else: # Show the user profile picture with dropdown menu for actions if the user is logged in ?>
            
    <?php if ($this->user['avatar_url'] != 'none'): ?>
            <div class="avatar avatar-sm avatar-bordered avatar-hoverable dropdown-toggle" data-target="#user-dropdown" title="Logged in as <?= $this->user['name'] ?>">
              <img class="avatar-img" src="/media/avatars/<?= $this->user['avatar_url'] ?>" alt="<?= $this->user['username'] ?>" />
            </div>
    <?php else: ?>
            <div class="nav-profile-icon dropdown-toggle" data-target="#user-dropdown" title="Logged in as <?= $this->user['name'] ?>">
              <span class="nav-user-letter" style="background-color: <?= $this->user['avatar_color'] ?>"><?= $this->user['first_letter'] ?></span>
            </div>
    <?php endif; ?>
            <div class="dropdown dropdown-right" id="user-dropdown">
              <div class="dropdown-contents">
                <p class="dropdown-title txt-gold">@<?= $this->user['username'] ?></p>
                <hr />
                <a href="/user/<?= $this->user['username'] ?>" class="dropdown-link" title="Go to your Profile">
                  <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-icon" viewBox="0 0 24 24" fill="#aaa" width="18px" height="18px">
                    <path d="M0 0h34v24H0z" fill="none" />
                    <path d="M12 5.9c1.16 0 2.1.94 2.1 2.1s-.94 2.1-2.1 2.1S9.9 9.16 9.9 8s.94-2.1 2.1-2.1m0 9c2.97 0 6.1 1.46 6.1 2.1v1.1H5.9V17c0-.64 3.13-2.1 6.1-2.1M12 4C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 9c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4z" />
                  </svg>
                  Profile
                </a>
                <a href="/user/logout?continue=<?= urlencode(SITE_URL) ?>" class="dropdown-link" title="Log out of your account">
                  <svg xmlns="http://www.w3.org/2000/svg" class="dropdown-icon" viewBox="0 0 24 24" fill="#aaa" width="18px" height="18px">
                    <path d="M0 0h34v24H0z" fill="none" />
                    <path d="M13 3h-2v10h3V3zm4.83 2.17l-1.42 1.42C17.99 7.86 19 9.81 19 12c0 3.87-3.13 7-7 7s-7-3.13-7-7c0-2.19 1.01-4.14 2.58-5.42L6.17 5.17C4.23 6.82 3 9.26 3 12c0 4.97 4.03 9 9 9s9-4.03 9-9c0-2.74-1.23-5.18-3.17-6.83z" />
                  </svg>  
                  Logout
                </a>
              </div>
            </div>
<?php endif; ?>
          </div>
        </div>
      </div>
    </nav>
<?php endif; ?>
<?php if (defined('ERR_PAGE_NOT_FOUND')): ?>
    <div class="wrapper">
      <div class="card txt-align-center">
        <div class="card-header">
          <h1 class="txt-gold">:(</h1>
        </div>
        <div class="card-body">
          <h3>404 - Page not found</h3>
          <p class="txt-grey">The link you followed may be broken or the page no longer exists.</p>
        </div>
        <div class="card-footer">
          <a href="/" class="link-gold link-no-underline txt-uppercase" title="Go back to home page">back to home</a>
        </div>
      </div>
    </div>
<?php endif; ?>