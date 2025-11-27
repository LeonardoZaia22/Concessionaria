<header>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="restrita.php">Classic Motors</a>
            </div>
            <div class="nav-menu">
                <a href="restrita.php" class="nav-link">Início</a>
                <?php if($_SESSION['nivel'] === 'admin'): ?>
                <a href="admin_carros.php" class="nav-link">Gerenciar Carros</a>
                <?php endif; ?>
                <a href="painel.php" class="nav-link">Meu Perfil</a>
                <a href="logout.php" class="nav-link logout-btn">Sair</a>
            </div>
        </div>
    </nav>
</header>