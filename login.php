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
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <a href="index.php">Classic Motors</a>
                </div>
                <div class="nav-menu">
                    <a href="index.php" class="nav-link">Início</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="login-container">
        <div class="login-form-container">
            <h1>Acesse sua conta</h1>
            <p>Entre para visualizar nosso acervo completo de carros clássicos</p>
            
            <form action="processalogin.php" method="POST" class="login-form">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" placeholder="Seu e-mail" required>
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" id="senha" placeholder="Sua senha" required>
                </div>
                
                <button type="submit" class="btn-primary btn-full">Entrar</button>
            </form>
            
            <div class="login-footer">
                <p>Não tem uma conta? <a href="#">Entre em contato conosco</a></p>
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