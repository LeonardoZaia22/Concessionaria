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
        // Aqui você pode adicionar formatação adicional se necessário
    });
    
    // Validação de formulários
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            // Validação básica - pode ser expandida conforme necessário
            const requiredFields = form.querySelectorAll('[required]');
            let valid = true;
            
            requiredFields.forEach(function(field) {
                if(!field.value.trim()) {
                    valid = false;
                    field.style.borderColor = 'var(--danger-color)';
                } else {
                    field.style.borderColor = '';
                }
            });
            
            if(!valid) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios.');
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
});