<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../config/db.php';

$app = AppFactory::create();

require __DIR__ . '/../routes/routes.php';

$app->run();