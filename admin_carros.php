<?php
session_start();
if(!isset($_SESSION['id'])) {
    header('location: index.php');
    exit();
}

// Verificar se é admin
if($_SESSION['email'] !== 'login@gmail.com') {
    header('location: restrita.php');
    exit();
}

include_once 'conexao.php';

// Adicionar carro
if(isset($_POST['adicionar_carro'])) {
    $modelo = $_POST['modelo'];
    $marca = $_POST['marca'];
    $ano = $_POST['ano'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];
    $quilometragem = $_POST['quilometragem'] ?? NULL;
    $combustivel = $_POST['combustivel'];
    $cambio = $_POST['cambio'];
    $cor = $_POST['cor'];
    $detalhes = $_POST['detalhes'];
    $destaque = isset($_POST['destaque']) ? 1 : 0;
    
    // Nome da imagem baseado no modelo e marca
    $imagem_nome = strtolower(str_replace(' ', '_', $marca . '_' . $modelo . '_' . $ano)) . '.jpg';
    
    $sql = "INSERT INTO carros (modelo, marca, ano, preco, descricao, imagem, quilometragem, combustivel, cambio, cor, detalhes, destaque, ativo) 
            VALUES (:modelo, :marca, :ano, :preco, :descricao, :imagem, :quilometragem, :combustivel, :cambio, :cor, :detalhes, :destaque, 1)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':modelo' => $modelo,
        ':marca' => $marca,
        ':ano' => $ano,
        ':preco' => $preco,
        ':descricao' => $descricao,
        ':imagem' => $imagem_nome,
        ':quilometragem' => $quilometragem,
        ':combustivel' => $combustivel,
        ':cambio' => $cambio,
        ':cor' => $cor,
        ':detalhes' => $detalhes,
        ':destaque' => $destaque
    ]);
    
    header('Location: admin_carros.php?sucesso=1');
    exit();
}

// Excluir carro
if(isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    
    // Marcar como inativo em vez de excluir
    $sql = "UPDATE carros SET ativo = 0 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    
    header('Location: admin_carros.php?sucesso=2');
    exit();
}

// Buscar todos os carros ativos
$carros = $pdo->query("SELECT * FROM carros WHERE ativo = 1 ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - Classic Motors</title>
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
                    <a href="admin_carros.php" class="nav-link active">Gerenciar Carros</a>
                    <a href="painel.php" class="nav-link">Meu Perfil</a>
                    <a href="logout.php" class="nav-link logout-btn">Sair</a>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="painel-section">
            <div class="container">
                <h1>Painel Administrativo - Gerenciar Carros</h1>
                
                <?php if(isset($_GET['sucesso'])): ?>
                    <div class="alert alert-success">
                        <?php echo $_GET['sucesso'] == 1 ? 'Carro adicionado com sucesso!' : 'Carro excluído com sucesso!'; ?>
                    </div>
                <?php endif; ?>

                <div class="painel-content">
                    <!-- Formulário Adicionar Carro -->
                    <div class="painel-card">
                        <h2>Adicionar Novo Carro</h2>
                        <form method="POST" class="painel-form">
                            <div class="form-group">
                                <label>Modelo *</label>
                                <input type="text" name="modelo" placeholder="Ex: Fusca 1300" required>
                            </div>
                            <div class="form-group">
                                <label>Marca *</label>
                                <input type="text" name="marca" placeholder="Ex: Volkswagen" required>
                            </div>
                            <div class="form-group">
                                <label>Ano *</label>
                                <input type="number" name="ano" min="1900" max="2025" placeholder="Ex: 1975" required>
                            </div>
                            <div class="form-group">
                                <label>Preço *</label>
                                <input type="number" step="0.01" name="preco" placeholder="Ex: 45000.00" required>
                            </div>
                            <div class="form-group">
                                <label>Quilometragem</label>
                                <input type="number" name="quilometragem" placeholder="Ex: 85000">
                            </div>
                            <div class="form-group">
                                <label>Combustível</label>
                                <select name="combustivel">
                                    <option value="Gasolina">Gasolina</option>
                                    <option value="Álcool">Álcool</option>
                                    <option value="Diesel">Diesel</option>
                                    <option value="Flex">Flex</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Câmbio</label>
                                <select name="cambio">
                                    <option value="Manual">Manual</option>
                                    <option value="Automático">Automático</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Cor</label>
                                <input type="text" name="cor" placeholder="Ex: Azul">
                            </div>
                            <div class="form-group">
                                <label>Descrição *</label>
                                <textarea name="descricao" rows="3" placeholder="Descrição detalhada do carro..." required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Detalhes Adicionais</label>
                                <textarea name="detalhes" rows="2" placeholder="Detalhes técnicos, histórico..."></textarea>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="destaque"> Destacar este carro (aparecerá na página inicial)
                                </label>
                            </div>
                            <button type="submit" name="adicionar_carro" class="btn-primary">Adicionar Carro</button>
                        </form>
                    </div>

                    <!-- Lista de Carros -->
                    <div class="painel-card">
                        <h2>Carros Cadastrados (<?php echo count($carros); ?>)</h2>
                        <div class="car-list">
                            <?php if(count($carros) > 0): ?>
                                <?php foreach($carros as $carro): ?>
                                <div class="car-item" style="border:1px solid var(--border-color); padding:15px; margin:10px 0; border-radius:5px; background: var(--bg-card);">
                                    <div style="display: flex; justify-content: space-between; align-items: start;">
                                        <div style="flex: 1;">
                                            <h3 style="margin: 0 0 10px 0; color: var(--primary-color);">
                                                <?= $carro['marca'] ?> <?= $carro['modelo'] ?> (<?= $carro['ano'] ?>)
                                                <?php if($carro['destaque']): ?>
                                                <span style="background: var(--primary-color); color: var(--secondary-color); padding: 2px 8px; border-radius: 10px; font-size: 0.8em; margin-left: 10px;">DESTAQUE</span>
                                                <?php endif; ?>
                                            </h3>
                                            <p style="margin: 5px 0;"><strong>Preço:</strong> R$ <?= number_format($carro['preco'], 2, ',', '.') ?></p>
                                            <p style="margin: 5px 0;"><strong>Combustível:</strong> <?= $carro['combustivel'] ?></p>
                                            <p style="margin: 5px 0;"><strong>Câmbio:</strong> <?= $carro['cambio'] ?></p>
                                            <p style="margin: 5px 0;"><strong>Cor:</strong> <?= $carro['cor'] ?></p>
                                            <?php if($carro['quilometragem']): ?>
                                            <p style="margin: 5px 0;"><strong>Quilometragem:</strong> <?= number_format($carro['quilometragem'], 0, '', '.') ?> km</p>
                                            <?php endif; ?>
                                            <p style="margin: 5px 0; color: var(--text-muted);"><?= $carro['descricao'] ?></p>
                                        </div>
                                        <div>
                                            <a href="admin_carros.php?excluir=<?= $carro['id'] ?>" 
                                               class="btn-secondary btn-small" 
                                               style="background: var(--danger-color); color: white; border: none;"
                                               onclick="return confirm('Tem certeza que deseja excluir <?= $carro['marca'] ?> <?= $carro['modelo'] ?>?')">
                                                Excluir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p style="text-align: center; color: var(--text-muted); padding: 20px;">
                                    Nenhum carro cadastrado ainda.
                                </p>
                            <?php endif; ?>
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