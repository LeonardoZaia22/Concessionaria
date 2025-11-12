<?php
session_start();

if((!isset($_SESSION['id'])) and (!isset($_SESSION['email'])) and (!isset($_SESSION['nome']))){
    header('location: index.php');
    exit();
}

include_once 'conexao.php';

// Buscar TODOS os carros ativos
$consulta_carros = "SELECT * FROM carros WHERE ativo = 1 ORDER BY destaque DESC, id DESC";
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
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
        }
        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            max-height: 80%;
        }
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }
        .car-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 10px;
            margin: 15px 0;
        }
        .gallery-item {
            cursor: pointer;
            border-radius: 5px;
            overflow: hidden;
            transition: transform 0.3s;
            border: 1px solid var(--border-color);
        }
        .gallery-item:hover {
            transform: scale(1.05);
        }
        .gallery-item img {
            width: 100%;
            height: 80px;
            object-fit: cover;
        }
        .car-card {
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }
        .image-placeholder {
            width: 100%;
            height: 200px;
            background: var(--bg-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <a href="restrita.php">Classic Motors</a>
                </div>
                <div class="nav-menu">
                    <a href="restrita.php" class="nav-link active">Início</a>
                    <a href="#acervo" class="nav-link">Acervo</a>
                    <?php if($_SESSION['nivel'] === 'admin'): ?>
                    <a href="admin_carros.php" class="nav-link">Gerenciar Carros</a>
                    <?php endif; ?>
                    <a href="painel.php" class="nav-link">Painel</a>
                    <a href="logout.php" class="nav-link logout-btn">Sair</a>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="welcome-section">
            <div class="container">
                <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome']); ?>!</h1>
                <p>Explore nosso acervo completo de carros clássicos</p>
                <?php if($_SESSION['nivel'] === 'admin'): ?>
                <div style="margin-top: 20px;">
                    <a href="admin_carros.php" class="btn-primary">Gerenciar Carros</a>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <section id="acervo" class="acervo-section">
            <div class="container">
                <h2>Nosso Acervo Completo</h2>
                <div class="car-grid">
                    <?php foreach($carros as $carro): ?>
                    <div class="car-card" onclick="abrirDetalhes(<?php echo $carro['id']; ?>)">
                        <div class="car-image">
                            <?php if(file_exists('img/' . $carro['imagem'])): ?>
                                <img src="img/<?php echo $carro['imagem']; ?>" alt="<?php echo htmlspecialchars($carro['modelo']); ?>">
                            <?php else: ?>
                                <div class="image-placeholder">
                                    Imagem não disponível
                                </div>
                            <?php endif; ?>
                            <?php if($carro['destaque']): ?>
                                <span class="destaque-badge">Destaque</span>
                            <?php endif; ?>
                        </div>
                        <div class="car-info">
                            <h3><?php echo htmlspecialchars($carro['marca']) . ' ' . htmlspecialchars($carro['modelo']) . ' ' . $carro['ano']; ?></h3>
                            <p class="car-price">R$ <?php echo number_format($carro['preco'], 2, ',', '.'); ?></p>
                            <p class="car-description"><?php echo htmlspecialchars($carro['descricao']); ?></p>
                            <div class="car-details">
                                <p><strong>Combustível:</strong> <?php echo htmlspecialchars($carro['combustivel'] ?? 'Gasolina'); ?></p>
                                <p><strong>Câmbio:</strong> <?php echo htmlspecialchars($carro['cambio'] ?? 'Manual'); ?></p>
                                <p><strong>Cor:</strong> <?php echo htmlspecialchars($carro['cor'] ?? 'Não informada'); ?></p>
                                <?php if($carro['quilometragem']): ?>
                                <p><strong>Quilometragem:</strong> <?php echo number_format($carro['quilometragem'], 0, '', '.'); ?> km</p>
                                <?php endif; ?>
                            </div>
                            <div style="text-align: center; margin-top: 10px;">
                                <small style="color: var(--primary-color);">Clique para ver mais detalhes e fotos</small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if(count($carros) === 0): ?>
                <div class="text-center">
                    <p>Nenhum carro cadastrado no momento.</p>
                    <?php if($_SESSION['nivel'] === 'admin'): ?>
                    <a href="admin_carros.php" class="btn-primary">Adicionar Primeiro Carro</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
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

    <!-- Modal de Detalhes do Carro -->
    <div id="detalhesModal" class="modal">
        <span class="close" onclick="fecharDetalhes()">&times;</span>
        <div class="modal-content" style="background: var(--bg-card); padding: 20px; border-radius: 10px; max-width: 900px; max-height: 90vh; overflow-y: auto;">
            <div id="detalhesContent"></div>
        </div>
    </div>

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

    <script>
        function abrirDetalhes(carroId) {
            document.getElementById('detalhesContent').innerHTML = '<div style="text-align: center; padding: 40px;">Carregando...</div>';
            document.getElementById('detalhesModal').style.display = 'block';
            
            fetch('detalhes_carro.php?id=' + carroId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao carregar detalhes');
                    }
                    return response.text();
                })
                .then(html => {
                    document.getElementById('detalhesContent').innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('detalhesContent').innerHTML = '<div style="text-align: center; padding: 40px; color: var(--danger-color);">Erro ao carregar detalhes do carro.</div>';
                    console.error('Erro:', error);
                });
        }

        function fecharDetalhes() {
            document.getElementById('detalhesModal').style.display = 'none';
        }

        function abrirModal(src) {
            const modal = document.createElement('div');
            modal.className = 'modal';
            modal.style.display = 'block';
            modal.innerHTML = `
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
                <img class="modal-content" src="${src}" onerror="this.style.display='none'; this.parentElement.querySelector('.close').style.display='none';">
            `;
            document.body.appendChild(modal);
            
            modal.onclick = function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                    document.body.removeChild(modal);
                }
            }
        }

        window.onclick = function(event) {
            const modal = document.getElementById('detalhesModal');
            if (event.target === modal) {
                fecharDetalhes();
            }
        }
    </script>
</body>
</html>