// JavaScript atualizado para o site do profissional de tricologia
// Implementação do carrossel automático e modal popup

document.addEventListener('DOMContentLoaded', function() {
    // ===== Carrossel Automático =====
    const carousel = document.getElementById('resultsCarousel');
    const carouselItems = carousel ? carousel.querySelectorAll('.carousel-item') : [];
    const indicators = document.querySelectorAll('.carousel-indicator');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    let currentSlide = 0;
    const totalSlides = carouselItems.length;
    let slideInterval;
    
    // Inicializar o carrossel
    function initCarousel() {
        // Posicionar os slides
        carouselItems.forEach((item, index) => {
            item.style.transform = `translateX(${index * 100}%)`;
        });
        
        // Iniciar o carrossel automático
        startCarouselInterval();
        
        // Adicionar eventos aos botões e indicadores
        if (prevBtn) prevBtn.addEventListener('click', prevSlide);
        if (nextBtn) nextBtn.addEventListener('click', nextSlide);
        
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                goToSlide(index);
            });
        });
        
        // Pausar o carrossel ao passar o mouse
        if (carousel) {
            carousel.addEventListener('mouseenter', () => {
                clearInterval(slideInterval);
            });
            
            // Retomar o carrossel ao remover o mouse
            carousel.addEventListener('mouseleave', () => {
                startCarouselInterval();
            });
        }
    }
    
    // Iniciar o intervalo do carrossel
    function startCarouselInterval() {
        clearInterval(slideInterval);
        slideInterval = setInterval(() => {
            nextSlide();
        }, 5000); // Mudar slide a cada 5 segundos
    }
    
    // Ir para o próximo slide
    function nextSlide() {
        currentSlide = (currentSlide + 2) % totalSlides;
        updateCarousel();
    }
    
    // Ir para o slide anterior
    function prevSlide() {
        currentSlide = (currentSlide - 2 + totalSlides) % totalSlides;
        updateCarousel();
    }
    
    // Ir para um slide específico
    function goToSlide(index) {
        currentSlide = index;
        updateCarousel();
    }
    
    // Atualizar a posição do carrossel
    function updateCarousel() {
        // Atualizar a posição dos slides
        carouselItems.forEach((item, index) => {
            item.style.transform = `translateX(${(index - currentSlide) * 100}%)`;
        });
        
        // Atualizar os indicadores
        indicators.forEach((indicator, index) => {
            if (index === currentSlide) {
                indicator.classList.add('active');
            } else {
                indicator.classList.remove('active');
            }
        });
        
        // Reiniciar o intervalo
        startCarouselInterval();
    }
    
    // Inicializar o carrossel se existir na página
    if (carousel && carouselItems.length > 0) {
        initCarousel();
    }
    
    // ===== Menu Mobile =====
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('nav ul');
    
    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });
    }
    
    // ===== Modal do Painel de Acesso (CORRIGIDO) =====
    const accessBtn = document.querySelector('a[href="#access-panel"]');
    const accessPanel = document.getElementById('accessPanel');
    const closeModal = document.querySelector('.close-modal');
    
    if (accessBtn && accessPanel) {
        // Abrir modal ao clicar no botão
        accessBtn.addEventListener('click', (e) => {
            e.preventDefault();
            accessPanel.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Impedir rolagem da página
        });
        
        // Fechar modal ao clicar no X
        if (closeModal) {
            closeModal.addEventListener('click', () => {
                accessPanel.style.display = 'none';
                document.body.style.overflow = ''; // Restaurar rolagem
            });
        }
        
        // Fechar modal ao clicar fora dele
        window.addEventListener('click', (e) => {
            if (e.target === accessPanel) {
                accessPanel.style.display = 'none';
                document.body.style.overflow = ''; // Restaurar rolagem
            }
        });
    }
    
    // ===== Formulário de Login =====
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
    
        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;
        const id_job = 'Login';
    
        // Fecha o modal antes de mostrar o loading
        accessPanel.style.display = 'none';
        document.body.style.overflow = '';
    
        Swal.fire({
            title: 'Entrando...',
            text: 'Aguarde enquanto verificamos seus dados.',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
    
        try {
            const response = await fetch('painel/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ email, password, id_job })
            });
    
            const result = await response.json();
    
            if (result.success) {
                window.location.href = result.redirect || 'painel/painel.php';
            } else {
                Swal.fire({
                    title: 'Erro!',
                    text: result.message || 'Email ou senha incorretos.',
                    icon: 'error'
                }).then(() => {
                    // Reabre o modal
                    accessPanel.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                });
            }
        } catch (error) {
            Swal.fire({
                title: 'Erro!',
                text: 'Não foi possível conectar com o servidor.',
                icon: 'error'
            }).then(() => {
                // Reabre o modal
                accessPanel.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });
        }
    });      
    
    // ===== Animação de Scroll =====
    const fadeElements = document.querySelectorAll('.fade-in');
    
    function checkFade() {
        fadeElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;
            
            if (elementTop < window.innerHeight - elementVisible) {
                element.classList.add('visible');
            }
        });
    }
    
    // Verificar elementos visíveis no carregamento
    checkFade();
    
    // Verificar elementos visíveis ao rolar
    window.addEventListener('scroll', checkFade);
    
    // ===== Navegação Suave =====
    const navLinks = document.querySelectorAll('nav a[href^="#"]');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
                
                // Fechar menu mobile se estiver aberto
                if (navMenu && navMenu.classList.contains('active')) {
                    navMenu.classList.remove('active');
                }
            }
        });
    });
    
    // ===== Cabeçalho com Scroll =====
    const header = document.getElementById('header');
    
    function updateHeader() {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }
    
    // Verificar posição do scroll no carregamento
    updateHeader();
    
    // Verificar posição do scroll ao rolar
    window.addEventListener('scroll', updateHeader);
});

// ===== Modal do Painel Adm Registro =====
const accessBtnReg = document.querySelector('a[href="#access-panelReg"]');
const accessPanelReg = document.getElementById('accessPanelReg');
const closeModalReg = document.querySelector('.close-modalReg');

if (accessBtnReg && accessPanelReg) {
    // Abrir modal ao clicar no botão
    accessBtnReg.addEventListener('click', (e) => {
        e.preventDefault();
        accessPanel.style.display = 'none';
        accessPanelReg.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Impedir rolagem da página
    });
    
    // Fechar modal ao clicar no X
    if (closeModalReg) {
        closeModalReg.addEventListener('click', () => {
            accessPanelReg.style.display = 'none';
            document.body.style.overflow = ''; // Restaurar rolagem
        });
    }
    
    // Fechar modal ao clicar fora dele
    window.addEventListener('click', (e) => {
        if (e.target === accessPanelReg) {
            accessPanelReg.style.display = 'none';
            document.body.style.overflow = ''; // Restaurar rolagem
        }
    });
}

// ===== Formulário de Registrar =====
RegForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const nome = document.getElementById('Regnome').value;
    const email = document.getElementById('RegloginEmail').value;
    const telefone = document.getElementById('Regtelefone').value;
    const cpf = document.getElementById('Regcpf').value;
    const password = document.getElementById('RegloginPassword').value;
    const password_conf = document.getElementById('RegloginPasswordConf').value;

    // Fecha o modal antes de mostrar o loading
    accessPanelReg.style.display = 'none';
    document.body.style.overflow = '';

    Swal.fire({
        title: 'Entrando...',
        text: 'Aguarde enquanto verificamos seus dados.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    try {
        const responseReg = await fetch('painel/registrar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ nome, email, telefone, cpf, password, password_conf })
        });

        const resultReg = await responseReg.json();

        if (resultReg.success) {
            window.location.href = resultReg.redirect || 'painel/painel.php';
        } else {
            Swal.fire({
                title: 'Erro!',
                text: resultReg.message || 'Dados incorretos',
                icon: 'error'
            }).then(() => {
                // Reabre o modal
                accessPanelReg.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });
        }
    } catch (error) {
        Swal.fire({
            title: 'Erro!',
            text: 'Não foi possível conectar com o servidor.',
            icon: 'error'
        }).then(() => {
            // Reabre o modal
            accessPanelReg.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    }
});

// ===== Modal do Painel Adm Recuperar =====
const accessBtnRec = document.querySelector('a[href="#access-panelRec"]');
const accessPanelRec = document.getElementById('accessPanelRec');
const closeModalRec = document.querySelector('.close-modalRec');

if (accessBtnRec && accessPanelRec) {
    // Abrir modal ao clicar no botão
    accessBtnRec.addEventListener('click', (e) => {
        e.preventDefault();
        accessPanel.style.display = 'none';
        accessPanelRec.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Impedir rolagem da página
    });
    
    // Fechar modal ao clicar no X
    if (closeModalRec) {
        closeModalRec.addEventListener('click', () => {
            accessPanelRec.style.display = 'none';
            document.body.style.overflow = ''; // Restaurar rolagem
        });
    }
    
    // Fechar modal ao clicar fora dele
    window.addEventListener('click', (e) => {
        if (e.target === accessPanelRec) {
            accessPanelRec.style.display = 'none';
            document.body.style.overflow = ''; // Restaurar rolagem
        }
    });
}

// ===== Formulário de Recuperar =====
RecForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const email = document.getElementById('RecloginEmail').value;
    const cpf = document.getElementById('Reccpf').value;

    // Fecha o modal antes de mostrar o loading
    accessPanelRec.style.display = 'none';
    document.body.style.overflow = '';

    Swal.fire({
        title: 'Entrando...',
        text: 'Aguarde enquanto verificamos seus dados.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    try {
        const responseRec = await fetch('painel/recuperar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ email, cpf })
        });

        const resultRec = await responseRec.json();

        if (resultRec.success) {
            window.location.href = resultRec.redirect || 'index.php';
        } else {
            Swal.fire({
                title: 'Erro!',
                text: resultRec.message || 'Dados Inválidos.',
                icon: 'error'
            }).then(() => {
                // Reabre o modal
                accessPanelRec.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });
        }
    } catch (error) {
        Swal.fire({
            title: 'Erro!',
            text: 'Não foi possível conectar com o servidor.',
            icon: 'error'
        }).then(() => {
            // Reabre o modal
            accessPanelRec.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    }
});