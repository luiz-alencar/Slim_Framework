<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/Database.php';
require __DIR__ . '/../config/auth.php';

$app = AppFactory::create();

require __DIR__ . '/../routes/usuario.php';
require __DIR__ . '/../routes/carro.php';

$app->run();