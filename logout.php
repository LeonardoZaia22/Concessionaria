<?php
// Inicia a sessão para poder acessar os dados do usuário logado
session_start();

// Remove todas as informações do usuário da sessão
unset(
    $_SESSION['id'],      // Remove o ID do usuário
    $_SESSION['nome'],    // Remove o nome do usuário  
    $_SESSION['email'],   // Remove o email do usuário
    $_SESSION['nivel']    // Remove o nível de acesso (admin/user)
);

// Redireciona o usuário de volta para a página inicial
header('location: index.php');
?>