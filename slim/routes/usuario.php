<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use function public\auth;

$app->group('', function() use ($app){
    $app->get('/usuario/get', function (Request $request, Response $response, $args) {
    
        $query = "SELECT * FROM usuario";
    
        try {
    
            $db = new Database();
            $db = $db->connect();
            $stmt = $db->query($query);
            $students = $stmt->fetchAll(PDO::FETCH_OBJ);        
    
            if(! empty($students)) {
    
                $response->getBody()->write(json_encode($students));
                return $response->withHeader('Content-Type', 'application.json')->withStatus(200);
    
            } else {
                $response->getBody()->write(json_encode(array('message' => "Nenhum registro foi encontrado!")));
                return $response->withHeader('Content-Type', 'application.json')->withStatus(200);
            }
    
        } catch(PDOException $exp) {
    
            $response->getBody()->write(json_encode(array('Error' => $exp->getMessage())));
            return $response->withHeader('Content-Type', 'application.json')->withStatus(500);
        }
        
    });
    
    
    $app->get('/usuario/get/{id}', function (Request $request, Response $response, $args) {
        
        $id = $args['id'];
        $query = "SELECT * FROM usuario WHERE id = $id";
    
        try {
    
            $db = new Database();
            $db = $db->connect();
    
            $stmt = $db->query($query);
            $student = $stmt->fetch(PDO::FETCH_OBJ);
    
            if(! empty($student)) {
    
                $response->getBody()->write(json_encode($student));
                return $response->withHeader('Content-Type', 'application.json')->withStatus(200);
    
            } else {
                $response->getBody()->write(json_encode(array('message' => "Nenhum registro foi encontrado!")));
                return $response->withHeader('Content-Type', 'application.json')->withStatus(200);
            }
    
        } catch(PDOException $exp) {
    
            $response->getBody()->write(json_encode(array('Error' => $exp->getMessage())));
            return $response->withHeader('Content-Type', 'application.json')->withStatus(500);
        }
    
    });
    
    
    $app->post('/usuario/post', function (Request $request, Response $response, $args) {
        $data = json_decode($request->getBody(), true);
    
        $nome = $data['nome'];
    
        $query = "INSERT INTO usuario (nome) VALUES (:nome)";
    
        try {
            $db = new Database();
            $db = $db->connect();
    
            $stmt = $db->prepare($query);
            $stmt->bindParam(':nome', $nome);
            $stmt->execute();
    
            $response->getBody()->write(json_encode(array('message' => 'Usuário adicionado!')));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(array('error' => $e->getMessage())));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });
    
    
    $app->put('/usuario/put/{id}', function (Request $request, Response $response, $args) {
        
        $data = json_decode($request->getBody(), true);
        $nome = $data['nome'];
        $id = $args['id'];
        
        $query = "UPDATE usuario SET nome = :nome WHERE id = :id";
    
        try {
    
            $db = new Database();
            $db = $db->connect();
    
            $stmt = $db->prepare($query);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':id', $id);
    
            $result = $stmt->execute();
    
            $response->getBody()->write(json_encode(array('message'=> 'Usuário atualizado!')));
            return $response->withHeader('Content-Type', 'application.json')->withStatus(200);
    
        } catch(PDOException $exp) {
    
            $response->getBody()->write(json_encode(array('Error' => $exp->getMessage())));
            return $response->withHeader('Content-Type', 'application.json')->withStatus(500);
        }
    
    });
    
    
    $app->delete('/usuario/delete/{id}', function (Request $request, Response $response, $args) {
        
        $id = $args['id'];
        $query = "DELETE FROM usuario WHERE id = $id";
    
        try {
    
            $db = new Database();
            $db = $db->connect();
    
            $stmt = $db->prepare($query);
            $result = $stmt->execute();
    
            $response->getBody()->write(json_encode(array('message'=> 'Usuário deletado!')));
            return $response->withHeader('Content-Type', 'application.json')->withStatus(200);
    
        } catch(PDOException $exp) {
    
            $response->getBody()->write(json_encode(array('Error' => $exp->getMessage())));
            return $response->withHeader('Content-Type', 'application.json')->withStatus(500);
        }
    
    });
})->add(auth());