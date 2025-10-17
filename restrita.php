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
    
    // Buscar carros do banco de dados
    $consulta_carros = "SELECT * FROM carros";
    $stmt_carros = $pdo->prepare($consulta_carros);
    $stmt_carros->execute();
    $carros = $stmt_carros->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acervo - Classic Motors</title>
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
                    <a href="#acervo" class="nav-link">Acervo</a>
                    <a href="painel.php" class="nav-link">Painel</a>
                    <a href="logout.php" class="nav-link logout-btn">Sair</a>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="welcome-section">
            <div class="container">
                <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h1>
                <p>Explore nosso acervo completo de carros clássicos</p>
            </div>
        </section>

        <section id="acervo" class="acervo-section">
            <div class="container">
                <h2>Nosso Acervo</h2>
                <div class="car-grid">
                    <?php foreach($carros as $carro): ?>
                    <div class="car-card">
                        <div class="car-image">
                            <img src="https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="<?php echo $carro['modelo']; ?>">
                            <?php if($carro['destaque']): ?>
                                <span class="destaque-badge">Destaque</span>
                            <?php endif; ?>
                        </div>
                        <div class="car-info">
                            <h3><?php echo $carro['marca'] . ' ' . $carro['modelo'] . ' ' . $carro['ano']; ?></h3>
                            <p class="car-price">R$ <?php echo number_format($carro['preco'], 2, ',', '.'); ?></p>
                            <p class="car-description"><?php echo $carro['descricao']; ?></p>
                            <button class="btn-secondary btn-small">Mais Informações</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="contact-section">
            <div class="container">
                <h2>Interessado em algum veículo?</h2>
                <p>Entre em contato conosco para agendar uma visita ou obter mais informações</p>
                <div class="contact-info">
                    <div class="contact-item">
                        <h3>Telefone</h3>
                        <p>(11) 3456-7890</p>
                    </div>
                    <div class="contact-item">
                        <h3>E-mail</h3>
                        <p>vendas@classicmotors.com.br</p>
                    </div>
                    <div class="contact-item">
                        <h3>Endereço</h3>
                        <p>Rua dos Clássicos, 123 - São Paulo, SP</p>
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
                <div class="footer-section">
                    <h3>Horário de Funcionamento</h3>
                    <p>Segunda a Sexta: 9h às 18h</p>
                    <p>Sábado: 9h às 13h</p>
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