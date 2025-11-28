<?php
// Inclui o arquivo de conexão com o banco de dados
include_once 'conexao.php';

// Variável para armazenar mensagens de erro
$erro = '';

// Verifica se o formulário foi enviado (método POST)
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pega os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $telefone = $_POST['telefone'] ?? '';  // Se não existir, usa string vazia
    $endereco = $_POST['endereco'] ?? '';  // Se não existir, usa string vazia
    
    // Verificar se email já existe no banco de dados
    $verificar_email = "SELECT id FROM usuarios WHERE email = :email";
    $stmt_verificar = $pdo->prepare($verificar_email);
    $stmt_verificar->bindParam(':email', $email);
    $stmt_verificar->execute();
    
    // Se encontrou algum usuário com este email
    if($stmt_verificar->rowCount() > 0) {
        $erro = "Este e-mail já está cadastrado.";
    } else {
        // Cria um hash seguro da senha (NUNCA salvar senha em texto puro)
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Prepara o comando SQL para inserir novo usuário
        $inserir_usuario = "INSERT INTO usuarios (nome, email, senha, telefone, endereco, nivel) 
                           VALUES (:nome, :email, :senha, :telefone, :endereco, 'user')";
        $stmt_inserir = $pdo->prepare($inserir_usuario);
        
        // Associa os valores aos parâmetros do SQL
        $stmt_inserir->bindParam(':nome', $nome);
        $stmt_inserir->bindParam(':email', $email);
        $stmt_inserir->bindParam(':senha', $senha_hash);
        $stmt_inserir->bindParam(':telefone', $telefone);
        $stmt_inserir->bindParam(':endereco', $endereco);
        
        // Tenta executar a inserção no banco
        if($stmt_inserir->execute()) {
            // Se deu certo, redireciona para a página de login com mensagem de sucesso
            header('Location: login.php?sucesso=1');
            exit();
        } else {
            // Se deu erro na inserção
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
            
            <?php if(!empty($erro)): ?>
                <!-- Mostra mensagem de erro se houver -->
                <div class="alert alert-success" style="background-color: rgba(220, 53, 69, 0.2); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3);">
                    <?php echo $erro; ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($_GET['sucesso'])): ?>
                <!-- Mostra mensagem de sucesso (vindo do redirecionamento) -->
                <div class="alert alert-success">
                    Conta criada com sucesso! Faça login para continuar.
                </div>
            <?php endif; ?>
            
            <!-- Formulário de cadastro -->
            <form action="criar_conta.php" method="POST" class="login-form">
                <!-- Campo nome completo -->
                <div class="form-group">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" name="nome" id="nome" placeholder="Seu nome completo" required value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>">
                </div>
                
                <!-- Campo email -->
                <div class="form-group">
                    <label for="email">E-mail *</label>
                    <input type="email" name="email" id="email" placeholder="Seu e-mail" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <!-- Campo senha -->
                <div class="form-group">
                    <label for="senha">Senha *</label>
                    <input type="password" name="senha" id="senha" placeholder="Sua senha" required>
                </div>
                
                <!-- Campo telefone (opcional) -->
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" name="telefone" id="telefone" placeholder="(11) 99999-9999" value="<?php echo isset($_POST['telefone']) ? htmlspecialchars($_POST['telefone']) : ''; ?>">
                </div>
                
                <!-- Campo endereço (opcional) -->
                <div class="form-group">
                    <label for="endereco">Endereço</label>
                    <textarea name="endereco" id="endereco" rows="3" placeholder="Digite seu endereço completo"><?php echo isset($_POST['endereco']) ? htmlspecialchars($_POST['endereco']) : ''; ?></textarea>
                </div>
                
                <!-- Botão para enviar o formulário -->
                <button type="submit" class="btn-primary btn-full">Criar Conta</button>
            </form>
            
            <div class="login-footer">
                <!-- Link para página de login -->
                <p>Já tem uma conta? <a href="login.php">Fazer login</a></p>
            </div>
        </div>
    </main>

    <?php
    // Inclui o rodapé da página
    include_once 'footer.php';
    ?>
</body>
</html>