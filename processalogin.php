<?php
session_start();
include_once 'conexao.php';

$email = $_POST['email'];
$senha = $_POST['senha'];

// Verificar se é o login padrão
if($email === 'login@gmail.com' && $senha === 'senha0102') {
    $_SESSION['id'] = 1;
    $_SESSION['nome'] = 'Administrador';
    $_SESSION['email'] = 'login@gmail.com';
    $_SESSION['nivel'] = 'admin';
    header('Location: restrita.php');
    exit();
}

// Verificar no banco com senha criptografada
$consulta = "SELECT * FROM usuarios WHERE email = :email";
$stmt = $pdo->prepare($consulta);
$stmt->bindParam(':email', $email);
$stmt->execute();

if($stmt->rowCount() == 1) {
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Verificar senha (comparação direta para senhas antigas não criptografadas)
    if($senha === $resultado['senha'] || password_verify($senha, $resultado['senha'])) {
        $_SESSION['id'] = $resultado['id'];
        $_SESSION['nome'] = $resultado['nome'];
        $_SESSION['email'] = $resultado['email'];
        $_SESSION['nivel'] = $resultado['nivel'] ?? 'user';
        header('Location: restrita.php');
        exit();
    }
}

// Login falhou
header('Location: login.php?erro=1');
exit();
?>