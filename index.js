// index.js
// Menu Mobile
const menuToggle = document.querySelector('.menu-toggle');
const nav = document.querySelector('nav ul');

if (menuToggle && nav) {
    menuToggle.addEventListener('click', () => {
        nav.classList.toggle('active');
        menuToggle.classList.toggle('active');
    });
}

// Fechar menu ao clicar em um link
const navLinks = document.querySelectorAll('nav ul li a');
navLinks.forEach(link => {
    link.addEventListener('click', () => {
        if (nav) nav.classList.remove('active');
        if (menuToggle) menuToggle.classList.remove('active');
    });
});

// Smooth Scroll para links internos
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 80,
                behavior: 'smooth'
            });
        }
    });
});

// Carousel Hero - Auto avanço
const carouselItems = document.querySelectorAll('.carousel-item');
const carouselIndicators = document.querySelectorAll('.carousel-indicators span');
const carouselPrev = document.querySelector('.carousel-prev');
const carouselNext = document.querySelector('.carousel-next');

if (carouselItems.length > 0) {
    let currentCarouselIndex = 0;

    function showCarouselItem(index) {
        carouselItems.forEach(item => item.classList.remove('active'));
        carouselIndicators.forEach(indicator => indicator.classList.remove('active'));
        
        carouselItems[index].classList.add('active');
        carouselIndicators[index].classList.add('active');
        currentCarouselIndex = index;
    }

    function nextCarouselItem() {
        let nextIndex = currentCarouselIndex + 1;
        if (nextIndex >= carouselItems.length) nextIndex = 0;
        showCarouselItem(nextIndex);
    }

    function prevCarouselItem() {
        let prevIndex = currentCarouselIndex - 1;
        if (prevIndex < 0) prevIndex = carouselItems.length - 1;
        showCarouselItem(prevIndex);
    }

    if (carouselNext) carouselNext.addEventListener('click', nextCarouselItem);
    if (carouselPrev) carouselPrev.addEventListener('click', prevCarouselItem);

    carouselIndicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            showCarouselItem(index);
        });
    });

    // Auto-advance carousel a cada 4 segundos
    let carouselInterval = setInterval(nextCarouselItem, 4000);

    // Pause carousel on hover
    const carousel = document.querySelector('.carousel');
    if (carousel) {
        carousel.addEventListener('mouseenter', () => {
            clearInterval(carouselInterval);
        });

        carousel.addEventListener('mouseleave', () => {
            carouselInterval = setInterval(nextCarouselItem, 4000);
        });
    }
}

// Modal da Lenda Porsche
const porscheModal = document.getElementById('porsche-modal');
const saibaMaisBtn = document.getElementById('saiba-mais-porsche');
const closePorscheModal = document.getElementById('close-porsche-modal');

// Abrir modal Porsche
if (saibaMaisBtn && porscheModal) {
    saibaMaisBtn.addEventListener('click', () => {
        porscheModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    });
}

// Fechar modal Porsche
if (closePorscheModal && porscheModal) {
    closePorscheModal.addEventListener('click', () => {
        porscheModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
}

// Fechar modal Porsche ao clicar fora
window.addEventListener('click', (e) => {
    if (e.target === porscheModal) {
        porscheModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
});

// Carousel Porsche Modal - Auto avanço
const porscheCarouselItems = document.querySelectorAll('.porsche-carousel-item');
const porscheCarouselIndicators = document.querySelectorAll('.porsche-carousel-indicators span');

if (porscheCarouselItems.length > 0) {
    let currentPorscheCarouselIndex = 0;

    function showPorscheCarouselItem(index) {
        porscheCarouselItems.forEach(item => item.classList.remove('active'));
        porscheCarouselIndicators.forEach(indicator => indicator.classList.remove('active'));
        
        porscheCarouselItems[index].classList.add('active');
        porscheCarouselIndicators[index].classList.add('active');
        currentPorscheCarouselIndex = index;
    }

    function nextPorscheCarouselItem() {
        let nextIndex = currentPorscheCarouselIndex + 1;
        if (nextIndex >= porscheCarouselItems.length) nextIndex = 0;
        showPorscheCarouselItem(nextIndex);
    }

    porscheCarouselIndicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            showPorscheCarouselItem(index);
        });
    });

    // Auto-advance carousel a cada 5 segundos
    let porscheCarouselInterval = setInterval(nextPorscheCarouselItem, 5000);

    // Pause carousel on hover
    const porscheCarousel = document.querySelector('.porsche-carousel');
    if (porscheCarousel) {
        porscheCarousel.addEventListener('mouseenter', () => {
            clearInterval(porscheCarouselInterval);
        });

        porscheCarousel.addEventListener('mouseleave', () => {
            porscheCarouselInterval = setInterval(nextPorscheCarouselItem, 5000);
        });
    }
}

// Modal de Detalhes do Carro
const modal = document.getElementById('carro-modal');
const carroCards = document.querySelectorAll('.carro-card');

// Dados dos carros
const carrosData = {
    'porsche-911t-targa': {
        nome: '1971 Porsche 911T Targa',
        preco: 'R$ 850.000',
        ano: '1971',
        quilometragem: '45.000 km',
        transmissao: 'Manual 5 velocidades',
        motor: '2.4L Flat-6',
        potencia: '140 HP',
        combustivel: 'Gasolina',
        cor: 'Verde Metálico',
        descricao: 'Este Porsche 911T Targa 1971 é uma verdadeira obra de arte sobre rodas. Com apenas 45.000 km rodados, este exemplar mantém todas as suas características originais intactas. O motor 2.4L Flat-6 entrega uma sonoridade inconfundível, enquanto a transmissão manual de 5 velocidades proporciona uma experiência de condução pura e conectada com a estrada. O interior em couro bege foi meticulosamente preservado, e a carroceria apresenta a pintura verde metálica original, recentemente realçada por nossos especialistas. Um investimento que só valoriza com o tempo.',
        imagens: [
            'img/img01-1971-Porsche-911T-Targa.jpg',
            'img/img02-Porsche-Targa.jpg',
            'img/img03-Porsche-Interior.jpg'
        ],
        features: [
            'Documentação original completa',
            'Manual do proprietário e ferramentas',
            'Histórico de manutenção detalhado',
            'Pintura 100% original',
            'Interior em couro bege conservado',
            'Sistema elétrico revisado',
            'Suspensão e freios originais',
            'Rodas de liga leve originais'
        ]
    },
    'chevrolet-opala': {
        nome: '1979 Chevrolet Opala',
        preco: 'R$ 95.000',
        ano: '1979',
        quilometragem: '78.000 km',
        transmissao: 'Manual 4 velocidades',
        motor: '4.1L 6 cilindros',
        potencia: '138 HP',
        combustivel: 'Gasolina',
        cor: 'Marrom',
        descricao: 'Este Chevrolet Opala 1979 representa o auge da indústria automotiva brasileira. Com apenas 78.000 km rodados, o carro mantém todas as suas características originais, incluindo o robusto motor 4.1L de 6 cilindros e a suave transmissão manual. O interior em veludo marrom está excepcionalmente conservado, com todos os detalhes funcionando perfeitamente. A carroceria foi recentemente polida e tratada, mantendo o brilho característico da pintura marrom original. Um clássico que evoca nostalgia e sofisticação em cada detalhe.',
        imagens: [
            'img/img04-1979-Chevrolet-Opala.jpg',
            'img/img04-1979-Chevrolet-Opala-2.jpg',
            'img/img04-1979-Chevrolet-Opala-3.jpg'
        ],
        features: [
            'Documentação e histórico completos',
            'Motor 4.1L original revisado',
            'Interior em veludo marrom conservado',
            'Pintura original marrom metálico',
            'Sistema de som original funcionando',
            'Rodas de aro 14 originais',
            'Todos os vidros e espelhos originais',
            'Tapetes originais preservados'
        ]
    },
    'ford-maverick': {
        nome: '1977 Ford Maverick',
        preco: 'R$ 87.000',
        ano: '1977',
        quilometragem: '62.000 km',
        transmissao: 'Manual 4 velocidades',
        motor: '3.0L V6',
        potencia: '130 HP',
        combustivel: 'Gasolina',
        cor: 'Laranja',
        descricao: 'O Ford Maverick 1977 é um ícone do design automotivo brasileiro. Com apenas 62.000 km rodados, este exemplar mantém sua essência original em cada detalhe. O motor 3.0L V6 entrega performance robusta e confiável, enquanto a transmissão manual proporciona uma condução envolvente. A pintura laranja, característica da época, foi cuidadosamente preservada e realçada. O interior em couro preto foi recentemente tratado e mantém seu charme vintage. Uma peça rara da história automotiva nacional, perfeita para colecionadores.',
        imagens: [
            'img/img05-1977-Ford-Maverick.jpg',
            'img/img05-1977-Ford-Maverick-2.jpg',
            'img/img05-1977-Ford-Maverick-3.jpg'
        ],
        features: [
            'Documentação original completa',
            'Motor V6 3.0L revisado',
            'Pintura laranja original',
            'Interior em couro preto conservado',
            'Instrumentação completa funcionando',
            'Sistema de escape original',
            'Faróis e lanternas originais',
            'Calhas cromadas preservadas'
        ]
    },
    'dodge-charger': {
        nome: '1978 Dodge Charger',
        preco: 'R$ 120.000',
        ano: '1978',
        quilometragem: '53.000 km',
        transmissao: 'Automático 3 velocidades',
        motor: '5.2L V8',
        potencia: '150 HP',
        combustivel: 'Gasolina',
        cor: 'Vermelho',
        descricao: 'Este Dodge Charger 1978 é um muscle car americano em estado de conservação impecável. Com apenas 53.000 km rodados, o carro mantém todas as suas características originais, incluindo o potente motor 5.2L V8 e a transmissão automática. A pintura vermelha está brilhante, e o interior em couro preto está como novo. Um verdadeiro símbolo de poder e estilo dos anos 70, perfeito para quem busca um carro com personalidade forte e presença marcante.',
        imagens: [
            'img/img07-1978-Dodge-Charger.jpg',
            'img/img07-1978-Dodge-Charger-2.jpg',
            'img/img07-1978-Dodge-Charger-3.jpg'
        ],
        features: [
            'Documentação americana original',
            'Motor V8 5.2L revisado',
            'Transmissão automática original',
            'Pintura vermelha metálica',
            'Interior em couro preto',
            'Rodas de aro 15 originais',
            'Sistema de som premium',
            'Ar condicionado funcionando'
        ]
    },
    'volkswagen-fusca': {
        nome: '1973 Volkswagen Fusca',
        preco: 'R$ 45.000',
        ano: '1973',
        quilometragem: '89.000 km',
        transmissao: 'Manual 4 velocidades',
        motor: '1.6L 4 cilindros',
        potencia: '50 HP',
        combustivel: 'Gasolina',
        cor: 'Azul',
        descricao: 'Este Volkswagen Fusca 1973 é um ícone do automobilismo brasileiro em excelente estado de conservação. Com 89.000 km rodados, o carro mantém todas as suas características originais, incluindo o motor 1.6L de 4 cilindros e a transmissão manual de 4 velocidades. A pintura azul está conservada, e o interior em tecido cinza foi recentemente higienizado. Um clássico atemporal que continua conquistando corações com seu charme simples e eficiência comprovada.',
        imagens: [
            'img/img08-1973-Volkswagen-Fusca.jpg',
            'img/img08-1973-Volkswagen-Fusca-2.jpg',
            'img/img08-1973-Volkswagen-Fusca-3.jpg'
        ],
        features: [
            'Documentação completa',
            'Motor 1.6L original',
            'Pintura azul original',
            'Interior em tecido original',
            'Sistema elétrico revisado',
            'Rodas de aro 15 originais',
            'Para-choques cromados',
            'Faróis originais'
        ]
    },
    'fiat-147': {
        nome: '1982 Fiat 147',
        preco: 'R$ 32.000',
        ano: '1982',
        quilometragem: '67.000 km',
        transmissao: 'Manual 4 velocidades',
        motor: '1.3L 4 cilindros',
        potencia: '58 HP',
        combustivel: 'Álcool',
        cor: 'Amarelo',
        descricao: 'Este Fiat 147 1982 é um clássico dos compactos brasileiros em ótimo estado de conservação. Com 67.000 km rodados, o carro mantém suas características originais, incluindo o motor 1.3L de 4 cilindros movido a álcool e a transmissão manual de 4 velocidades. A pintura amarela está bem conservada, e o interior em tecido marrom está original. Um pedaço da história do automobilismo nacional que representa uma era de inovação e praticidade.',
        imagens: [
            'img/img12-1982-Fiat-147.jpg',
            'img/img12-1982-Fiat-147-2.jpg',
            'img/img12-1982-Fiat-147-3.jpg'
        ],
        features: [
            'Documentação em dia',
            'Motor 1.3L a álcool',
            'Pintura amarela original',
            'Interior em tecido marrom',
            'Sistema elétrico revisado',
            'Rodas de aro 13 originais',
            'Vidros e espelhos originais',
            'Lanternas traseiras características'
        ]
    }
};

// Abrir modal ao clicar em um carro
carroCards.forEach(card => {
    card.addEventListener('click', () => {
        const carId = card.getAttribute('data-car');
        const carData = carrosData[carId];
        
        if (carData) {
            openCarModal(carData);
        }
    });
});

// Função para abrir o modal com os dados do carro
function openCarModal(carData) {
    const modalBody = document.querySelector('#carro-modal .modal-body');
    
    // Criar o conteúdo do modal
    modalBody.innerHTML = `
        <div class="car-modal-content">
            <div class="car-modal-images">
                <div class="car-modal-main-image">
                    <img src="${carData.imagens[0]}" alt="${carData.nome}">
                </div>
                ${carData.imagens.length > 1 ? `
                <div class="car-modal-thumbnails">
                    ${carData.imagens.map((img, index) => `
                        <div class="car-modal-thumbnail ${index === 0 ? 'active' : ''}">
                            <img src="${img}" alt="${carData.nome}">
                        </div>
                    `).join('')}
                </div>
                ` : ''}
            </div>
            <div class="car-modal-info">
                <h2>${carData.nome}</h2>
                <div class="car-modal-price">${carData.preco}</div>
                <p class="car-modal-description">${carData.descricao}</p>
                
                <div class="car-modal-details">
                    <div class="detail-item">
                        <span class="detail-label">Ano:</span>
                        <span class="detail-value">${carData.ano}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Quilometragem:</span>
                        <span class="detail-value">${carData.quilometragem}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Transmissão:</span>
                        <span class="detail-value">${carData.transmissao}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Motor:</span>
                        <span class="detail-value">${carData.motor}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Potência:</span>
                        <span class="detail-value">${carData.potencia}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Combustível:</span>
                        <span class="detail-value">${carData.combustivel}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Cor:</span>
                        <span class="detail-value">${carData.cor}</span>
                    </div>
                </div>
                
                ${carData.features ? `
                <div class="car-modal-features">
                    <h4>Características Especiais</h4>
                    <ul>
                        ${carData.features.map(feature => `<li>${feature}</li>`).join('')}
                    </ul>
                </div>
                ` : ''}
            </div>
        </div>
    `;
    
    // Adicionar funcionalidade às miniaturas (se houver mais de uma imagem)
    if (carData.imagens.length > 1) {
        const thumbnails = modalBody.querySelectorAll('.car-modal-thumbnail');
        const mainImage = modalBody.querySelector('.car-modal-main-image img');
        
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', () => {
                thumbnails.forEach(t => t.classList.remove('active'));
                thumb.classList.add('active');
                mainImage.src = thumb.querySelector('img').src;
            });
        });
    }
    
    // Mostrar o modal
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Fechar modal - usar event delegation para o botão de fechar
document.addEventListener('click', function(e) {
    // Fechar modal do carro ao clicar no X
    if (e.target.classList.contains('close-modal')) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    // Fechar modal do carro ao clicar fora
    if (e.target === modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
});

// Formulário de contato
const formContato = document.getElementById('form-contato');
if (formContato) {
    formContato.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Aqui você pode adicionar a lógica para enviar o formulário
        alert('Mensagem enviada com sucesso! Entraremos em contato em breve.');
        formContato.reset();
    });
}

// Adicionar classe ao header quando scrollar
window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    if (window.scrollY > 100) {
        header.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.5)';
    } else {
        header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.5)';
    }
});

// Efeito de digitação no título do hero
const heroTitle = document.querySelector('.hero-content h2');
if (heroTitle) {
    const text = heroTitle.textContent;
    heroTitle.textContent = '';
    
    let i = 0;
    const typeWriter = () => {
        if (i < text.length) {
            heroTitle.textContent += text.charAt(i);
            i++;
            setTimeout(typeWriter, 100);
        }
    };
    
    // Iniciar efeito após um breve delay
    setTimeout(typeWriter, 1000);
}