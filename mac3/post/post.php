<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("post");
    return $response;
});