<?php if (!defined(basename(__FILE__) . '-included')) return http_response_code(404); ?>

<!DOCTYPE html>
<html class="root" lang="en-US">
	<head>
		<meta charset="utf-8" />
		<title><?= $this->page_data['title'] ?></title>

<?php if (!$this->device_no_fit): # Don't make device responsive if this value is set to true from certain pages ?>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0" />
<?php endif; ?>

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="<?= ICONS_PATH ?>ms-icon-144x144.png">
		<meta name="theme-color" content="#000000" />

		<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Saira+Condensed:100,200,400" rel="stylesheet">
		<link href="//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<link rel="icon" sizes="192x192" href="<?= ICONS_PATH ?>favicon.png">
		<link rel="apple-touch-icon" sizes="76x76" href="<?= ICONS_PATH ?>apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="120x120" href="<?= ICONS_PATH ?>apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="152x152" href="<?= ICONS_PATH ?>apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="<?= ICONS_PATH ?>apple-icon-180x180.png">

<?php if ($this->user_logging_out): ?>
    	<meta http-equiv="refresh" content="1;url=<?= $this->continue ?>" />
<?php endif; ?>

    	<link rel="manifest" href="<?= ICONS_PATH ?>manifest.json">
<?php if (isset($this->page_data['name']) && $this->page_data['name'] === PAGE_CHECKOUT): # Load stripe's CSS ?>
    	<link rel="stylesheet" href="/user/src/css/stripe.css" />
<?php endif; ?>
		<link rel="stylesheet" href="/user/src/trumbowyg/ui/trumbowyg.min.css" />
		<link rel="stylesheet" href="/user/src/css/croppie.min.css" />
		<link rel="stylesheet" href="/user/src/css/user-profile.min.css?<?= time() ?>" />
		<link rel="stylesheet" href="/user/src/css/country-flags.min.css" />

<?php if ($this->user): ?>
		<style>
		.input {
			margin-top: .5rem !important;
		}
		.input,
		.textarea {
			padding: 1rem !important;
			border-radius: 5px !important;
		}

		.input-sub-label {
			margin-left: 0 !important;
		}
		</style>
<?php endif; ?>

<?php if ($this->editable_analysis_data): ?>
    	<script>window._EDITABLE_ANALYSIS_DATA = <?= json_encode($this->editable_analysis_data) ?>;</script>
<?php endif; ?>

<?php if ($this->editable_news_data): ?>
		<script>window._EDITABLE_NEWS_DATA = <?= json_encode($this->editable_news_data) ?>;</script>
<?php endif; ?>
		<noscript>
			<div class="sticky-banner align-center" style="display: block;">
			<span class="txt-gold"><?= SITE_NAME ?></span> works better with JavaScript enabled browsers.
			</div>
		</noscript>
	</head>
  	<body class="body">

<?php if (defined('ERR_PAGE_NOT_FOUND')): # Show the following markup if the 404 page is triggered ?>
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