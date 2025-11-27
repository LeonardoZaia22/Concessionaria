<?php
session_start();
include_once 'conexao.php';

$email = $_POST['email'];
$senha = $_POST['senha'];

// Verificar no banco
$consulta = "SELECT * FROM usuarios WHERE email = :email";
$stmt = $pdo->prepare($consulta);
$stmt->bindParam(':email', $email);
$stmt->execute();

if($stmt->rowCount() == 1) {
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Verificar senha (agora comparação direta para contas pré-cadastradas)
    if($senha === $resultado['senha']) {
        $_SESSION['id'] = $resultado['id'];
        $_SESSION['nome'] = $resultado['nome'];
        $_SESSION['email'] = $resultado['email'];
        $_SESSION['nivel'] = $resultado['nivel'];
        header('Location: restrita.php');
        exit();
    }
}

// Login falhou
header('Location: login.php?erro=1');
exit();
?>