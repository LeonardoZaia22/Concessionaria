<?php
include_once 'conexao.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $telefone = $_POST['telefone'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    
    // Verificar se email já existe
    $verificar_email = "SELECT id FROM usuarios WHERE email = :email";
    $stmt_verificar = $pdo->prepare($verificar_email);
    $stmt_verificar->bindParam(':email', $email);
    $stmt_verificar->execute();
    
    if($stmt_verificar->rowCount() > 0) {
        $erro = "Este e-mail já está cadastrado.";
    } else {
        // Inserir novo usuário
        $inserir_usuario = "INSERT INTO usuarios (nome, email, senha, telefone, endereco, nivel) 
                           VALUES (:nome, :email, :senha, :telefone, :endereco, 'user')";
        $stmt_inserir = $pdo->prepare($inserir_usuario);
        $stmt_inserir->bindParam(':nome', $nome);
        $stmt_inserir->bindParam(':email', $email);
        $stmt_inserir->bindParam(':senha', $senha);
        $stmt_inserir->bindParam(':telefone', $telefone);
        $stmt_inserir->bindParam(':endereco', $endereco);
        
        if($stmt_inserir->execute()) {
            header('Location: login.php?sucesso=1');
            exit();
        } else {
            $erro = "Erro ao criar conta. Tente novamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Classic Motors</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="login-page">
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <a href="index.php">Classic Motors</a>
                </div>
                <div class="nav-menu">
                    <a href="index.php" class="nav-link">Início</a>
                    <a href="login.php" class="nav-link">Login</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="login-container">
        <div class="login-form-container">
            <h1>Criar Conta</h1>
            <p>Preencha os dados abaixo para criar sua conta</p>
            
            <?php if(isset($erro)): ?>
                <div class="alert alert-success" style="background-color: rgba(220, 53, 69, 0.2); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3);">
                    <?php echo $erro; ?>
                </div>
            <?php endif; ?>
            
            <form action="criar_conta.php" method="POST" class="login-form">
                <div class="form-group">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" name="nome" id="nome" placeholder="Seu nome completo" required>
                </div>
                
                <div class="form-group">
                    <label for="email">E-mail *</label>
                    <input type="email" name="email" id="email" placeholder="Seu e-mail" required>
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha *</label>
                    <input type="password" name="senha" id="senha" placeholder="Sua senha" required>
                </div>
                
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" name="telefone" id="telefone" placeholder="(11) 99999-9999">
                </div>
                
                <div class="form-group">
                    <label for="endereco">Endereço</label>
                    <textarea name="endereco" id="endereco" rows="3" placeholder="Digite seu endereço completo"></textarea>
                </div>
                
                <button type="submit" class="btn-primary btn-full">Criar Conta</button>
            </form>
            
            <div class="login-footer">
                <p>Já tem uma conta? <a href="login.php">Fazer login</a></p>
            </div>
        </div>
    </main>

    <footer class="login-footer-page">
        <div class="container">
            <p>&copy; 2025 Classic Motors. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>