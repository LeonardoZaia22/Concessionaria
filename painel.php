<?php
    session_start();

    if((!isset($_SESSION['id'])) and (!isset($_SESSION['email'])) and (!isset($_SESSION['nome']))){
        unset(
            $_SESSION['id'],
            $_SESSION['nome'],
            $_SESSION['email']
        );

        header('location: index.php');
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
                    <a href="restrita.php#acervo" class="nav-link">Acervo</a>
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
                                <label for="nome">Nome Completo</label>
                                <input type="text" name="nome" id="nome" value="<?php echo $usuario['nome']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" name="email" id="email" value="<?php echo $usuario['email']; ?>" disabled>
                                <small>O e-mail não pode ser alterado</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="telefone">Telefone</label>
                                <input type="text" name="telefone" id="telefone" value="<?php echo $usuario['telefone'] ?? ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="endereco">Endereço</label>
                                <textarea name="endereco" id="endereco" rows="3"><?php echo $usuario['endereco'] ?? ''; ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn-primary">Atualizar Perfil</button>
                        </form>
                    </div>
                    
                    <div class="painel-card">
                        <h2>Minhas Preferências</h2>
                        <div class="preferences">
                            <div class="preference-item">
                                <h3>Notificações</h3>
                                <p>Receber novidades por e-mail</p>
                                <label class="switch">
                                    <input type="checkbox" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            
                            <div class="preference-item">
                                <h3>Interesses</h3>
                                <div class="interests">
                                    <label class="checkbox-label">
                                        <input type="checkbox" checked> Carros anos 60
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" checked> Carros anos 70
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox"> Carros anos 80
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox"> Carros americanos
                                    </label>
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