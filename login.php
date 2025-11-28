<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Classic Motors</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="login-page">

    <?php
    // Inclui o cabeçalho do site
    include_once 'includes/header.php';
    ?>

    <!-- Mostra mensagem se a conta foi excluída -->
    <?php if(isset($_GET['conta_excluida'])): ?>
    <div class="alert alert-success" style="margin: 20px auto; max-width: 450px; text-align: center;">
        Sua conta foi excluída com sucesso. Sentiremos sua falta!
    </div>
    <?php endif; ?>

    <main class="login-container">
        <div class="login-form-container">
            <h1>Acesse sua conta</h1>
            <p>Entre para visualizar nosso acervo completo de carros clássicos</p>
            
            <!-- Mostra mensagem de erro se o login falhar -->
            <?php if(isset($_GET['erro'])): ?>
                <div class="alert alert-success" style="background-color: rgba(220, 53, 69, 0.2); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3);">
                    E-mail ou senha incorretos!
                </div>
            <?php endif; ?>
            
            <!-- Mostra mensagem de sucesso se criou conta -->
            <?php if(isset($_GET['sucesso'])): ?>
                <div class="alert alert-success">
                    Conta criada com sucesso! Faça login para continuar.
                </div>
            <?php endif; ?>
            
            <!-- Formulário de login que envia dados para processalogin.php -->
            <form action="processalogin.php" method="POST" class="login-form">
                <!-- Campo de email -->
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" placeholder="Seu e-mail" required>
                </div>
                
                <!-- Campo de senha -->
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" id="senha" placeholder="Sua senha" required>
                </div>
                
                <!-- Botão para enviar o formulário -->
                <button type="submit" class="btn-primary btn-full">Entrar</button>
            </form>
            
            <!-- Link para criar conta se não tiver uma -->
            <div class="login-footer">
                <p>Não tem uma conta? <a href="criar_conta.php">Criar conta</a></p>
            </div>
        </div>
    </main>

    <?php
    // Inclui o rodapé do site
    include_once 'includes/footer.php';
    ?>
</body>
</html>