<?php

# Is maintenance mode
$is_maintenance_mode = false;

# Mail address
define('SERVER_MAIL_ADDRESS', 'noreply@goldenrulestrade.com');

# Site support email
define('SITE_SUPPORT_EMAIL', 'support@goldenrulestrade.com');

# Refund email
define('SITE_REFUND_EMAIL', 'refund@goldenrulestrade.com');

# Main site name
define('SITE_NAME', 'GoldenRules');

# Site description
define('SITE_DESCRIPTION', 'Helping individual traders gain the correct knowledge in understanding the art of investing');

# Site keywords
define('SITE_KEYWORDS', 'forex,forex trading,forex market,stock exchange,stock market,investment strategy,business,forex news,stock news,risk management');

# Root URL of the site
define('SITE_URL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME']);

# Assets paths
define('CSS_PATH', '/src/css/');
define('JS_PATH', '/src/js/');
define('ICONS_PATH', '/src/icons/');
define('IMAGES_PATH', '/src/images/');
define('AVATAR_PATH', '/media/avatars/');
define('THUMBNAIL_PATH', '/media/thumbnails/');

# The folder containing the templates for the main site
define('DEFAULT_TEMPLATES_DIR', __DIR__ . '/templates/');

# The folder containing the templates for the user area
define('USER_TEMPLATES_DIR', __DIR__ . '/user/templates/');

# The folder containing the templates for the admin panel.
define('ADMIN_TEMPLATES_DIR', __DIR__ . '/admin/templates/');

/**
 * The page name's prefix in the URL for the GET variable.
 * Used by the dynamic page system. The displaypage is
 * for the first page like goldenrulestrade.com/pagename/
 * and the sub is secondary like goldenrulestrade.com/pagename/subpage/
 */
define('PAGE_URL_PREFIX', 'displaypage');
define('SUB_PAGE_PREFIX', 'sub');

# Page names
define('PAGE_DEFAULT', 'default'); # For the index
define('PAGE_NEWS', 'news');
define('PAGE_TRADING_ANALYSIS', 'trading-analysis');
define('PAGE_ABOUT', 'about');
define('PAGE_SINGLE_NEWS', 'single-news');
define('PAGE_LOGIN', 'login');
define('PAGE_SIGNUP', 'signup');
define('PAGE_GOLDENSTRATEGY', 'golden-strategy');

/**
 * Page does not actually exist, we just need the constant 
 * defined in order for other sub pages to work so the URL
 * would be like goldenrulestrade.com/legal/privacy-policy/
 */
define('PAGE_LEGAL', 'legal');

# Sub pages
define('SUB_PAGE_TAC', 'terms-and-conditions');
define('SUB_PAGE_PRIVACY_POLICY', 'privacy-policy');
define('SUB_PAGE_REFUND_POLICY', 'refund-policy');

# Page names for the user section

# For the forgot password page where they enter the email address
define('PAGE_PASSWORD_RECOVER', 'password-recover');

# After the user clicks the reset password link sent to their email
define('PAGE_RESET_PASSWORD', 'reset-password');

# After the user clicks the verify email link sent to their email
define('PAGE_VERIFY_EMAIL', 'verify-email');

# After the user clicks the change email link sent to their email
define('PAGE_CHANGE_EMAIL', 'change-email');

# For the user logout script
define('PAGE_LOGOUT', 'logout');

# For the paid membership
define('PAGE_PAID_MEMBERSHIP', 'paid-membership');
define('PAGE_CHECKOUT', 'checkout');

# for the user profile's tabs
define('TAB_DASHBOARD', 'dashboard');
define('TAB_NEWS', 'news');
define('TAB_POST_NEWS', 'post-news');
define('TAB_EDIT_NEWS', 'edit-news');
define('TAB_TRADING_ANALYSIS', 'trading-analysis');
define('TAB_POST_ANALYSIS', 'post-analysis');
define('TAB_EDIT_ANALYSIS', 'edit-analysis');
define('TAB_MENTORS', 'mentors');
define('TAB_SETTINGS', 'settings');
define('TAB_PAYMENTS', 'payments');
define('TAB_STUDENTS', 'students');
define('TAB_QUOTES', 'quotes');
define('TAB_USERS', 'users');
define('TAB_VIEW_USER', 'view-user');
define('TAB_NOT_FOUND', 'not-found');
define('TAB_LOGS', 'logs');
define('TAB_USER_LOGS', 'user-logs');
define('TAB_ADMIN_LOGS', 'admin-logs');