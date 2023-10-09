<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once '../defines.php';
include_once '../autoload.php';

$app = new App();

$page_name = strtolower(!empty($_GET[PAGE_URL_PREFIX]) ? $_GET[PAGE_URL_PREFIX] : PAGE_DEFAULT);
$page_sub = !empty($_GET[SUB_PAGE_PREFIX]) ? strtolower($_GET[SUB_PAGE_PREFIX]) : null;
$page_data = [];

session_start();

if (!strpos($_SERVER['REQUEST_URI'], 'index.php')) {
	switch ($page_name) {
		case PAGE_DEFAULT: {
			if (is_null($page_sub)) {
				$to = (User::isLoggedIn()) ? '/user/' . $_SESSION['grt_user']['username'] : '/login';
				header('location: ' . $to);
			}
			break;
		}

		case PAGE_PASSWORD_RECOVER: {
			if (is_null($page_sub)) {
				$page_data['title'] = 'Account Recover - Enter your Email address | ' . SITE_NAME;
			}
			break;
		}

		case PAGE_RESET_PASSWORD: {
			if (is_null($page_sub)) {
				header('Cache-Control: no-store, must-revalidate, max-age=0');
				header('Pragma: no-cache');
				header('Expires: Sat, 26 July 1997 05:00:00 GMT');

				$reset_token = App::getCustomQueryParam('_rt'); // Page token
				$reset_for_token = App::getCustomQueryParam('_rft'); // User token

				if (!is_null($reset_token) && !is_null($reset_for_token)) {
					if (!filter_var($reset_token, FILTER_SANITIZE_STRING) || !filter_var($reset_for_token, FILTER_SANITIZE_STRING))
						return http_response_code(410);

					$db = new Database();
					$connection = $db->connect();

					$request = new PageRequest($connection);
					$request_data = $request->getData($reset_token);
					$tmp = $request_data['data'];

					if (empty($tmp) || $tmp['type'] !== PageRequest::REQUEST_TYPE_RESET_PASSWORD || $tmp['requested_for'] !== $reset_for_token) {
						$db->close();
						return http_response_code(410);
					}

					$request->token = $reset_token;

					if ($tmp['timestamp'] < time()) {
						$request->Delete();
						$db->close();
						return http_response_code(410);
					}

					$page_data['title'] = 'Change account password - ' . SITE_NAME;
					$app->reset_password_data = [
						'page_token' => $reset_token,
						'from_token' => $reset_for_token,
						'timestamp' => $tmp['timestamp']
					];
					$db->close();
				}
			}
			break;
		}

		case PAGE_VERIFY_EMAIL: {
			if (is_null($page_sub)) {
				header('Cache-Control: no-store, must-revalidate, max-age=0');
				header('Pragma: no-cache');
				header('Expires: Sat, 26 July 1997 05:00:00 GMT');

				$verification_token = App::getCustomQueryParam('_vt'); // Page token
				$verification_for_token = App::getCustomQueryParam('_vft'); // User token

				if (!is_null($verification_token) || !is_null($verification_for_token)) {
					if (!filter_var($verification_token, FILTER_SANITIZE_STRING) || !filter_var($verification_for_token, FILTER_SANITIZE_STRING))
						return http_response_code(410);

					$db = new Database();
					$connection = $db->connect();

					$request = new PageRequest($connection);
					$request_data = $request->getData($verification_token);
					$tmp = $request_data['data'];

					if (empty($tmp) || $tmp['type'] !== PageRequest::REQUEST_TYPE_VERIFY_EMAIL || $tmp['requested_for'] !== $verification_for_token) {
						$db->close();
						return http_response_code(410);
					}

					$user = new User($connection);
					$page_data['title'] = 'Email verification - ' . SITE_NAME;
					$user->uid_token = $verification_for_token;

					if ($app->verify_status = $user->updateEmailVerification()) {
						$request->token = $verification_token;
						$request->delete();
					}
					$db->close();
				}
			}
			break;
		}

		case PAGE_CHANGE_EMAIL: {
			if (is_null($page_sub)) {
				header('Cache-Control: no-store, must-revalidate, max-age=0');
				header('Pragma: no-cache');
				header('Expires: Sat, 26 July 1997 05:00:00 GMT');

				$change_email_token = App::getCustomQueryParam('_cet'); // Page token
				$change_email_for_token = App::getCustomQueryParam('_ceft'); // User token

				if (!is_null($change_email_token) || !is_null($change_email_for_token)) {
					if (!filter_var($change_email_token, FILTER_SANITIZE_STRING) || !filter_var($change_email_for_token, FILTER_SANITIZE_STRING))
						return http_response_code(410);

					$db = new Database();
					$connection = $db->connect();

					$request = new PageRequest($connection);
					$request_data = $request->getData($change_email_token);
					$tmp = $request_data['data'];

					if (empty($tmp) || $tmp['type'] !== PageRequest::REQUEST_TYPE_CHANGE_EMAIL || $tmp['requested_for'] !== $change_email_for_token) {
						$db->close();
						return http_response_code(410);
					}
					
					$request->token = $change_email_token;

					if ($tmp['timestamp'] < time()) {
						$request->Delete();
						$db->close();
						return http_response_code(410);
					}

					$user = new User($connection);

					$page_data['title'] = 'Change Email address - ' . SITE_NAME;
					$app->page_data = $page_data;
					$user->uid_token = $change_email_for_token;
					$emails = $user->updateEmail();
					$tmp = $user->getDataByEmail($emails['data']['temporary_email']);
					$app->emails = $emails;

					if (!empty($emails['data'])) {
						$request->Delete();

						$log = new ActivityLog($connection);
						$log->text = 'Email address was changed from <strong>' . $emails['data']['email'] . '</strong> to <strong>' . $emails['data']['temporary_email'] . '</strong> for user <strong>' . $tmp['data']['first_name'] . ' ' . $tmp['data']['last_name'] . '</strong>.';
						$log->Create(ActivityLog::ACTIVITY_LOG_TYPE_USER);
					}
					
					$db->close();
				}
			}
			break;
		}

		case PAGE_PAID_MEMBERSHIP: {
			if (!User::isLoggedIn())
				return header('location: /login?_refsrc=' . urlencode(SITE_URL . '/user/paid-membership/' . PAGE_CHECKOUT) . '&ref_for=paid_membership');
			
			if (is_null($page_sub))
				return header('location: /user/paid-membership/' . PAGE_CHECKOUT);

			if ($page_sub == PAGE_CHECKOUT) {
				$status = App::getCustomQueryParam('payment_status');

				if (!is_null($status) && isset($_SESSION['card_payment_data'])) {
					$page_data['title'] = SITE_NAME .' - Payment Success';
					$app->user_payment_data = $_SESSION['card_payment_data'];
					$page_name = 'payment-success';
					unset($_SESSION['card_payment_data']);
					break;
				}

				$db = new Database();
				$payment = new Payment($db->connect());
				$app->has_already_paid = $payment->hasAlreadyPaid($_SESSION['grt_user']['id']);
				$db->close();
				$page_data = [
					'title' => SITE_NAME .' - Payment Checkout',
					'name' => PAGE_CHECKOUT
				];

				$user_data = $_SESSION['grt_user'];
				$app->user = [
					'uid_token' => $user_data['uid_token'],
					'name' => $user_data['name'],
					'email' => $user_data['email'],
					'price' => sprintf('%.2f', Payment::DEFAULT_PAYMENT_CHARGE_AMOUNT / 100),
					'email_verified' => (bool) $user_data['email_verified'],
					'paid_membership' => (bool) $user_data['paid_membership']
				];
				define('HIDE_HEADER_AND_FOOTER', true);
				$page_name = 'payment-checkout';
			}
			break;
		}

		case PAGE_LOGOUT: {
			if (User::isLoggedIn() || is_null($page_sub)) {
				$continue = App::getCustomQueryParam('continue');

				if (!is_null($continue) && isset($_SESSION['grt_user']['logged_in'])) {
					$page_data = [
						'title' => 'Logging out...',
						'description' => SITE_DESCRIPTION
					];

					$db = new Database();
					$user = new User($db->connect());

					$user->id = $_SESSION['grt_user']['id'];
					$user->removeActiveSession();

					$app->continue = $continue;
					$app->user_logging_out = true;
					unset($_SESSION['grt_user']);
					session_destroy();
				}
			}
		}

		default: {
			if (isset($_SESSION['grt_user']['logged_in']) && !strcasecmp($page_name, $_SESSION['grt_user']['username'])) {
				$template_dir = 'templates/tabs/';
				$page_name = 'user';
				
				$user_data = $_SESSION['grt_user'];

				$username = $user_data['username'];
				$is_admin = (bool) $user_data['is_admin'];

				$db = new Database();
				$connection = $db->connect();
				$payment = new Payment($connection);
				$user = new User($connection);

				if ($is_admin) {
					$news = new News($connection);
					$analysis = new TradingAnalysis($connection);

					$app->web_stats_count = [
						'total_news' => $news->getTotal(),
						'total_users' => $user->getTotal(),
						'total_trading_analysis' => $analysis->getTotal()
					];
				}
				
				$app->user = $user_data;
				$app->base_url = '/user/' . $username . '/';
				$app->count_students = $user->getTotalStudents($user_data['id']);
				$app->pending_payments = $payment->countPending();

				if (is_null($page_sub)) {
					$tab_name = $is_admin ? 'Dashboard' : 'Profile';
					$page_data = [
						'title' => $username . ' - ' .  $tab_name . ' | ' . SITE_NAME,
						'tab' => [
							'name' => $tab_name,
							'template' => $template_dir . TAB_DASHBOARD . '.template.php',
						]
					];
					break;
				}

				switch ($page_sub) {
					case TAB_SETTINGS:
						$_SESSION['csrf_token'] = App::getRandomString(30);
						$page_data = [
							'title' => $page_name . ' - Settings | ' . SITE_NAME,
							'tab' => [
								'name' => $page_sub,
								'template' => $template_dir . TAB_SETTINGS . '.template.php'
							],
							'csrf_token' => $_SESSION['csrf_token']
						];
						break;
		
					case TAB_MENTORS:
						if (!$user_data['paid_membership'] || $user_data['is_mentor'])
							break;

						$_SESSION['csrf_token'] = App::getRandomString(30);
						$page_data = [
							'title' => $page_name . ' - Mentors | ' . SITE_NAME,
							'tab' => [
								'name' => $page_sub,
								'template' => $template_dir . TAB_MENTORS . '.template.php'
							],
							'csrf_token' => $_SESSION['csrf_token']
						];
						break;

					case TAB_NEWS:
						if (!$is_admin)
							break;
						
						$action = App::getCustomQueryParam('action');

						if (is_null($action)) {
							$_SESSION['csrf_token'] = App::getRandomString(30);
							$page_data = [
								'title' => $page_name . ' - News | ' . SITE_NAME,
								'tab' => [
									'name' => $page_sub,
									'template' => $template_dir . TAB_NEWS . '.template.php'
								],
								'csrf_token' => $_SESSION['csrf_token']
							];
							break;
						}

						switch ($action) {
							case 'post':
								$_SESSION['csrf_token'] = App::getRandomString(30);
								$page_data = [
									'title' => $page_name . ' - Post news | ' . SITE_NAME,
									'tab' => [
										'name' => TAB_POST_NEWS,
										'template' => $template_dir . TAB_POST_NEWS . '.template.php'
									],
									'csrf_token' => $_SESSION['csrf_token']
								];
								$app->device_no_fit = true;
								break;

							case 'edit':
								$id = App::getCustomQueryParam('news');

								$db = new Database();
								$news = new news($db->connect());
								$data = $news->readSingle($id);

								if (!empty($data)) {
									$_SESSION['csrf_token'] = App::getRandomString(30);
									$editable_news_contents = $data;
									$editable_news_contents['body'] = html_entity_decode($data['body']);
									$editable_news_contents['author_avatar'] = ($data['author_avatar_url'] != 'none') ? $data['author_avatar_url'] : null;
									$editable_news_contents['has_thumbnail'] = ($data['thumbnail_url'] !== 'no-thumb.png') ? true : false;
									
									$page_data = [
										'title' => $page_name . ' - Editing news "' . $data['title'] . '" | ' . SITE_NAME,
										'tab' => [
											'name' => TAB_EDIT_NEWS,
											'template' => $template_dir . TAB_EDIT_NEWS . '.template.php'
										],
										'csrf_token' => $_SESSION['csrf_token']
									];
									$app->editable_news_data = $editable_news_contents;
								}
								$app->device_no_fit = true;
								break;

							default:
								break;
						}
						break;

					case TAB_TRADING_ANALYSIS:
						$action = App::getCustomQueryParam('action');

						if (is_null($action)) {
							$_SESSION['csrf_token'] = App::getRandomString(30);
							$page_data = [
								'title' => $page_name . ' - Trading analysis | ' . SITE_NAME,
								'tab' => [
									'name' => $page_sub,
									'template' => $template_dir . TAB_TRADING_ANALYSIS . '.template.php'
								],
								'csrf_token' => $_SESSION['csrf_token']
							];
							break;
						}
					
						switch ($action) {
							case 'post':
								$_SESSION['csrf_token'] = App::getRandomString(30);
								$page_data = [
									'title' => $page_name . ' - Post a new trading analysis | ' . SITE_NAME,
									'tab' => [
										'name' => TAB_POST_ANALYSIS,
										'template' => $template_dir . TAB_POST_ANALYSIS . '.template.php'
									],
									'csrf_token' => $_SESSION['csrf_token']
								];
								$app->device_no_fit = true;
								break;

							case 'edit':
								$url_title = App::getCustomQueryParam('analysis');

								$db = new Database();
								$analysis = new TradingAnalysis($db->connect());
								$data = $analysis->readSingle($url_title);

								if (!empty($data)) {
									$_SESSION['csrf_token'] = App::getRandomString(30);
									$editable_analysis_contents = $data;
									$editable_analysis_contents['has_thumbnail'] = ($data['thumbnail_url'] !== 'no-thumb.png') ? true : false;
									$editable_analysis_contents['body'] = html_entity_decode($data['body']);
									$page_data = [
										'title' => $page_name . ' - Editing trading analysis "' . $url_title . '" | ' . SITE_NAME,
										'tab' => [
											'name' => TAB_EDIT_ANALYSIS,
											'template' => $template_dir . TAB_EDIT_ANALYSIS . '.template.php'
										],
										'csrf_token' => $_SESSION['csrf_token']
									];
									$app->editable_analysis_data = $editable_analysis_contents;
								}
								$app->device_no_fit = true;
								break;
		
							default:
								break;					
						}
						break;

					case TAB_PAYMENTS:
						if (!$is_admin)
							break;

						$_SESSION['csrf_token'] = App::getRandomString(30);
						$page_data = [
							'title' => $page_name . ' - Payments | ' . SITE_NAME,
							'tab' => [
								'name' => TAB_PAYMENTS,
								'template' => $template_dir . TAB_PAYMENTS . '.template.php'
							],
							'csrf_token' => $_SESSION['csrf_token']
						];
						break;

					case TAB_STUDENTS:
						$_SESSION['csrf_token'] = App::getRandomString(30);
						$page_data = [
							'title' => $page_name . ' - Payments | ' . SITE_NAME,
							'tab' => [
								'name' => TAB_STUDENTS,
								'template' => $template_dir . TAB_STUDENTS . '.template.php'
							],
							'csrf_token' => $_SESSION['csrf_token']
						];
						break;

					case TAB_USERS:
						if (!$is_admin)
							break;

						$user_token = App::getCustomQueryParam('u');

						if (is_null($user_token)) {
							$page_data = [
								'title' => 'All Registered Users | ' . SITE_NAME,
								'tab' => [
									'name' => 'user info',
									'template' => $template_dir . TAB_USERS . '.template.php'
								]
							];
							break;
						}

						$db = new Database();
						$user = new User($db->connect());
						$data = $user->getDataById($user_token);
		
						if (!empty($data)) {
							$ip = $data['ip'];
							$last_logged_ip = $data['last_logged_ip'];
							$user_data = $data;
							$user_data['ip'] = ($ip == '::1') ? 'Unknown' : $ip;
							$user_data['last_logged_ip'] =  ($last_logged_ip == '::1') ? 'Unknown' : $last_logged_ip;
							$app->user_data = $user_data;
							$page_data = [
								'title' => 'Viewing ' . $user_data['name'] . '\'s account statistics | ' . SITE_NAME,
								'tab' => [
									'name' => 'user info',
									'template' => $template_dir . TAB_VIEW_USER . '.template.php'
								]
							];
						}
						break;

					case TAB_LOGS:
						if (!$is_admin)
							break;

						$type = App::getCustomQueryParam('type');

						if ($type == 'admin' || $type == 'user') {
							$tab_name = ucfirst($type) . ' Activity Logs';
							$page_data = [
								'title' => $page_name . ' ' . $tab_name . SITE_NAME,
								'tab' => [
									'name' => $tab_name,
									'template' => $template_dir . $type . '-logs.template.php'
								]
							];
							break;
						}
					
						$page_data = [
							'title' => '404 - Page not found',
							'tab' => [
								'name' => TAB_NOT_FOUND,
								'template' => $template_dir . TAB_NOT_FOUND . '.template.php'
							]
						];
						break;
					
					case TAB_QUOTES:
						if (!$is_admin)
							break;

						$_SESSION['csrf_token'] = App::getRandomString(30);
						$page_data = [
							'title' => $page_name . ' - Quotes | ' . SITE_NAME,
							'tab' => [
								'name' => $page_sub,
								'template' => $template_dir . TAB_QUOTES . '.template.php'
							],
							'csrf_token' => $_SESSION['csrf_token']
						];
						break;

					default:
						break;
				}
			}
			break;
		}
	}
}

if (!empty($page_data)) {
	$app->page_data = $page_data;
} else {
	if (isset($_SESSION['grt_user']['logged_in'])) {
		$app->page_data = [
			'title' => '404 - Page not found',
			'tab' => [
				'name' => TAB_NOT_FOUND,
				'template' => 'templates/tabs/' . TAB_NOT_FOUND . '.template.php'
			]
		];
	} else {
		$app->pageNotFound();
	}
}

$templates = (defined('ERR_PAGE_NOT_FOUND')) ? ['header', 'footer'] : ['header', $page_name, 'footer'];
$app->render($templates, USER_TEMPLATES_DIR);