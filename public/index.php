<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/get', function (Request $request, Response $response, $args) {
    $response->getBody()->write("METODO GET");
    return $response;
});

$app->post('/post', function (Request $request, Response $response, $args) {
    $response->getBody()->write("METODO PUST");
    return $response;
});

$app->put('/put', function (Request $request, Response $response, $args) {
    $response->getBody()->write("METODO PUT");
    return $response;
});

$app->delete('/delete', function (Request $request, Response $response, $args) {
    $response->getBody()->write("METODO DELETE");
    return $response;
});

$app->run();
