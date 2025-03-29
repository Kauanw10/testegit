<?php 
    
    // require_once "vendor/autoload.php";

   class Usuario {
       private $nome;
       private $email;
       private $senha;
       private $pdo;
       private $mongo;
   
       // Construtor da classe
       public function __construct($nome, $email, $senha) {
           $this->nome = $nome;
           $this->email = $email;
           $this->senha = password_hash($senha, PASSWORD_DEFAULT); // Hash seguro da senha
 
          // Conectar ao MySQL
          try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=solvettestesbd', 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro ao conectar ao MySQL: " . $e->getMessage());
        }

        // Conectar ao MongoDB
        try {
            $this->mongo = new MongoDB\Client("mongodb://localhost:27017");
            $this->mongo = $client->selectDatabase('solvettestesbd'); // Selecionando o banco de dados
        } catch (Exception $e) {
            die("Erro ao conectar ao MongoDB: " . $e->getMessage());
        }
    }
   
       // Getters (métodos para acessar os atributos)
       public function getNome() {
           return $this->nome;
       }
   
       public function getEmail() {
           return $this->email;
       }
   
       // Método para salvar o usuário no banco de dados
       public function salvar() {
           try {
               // Inserção no banco de dados
               $stmt = $this->pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
               $stmt->bindParam(':nome', $this->nome);
               $stmt->bindParam(':email', $this->email);
               $stmt->bindParam(':senha', $this->senha);
               $stmt->execute();

               // Criando o Log de atividade para o MongoDB
               $log = [
                'nome' => $this->nome,
                'email' => $this->email,
                'acao'=> 'Cadastro de usuário',
                'data' => new MongoDB\BSON\UTCDateTime()
               ];
   
                 // Inserindo o log no MongoDB
                 $collection = $this->mongo->selectCollection('logs');
                 $collection->insertOne($log);
                 
               return "Usuário cadastrado com sucesso!";
               
           } catch (PDOException $e) {
               return "Erro ao salvar usuário: " . $e->getMessage();
           }
       }
   }
  
?>