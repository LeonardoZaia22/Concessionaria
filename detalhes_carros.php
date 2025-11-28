<?php
// Inicia a sessão para verificar se usuário está logado
session_start();
// Inclui o arquivo de conexão com o banco de dados
include_once 'conexao.php';

// Verifica se foi passado o ID do carro pela URL
if(!isset($_GET['id'])) {
    die('ID do carro não especificado'); // Para o código se não tiver ID
}

// Pega o ID do carro da URL
$carro_id = $_GET['id'];

// Buscar informações do carro no banco de dados
$sql_carro = "SELECT * FROM carros WHERE id = :id AND ativo = 1"; // Só busca carros ativos
$stmt_carro = $pdo->prepare($sql_carro);
$stmt_carro->execute([':id' => $carro_id]);
$carro = $stmt_carro->fetch(); // Pega o primeiro resultado

// Se não encontrou o carro, mostra mensagem de erro
if(!$carro) {
    die('Carro não encontrado');
}

// Buscar fotos adicionais do carro
$sql_fotos = "SELECT * FROM fotos_carros WHERE carro_id = :carro_id";
$stmt_fotos = $pdo->prepare($sql_fotos);
$stmt_fotos->execute([':carro_id' => $carro_id]);
$fotos = $stmt_fotos->fetchAll(); // Pega todas as fotos
?>

<div class="detalhes-carro">
    <!-- Título com marca, modelo e ano do carro -->
    <h2><?php echo htmlspecialchars($carro['marca']) . ' ' . htmlspecialchars($carro['modelo']) . ' (' . $carro['ano'] . ')'; ?>
        <!-- Mostra badge se o carro for destaque -->
        <?php if($carro['destaque']): ?>
            <span style="background: var(--primary-color); color: var(--secondary-color); padding: 5px 15px; border-radius: 20px; font-size: 0.8em; margin-left: 15px;">DESTAQUE</span>
        <?php endif; ?>
    </h2>

    <div class="info-grid">
        <!-- Coluna da Galeria de Fotos -->
        <div>
            <h3 style="color: var(--primary-color); margin-bottom: 15px;">Galeria de Fotos</h3>
            <div class="car-gallery">
                <!-- Imagem Principal do carro -->
                <div class="gallery-item" onclick="abrirModal('img/<?php echo $carro['imagem']; ?>')">
                    <?php if(file_exists('img/' . $carro['imagem'])): ?>
                        <!-- Se a imagem existe, mostra ela -->
                        <img src="img/<?php echo $carro['imagem']; ?>" alt="Imagem principal">
                    <?php else: ?>
                        <!-- Se não existe, mostra placeholder -->
                        <div style="height: 80px; display: flex; align-items: center; justify-content: center; background: var(--bg-dark); color: var(--text-muted);">
                            Sem imagem
                        </div>
                    <?php endif; ?>
                    <div style="text-align: center; margin-top: 5px; font-size: 0.8em;">Principal</div>
                </div>
                
                <!-- Loop para mostrar todas as fotos adicionais -->
                <?php foreach($fotos as $index => $foto): ?>
                    <div class="gallery-item" onclick="abrirModal('img/carros/<?php echo $foto['foto_nome']; ?>')">
                        <?php if(file_exists('img/carros/' . $foto['foto_nome'])): ?>
                            <img src="img/carros/<?php echo $foto['foto_nome']; ?>" alt="Foto adicional <?php echo $index + 1; ?>">
                        <?php else: ?>
                            <div style="height: 80px; display: flex; align-items: center; justify-content: center; background: var(--bg-dark); color: var(--text-muted);">
                                Sem imagem
                            </div>
                        <?php endif; ?>
                        <div style="text-align: center; margin-top: 5px; font-size: 0.8em;">Foto <?php echo $index + 1; ?></div>
                    </div>
                <?php endforeach; ?>
                
                <!-- Mensagem se não tiver fotos adicionais -->
                <?php if(empty($fotos)): ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 20px; color: var(--text-muted);">
                        Nenhuma foto adicional disponível
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Coluna das Informações do Carro -->
        <div>
            <h3 style="color: var(--primary-color); margin-bottom: 15px;">Informações do Veículo</h3>
            <div class="car-info-card">
                <!-- Mostra todas as informações do carro -->
                <p><strong>Preço:</strong> R$ <?php echo number_format($carro['preco'], 2, ',', '.'); ?></p>
                <p><strong>Marca:</strong> <?php echo htmlspecialchars($carro['marca']); ?></p>
                <p><strong>Modelo:</strong> <?php echo htmlspecialchars($carro['modelo']); ?></p>
                <p><strong>Ano:</strong> <?php echo $carro['ano']; ?></p>
                <p><strong>Combustível:</strong> <?php echo htmlspecialchars($carro['combustivel']); ?></p>
                <p><strong>Câmbio:</strong> <?php echo htmlspecialchars($carro['cambio']); ?></p>
                <p><strong>Cor:</strong> <?php echo htmlspecialchars($carro['cor']); ?></p>
                <!-- Mostra quilometragem só se tiver valor -->
                <?php if($carro['quilometragem']): ?>
                    <p><strong>Quilometragem:</strong> <?php echo number_format($carro['quilometragem'], 0, '', '.'); ?> km</p>
                <?php endif; ?>
                <!-- Mostra final da placa só se tiver valor -->
                <?php if($carro['final_placa']): ?>
                    <p><strong>Final da Placa:</strong> <?php echo $carro['final_placa']; ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Seção de Descrição do carro -->
    <div style="margin-bottom: 25px;">
        <h3 style="color: var(--primary-color); margin-bottom: 10px;">Descrição</h3>
        <div style="background: var(--bg-dark); padding: 20px; border-radius: 8px; line-height: 1.6;">
            <!-- nl2br transforma quebras de linha em <br> -->
            <?php echo nl2br(htmlspecialchars($carro['descricao'])); ?>
        </div>
    </div>

    <!-- Seção de Detalhes Adicionais (só mostra se tiver conteúdo) -->
    <?php if(!empty($carro['detalhes'])): ?>
    <div style="margin-bottom: 25px;">
        <h3 style="color: var(--primary-color); margin-bottom: 10px;">Detalhes Adicionais</h3>
        <div style="background: var(--bg-dark); padding: 20px; border-radius: 8px; line-height: 1.6;">
            <?php echo nl2br(htmlspecialchars($carro['detalhes'])); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Seção de Contato -->
    <div style="background: var(--primary-color); color: var(--secondary-color); padding: 25px; border-radius: 8px; text-align: center;">
        <h3 style="margin-bottom: 15px;">Interessado neste veículo?</h3>
        <p style="margin-bottom: 20px; font-size: 1.1em;">Entre em contato conosco para mais informações ou agendar uma visita</p>
        <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap;">
            <div style="font-size: 1.1em;">
                <strong>Telefone:</strong> (11) 3456-7890
            </div>
            <div style="font-size: 1.1em;">
                <strong>E-mail:</strong> vendas@classicmotors.com.br
            </div>
            <div style="font-size: 1.1em;">
                <strong>WhatsApp:</strong> (11) 98765-4321
            </div>
        </div>
    </div>
</div>