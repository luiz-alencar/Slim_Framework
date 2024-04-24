<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;

class UsuarioController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index(Request $request, Response $response)
    {
        $stmt = $this->pdo->query('SELECT * FROM usuario');
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $response->withJson($usuarios);
    }

    public function create(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $stmt = $this->pdo->prepare('INSERT INTO usuario (nome, nascimento, telefone, tipo_usuario, senha) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$data['nome'], $data['nascimento'], $data['telefone'], $data['tipo_usuario'], $data['senha']]);
        return $response->withJson(['message' => 'Usuário criado com sucesso']);
    }

    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $stmt = $this->pdo->prepare('UPDATE usuario SET nome = ?, nascimento = ?, telefone = ?, tipo_usuario = ?, senha = ? WHERE id = ?');
        $stmt->execute([$data['nome'], $data['nascimento'], $data['telefone'], $data['tipo_usuario'], $data['senha'], $args['id']]);
        return $response->withJson(['message' => 'Usuário atualizado com sucesso']);
    }

    public function delete(Request $request, Response $response, $args)
    {
        $stmt = $this->pdo->prepare('DELETE FROM usuario WHERE id = ?');
        $stmt->execute([$args['id']]);
        return $response->withJson(['message' => 'Usuário deletado com sucesso']);
    }
}
