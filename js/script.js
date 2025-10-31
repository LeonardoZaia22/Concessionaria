// Funções para interatividade do site
document.addEventListener('DOMContentLoaded', function() {
    // Menu mobile (para futura implementação)
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if(menuToggle) {
        menuToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
    }
    
    // Formatação de preços
    const priceElements = document.querySelectorAll('.car-price');
    priceElements.forEach(function(element) {
        const price = element.textContent;
        // Formatação adicional pode ser adicionada aqui
    });
    
    // Validação de formulários
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let valid = true;
            
            requiredFields.forEach(function(field) {
                if(!field.value.trim()) {
                    valid = false;
                    field.style.borderColor = 'var(--danger-color)';
                    
                    // Adicionar mensagem de erro
                    let errorMsg = field.parentNode.querySelector('.error-msg');
                    if(!errorMsg) {
                        errorMsg = document.createElement('small');
                        errorMsg.className = 'error-msg';
                        errorMsg.style.color = 'var(--danger-color)';
                        errorMsg.textContent = 'Este campo é obrigatório';
                        field.parentNode.appendChild(errorMsg);
                    }
                } else {
                    field.style.borderColor = '';
                    const errorMsg = field.parentNode.querySelector('.error-msg');
                    if(errorMsg) {
                        errorMsg.remove();
                    }
                }
            });
            
            if(!valid) {
                e.preventDefault();
                // Scroll para o primeiro erro
                const firstError = form.querySelector('[required]:invalid');
                if(firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    });
    
    // Efeito de hover nos cards de carro
    const carCards = document.querySelectorAll('.car-card');
    carCards.forEach(function(card) {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Botões de informação
    const infoButtons = document.querySelectorAll('.btn-secondary');
    infoButtons.forEach(function(button) {
        if(button.textContent.includes('Informações')) {
            button.addEventListener('click', function() {
                alert('Para mais informações sobre este veículo, entre em contato conosco:\n📞 (11) 3456-7890\n✉️ vendas@classicmotors.com.br');
            });
        }
    });
    
    // Feedback visual para links
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            navLinks.forEach(function(l) {
                l.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
});

// Função para mostrar loading em botões
function showLoading(button) {
    const originalText = button.innerHTML;
    button.innerHTML = '<span>Carregando...</span>';
    button.disabled = true;
    
    return function() {
        button.innerHTML = originalText;
        button.disabled = false;
    };
}