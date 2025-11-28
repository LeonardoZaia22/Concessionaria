<?php
// Inicia a sessão para acessar dados do usuário logado
session_start();

// Verifica se o usuário está logado - se não tiver ID, email e nome na sessão
if((!isset($_SESSION['id'])) and (!isset($_SESSION['email'])) and (!isset($_SESSION['nome']))){
    // Se não estiver logado, redireciona para a página inicial
    header('location: index.php');
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include_once 'conexao.php';

// Processa a exclusão da conta quando o formulário é enviado
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Se o usuário confirmou a exclusão
    if(isset($_POST['confirmar_exclusao'])) {
        $user_id = $_SESSION['id'];
        $email = $_SESSION['email'];
        
        // Verifica se é administrador para ver se pode excluir
        if($_SESSION['nivel'] === 'admin') {
            // Conta quantos administradores existem no sistema
            $sql_count_admins = "SELECT COUNT(*) as total FROM usuarios WHERE nivel = 'admin'";
            $stmt_count = $pdo->prepare($sql_count_admins);
            $stmt_count->execute();
            $total_admins = $stmt_count->fetch()['total'];
            
            // Se for o único admin, não permite excluir
            if($total_admins <= 1) {
                $erro = "Não é possível excluir a única conta de administrador do sistema.";
            }
        }
        
        // Se não houve erro (não é o último admin)
        if(!isset($erro)) {
            // Comando SQL para excluir o usuário do banco
            $sql_excluir = "DELETE FROM usuarios WHERE id = :id";
            $stmt_excluir = $pdo->prepare($sql_excluir);
            
            // Tenta executar a exclusão
            if($stmt_excluir->execute([':id' => $user_id])) {
                // Se conseguiu excluir, destrói a sessão e redireciona
                session_destroy();
                header('Location: index.php?conta_excluida=1');
                exit();
            } else {
                // Se deu erro na exclusão
                $erro = "Erro ao excluir conta. Tente novamente.";
            }
        }
    } else {
        // Se cancelou a exclusão, volta para o painel
        header('Location: painel.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Exclusão - Classic Motors</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Estilos para a página de confirmação de exclusão */
        .exclusao-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: var(--bg-card);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            text-align: center;
        }
        .warning-icon {
            font-size: 4rem;
            color: var(--danger-color);
            margin-bottom: 20px;
        }
        .btn-danger {
            background: var(--danger-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            margin: 5px;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .btn-secondary {
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            margin: 5px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-secondary:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <?php
    // Inclui o cabeçalho da página
    include_once 'includes/header2.php';
    ?>

    <main>
        <section class="painel-section">
            <div class="container">
                <div class="exclusao-container">
                    <?php if(isset($erro)): ?>
                        <!-- Mostra mensagem de erro se não puder excluir -->
                        <div class="alert alert-success" style="background-color: rgba(220, 53, 69, 0.2); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3);">
                            <?php echo $erro; ?>
                        </div>
                        <a href="painel.php" class="btn-secondary">Voltar ao Painel</a>
                    <?php else: ?>
                        <!-- Interface de confirmação de exclusão -->
                        <div class="warning-icon">⚠️</div>
                        <h1 style="color: var(--danger-color);">Confirmar Exclusão de Conta</h1>
                        
                        <!-- Área de aviso sobre o que será perdido -->
                        <div style="background: rgba(220, 53, 69, 0.1); padding: 20px; border-radius: 8px; margin: 20px 0;">
                            <h3>Você está prestes a excluir sua conta permanentemente!</h3>
                            <p><strong>Esta ação não pode ser desfeita.</strong></p>
                            
                            <div style="text-align: left; margin: 20px 0;">
                                <h4>O que será perdido:</h4>
                                <ul>
                                    <li>Seus dados pessoais</li>
                                    <li>Seu histórico na plataforma</li>
                                    <li>Seu acesso ao sistema</li>
                                    <li>Todas as suas informações</li>
                                </ul>
                            </div>
                            
                            <!-- Mostra informações do usuário que será excluído -->
                            <p><strong>Usuário:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
                            <p><strong>Nome:</strong> <?php echo htmlspecialchars($_SESSION['nome']); ?></p>
                        </div>

                        <!-- Formulário de confirmação -->
                        <form action="confirmar_exclusao.php" method="POST">
                            <div style="margin: 25px 0;">
                                <label style="display: block; margin-bottom: 10px;">
                                    <!-- Checkbox de confirmação obrigatória -->
                                    <input type="checkbox" name="confirmacao" required>
                                    <strong>Eu entendo que esta ação é irreversível</strong>
                                </label>
                            </div>
                            
                            <div>
                                <!-- Botão para confirmar exclusão (com confirmação JavaScript) -->
                                <button type="submit" name="confirmar_exclusao" class="btn-danger" onclick="return confirm('CONFIRMAÇÃO FINAL: Tem ABSOLUTA certeza que deseja EXCLUIR SUA CONTA PERMANENTEMENTE?')">
                                    SIM, EXCLUIR MINHA CONTA
                                </button>
                                <!-- Botão para cancelar -->
                                <button type="submit" name="cancelar" class="btn-secondary">
                                    Cancelar e Voltar
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <?php
    // Inclui o rodapé da página
    include_once 'includes/footer.php';
    ?>
</body>
</html>