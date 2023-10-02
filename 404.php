<?php

require_once 'defines.php';
require_once 'autoload.php';

$app = new App();
$app->pageNotFound();
$app->render(['header', 'footer'], DEFAULT_TEMPLATES_DIR);