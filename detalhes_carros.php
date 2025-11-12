<?php
session_start();
include_once 'conexao.php';

if(!isset($_GET['id'])) {
    die('ID do carro não especificado');
}

$carro_id = $_GET['id'];

// Buscar informações do carro
$sql_carro = "SELECT * FROM carros WHERE id = :id AND ativo = 1";
$stmt_carro = $pdo->prepare($sql_carro);
$stmt_carro->execute([':id' => $carro_id]);
$carro = $stmt_carro->fetch();

if(!$carro) {
    die('Carro não encontrado');
}

// Buscar fotos adicionais
$sql_fotos = "SELECT * FROM fotos_carros WHERE carro_id = :carro_id";
$stmt_fotos = $pdo->prepare($sql_fotos);
$stmt_fotos->execute([':carro_id' => $carro_id]);
$fotos = $stmt_fotos->fetchAll();
?>

<div style="color: var(--text-color);">
    <h2 style="color: var(--primary-color); margin-bottom: 20px;">
        <?php echo htmlspecialchars($carro['marca']) . ' ' . htmlspecialchars($carro['modelo']) . ' (' . $carro['ano'] . ')'; ?>
        <?php if($carro['destaque']): ?>
            <span style="background: var(--primary-color); color: var(--secondary-color); padding: 5px 10px; border-radius: 15px; font-size: 0.8em; margin-left: 10px;">DESTAQUE</span>
        <?php endif; ?>
    </h2>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
        <!-- Galeria de Fotos -->
        <div>
            <h3 style="margin-bottom: 15px; color: var(--primary-color);">Galeria de Fotos</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px;">
                <!-- Imagem Principal -->
                <div style="cursor: pointer;" onclick="parent.abrirModal('img/<?php echo $carro['imagem']; ?>')">
                    <?php if(file_exists('img/' . $carro['imagem'])): ?>
                        <img src="img/<?php echo $carro['imagem']; ?>" 
                             alt="Imagem principal" 
                             style="width: 100%; height: 120px; object-fit: cover; border-radius: 5px;">
                    <?php else: ?>
                        <div style="width: 100%; height: 120px; background: var(--bg-dark); display: flex; align-items: center; justify-content: center; border-radius: 5px; color: var(--text-muted);">
                            Sem imagem
                        </div>
                    <?php endif; ?>
                    <div style="text-align: center; margin-top: 5px; font-size: 0.9em;">Principal</div>
                </div>
                
                <!-- Fotos Adicionais -->
                <?php foreach($fotos as $foto): ?>
                    <div style="cursor: pointer;" onclick="parent.abrirModal('img/carros/<?php echo $foto['foto_nome']; ?>')">
                        <?php if(file_exists('img/carros/' . $foto['foto_nome'])): ?>
                            <img src="img/carros/<?php echo $foto['foto_nome']; ?>" 
                                 alt="Foto adicional" 
                                 style="width: 100%; height: 120px; object-fit: cover; border-radius: 5px;">
                        <?php else: ?>
                            <div style="width: 100%; height: 120px; background: var(--bg-dark); display: flex; align-items: center; justify-content: center; border-radius: 5px; color: var(--text-muted);">
                                Sem imagem
                            </div>
                        <?php endif; ?>
                        <div style="text-align: center; margin-top: 5px; font-size: 0.9em;">Foto <?php echo $foto['id']; ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Informações do Carro -->
        <div>
            <h3 style="margin-bottom: 15px; color: var(--primary-color);">Informações do Veículo</h3>
            <div style="background: var(--bg-dark); padding: 20px; border-radius: 5px; border-left: 3px solid var(--primary-color);">
                <p style="margin: 10px 0;"><strong>Preço:</strong> R$ <?php echo number_format($carro['preco'], 2, ',', '.'); ?></p>
                <p style="margin: 10px 0;"><strong>Marca:</strong> <?php echo htmlspecialchars($carro['marca']); ?></p>
                <p style="margin: 10px 0;"><strong>Modelo:</strong> <?php echo htmlspecialchars($carro['modelo']); ?></p>
                <p style="margin: 10px 0;"><strong>Ano:</strong> <?php echo $carro['ano']; ?></p>
                <p style="margin: 10px 0;"><strong>Combustível:</strong> <?php echo htmlspecialchars($carro['combustivel']); ?></p>
                <p style="margin: 10px 0;"><strong>Câmbio:</strong> <?php echo htmlspecialchars($carro['cambio']); ?></p>
                <p style="margin: 10px 0;"><strong>Cor:</strong> <?php echo htmlspecialchars($carro['cor']); ?></p>
                <?php if($carro['quilometragem']): ?>
                    <p style="margin: 10px 0;"><strong>Quilometragem:</strong> <?php echo number_format($carro['quilometragem'], 0, '', '.'); ?> km</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Descrição e Detalhes -->
    <div style="margin-bottom: 20px;">
        <h3 style="margin-bottom: 10px; color: var(--primary-color);">Descrição</h3>
        <p style="line-height: 1.6; background: var(--bg-dark); padding: 15px; border-radius: 5px;">
            <?php echo nl2br(htmlspecialchars($carro['descricao'])); ?>
        </p>
    </div>

    <?php if(!empty($carro['detalhes'])): ?>
    <div style="margin-bottom: 20px;">
        <h3 style="margin-bottom: 10px; color: var(--primary-color);">Detalhes Adicionais</h3>
        <p style="line-height: 1.6; background: var(--bg-dark); padding: 15px; border-radius: 5px;">
            <?php echo nl2br(htmlspecialchars($carro['detalhes'])); ?>
        </p>
    </div>
    <?php endif; ?>

    <!-- Contato -->
    <div style="background: var(--primary-color); color: var(--secondary-color); padding: 20px; border-radius: 5px; text-align: center;">
        <h3 style="margin-bottom: 10px;">Interessado neste veículo?</h3>
        <p style="margin-bottom: 15px;">Entre em contato conosco para mais informações ou agendar uma visita</p>
        <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
            <div>
                <strong>Telefone:</strong> (11) 3456-7890
            </div>
            <div>
                <strong>E-mail:</strong> vendas@classicmotors.com.br
            </div>
        </div>
    </div>
</div>