<?php

include_once 'defines.php';
require_once 'autoload.php';

$app = new App();

$page_name = strtolower(!empty($_GET[PAGE_URL_PREFIX]) ? $_GET[PAGE_URL_PREFIX] : PAGE_DEFAULT);
$page_sub = !empty($_GET[SUB_PAGE_PREFIX]) ? strtolower($_GET[SUB_PAGE_PREFIX]) : null;
$page_data = [];

session_start();

if ($is_maintenance_mode) {
	$page_data = [
		'title' => SITE_NAME,
		'description' => SITE_DESCRIPTION,
		'og' => [
			'title' => SITE_NAME,
			'url' => SITE_URL,
			'image' => SITE_URL . ICONS_PATH . 'og_logo.jpg',
			'type' => 'website'
		],
		'is_homepage' => true
	];
	$page_name = 'maintenance';
	define('HIDE_HEADER_AND_FOOTER', true);
} else {
	if (isset($_SESSION['grt_user']['logged_in'])) {
		$user_data = $_SESSION['grt_user'];
		$app->user = [
			'name' => $user_data['name'],
			'uid_token' => $user_data['uid_token'],
			'avatar_color' => $user_data['avatar_color'],
			'first_letter' => $user_data['first_letter'],
			'username' => $user_data['username'],
			'email' => $user_data['email'],
			'avatar_url' => $user_data['avatar_url']
		];
		$app->user_logged_in = $_SESSION['grt_user']['logged_in'];
	}
	
	if (!strpos($_SERVER['REQUEST_URI'], 'index.php')) {
		switch ($page_name) {
			case PAGE_DEFAULT: {
				if (is_null($page_sub)) {
					$news_data = [];
					$page_data = [
						'title' => SITE_NAME,
						'description' => SITE_DESCRIPTION,
						'og' => [
							'title' => SITE_NAME,
							'url' => SITE_URL,
							'image' => SITE_URL . ICONS_PATH . 'og_logo.jpg',
							'type' => 'website'
						],
						'is_homepage' => true
					];

					$db = new Database();
					$connection = $db->connect();

					$quotes = new Quotes($connection);
					$news = new News($connection);
					$news_list = $news->readAll(null, 3);
					
					foreach ($news_list as $key => $val) {
						if (!empty($val)) {
							$news_data[$key] = [
								'author' => $val['author'],
								'author_first_letter' => $val['author_first_letter'],
								'author_avatar_url' => $val['author_avatar_url'] !== 'none' ? SITE_URL . AVATAR_PATH . $val['author_avatar_url'] : null,
								'author_avatar_color' => $val['author_avatar_color'],
								'title' => App::ellipsis($val['title'], 33),
								'url' => '/news/' . $val['url_title'],
								'posted_date' => 'By ' . $val['author'] . ', ' . App::getAgoFromUnix($val['posted_date_unix'])
							];
						}
					}

					$app->news = $news_data;
					$app->quote = $quotes->getRandom();
					$app->user_count = $app->countUsers($connection);
					$db->close();

					$app->mentorship_items = [
						[
							'title' => 'Risk management skill',
							'paragraph' => 'At GoldenRules, we effectively teach Proper Risk Management to minimize loss because not everyday will be profitable and you want to be prepared.',
							'icon' => 'risk-management-skill'
						],
						[
							'title' => 'Psychology training',
							'paragraph' => 'With our psychology training, you can acquire the knowledge that allows new traders to consistently gain profits from the markets by understanding the spiritual side of trading and how to use it to their advantage.',
							'icon' => 'psychology-training'
						],
						[
							'title' => 'Technical analysis',
							'paragraph' => 'With technical analysis being a key element in mastering the concept of market analysis, we help new and experienced traders with when and where to get in and out of trades.',
							'icon' => 'technical-analysis'
						],
						[
							'title' => 'Wealth creation',
							'paragraph' => 'We offer other investment opportunities for members to capitalize on to help achieve their financial goals.',
							'icon' => 'wealth-creation'
						],
						[
							'title' => 'Trading strategy',
							'paragraph' => 'Stop guessing what to do. Instead, familiarize yourself with a proven strategy that allows you to trade a system that is proven to generate consistent profits.',
							'icon' => 'trading-strategy'
						],
						[
							'title' => 'Trading community',
							'paragraph' => 'All investors started somewhere and got help along the way, this is not a coincidence. There are many advantages of being a part of a trading family as opposed to doing it alone',
							'icon' => 'trading-community'
						]
					];
				}
				break;
			}

			case PAGE_NEWS: {
				$db = new Database();
				$news = new News($db->connect());

				if (is_null($page_sub)) {
					$news_page = App::getCustomQueryParam('page');
					$page_data = [
						'title' => 'News - ' . SITE_NAME,
						'description' => 'Stay up to date with the latest news on Forex and Stock market, provided by ' . SITE_NAME,
						'og' => [
							'title' => 'News - ' . SITE_NAME,
							'url' => SITE_URL . '/news',
							'image' => SITE_URL . ICONS_PATH . 'og_logo.jpg',
							'type' => 'website'
						]
					];

					$page_index = (is_null($news_page) || !is_numeric($news_page)) ? 1 : $news_page;
					$limit_per_page = 9;
					$first_page_results = ($page_index - 1) * $limit_per_page;
					$total_news = $news->getTotal();
					$number_of_pages = (empty($total_news)) ? 1 : ceil($total_news / $limit_per_page);

					if ($page_index > $number_of_pages) {
						$page_data = [];
						$db->close();
						break;
					}

					$news_data = [];
					$og_url = SITE_URL . '/news';
					$page_data['og']['url'] = (is_null($news_page)) ? $og_url : $og_url . '/?page=' . $page_index;

					$news_list = $news->readAll($first_page_results, $limit_per_page);

					foreach ($news_list as $key => $val) {
						if (!empty($val)) {
							$body = App::sanitizeBody($val['body']);
							$body = App::ellipsis($body, 112);
							$news_data[$key] = [
								'author' => $val['author'],
								'author_first_letter' => $val['author_first_letter'],
								'author_avatar_url' => $val['author_avatar_url'] !== 'none' ? SITE_URL . AVATAR_PATH . $val['author_avatar_url'] : null,
								'author_avatar_color' => $val['author_avatar_color'],
								'title' => App::ellipsis($val['title'], 60),
								'body' => $body,
								'thumbnail_url' => SITE_URL . THUMBNAIL_PATH . 'news/' . $val['thumbnail_url'],
								'url' => '/news/' . $val['url_title'],
								'author' => $val['author'],
								'posted_date' => 'by ' . $val['author'] . ' on ' . date('M j, Y', $val['posted_date_unix'])
							];
						}
					}

					$app->news_list = $news_data;
					$app->number_of_pages = $number_of_pages;
					$app->active_page_index = $page_index;
					$db->close();
					break;
				}

				$url_title = filter_input(INPUT_GET, SUB_PAGE_PREFIX, FILTER_SANITIZE_STRING);
				$single_news = $news->readSingle($url_title);

				if (!empty($single_news)) {
					$tmp = $single_news;
					$title = $tmp['title'];
					$body = $tmp['body'];
					$description = App::sanitizeBody($body);

					$news_data = $tmp;
					$news_data['body'] = html_entity_decode($body);
					$news_data['description'] = $description;
					$news_data['share_url'] = urlencode(SITE_URL . '/news/' . $url_title);
					$news_data['author_avatar_url'] = ($tmp['author_avatar_url'] != 'none') ? SITE_URL . AVATAR_PATH . $tmp['author_avatar_url'] : null;

					$app->news_data = $news_data;

					$related_news_data = [];
					$related_news = $news->getRelated($tmp['id'], $title, $body);

					foreach ($related_news as $key => $val) {
						$related_title = App::ellipsis($val['title'], 60);
						$related_body = App::sanitizeBody($val['body']);
						$related_body = App::ellipsis($related_body, 112);
						$related_thumbnail_url = SITE_URL . THUMBNAIL_PATH . 'news/' . $val['thumbnail_url'];
						$related_url = '/news/' . $val['url_title'];
						$related_news_data[$key] = [
							'title' => $related_title,
							'body' => $related_body,
							'thumbnail_url' => $related_thumbnail_url,
							'url' => $related_url
						];
					}
					$page_data = [
						'title' => $title,
						'description' => App::ellipsis($description, 200),
						'og' => [
							'title' => $title,
							'url' => SITE_URL . '/news/' . $url_title,
							'image' => SITE_URL . THUMBNAIL_PATH . 'news/' . $tmp['thumbnail_url'],
							'type' => 'article'
						]
					];
					$page_name = 'sub/news-article';
					$app->related_news = $related_news_data;
				}
				$db->close();
				break;
			}

			case PAGE_TRADING_ANALYSIS: {
				$db = new Database();
				$analysis = new TradingAnalysis($db->connect());

				if (is_null($page_sub)) {
					$analysis_page = App::getCustomQueryParam('page');
					$page_data = [
						'title' => 'Trading Analysis - ' . SITE_NAME,
						'description' => 'Forex trading analysis by the ' . SITE_NAME . ' members',
						'og' => [
							'title' => 'Trading Analysis - ' . SITE_NAME,
							'url' => SITE_URL . '/trading-analysis',
							'image' => SITE_URL . ICONS_PATH . 'og_logo.jpg',
							'type' => 'website'
						]
					];

					$page_index = (is_null($analysis_page) || !is_numeric($analysis_page)) ? 1 : $analysis_page;
					$limit_per_page = 9;
					$first_page_results = ($page_index - 1) * $limit_per_page;
					$total_analysis = $analysis->getTotal();
					$number_of_pages = (empty($total_analysis)) ? 1 : ceil($total_analysis / $limit_per_page);

					if ($page_index > $number_of_pages) {
						$page_data = [];
						$db->close();
						break;
					}

					$analysis_data = [];
					$og_url = SITE_URL . '/trading-analysis';
					$page_data['og']['url'] = (is_null($analysis_page)) ? $og_url : $og_url . '/?page=' . $page_index;
					$analysis_list = $analysis->readAll($first_page_results, $limit_per_page);

					foreach ($analysis_list as $key => $val) {
						if (!empty($val)) {
							$analysis_data[$key] = [
								'author' => $val['author'],
								'author_first_letter' => $val['author_first_letter'],
								'author_avatar_url' => $val['author_avatar_url'] !== 'none' ? SITE_URL . AVATAR_PATH . $val['author_avatar_url'] : null,
								'author_avatar_color' => $val['author_avatar_color'],
								'title' => App::ellipsis($val['title'], 60),
								'thumbnail_url' => SITE_URL . THUMBNAIL_PATH . 'analysis/' . $val['thumbnail_url'],
								'url' => '/trading-analysis/' . $val['url_title'],
								'author' => $val['author'],
								'posted_date' => 'by ' . $val['author'] . ' on ' . date('M j, Y', $val['posted_date_unix']),
							];
						}
					}
					$app->analysis_list = $analysis_data;
					$app->number_of_pages = $number_of_pages;
					$app->active_page_index = $page_index;
					$db->close();
					break;
				}
				$url_title = filter_input(INPUT_GET, SUB_PAGE_PREFIX, FILTER_SANITIZE_STRING);
				$single_analysis = $analysis->readSingle($url_title);

				if (!empty($single_analysis)) {
					$tmp = $single_analysis;
					$title = $tmp['title'];
					$description = App::sanitizeBody($tmp['body']);
					$thumbnail = SITE_URL . THUMBNAIL_PATH . 'analysis/' . $tmp['thumbnail_url'];

					$analysis_data = $tmp;
					$analysis_data['body'] = html_entity_decode($tmp['body']);
					$analysis_data['description'] = $description;
					$analysis_data['thumbnail'] = $thumbnail;
					$analysis_data['author_avatar_url'] = $tmp['author_avatar_url'] !== 'none' ? SITE_URL . AVATAR_PATH . $tmp['author_avatar_url'] : null;
					$page_data = [
						'title' => $title,
						'description' => App::ellipsis($description, 200),
						'og' => [
							'title' => $title,
							'url' => SITE_URL . '/trading-analysis/' . $url_title,
							'image' => $thumbnail,
							'type' => 'article'
						]
					];
					$app->analysis_data = $analysis_data;
					$page_name = 'sub/analysis-article';
				}
				$db->close();
				break;
			}

			case PAGE_ABOUT: {
				if (is_null($page_sub)) {
					$page_data = [
						'title' => 'About - ' . SITE_NAME,
						'description' => 'GoldenRules is a group of young investors who are passionate about trading and devote their daily lives around the markets. Our goal is not to become a “guru” of trading, but actually help those in need of developing this skillset. We have spent thousands of hours and dollars in order to learn what we know now and ...',
						'og' => [
							'title' => 'About - ' . SITE_NAME,
							'url' => SITE_URL . '/about',
							'image' => SITE_URL . ICONS_PATH . 'og_logo.jpg',
							'type' => 'website'
						]
					];
				}
				break;
			}

			case PAGE_LEGAL: {
				if (is_null($page_sub))
					return header('location: /legal/terms-and-conditions');

				$title = '';
				$description = '';
				switch ($page_sub) {
					case SUB_PAGE_TAC:
						$title = 'Terms and Conditions - ' . SITE_NAME;
						$description = 'These terms and conditions outline the rules and regulations for the use of GoldenRules Investments’ Website, located at www.goldenrulestrade.com. By accessing this website, we assume you accept these terms and conditions. Do not continue to use GoldenRules if you do not agree to take all of the terms and conditions stated on this page.';
						break;
					case SUB_PAGE_PRIVACY_POLICY:
						$title = 'Privacy Policy - ' . SITE_NAME;
						$description = 'At GoldenRules, accessible from www.goldenrulestrade.com, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by GoldenRules and how we use it. This privacy policy applies only to our online activities and is valid for visitors to our website with regards to the information that they shared and/or collect in GoldenRules. This policy is not applicable to any information collected offline or via channels other than this website.';
						break;
					case SUB_PAGE_REFUND_POLICY:
						$title = 'Refund Policy - ' . SITE_NAME;
						$description = 'This Refund Policy outlines how you can request for a refund and how GoldenRules refunds your money after you have made a purchase.';
						break;
					default:
						break;
				}
				if ($title !== '') {
					$page_data = [
						'title' => $title,
						'description' => $description,
						'og' => [
							'title' => $title,
							'url' => SITE_URL . 'legal/' . $page_sub,
							'image' => SITE_URL . ICONS_PATH . 'og_logo.jpg',
							'type' => 'website'
						]
					];
					$page_name = 'sub/' . $page_sub;
				}
				break;
			}

			case PAGE_LOGIN: {
				if ($app->user_logged_in)
					return header('location: /');

				if (is_null($page_sub)) {
					$redirect_url = App::getCustomQueryParam('_refsrc');
					$ref_for = App::getCustomQueryParam('ref_for');
					$page_data = [
						'title' => SITE_NAME .' - Account Login',
						'description' => SITE_DESCRIPTION,
						'og' => [
							'title' => SITE_NAME .' - Account Login',
							'url' => SITE_URL . '/login',
							'image' => SITE_URL . ICONS_PATH . 'og_logo.jpg',
							'type' => 'website'
						]
					];

					if (!is_null($redirect_url) && $redirect_url == SITE_URL . '/user/paid-membership/checkout' && $ref_for == 'paid_membership') {
						$app->continue_msg = 'You must login to your ' . SITE_NAME . ' account before you can proceed to pay.';
					}
					define('HIDE_HEADER_AND_FOOTER', true);
				}
				break;
			}

			case PAGE_SIGNUP: {
				if ($app->user_logged_in)
					return header('location: /');

				if (is_null($page_sub)) {
					$page_data = [
						'title' => SITE_NAME .' - Account Registration',
						'description' => SITE_DESCRIPTION,
						'og' => [
							'title' => SITE_NAME .' - Account Registration',
							'url' => SITE_URL . '/signup',
							'image' => SITE_URL . ICONS_PATH . 'og_logo.jpg',
							'type' => 'website'
						]
					];
					define('HIDE_HEADER_AND_FOOTER', true);
				}
				break;
			}

			case PAGE_GOLDENSTRATEGY: {
				if (is_null($page_sub)) {
					$page_data = [
						'title' => SITE_NAME .' - GoldenStrategy',
						'description' => 'The GoldenStrategy is not about getting every trade correct, instead, it is more about maximizing profits on each trade that is placed to ensure that every trade has a good risk to reward ratio.',
						'og' => [
							'title' => SITE_NAME .' - GoldenStrategy',
							'url' => SITE_URL . '/golden-strategy',
							'image' => SITE_URL . ICONS_PATH . 'og_logo.jpg',
							'type' => 'website'
						]
					];
				}
				break;
			}

			default: {
				break;
			}
		}
	}
}

if (!empty($page_data)) {
	$app->page_data = $page_data;
} else {
	$app->pageNotFound();
}

$templates = (defined('ERR_PAGE_NOT_FOUND')) ? ['header', 'footer'] : ['header', $page_name, 'footer'];
$app->render($templates, DEFAULT_TEMPLATES_DIR);