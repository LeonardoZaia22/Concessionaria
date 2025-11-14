<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classic Motors - Concessionária de Carros Antigos</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <a href="index.php">Classic Motors</a>
                </div>
                <div class="nav-menu">
                    <a href="index.php" class="nav-link">Início</a>
                    <a href="login.php" class="nav-link login-btn">Login</a>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>Bem-vindo à Classic Motors</h1>
                <p>Descubra a beleza atemporal dos carros clássicos. Nossa concessionária especializada oferece os veículos mais icônicos dos anos 60, 70 e 80, cuidadosamente restaurados e preservados.</p>
                <div class="hero-buttons">
                    <a href="login.php" class="btn-primary">Acessar Acervo</a>
                </div>
            </div>
            <div class="hero-image">
                <div class="car-slider">
                    <div class="car-slide active">
                        <img src="img/img05.jpg" alt="Carro clássico">
                    </div>
                </div>
            </div>
        </section>

        <section id="sobre" class="about">
            <div class="container">
                <h2>Sobre a Classic Motors</h2>
                <div class="about-content">
                    <div class="about-text">
                        <p>Há mais de 20 anos no mercado, a Classic Motors é referência em veículos clássicos e antigos. Nossa missão é conectar colecionadores e entusiastas aos carros que marcaram época.</p>
                        <p>Contamos com uma equipe especializada em restauração e manutenção, garantindo que cada veículo em nosso acervo esteja em perfeitas condições de funcionamento e conservação.</p>
                        <ul>
                            <li>Veículos originais e documentados</li>
                            <li>Processo de restauração transparente</li>
                            <li>Financiamento facilitado</li>
                            <li>Garantia de 6 meses na mecânica</li>
                        </ul>
                    </div>
                    <div class="about-image">
                        <img src="img/img02.jpg" alt="Oficina de restauração">
                    </div>
                </div>
            </div>
        </section>

        <section class="destaques">
            <div class="container">
                <h2>Destaques do Acervo</h2>
                <div class="car-grid">
                    <div class="car-card">
                        <div class="car-image">
                            <img src="img/img03.jpg" alt="Fusca">
                        </div>
                        <div class="car-info">
                            <h3>Volkswagen Fusca 1975</h3>
                            <p class="car-price">R$ 45.000,00</p>
                        </div>
                    </div>
                    <div class="car-card">
                        <div class="car-image">
                            <img src="img/img04.jpg" alt="Opala">
                        </div>
                        <div class="car-info">
                            <h3>Chevrolet Opala 1982</h3>
                            <p class="car-price">R$ 65.000,00</p>
                        </div>
                    </div>
                    <div class="car-card">
                        <div class="car-image">
                            <img src="img/img05.jpg" alt="Maverick">
                        </div>
                        <div class="car-info">
                            <h3>Ford Maverick 1974</h3>
                            <p class="car-price">R$ 75.000,00</p>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <a href="login.php" class="btn-primary">Ver Acervo Completo</a>
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
                    <p>Endereço: Rua dos Clássicos, 123 - São Paulo, SP</p>
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