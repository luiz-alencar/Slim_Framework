# Bem-vind@s ao tutorial do Slim_Framework 4!!💻

<picture>
  <img alt="Shows an illustrated sun in light mode and a moon with stars in dark mode." src="https://user-images.githubusercontent.com/781074/82730649-87608800-9d01-11ea-83ea-6112f973b051.png">
</picture>

<br>

## O que é um framework? 
> Um framework é uma estrutura de suporte definida para ajudar a desenvolver e organizar softwares. Ele oferece um conjunto de ferramentas, bibliotecas, padrões e diretrizes que permitem aos desenvolvedores criar aplicações de forma mais eficiente e consistente.

## E o Slim framework?
> O Slim é um framework PHP usado para desenvolver aplicações web e APIs de forma rápida e eficiente. Ele é conhecido por ser minimalista e por facilitar a criação de rotas, o gerenciamento de dependências e a manipulação de requisições e respostas HTTP. 

## Você sabe o que é uma API?
> API significa Interface de Programação de Aplicações (em inglês, Application Programming Interface). É um conjunto de regras e protocolos que permite que diferentes softwares se comuniquem entre si. Em termos simples, uma API define como diferentes componentes de software devem interagir uns com os outros. Existem diferentes tipos de APIs, mas a mais comum é a API web, que permite que aplicações se comuniquem através da internet.

<br>

# Vamos começar com alguns comandos simples... Certeza que você já conhece! 😉

### 1º passo -> Verificar se você tem o php em sua máquina, execute o comando: 

    $ php -v 

-> Caso não tenha, o próprio terminal te dará como instalá-lo, segue código: 

    $ sudo apt-get install php8.1-mysql

### 2º passo -> Verificar se você tem o composer em sua máquina, segue código:

    $ composer --version

- Caso não tenha, o próprio terminal te dará como instalá-lo, segue código:

      $ sudo apt-get install composer
 
### 3º passo -> Escolher a pasta onde irá começar seu projeto.

### 4º passo -> Na pasta desejada, abra o terminal e execute o comando: 

    $ composer require slim/slim:"4.*”

- Ele irá criar toda a estrutura do Slim Framework 4.

### 5º passo -> Implementação do psr7: 

> o PSR-7 tem como objetivo modelar mensagens HTTP

    $ composer require slim/psr7 -w

### 6º passo -> Baixar o arquivo "tarefa.sql" que está na pasta config
 
 - Após baixar o arquivo, crie uma pasta com o nome "config" dentro pasta raiz
 -  Copie e cole o arquivo "tarefa.sql" dentro da pasta "config"
 
### 7º passo ->  Criar pasta do docker que conterá nosso banco:
 
 - Baixe o arquivo: "docker-compose.yml"
 - Neste momento o arquivo deve ser colocado na pasta raiz.

 ### 8º passo -> Vamos inicializar o container:

 - Antes de inicializar o docker temos que verificar se existem container's ativos, use o comando:
     
        $ docker ps -a
        $ docker stop "nome do container"
        $ docker rm "nome do container"

 - Após a verificação, vamos subir nosso container:
 
        $ docker-compose up -d
    
### 9º passo -> Criar a conexão com o banco de dados do container 

 - Dentro da pasta "config", crie um arquivo "database.php" e cole o seguinte código:
  
        <?php
        class Database {
            private $host = "127.0.0.1";
            private $port = "3308"; 
            private $user = "root";
            private $db = "tarefa";
            private $pwd = "123";
            private $conn = NULL;
        
            public function connect() {
        
                try{
                    $this->conn = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->db", $this->user, $this->pwd);
                    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch(PDOException $e) {
                    echo "Connection Error: " . $e->getMessage();
                }
        
                return $this->conn;
            }
        }

### 10º passo -> Criar a pasta "public" e os testes do "index.php"

- Crie uma pasta com o nome "public" dentro pasta raiz
- Dentro da pasta "public" crie um arquivo "index.php"

- Copie o código e cole no arquivo "index.php" que você acabou de criar:

      <?php
      use Psr\Http\Message\ResponseInterface as Response;
      use Psr\Http\Message\ServerRequestInterface as Request;
      use Slim\Factory\AppFactory;
      
      require __DIR__ . '/../vendor/autoload.php';
      
      $app = AppFactory::create();
      
      $app->get('/', function (Request $request, Response $response, $args) {
          $response->getBody()->write("Hello World");
          return $response;
      });
      
      $app->run();

- Para fins de teste, utilize o seguinte código:

      $ php -S localhost:8000 -t public

### 11º passo -> Baixar o Insomnia:

- Para fazer os teste de requisões, baixe o aplicativo disponível no link a seguir:

      https://insomnia.rest/download.

### 12º passo -> Criar a pasta "routes"

- Crie uma pasta com o nome "routes" dentro pasta raiz
- Dentro da pasta "routes" você criará dois arquivos: "carro.php" e "usuario.php"

### 13º passo -> Editando "index.php"

- No arquivo "index.php" você vai acrescentar o require de database, usuario e carro como está no código abaixo:

      require __DIR__ . '/../config/Database.php';
      
      $app = AppFactory::create();
      
      require __DIR__ . '/../routes/usuario.php';
      require __DIR__ . '/../routes/carro.php';

### 14º passo -> Introdução ao método get no arquivo "usuario.php" 
    
    <?php
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    
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

### 15º passo -> Introdução ao método get no arquivo "usuario.php" selecionando por id:

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

### 16º passo -> Introdução ao método post no arquivo "usuario.php"

    $app->post('/usuario/post', function (Request $request, Response $response, $args) {
        $data = json_decode($request->getBody(), true);
    
        $nome = $data['nome'];
    
        // Consulta SQL para inserir o id e nome na tabela produto
        $query = "INSERT INTO usuario (nome) VALUES (:nome)";
    
        try {
            // Conexão com o banco de dados
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

### 17º passo -> Introdução ao método delete no arquivo "usuario.php"

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

### 18º passo -> Introdução ao método put no arquivo "usuario.php"

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

### 19º passo -> Introdução ao método get no "arquivo carro.php" 

    $app->get('/carro/get', function (Request $request, Response $response, $args) {
        
        $query = "SELECT * FROM carro";
    
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
    
### 20º passo -> Introdução ao método get no arquivo "carro.php" selecionando por id

    $app->get('/carro/get/{id}', function (Request $request, Response $response, $args) {
        
        $id = $args['id'];
        $query = "SELECT * FROM carro WHERE id = $id";
    
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

### 21º passo -> Introdução ao método post no arquivo "carro.php"

    $app->post('/carro/post', function (Request $request, Response $response, $args) {
        $data = json_decode($request->getBody(), true);
    
        // Verifica se os dados necessários estão presentes 
        #if (!isset($data['id']) || !isset($data['nome'])) {
        #    $response->getBody()->write(json_encode(array('error' => 'ID e Nome são requeridos para continuar')));
        #    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        #}
    
        # $id = $data['id'];
        $nome = $data['nome'];
        $usuario_id = $data['usuario_id'];
    
        // Consulta SQL para inserir o id e nome na tabela produto
        $query = "INSERT INTO carro (nome, usuario_id) VALUES (:nome, :usuario_id)";
    
        try {
            // Conexão com o banco de dados
            $db = new Database();
            $db = $db->connect();
    
            $stmt = $db->prepare($query);
            #$stmt->bindParam(':id', $id);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->execute();
    
            $response->getBody()->write(json_encode(array('message' => 'Usuário adicionado!')));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(array('error' => $e->getMessage())));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

### 22º passo -> Introdução ao método delete no arquivo "carro.php"

    $app->delete('/carro/delete/{id}', function (Request $request, Response $response, $args) {
        
        $id = $args['id'];
        $query = "DELETE FROM carro WHERE id = $id";
    
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

### 23º passo -> Introdução ao método put no arquivo "carro.php"

    $app->put('/carro/put/{id}', function (Request $request, Response $response, $args) {
        
        $data = json_decode($request->getBody(), true);
        $nome = $data['nome'];
        $id = $args['id'];
        
        $query = "UPDATE carro SET nome = :nome WHERE id = :id";
    
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

    
### 24º passo -> Execute o comando a seguir para inicializar a api: 

    $ php -S localhost:8000 -t public
