<?php
// Inicia a sessão para poder guardar os dados do usuário logado
session_start();
// Inclui o arquivo de conexão com o banco de dados
include_once 'conexao.php';

// Pega o email e senha do formulário de login
$email = $_POST['email'] ?? '';  // Se não existir, usa string vazia
$senha = $_POST['senha'] ?? '';  // Se não existir, usa string vazia

// Verifica se email ou senha estão vazios
if (empty($email) || empty($senha)) {
    // Se estiverem vazios, volta para a página de login com erro
    header('Location: login.php?erro=1');
    exit();
}

// Buscar usuário por email no banco de dados
// IMPORTANTE: não compara a senha no SQL, só busca o usuário pelo email
$consulta = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
$stmt = $pdo->prepare($consulta);
$stmt->bindParam(':email', $email);
$stmt->execute();

// Verifica se encontrou algum usuário com este email
if($stmt->rowCount() == 1) {
    // Se encontrou, pega todos os dados do usuário
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $hash = $resultado['senha'];  // Pega o hash da senha que está no banco

    // Verifica se a senha digitada confere com o hash do banco
    // password_verify é a função correta para verificar senhas com hash
    if (password_verify($senha, $hash)) {
        // SENHA CORRETA - Login bem-sucedido
        
        // Guarda os dados do usuário na sessão
        $_SESSION['id'] = $resultado['id'];        // ID do usuário
        $_SESSION['nome'] = $resultado['nome'];    // Nome do usuário
        $_SESSION['email'] = $resultado['email'];  // Email do usuário
        $_SESSION['nivel'] = $resultado['nivel'];  // Nível (admin ou user)
        
        // Redireciona para a página restrita (área logada)
        header('Location: restrita.php');
        exit();
    } else {
        // SENHA INCORRETA - mas o email existe
        header('Location: login.php?erro=1');
        exit();
    }
}

// Se chegou aqui, é porque NÃO ENCONTROU usuário com este email
header('Location: login.php?erro=1');
exit();
?>