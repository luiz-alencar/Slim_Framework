<?php
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;

require __DIR__ . '/../vendor/autoload.php';

// Configuração do banco de dados
$dsn = 'mysql:host=localhost;dbname=seu_banco_de_dados';
$username = 'seu_usuario';
$password = 'sua_senha';

// Cria a conexão com o banco de dados
$pdo = new PDO($dsn, $username, $password);

$app = AppFactory::create();

// Middleware para adicionar o objeto PDO às requisições
$app->add(function ($request, $handler) use ($pdo) {
    $request = $request->withAttribute('pdo', $pdo);
    return $handler->handle($request);
});

// Importa os controllers
use App\controller\UsuarioController;
use App\controller\TarefaController;

// Criação dos controllers
$usuarioController = new UsuarioController($pdo);
$tarefaController = new TarefaController($pdo);

// Rotas
$app->get('/usuarios', [$usuarioController, 'index']);
$app->post('/usuarios', [$usuarioController, 'create']);
$app->put('/usuarios/{id}', [$usuarioController, 'update']);
$app->delete('/usuarios/{id}', [$usuarioController, 'delete']);

$app->get('/tarefas', [$tarefaController, 'index']);
$app->post('/tarefas', [$tarefaController, 'create']);
$app->put('/tarefas/{id}', [$tarefaController, 'update']);
$app->delete('/tarefas/{id}', [$tarefaController, 'delete']);

$app->run();
