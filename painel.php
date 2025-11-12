<?php
session_start();

if((!isset($_SESSION['id'])) and (!isset($_SESSION['email'])) and (!isset($_SESSION['nome']))){
    header('location: index.php');
    exit();
}

include_once 'conexao.php';

// Buscar dados do usuário
$user_id = $_SESSION['id'];
$consulta_usuario = "SELECT * FROM usuarios WHERE id = :id";
$stmt_usuario = $pdo->prepare($consulta_usuario);
$stmt_usuario->bindParam(':id', $user_id);
$stmt_usuario->execute();
$usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);

// Processar atualização do perfil
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    
    $atualizar_usuario = "UPDATE usuarios SET nome = :nome, telefone = :telefone, endereco = :endereco WHERE id = :id";
    $stmt_atualizar = $pdo->prepare($atualizar_usuario);
    $stmt_atualizar->bindParam(':nome', $nome);
    $stmt_atualizar->bindParam(':telefone', $telefone);
    $stmt_atualizar->bindParam(':endereco', $endereco);
    $stmt_atualizar->bindParam(':id', $user_id);
    
    if($stmt_atualizar->execute()) {
        $_SESSION['nome'] = $nome;
        $mensagem = "Perfil atualizado com sucesso!";
        // Recarregar dados do usuário
        $stmt_usuario->execute();
        $usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);
    } else {
        $mensagem = "Erro ao atualizar perfil. Tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Usuário - Classic Motors</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <a href="restrita.php">Classic Motors</a>
                </div>
                <div class="nav-menu">
                    <a href="restrita.php" class="nav-link">Início</a>
                    <?php if($_SESSION['nivel'] === 'admin'): ?>
                    <a href="admin_carros.php" class="nav-link">Gerenciar Carros</a>
                    <?php endif; ?>
                    <a href="painel.php" class="nav-link active">Painel</a>
                    <a href="logout.php" class="nav-link logout-btn">Sair</a>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="painel-section">
            <div class="container">
                <h1>Painel do Usuário</h1>
                
                <?php if(isset($mensagem)): ?>
                    <div class="alert alert-success">
                        <?php echo $mensagem; ?>
                    </div>
                <?php endif; ?>
                
                <div class="painel-content">
                    <div class="painel-card">
                        <h2>Meus Dados</h2>
                        <form action="painel.php" method="POST" class="painel-form">
                            <div class="form-group">
                                <label for="nome">Nome Completo *</label>
                                <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" disabled>
                                <small>O e-mail não pode ser alterado</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="telefone">Telefone</label>
                                <input type="text" name="telefone" id="telefone" value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>" placeholder="(11) 99999-9999">
                            </div>
                            
                            <div class="form-group">
                                <label for="endereco">Endereço</label>
                                <textarea name="endereco" id="endereco" rows="3" placeholder="Digite seu endereço completo"><?php echo htmlspecialchars($usuario['endereco'] ?? ''); ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn-primary">Atualizar Perfil</button>
                        </form>
                    </div>
                    
                    <div class="painel-card">
                        <h2>Informações da Conta</h2>
                        <div class="preferences">
                            <div class="preference-item">
                                <h3>Tipo de Conta</h3>
                                <p>
                                    <?php echo ($_SESSION['nivel'] === 'admin') ? 'Administrador' : 'Usuário'; ?>
                                </p>
                            </div>
                            
                            <div class="preference-item">
                                <h3>Ações Disponíveis</h3>
                                <div style="margin-top: 15px;">
                                    <a href="restrita.php" class="btn-secondary btn-small" style="margin-right: 10px;">Ver Acervo</a>
                                    <?php if($_SESSION['nivel'] === 'admin'): ?>
                                    <a href="admin_carros.php" class="btn-primary btn-small">Gerenciar Carros</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Classic Motors</h3>
                    <p>Concessionária especializada em carros antigos e clássicos.</p>
                </div>
                <div class="footer-section">
                    <h3>Contato</h3>
                    <p>Email: contato@classicmotors.com.br</p>
                    <p>Telefone: (11) 3456-7890</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Classic Motors. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html> 