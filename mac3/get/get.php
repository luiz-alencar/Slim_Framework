<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/', function (Request $request, Response $response, $args) {

    $sql = "SELECT * FROM produto";

        $db = new Database();
        $db = $db->conectar();

        $stmt = $db->query($sql);
        $produto = $stmt->fetchAll(PDO::FETCH_OBJ);
        $response->getBody()->write(json_encode($produto));
    

});