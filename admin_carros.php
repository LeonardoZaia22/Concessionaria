<?php
session_start();
if(!isset($_SESSION['id'])) {
    header('location: index.php');
    exit();
}

// Verificar se é admin
if($_SESSION['nivel'] !== 'admin') {
    header('location: restrita.php');
    exit();
}

include_once 'conexao.php';

// Criar pasta de imagens se não existir
if (!file_exists('img/carros')) {
    mkdir('img/carros', 0777, true);
}

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
    
    // Upload da imagem principal
    $imagem_nome = 'img05.jpg';
    if(isset($_FILES['imagem_principal']) && $_FILES['imagem_principal']['error'] === 0) {
        $extensao = pathinfo($_FILES['imagem_principal']['name'], PATHINFO_EXTENSION);
        $imagem_nome = strtolower(str_replace(' ', '_', $marca . '_' . $modelo . '_' . $ano)) . '_principal.' . $extensao;
        $imagem_nome = preg_replace('/[^a-zA-Z0-9._]/', '', $imagem_nome);
        move_uploaded_file($_FILES['imagem_principal']['tmp_name'], 'img/' . $imagem_nome);
    }
    
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
    
    $carro_id = $pdo->lastInsertId();
    
    // Upload de fotos adicionais (máximo 5)
    if(isset($_FILES['fotos_adicionais'])) {
        $fotos_count = 0;
        foreach($_FILES['fotos_adicionais']['tmp_name'] as $key => $tmp_name) {
            if($fotos_count >= 5) break;
            
            if($_FILES['fotos_adicionais']['error'][$key] === 0) {
                $extensao = pathinfo($_FILES['fotos_adicionais']['name'][$key], PATHINFO_EXTENSION);
                $foto_nome = strtolower(str_replace(' ', '_', $marca . '_' . $modelo . '_' . $ano)) . '_' . time() . '_' . $key . '.' . $extensao;
                $foto_nome = preg_replace('/[^a-zA-Z0-9._]/', '', $foto_nome);
                
                if(move_uploaded_file($tmp_name, 'img/carros/' . $foto_nome)) {
                    $sql_foto = "INSERT INTO fotos_carros (carro_id, foto_nome) VALUES (:carro_id, :foto_nome)";
                    $stmt_foto = $pdo->prepare($sql_foto);
                    $stmt_foto->execute([':carro_id' => $carro_id, ':foto_nome' => $foto_nome]);
                    $fotos_count++;
                }
            }
        }
    }
    
    header('Location: admin_carros.php?sucesso=1');
    exit();
}

// Editar carro
if(isset($_POST['editar_carro'])) {
    $id = $_POST['id'];
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
    
    // Buscar carro atual
    $sql_carro = "SELECT * FROM carros WHERE id = :id";
    $stmt_carro = $pdo->prepare($sql_carro);
    $stmt_carro->execute([':id' => $id]);
    $carro_atual = $stmt_carro->fetch();
    
    $imagem_nome = $carro_atual['imagem'];
    
    // Upload da nova imagem principal
    if(isset($_FILES['imagem_principal']) && $_FILES['imagem_principal']['error'] === 0) {
        if($imagem_nome !== 'img05.jpg' && file_exists('img/' . $imagem_nome)) {
            unlink('img/' . $imagem_nome);
        }
        
        $extensao = pathinfo($_FILES['imagem_principal']['name'], PATHINFO_EXTENSION);
        $imagem_nome = strtolower(str_replace(' ', '_', $marca . '_' . $modelo . '_' . $ano)) . '_principal.' . $extensao;
        $imagem_nome = preg_replace('/[^a-zA-Z0-9._]/', '', $imagem_nome);
        move_uploaded_file($_FILES['imagem_principal']['tmp_name'], 'img/' . $imagem_nome);
    }
    
    $sql = "UPDATE carros SET modelo = :modelo, marca = :marca, ano = :ano, preco = :preco, descricao = :descricao, 
            imagem = :imagem, quilometragem = :quilometragem, combustivel = :combustivel, cambio = :cambio, 
            cor = :cor, detalhes = :detalhes, destaque = :destaque WHERE id = :id";
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
        ':destaque' => $destaque,
        ':id' => $id
    ]);
    
    // Upload de fotos adicionais
    if(isset($_FILES['fotos_adicionais'])) {
        $sql_count = "SELECT COUNT(*) as total FROM fotos_carros WHERE carro_id = :carro_id";
        $stmt_count = $pdo->prepare($sql_count);
        $stmt_count->execute([':carro_id' => $id]);
        $fotos_existentes = $stmt_count->fetch()['total'];
        
        $fotos_count = 0;
        $max_fotos = 5 - $fotos_existentes;
        
        foreach($_FILES['fotos_adicionais']['tmp_name'] as $key => $tmp_name) {
            if($fotos_count >= $max_fotos) break;
            
            if($_FILES['fotos_adicionais']['error'][$key] === 0) {
                $extensao = pathinfo($_FILES['fotos_adicionais']['name'][$key], PATHINFO_EXTENSION);
                $foto_nome = strtolower(str_replace(' ', '_', $marca . '_' . $modelo . '_' . $ano)) . '_' . time() . '_' . $key . '.' . $extensao;
                $foto_nome = preg_replace('/[^a-zA-Z0-9._]/', '', $foto_nome);
                
                if(move_uploaded_file($tmp_name, 'img/carros/' . $foto_nome)) {
                    $sql_foto = "INSERT INTO fotos_carros (carro_id, foto_nome) VALUES (:carro_id, :foto_nome)";
                    $stmt_foto = $pdo->prepare($sql_foto);
                    $stmt_foto->execute([':carro_id' => $id, ':foto_nome' => $foto_nome]);
                    $fotos_count++;
                }
            }
        }
    }
    
    header('Location: admin_carros.php?sucesso=3');
    exit();
}

// Excluir carro
if(isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    
    $sql_carro = "SELECT * FROM carros WHERE id = :id";
    $stmt_carro = $pdo->prepare($sql_carro);
    $stmt_carro->execute([':id' => $id]);
    $carro = $stmt_carro->fetch();
    
    if($carro['imagem'] !== 'img05.jpg' && file_exists('img/' . $carro['imagem'])) {
        unlink('img/' . $carro['imagem']);
    }
    
    $sql_fotos = "SELECT * FROM fotos_carros WHERE carro_id = :carro_id";
    $stmt_fotos = $pdo->prepare($sql_fotos);
    $stmt_fotos->execute([':carro_id' => $id]);
    $fotos = $stmt_fotos->fetchAll();
    
    foreach($fotos as $foto) {
        if(file_exists('img/carros/' . $foto['foto_nome'])) {
            unlink('img/carros/' . $foto['foto_nome']);
        }
    }
    
    $sql_delete_fotos = "DELETE FROM fotos_carros WHERE carro_id = :carro_id";
    $stmt_delete_fotos = $pdo->prepare($sql_delete_fotos);
    $stmt_delete_fotos->execute([':carro_id' => $id]);
    
    $sql = "UPDATE carros SET ativo = 0 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    
    header('Location: admin_carros.php?sucesso=2');
    exit();
}

// Excluir foto adicional
if(isset($_GET['excluir_foto'])) {
    $foto_id = $_GET['excluir_foto'];
    
    $sql_foto = "SELECT * FROM fotos_carros WHERE id = :id";
    $stmt_foto = $pdo->prepare($sql_foto);
    $stmt_foto->execute([':id' => $foto_id]);
    $foto = $stmt_foto->fetch();
    
    if($foto && file_exists('img/carros/' . $foto['foto_nome'])) {
        unlink('img/carros/' . $foto['foto_nome']);
    }
    
    $sql_delete = "DELETE FROM fotos_carros WHERE id = :id";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([':id' => $foto_id]);
    
    header('Location: admin_carros.php?sucesso=4&carro=' . $foto['carro_id']);
    exit();
}

// Buscar carro para edição
$carro_editar = null;
$fotos_adicionais = [];
if(isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $sql = "SELECT * FROM carros WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $carro_editar = $stmt->fetch();
    
    if($carro_editar) {
        $sql_fotos = "SELECT * FROM fotos_carros WHERE carro_id = :carro_id";
        $stmt_fotos = $pdo->prepare($sql_fotos);
        $stmt_fotos->execute([':carro_id' => $id]);
        $fotos_adicionais = $stmt_fotos->fetchAll();
    }
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
    <style>
        .foto-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
            margin: 15px 0;
        }
        .foto-item {
            position: relative;
            border-radius: 5px;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }
        .foto-item img {
            width: 100%;
            height: 100px;
            object-fit: cover;
        }
        .foto-item .excluir-foto {
            position: absolute;
            top: 5px;
            right: 5px;
            background: var(--danger-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            cursor: pointer;
            font-size: 12px;
        }
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
        .file-info {
            background: var(--bg-dark);
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 3px solid var(--primary-color);
        }
        .car-image-placeholder {
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
                        <?php 
                        switch($_GET['sucesso']) {
                            case 1: echo 'Carro adicionado com sucesso!'; break;
                            case 2: echo 'Carro excluído com sucesso!'; break;
                            case 3: echo 'Carro editado com sucesso!'; break;
                            case 4: echo 'Foto excluída com sucesso!'; break;
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <div class="painel-content">
                    <!-- Formulário Adicionar/Editar Carro -->
                    <div class="painel-card">
                        <h2><?php echo $carro_editar ? 'Editar Carro' : 'Adicionar Novo Carro'; ?></h2>
                        <form method="POST" class="painel-form" enctype="multipart/form-data">
                            <?php if($carro_editar): ?>
                                <input type="hidden" name="id" value="<?php echo $carro_editar['id']; ?>">
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label>Modelo *</label>
                                <input type="text" name="modelo" placeholder="Ex: Fusca 1300" 
                                       value="<?php echo $carro_editar ? htmlspecialchars($carro_editar['modelo']) : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Marca *</label>
                                <input type="text" name="marca" placeholder="Ex: Volkswagen" 
                                       value="<?php echo $carro_editar ? htmlspecialchars($carro_editar['marca']) : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Ano *</label>
                                <input type="number" name="ano" min="1900" max="2025" placeholder="Ex: 1975" 
                                       value="<?php echo $carro_editar ? $carro_editar['ano'] : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Preço *</label>
                                <input type="number" step="0.01" name="preco" placeholder="Ex: 45000.00" 
                                       value="<?php echo $carro_editar ? $carro_editar['preco'] : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Quilometragem</label>
                                <input type="number" name="quilometragem" placeholder="Ex: 85000" 
                                       value="<?php echo $carro_editar ? $carro_editar['quilometragem'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Combustível</label>
                                <select name="combustivel">
                                    <option value="Gasolina" <?php echo ($carro_editar && $carro_editar['combustivel'] == 'Gasolina') ? 'selected' : ''; ?>>Gasolina</option>
                                    <option value="Álcool" <?php echo ($carro_editar && $carro_editar['combustivel'] == 'Álcool') ? 'selected' : ''; ?>>Álcool</option>
                                    <option value="Diesel" <?php echo ($carro_editar && $carro_editar['combustivel'] == 'Diesel') ? 'selected' : ''; ?>>Diesel</option>
                                    <option value="Flex" <?php echo ($carro_editar && $carro_editar['combustivel'] == 'Flex') ? 'selected' : ''; ?>>Flex</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Câmbio</label>
                                <select name="cambio">
                                    <option value="Manual" <?php echo ($carro_editar && $carro_editar['cambio'] == 'Manual') ? 'selected' : ''; ?>>Manual</option>
                                    <option value="Automático" <?php echo ($carro_editar && $carro_editar['cambio'] == 'Automático') ? 'selected' : ''; ?>>Automático</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Cor</label>
                                <input type="text" name="cor" placeholder="Ex: Azul" 
                                       value="<?php echo $carro_editar ? htmlspecialchars($carro_editar['cor']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Descrição *</label>
                                <textarea name="descricao" rows="3" placeholder="Descrição detalhada do carro..." required><?php echo $carro_editar ? htmlspecialchars($carro_editar['descricao']) : ''; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Detalhes Adicionais</label>
                                <textarea name="detalhes" rows="2" placeholder="Detalhes técnicos, histórico..."><?php echo $carro_editar ? htmlspecialchars($carro_editar['detalhes']) : ''; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Imagem Principal</label>
                                <input type="file" name="imagem_principal" accept="image/*">
                                <small>Selecione a imagem principal do carro (JPG, PNG, GIF). Se não selecionar, será usada a imagem padrão.</small>
                                <?php if($carro_editar): ?>
                                    <div style="margin-top: 10px;">
                                        <?php if(file_exists('img/' . $carro_editar['imagem'])): ?>
                                            <img src="img/<?php echo $carro_editar['imagem']; ?>" alt="Imagem atual" style="max-width: 200px; border-radius: 5px;">
                                        <?php else: ?>
                                            <div class="car-image-placeholder">
                                                Imagem não disponível
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label>Fotos Adicionais</label>
                                <input type="file" name="fotos_adicionais[]" multiple accept="image/*" id="fotosInput">
                                <small>Selecione até 5 fotos adicionais do carro (JPG, PNG, GIF)</small>
                                <div class="file-info" id="fileInfo">
                                    Nenhum arquivo selecionado
                                </div>
                                
                                <?php if($carro_editar && count($fotos_adicionais) > 0): ?>
                                    <div class="foto-grid">
                                        <?php foreach($fotos_adicionais as $foto): ?>
                                            <div class="foto-item">
                                                <?php if(file_exists('img/carros/' . $foto['foto_nome'])): ?>
                                                    <img src="img/carros/<?php echo $foto['foto_nome']; ?>" alt="Foto adicional">
                                                <?php else: ?>
                                                    <div style="height: 100px; display: flex; align-items: center; justify-content: center; background: var(--bg-dark); color: var(--text-muted);">
                                                        Imagem não encontrada
                                                    </div>
                                                <?php endif; ?>
                                                <button type="button" class="excluir-foto" 
                                                        onclick="excluirFoto(<?php echo $foto['id']; ?>)">×</button>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="destaque" <?php echo ($carro_editar && $carro_editar['destaque']) ? 'checked' : ''; ?>> 
                                    Destacar este carro (aparecerá na página inicial)
                                </label>
                            </div>
                            <button type="submit" name="<?php echo $carro_editar ? 'editar_carro' : 'adicionar_carro'; ?>" class="btn-primary">
                                <?php echo $carro_editar ? 'Atualizar Carro' : 'Adicionar Carro'; ?>
                            </button>
                            <?php if($carro_editar): ?>
                                <a href="admin_carros.php" class="btn-secondary">Cancelar Edição</a>
                            <?php endif; ?>
                        </form>
                    </div>

                    <!-- Lista de Carros -->
                    <div class="painel-card">
                        <h2>Carros Cadastrados (<?php echo count($carros); ?>)</h2>
                        <div class="car-list">
                            <?php if(count($carros) > 0): ?>
                                <?php foreach($carros as $carro): ?>
                                <?php 
                                $sql_fotos_carro = "SELECT * FROM fotos_carros WHERE carro_id = :carro_id";
                                $stmt_fotos_carro = $pdo->prepare($sql_fotos_carro);
                                $stmt_fotos_carro->execute([':carro_id' => $carro['id']]);
                                $fotos_carro = $stmt_fotos_carro->fetchAll();
                                ?>
                                <div class="car-item" style="border:1px solid var(--border-color); padding:15px; margin:10px 0; border-radius:5px; background: var(--bg-card);">
                                    <div style="display: flex; justify-content: space-between; align-items: start;">
                                        <div style="flex: 1;">
                                            <h3 style="margin: 0 0 10px 0; color: var(--primary-color);">
                                                <?= htmlspecialchars($carro['marca']) ?> <?= htmlspecialchars($carro['modelo']) ?> (<?= $carro['ano'] ?>)
                                                <?php if($carro['destaque']): ?>
                                                <span style="background: var(--primary-color); color: var(--secondary-color); padding: 2px 8px; border-radius: 10px; font-size: 0.8em; margin-left: 10px;">DESTAQUE</span>
                                                <?php endif; ?>
                                            </h3>
                                            <p style="margin: 5px 0;"><strong>Preço:</strong> R$ <?= number_format($carro['preco'], 2, ',', '.') ?></p>
                                            <p style="margin: 5px 0;"><strong>Combustível:</strong> <?= htmlspecialchars($carro['combustivel']) ?></p>
                                            <p style="margin: 5px 0;"><strong>Câmbio:</strong> <?= htmlspecialchars($carro['cambio']) ?></p>
                                            <p style="margin: 5px 0;"><strong>Cor:</strong> <?= htmlspecialchars($carro['cor']) ?></p>
                                            <?php if($carro['quilometragem']): ?>
                                            <p style="margin: 5px 0;"><strong>Quilometragem:</strong> <?= number_format($carro['quilometragem'], 0, '', '.') ?> km</p>
                                            <?php endif; ?>
                                            <p style="margin: 5px 0; color: var(--text-muted);"><?= htmlspecialchars($carro['descricao']) ?></p>
                                            
                                            <!-- Galeria de Fotos -->
                                            <div class="car-gallery">
                                                <!-- Imagem Principal -->
                                                <div class="gallery-item" onclick="abrirModal('img/<?= $carro['imagem'] ?>')">
                                                    <?php if(file_exists('img/' . $carro['imagem'])): ?>
                                                        <img src="img/<?= $carro['imagem'] ?>" alt="Imagem principal">
                                                    <?php else: ?>
                                                        <div style="height: 80px; display: flex; align-items: center; justify-content: center; background: var(--bg-dark); color: var(--text-muted);">
                                                            Sem imagem
                                                        </div>
                                                    <?php endif; ?>
                                                    <div style="text-align: center; font-size: 0.8em; margin-top: 5px;">Principal</div>
                                                </div>
                                                
                                                <!-- Fotos Adicionais -->
                                                <?php foreach($fotos_carro as $foto): ?>
                                                    <div class="gallery-item" onclick="abrirModal('img/carros/<?= $foto['foto_nome'] ?>')">
                                                        <?php if(file_exists('img/carros/' . $foto['foto_nome'])): ?>
                                                            <img src="img/carros/<?= $foto['foto_nome'] ?>" alt="Foto adicional">
                                                        <?php else: ?>
                                                            <div style="height: 80px; display: flex; align-items: center; justify-content: center; background: var(--bg-dark); color: var(--text-muted);">
                                                                Sem imagem
                                                            </div>
                                                        <?php endif; ?>
                                                        <div style="text-align: center; font-size: 0.8em; margin-top: 5px;">Foto <?= $foto['id'] ?></div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <div style="display: flex; flex-direction: column; gap: 5px;">
                                            <a href="admin_carros.php?editar=<?= $carro['id'] ?>" class="btn-primary btn-small">Editar</a>
                                            <a href="admin_carros.php?excluir=<?= $carro['id'] ?>" 
                                               class="btn-secondary btn-small" 
                                               style="background: var(--danger-color); color: white; border: none;"
                                               onclick="return confirm('Tem certeza que deseja excluir <?= htmlspecialchars($carro['marca']) ?> <?= htmlspecialchars($carro['modelo']) ?>?')">
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

    <!-- Modal para visualização de imagens -->
    <div id="imageModal" class="modal">
        <span class="close" onclick="fecharModal()">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <?php
    include_once 'footer.php';
    ?>

    <script>
        document.getElementById('fotosInput').addEventListener('change', function(e) {
            const files = e.target.files;
            const fileInfo = document.getElementById('fileInfo');
            
            if (files.length > 5) {
                alert('Você pode selecionar no máximo 5 fotos.');
                this.value = '';
                fileInfo.textContent = 'Nenhum arquivo selecionado';
                return;
            }
            
            if (files.length === 0) {
                fileInfo.textContent = 'Nenhum arquivo selecionado';
            } else if (files.length === 1) {
                fileInfo.textContent = `1 arquivo selecionado: ${files[0].name}`;
            } else {
                fileInfo.textContent = `${files.length} arquivos selecionados`;
            }
        });

        function excluirFoto(fotoId) {
            if(confirm('Tem certeza que deseja excluir esta foto?')) {
                window.location.href = 'admin_carros.php?excluir_foto=' + fotoId;
            }
        }
        
        function abrirModal(src) {
            const img = new Image();
            img.onload = function() {
                document.getElementById('imageModal').style.display = 'block';
                document.getElementById('modalImage').src = src;
            }
            img.onerror = function() {
                alert('Imagem não encontrada: ' + src);
            }
            img.src = src;
        }
        
        function fecharModal() {
            document.getElementById('imageModal').style.display = 'none';
        }
        
        window.onclick = function(event) {
            const modal = document.getElementById('imageModal');
            if (event.target === modal) {
                fecharModal();
            }
        }
    </script>
</body>
</html>