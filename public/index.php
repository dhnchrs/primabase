<?php
if (PHP_SAPI == 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require '../vendor/autoload.php';
require '../src/settings/settings.php';

session_start();

require '../src/dependencies/dependencies.php';
require '../src/middleware/middleware.php';
require '../src/routes/login.php';
require '../src/routes/home.php';
require '../src/routes/tenant.php';
require '../src/routes/pos.php';
require '../src/routes/logger.php';
require '../src/routes/periodic.php';
require '../src/routes/anomali.php';

$app->run();