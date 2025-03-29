<?php 
    require_once 'usuario.php';

    // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //     echo "<pre>";
    //     print_r($_POST);
    //     echo "</pre>";
    //     exit; // Para interromper o script e visualizar os dados
    // }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Captura e sanitiza os dados
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $senha = $_POST['senha'];
    
        if (empty($nome) || !$email || empty($senha)) {
            die("Todos os campos são obrigatórios e o e-mail deve ser válido.");
        }
    
        // Cria o usuário
        $usuario = new Usuario($nome, $email, $senha);
    
        // Salva no banco
        $resultado = $usuario->salvar();

       // Verifica se houve sucesso
    if ($resultado === "Usuário cadastrado com sucesso!") {
        // Redireciona para a página inicial
        header('Location: pagina-apresent.html');
        exit(); // Garante que o script pare após o redirecionamento
    } else {
        // Exibe a mensagem de erro
        echo $resultado;
    }
    }
   
    
?>