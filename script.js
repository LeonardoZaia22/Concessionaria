// Navegação entre páginas
document.addEventListener('DOMContentLoaded', function() {
    // Configurar navegação
    const navLinks = document.querySelectorAll('.nav-link');
    const pages = document.querySelectorAll('.page');
    
    // Função para mostrar página específica
    function showPage(pageId) {
        pages.forEach(page => {
            page.classList.remove('active');
        });
        
        const targetPage = document.getElementById(pageId);
        if (targetPage) {
            targetPage.classList.add('active');
        }
        
        // Salvar a página atual no localStorage
        localStorage.setItem('currentPage', pageId);
    }
    
    // Adicionar eventos de clique aos links de navegação
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetPage = this.getAttribute('data-page');
            showPage(targetPage);
        });
    });
    
    // Botões "Saiba Mais" e "Ver Detalhes"
    const infoButtons = document.querySelectorAll('.btn-info');
    infoButtons.forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.feature-card, .dashboard-card');
            const targetPage = card.getAttribute('data-page');
            showPage(targetPage);
        });
    });
    
    // Botões Voltar
    const backButtons = document.querySelectorAll('.btn-back');
    backButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetPage = this.getAttribute('data-back');
            showPage(targetPage);
        });
    });
    
    // Verificar se há uma página salva no localStorage
    const savedPage = localStorage.getItem('currentPage');
    if (savedPage) {
        showPage(savedPage);
    } else {
        showPage('home');
    }
    
    // Sistema de Login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            // Simulação de autenticação
            if (username && password) {
                // Salvar informações do usuário
                localStorage.setItem('userLoggedIn', 'true');
                localStorage.setItem('userName', username);
                
                // Atualizar nome no painel
                const userNameElement = document.getElementById('userName');
                if (userNameElement) {
                    userNameElement.textContent = username;
                }
                
                // Redirecionar para o painel
                showPage('dashboard');
                
                // Limpar formulário
                loginForm.reset();
            } else {
                alert('Por favor, preencha todos os campos!');
            }
        });
    }
    
    // Verificar se o usuário já está logado
    const userLoggedIn = localStorage.getItem('userLoggedIn');
    if (userLoggedIn === 'true') {
        const userName = localStorage.getItem('userName');
        const userNameElement = document.getElementById('userName');
        if (userNameElement && userName) {
            userNameElement.textContent = userName;
        }
    }
});