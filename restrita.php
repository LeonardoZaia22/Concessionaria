<?php
session_start();

if((!isset($_SESSION['id'])) and (!isset($_SESSION['email'])) and (!isset($_SESSION['nome']))){
    header('location: index.php');
    exit();
}

include_once 'conexao.php';
include_once 'config_imagens.php'; // ADICIONEI ESTA LINHA

// Buscar TODOS os carros ativos
$consulta_carros = "SELECT * FROM carros WHERE ativo = 1 ORDER BY destaque DESC, id DESC";
$stmt_carros = $pdo->prepare($consulta_carros);
$stmt_carros->execute();
$carros = $stmt_carros->fetchAll(PDO::FETCH_ASSOC);

// Buscar fotos de todos os carros de uma vez (mais eficiente)
$fotos_por_carro = [];
foreach($carros as $carro) {
    $sql_fotos = "SELECT * FROM fotos_carros WHERE carro_id = :carro_id";
    $stmt_fotos = $pdo->prepare($sql_fotos);
    $stmt_fotos->execute([':carro_id' => $carro['id']]);
    $fotos_por_carro[$carro['id']] = $stmt_fotos->fetchAll();
}
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
        .modal-detalhes {
            background: var(--bg-card);
            padding: 30px;
            border-radius: 10px;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            margin: 2% auto;
            position: relative;
            color: var(--text-color);
        }
        .modal-detalhes h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        .car-info-card {
            background: var(--bg-dark);
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
        }
        .car-info-card p {
            margin: 8px 0;
        }
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            .modal-detalhes {
                margin: 5% auto;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <?php
    include_once 'includes/header2.php';
    ?>

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
                    <?php 
                    $fotos = $fotos_por_carro[$carro['id']] ?? [];
                    // LINHA SIMPLES PARA PEGAR A IMAGEM CORRETA
                    $imagem_principal = getImagemCarro($carro['modelo'], $carro['marca']);
                    ?>
                    <div class="car-card" onclick="abrirDetalhes(<?php echo $carro['id']; ?>)">
                        <div class="car-image">
                            <img src="img/<?php echo $imagem_principal; ?>" alt="<?php echo htmlspecialchars($carro['marca'] . ' ' . $carro['modelo']); ?>">
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
        <div class="modal-detalhes">
            <div id="detalhesContent">
                <!-- Os detalhes serão carregados aqui via JavaScript -->
            </div>
        </div>
    </div>

    <?php
    include_once 'includes/footer.php';
    ?>

    <script>
        // Dados dos carros em formato JavaScript
        const carrosData = {
            <?php foreach($carros as $carro): ?>
            <?php 
            $fotos = $fotos_por_carro[$carro['id']] ?? [];
            $imagem_principal = getImagemCarro($carro['modelo'], $carro['marca']);
            ?>
            <?php echo $carro['id']; ?>: {
                id: <?php echo $carro['id']; ?>,
                marca: "<?php echo htmlspecialchars($carro['marca']); ?>",
                modelo: "<?php echo htmlspecialchars($carro['modelo']); ?>",
                ano: <?php echo $carro['ano']; ?>,
                preco: "<?php echo number_format($carro['preco'], 2, ',', '.'); ?>",
                descricao: `<?php echo addslashes($carro['descricao']); ?>`,
                imagem: "<?php echo $imagem_principal; ?>",
                destaque: <?php echo $carro['destaque'] ? 'true' : 'false'; ?>,
                quilometragem: <?php echo $carro['quilometragem'] ?? 'null'; ?>,
                combustivel: "<?php echo htmlspecialchars($carro['combustivel']); ?>",
                cambio: "<?php echo htmlspecialchars($carro['cambio']); ?>",
                cor: "<?php echo htmlspecialchars($carro['cor']); ?>",
                final_placa: <?php echo $carro['final_placa'] ? "'" . $carro['final_placa'] . "'" : 'null'; ?>,
                detalhes: `<?php echo addslashes($carro['detalhes'] ?? ''); ?>`,
                fotos: [
                    <?php foreach($fotos as $foto): ?>
                    {
                        id: <?php echo $foto['id']; ?>,
                        nome: "<?php echo $foto['foto_nome']; ?>"
                    },
                    <?php endforeach; ?>
                ]
            },
            <?php endforeach; ?>
        };

        function abrirDetalhes(carroId) {
            console.log('Abrindo detalhes do carro ID:', carroId);
            
            const carro = carrosData[carroId];
            if (!carro) {
                document.getElementById('detalhesContent').innerHTML = 
                    '<div style="text-align: center; padding: 40px; color: var(--danger-color);">Carro não encontrado.</div>';
                document.getElementById('detalhesModal').style.display = 'block';
                return;
            }

            // Gerar HTML dos detalhes
            let fotosHTML = '';
            if (carro.fotos && carro.fotos.length > 0) {
                carro.fotos.forEach((foto, index) => {
                    fotosHTML += `
                        <div class="gallery-item" onclick="abrirModal('img/carros/${foto.nome}')">
                            <img src="img/carros/${foto.nome}" alt="Foto adicional ${index + 1}">
                            <div style="text-align: center; margin-top: 5px; font-size: 0.8em;">Foto ${index + 1}</div>
                        </div>
                    `;
                });
            } else {
                fotosHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 20px; color: var(--text-muted);">Nenhuma foto adicional disponível</div>';
            }

            const detalhesHTML = `
                <div class="detalhes-carro">
                    <h2>${carro.marca} ${carro.modelo} (${carro.ano})
                        ${carro.destaque ? '<span style="background: var(--primary-color); color: var(--secondary-color); padding: 5px 15px; border-radius: 20px; font-size: 0.8em; margin-left: 15px;">DESTAQUE</span>' : ''}
                    </h2>

                    <div class="info-grid">
                        <!-- Galeria de Fotos -->
                        <div>
                            <h3 style="color: var(--primary-color); margin-bottom: 15px;">Galeria de Fotos</h3>
                            <div class="car-gallery">
                                <!-- Imagem Principal -->
                                <div class="gallery-item" onclick="abrirModal('img/${carro.imagem}')">
                                    <img src="img/${carro.imagem}" alt="Imagem principal">
                                    <div style="text-align: center; margin-top: 5px; font-size: 0.8em;">Principal</div>
                                </div>
                                ${fotosHTML}
                            </div>
                        </div>

                        <!-- Informações do Carro -->
                        <div>
                            <h3 style="color: var(--primary-color); margin-bottom: 15px;">Informações do Veículo</h3>
                            <div class="car-info-card">
                                <p><strong>Preço:</strong> R$ ${carro.preco}</p>
                                <p><strong>Marca:</strong> ${carro.marca}</p>
                                <p><strong>Modelo:</strong> ${carro.modelo}</p>
                                <p><strong>Ano:</strong> ${carro.ano}</p>
                                <p><strong>Combustível:</strong> ${carro.combustivel}</p>
                                <p><strong>Câmbio:</strong> ${carro.cambio}</p>
                                <p><strong>Cor:</strong> ${carro.cor}</p>
                                ${carro.quilometragem ? `<p><strong>Quilometragem:</strong> ${carro.quilometragem.toLocaleString('pt-BR')} km</p>` : ''}
                                ${carro.final_placa ? `<p><strong>Final da Placa:</strong> ${carro.final_placa}</p>` : ''}
                            </div>
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div style="margin-bottom: 25px;">
                        <h3 style="color: var(--primary-color); margin-bottom: 10px;">Descrição</h3>
                        <div style="background: var(--bg-dark); padding: 20px; border-radius: 8px; line-height: 1.6;">
                            ${carro.descricao.replace(/\n/g, '<br>')}
                        </div>
                    </div>

                    ${carro.detalhes ? `
                    <div style="margin-bottom: 25px;">
                        <h3 style="color: var(--primary-color); margin-bottom: 10px;">Detalhes Adicionais</h3>
                        <div style="background: var(--bg-dark); padding: 20px; border-radius: 8px; line-height: 1.6;">
                            ${carro.detalhes.replace(/\n/g, '<br>')}
                        </div>
                    </div>
                    ` : ''}

                    <!-- Contato -->
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
            `;

            document.getElementById('detalhesContent').innerHTML = detalhesHTML;
            document.getElementById('detalhesModal').style.display = 'block';
        }

        function fecharDetalhes() {
            document.getElementById('detalhesModal').style.display = 'none';
        }

        function abrirModal(src) {
            console.log('Abrindo imagem:', src);
            const modal = document.createElement('div');
            modal.className = 'modal';
            modal.style.display = 'block';
            modal.innerHTML = `
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
                <img class="modal-content" src="${src}" alt="Imagem ampliada">
            `;
            document.body.appendChild(modal);
            
            modal.onclick = function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                    document.body.removeChild(modal);
                }
            }
        }

        // Fechar modal ao clicar fora
        window.onclick = function(event) {
            const modal = document.getElementById('detalhesModal');
            if (event.target === modal) {
                fecharDetalhes();
            }
        }

        // Fechar modal com ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                fecharDetalhes();
            }
        });
    </script>
</body>
</html>