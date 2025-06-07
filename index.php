<?php

require('config/database.php');

//Ajustar Telefone
$ddd = substr($config_telefone, 0, 2);
$prefixo = substr($config_telefone, 2, 5);
$sufixo = substr($config_telefone, 7);
$telefone = "($ddd) $prefixo-$sufixo";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $doc_nome = mysqli_real_escape_string($conn_msqli, $_POST['name']);
    $doc_telefone = preg_replace('/[^\d]/', '',mysqli_real_escape_string($conn_msqli, $_POST['phone']));
    $message = mysqli_real_escape_string($conn_msqli, $_POST['message']);

   
           if ($envio_whatsapp === 'ativado') {
               $doc_telefonewhats = "5571997417190";
               $msg_whatsapp = "Olá Carol, alguém mandou uma mensagem pra você pelo seu site.\n\n".
                   "Nome: $doc_nome\n".
                   "Telefone: $doc_telefone\n".
                   "Mensagem: $message\n";
   
               enviarWhatsapp($doc_telefonewhats, $msg_whatsapp);
           }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caroline Ferraz | Especialista em Tricologia</title>
    <meta name="description" content="Tratamentos especializados para cabelo e couro cabeludo com abordagem personalizada. Consulte nosso especialista em tricologia.">
    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/title-highlights.css">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Cabeçalho -->
    <header id="header">
        <div class="container header-container">
            <a href="#" class="logo">
                <span>Caroline Ferraz</span>
            </a>
            
            <nav>
                <ul>
                    <li><a href="#home">Início</a></li>
                    <li><a href="#services">Programa Haircupere</a></li>
                    <li><a href="#results">Resultados</a></li>
                    <li><a href="#reviews">Depoimentos</a></li>
                    <li><a href="#about">Sobre</a></li>
                    <li><a href="#contact">Contato</a></li>
                </ul>
                <button class="menu-toggle" aria-label="Menu">
                    <i class="fas fa-bars"></i>
                </button>
            </nav>
            
            <a href="#access-panel" class="btn btn-primary">Acesse sua Conta</a>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text fade-in">
                    <h1>Farmacêutica Especialista em Queda, Calvície e Crescimento</h1>
                    <p class="mb-4">Oferecemos cuidado capilar com ciência, plano exclusivo e acompanhamento real para recuperar saúde e beleza dos seus cabelos.</p>
                    <a href="https://wa.me/5571991293370?text=Ola%20Carol%20tudo%20bem?%20Gostaria%20de%20agendar%20uma%20consulta!" target="_blank" class="btn btn-primary btn-lg">Agende sua Consulta</a>
                </div>
                
                <div class="hero-image fade-in">
                    <div class="hero-img-container">
                        <!-- Placeholder para foto do profissional -->
                        <img src="images/carol.jpeg" alt="Caroline Ferraz" class="hero-img">
                    </div>
                    <div class="hero-shape hero-shape-1"></div>
                    <div class="hero-shape hero-shape-2"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Serviços -->
    <section id="services2" class="section bg-light">
        <div class="container">
            <h2 class="section-title">Você precisa de um atendimento especializado se:</h2>
            
            <div class="grid grid-1 gap-lg">
                <!-- Serviço 1 -->
                <div class="card fade-in">
                    <div class="card-body">
                        <p class="section-subtitle text-justify">
                            <i class="fas fa-check-square text-primary"></i> Está passando por uma queda de cabelo acentuada
                            <br><i class="fas fa-check-square text-primary"></i> Sente os cabelos mais finos e o couro cabeludo aparente
                            <br><i class="fas fa-check-square text-primary"></i> Têm falhas no couro cabeludo
                            <br><i class="fas fa-check-square text-primary"></i> Percebe que seu volume reduziu nos últimos anos
                            <br><i class="fas fa-check-square text-primary"></i> Está cansado(a) de “tentar de tudo” e não ter resultados</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Serviços -->
    <section id="services" class="section bg-light">
        <div class="container">
            <h2 class="section-title">Programa Haircupere</h2>
            <p class="section-subtitle">Descubra o Haircupere - O programa que une ciência, acolhimento e resulados reais</p>
            
            <div class="grid grid-3 gap-lg">
                <!-- Serviço 1 -->
                <div class="card fade-in">
                    <div class="card-body">
                        <h3 class="card-title">Descobrindo a raiz do problema</h3>
                        <p class="card-text">Para começar, precisamos realizar um mapeamento detalhado da sua queixa, histórico de saúde, estilo de vida, fatores que afetam os resultados, alimentação, qualidade do sono, entre outros.
                            <br>Também vamos identificar alterações que possa existir nos exames laboratoriais, realizar teste especifico e análisar seu couro cabeludo com Micróscopico digital-Tricoscópico</p>
                    </div>
                </div>
                
                <!-- Serviço 2 -->
                <div class="card fade-in">
                    <div class="card-body">
                        <h3 class="card-title">Construindo seu plano de tratamento exclusivo</h3>
                        <p class="card-text">É hora de elaborar o plano de tratamento desenvolvido conforme sua necessidade, então aqui será o momento para trabalharmos com estratégias e técnicas personalizadas e comprovadas cientificamente.
                            <br>Também iremos trabalhar o conceito de tratamento IN & OUT tratando de dentro para fora e de fora para dentro com um Home Care tópico e Oral.
                            <br>Além de estabelecer um plano de ação para ajuste de saúde e rotina afinal cabelo e saúde precisam andar juntos.</p>
                        <a href="https://wa.me/5571991293370?text=Ola%20Carol%20tudo%20bem?%20Gostaria%20de%20saber%20mais%20do%20programa%20Haircupere!" target="_blank" class="btn btn-outline">Saiba Mais sobre o Haircupere</a>
                    </div>
                </div>
                
                <!-- Serviço 3 -->
                <div class="card fade-in">
                    <div class="card-body">
                        <h3 class="card-title">Suporte e monitoramento</h3>
                        <p class="card-text">Oba, esse é o passo que amamos! Aqui você terá um suporte contínuo para garantir sucesso nos seus resultados.
                            <br>Você terá acesso a Materiais de apoio para te ajudar na organização e planejamento da sua rotina.
                            <br>Suporte on-line para você tirar suas dúvidas sempre que quiser e apoio para que você não desista!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Carrossel de Resultados -->
    <section id="results" class="section">
        <div class="container">
            <h2 class="section-title">Resultados Transformadores</h2>
            <p class="section-subtitle">Veja a diferença que nossos tratamentos fazem na vida dos nossos pacientes</p>
            
            <div class="carousel fade-in">
                <div class="carousel-inner" id="resultsCarousel">

                    <!-- Slide 1 -->
                    <div class="carousel-item">
                        <div class="grid">
                            <div>
                                <img src="images/resultado_01.jpg" alt="Antes do Tratamento" class="carousel-img">
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="carousel-item">
                        <div class="grid">
                            <div>
                                <img src="images/resultado_02.jpg" alt="Antes do Tratamento" class="carousel-img">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Slide 3 -->
                    <div class="carousel-item">
                        <div class="grid">
                            <div>
                                <img src="images/resultado_03.jpg" alt="Antes do Tratamento" class="carousel-img">
                            </div>
                        </div>
                    </div>

                    <!-- Slide 4 -->
                    <div class="carousel-item">
                        <div class="grid">
                            <div>
                                <img src="images/resultado_04.jpg" alt="Antes do Tratamento" class="carousel-img">
                            </div>
                        </div>
                    </div>

                    <!-- Slide 5 -->
                    <div class="carousel-item">
                        <div class="grid">
                            <div>
                                <img src="images/resultado_05.jpg" alt="Antes do Tratamento" class="carousel-img">
                            </div>
                        </div>
                    </div>

                    <!-- Slide 1 -->
                    <div class="carousel-item">
                        <div class="grid">
                            <div>
                                <img src="images/resultado_01.jpg" alt="Antes do Tratamento" class="carousel-img">
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="carousel-item">
                        <div class="grid">
                            <div>
                                <img src="images/resultado_02.jpg" alt="Antes do Tratamento" class="carousel-img">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Slide 3 -->
                    <div class="carousel-item">
                        <div class="grid">
                            <div>
                                <img src="images/resultado_03.jpg" alt="Antes do Tratamento" class="carousel-img">
                            </div>
                        </div>
                    </div>

                    <!-- Slide 4 -->
                    <div class="carousel-item">
                        <div class="grid">
                            <div>
                                <img src="images/resultado_04.jpg" alt="Antes do Tratamento" class="carousel-img">
                            </div>
                        </div>
                    </div>

                    <!-- Slide 5 -->
                    <div class="carousel-item">
                        <div class="grid">
                            <div>
                                <img src="images/resultado_05.jpg" alt="Antes do Tratamento" class="carousel-img">
                            </div>
                        </div>
                    </div>
                    

                </div>
                
                <div class="carousel-controls">
                    <button class="carousel-control carousel-control-prev" id="prevBtn">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="carousel-control carousel-control-next" id="nextBtn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p>Estes resultados demonstram a eficácia dos nossos tratamentos especializados. Os resultados individuais podem variar de acordo com cada caso.</p>
                <a href="https://wa.me/5571991293370?text=Ola%20Carol%20tudo%20bem?%20Gostaria%20de%20agendar%20minha%20avaliação!" class="btn btn-secondary mt-2">Agende sua Consulta</a>
            </div>
        </div>
    </section>

    <!-- Depoimentos -->
    <section id="reviews" class="section">
        <div class="container">
            <h2 class="section-title">Depoimentos de Pacientes</h2>
            <p class="section-subtitle">O que nossos pacientes dizem sobre os resultados obtidos</p>
            <!-- Elfsight Google Reviews | Untitled Google Reviews -->
            <script src="https://static.elfsight.com/platform/platform.js" async></script>
            <div class="elfsight-app-21320711-1a35-4138-b063-aee54ae6198b" data-elfsight-app-lazy></div>
        </div>
    </section>

    <!-- Sobre Section -->
    <section id="about" class="section bg-light">
        <div class="container">
            <h2 class="section-title">Quer saber um pouco mais sobre quem vai te atender?</h2>
            <p class="section-subtitle">Então deixa eu me apresentar!</p>
            
            <div class="grid grid-2 gap-lg">

            <div class="fade-in">
                    <div class="card">
                        <div class="card-body">
                        <img src="images/carol_sobre.jpg" alt="Caroline Ferraz" class="hero-img">
                        </div>
                    </div>
                </div>

                <div class="fade-in">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="mb-2">Sobre mim</h3>
                            <p>Sou Farmacêutica formada pelo Centro Universitário Estácio da Bahia, Pós graduada  em Tricologia e terapias capilares, apaixonada por cuidar da saúde dos cabelos de forma profunda, personalizada e baseada em ciência.
                            <br><br>Membro da international Trichoscopy Society (ITS) desde de 2023.
                            <br><br>Idealizadora do programa Haircupere, um programa para transformar o cuidado capilar em um caminho possível, acolhedor e eficiente para quem sente que já tentou de tudo.
                            <br><br>Minha conduta na Tricologia une tecnologia, escuta ativa e experiência sensorial em cada consulta.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Contato -->
    <section id="contact" class="section bg-light">
        <div class="container">
            <h2 class="section-title">Entre em Contato</h2>
            <p class="section-subtitle">Estamos prontos para ajudar você a recuperar a saúde e beleza do seu cabelo</p>
            
            <div class="contact-container">
                <div class="contact-info fade-in">
                    <div class="contact-card">
                        <h3 class="mb-3">Informações de Contato</h3>
                        
                        <div class="contact-item">
                            <div class="contact-icon bg-primary-light">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                            </div>
                            <div>
                                <h4 class="mb-1">Endereço</h4>
                                <p><?php echo $config_endereco; ?></p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon bg-primary-light">
                                <i class="fas fa-phone text-primary"></i>
                            </div>
                            <div>
                                <h4 class="mb-1">Telefone</h4>
                                <p><?php echo $telefone; ?></p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon bg-primary-light">
                                <i class="fas fa-envelope text-primary"></i>
                            </div>
                            <div>
                                <h4 class="mb-1">Email</h4>
                                <p><?php echo $config_email; ?></p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon bg-primary-light">
                                <i class="fas fa-clock text-primary"></i>
                            </div>
                            <div>
                                <h4 class="mb-1">Horário de Atendimento</h4>
                                <p>Segunda a Sexta: 9h às 18h<br>Sábado: 9h às 13h</p>
                            </div>
                        </div>
                        
                        <h4 class="mt-4 mb-2">Redes Sociais</h4>
                        <div class="social-links">
                            <a href="https://wa.me/5571991293370?text=Ola%20Carol%20tudo%20bem?%20Gostaria%20de%20tirar%20algumas%20duvidas!" target="_blank" class="social-link" aria-label="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="https://www.instagram.com/carolferraz.tricologia" target="_blank" class="social-link" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form fade-in">
                    <form action="" method="POST">
                        <h3 class="mb-3">Envie uma Mensagem</h3>
                        
                        <div class="form-group">
                            <label for="name" class="form-label">Seu Nome</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone" class="form-label">Seu Telefone</label>
                            <input type="tel" id="phone" name="phone" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="message" class="form-label">Sua Mensagem</label>
                            <textarea id="message" name="message" class="form-control" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block">Enviar Mensagem</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Rodapé -->
    <footer>
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; <?php echo date("Y"); ?> Tricologia Especializada. Todos os direitos reservados.</p>
                <p class="mt-1">
                    <a href="#">Política de Privacidade</a> | 
                    <a href="#">Termos de Uso</a> | 
                    <a href="#">Mapa do Site</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Modal do Painel Adm Login -->
    <div id="accessPanel" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2 class="modal-title">Painel de Acesso</h2>
            <form id="loginForm" method="POST">
                <div class="form-group">
                    <label for="loginEmail" class="form-label">Email</label>
                    <input type="email" id="loginEmail" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="loginPassword" class="form-label">Senha</label>
                    <input type="password" id="loginPassword" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Entrar</button>
            </form>
                <div class="text-center mt-2">
                    <a href="#access-panelRec" id="forgotPassword">Esqueceu sua senha?</a>
                </div>
                <!-- <div class="text-center mt-2">
                    Não tem conta? <a href="#access-panelReg" id="register">Registre-se</a>
                </div> -->
        </div>
    </div>

    <!-- Modal do Painel Adm Rergistrar -->
    <div id="accessPanelReg" class="modalReg">
            <div class="modal-content">
                <span class="close-modalReg">&times;</span>
                <h2 class="modal-title">Cadastrar</h2>
                <form id="RegForm" method="POST">
                    <div class="form-group">
                        <label for="Regnome" class="form-label">Nome Completo</label>
                        <input type="text" id="Regnome" name="nome" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="RegloginEmail" class="form-label">Email</label>
                        <input type="email" id="RegloginEmail" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="Regtelefone" class="form-label">Telefone</label>
                        <input type="text" id="Regtelefone" name="telefone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="Regcpf" class="form-label">CPF</label>
                        <input type="text" id="Regcpf" name="cpf" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="RegloginPassword" class="form-label">Senha</label>
                        <input type="password" id="RegloginPassword" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="RegloginPasswordConf" class="form-label">Confirmar Senha</label>
                        <input type="password" id="RegloginPasswordConf" name="password_conf" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Registrar</button>
                </form>
            </div>
        </div>

        <!-- Modal do Painel Adm Recuperar -->
    <div id="accessPanelRec" class="modalRec">
            <div class="modal-content">
                <span class="close-modalRec">&times;</span>
                <h2 class="modal-title">Recuperar Acesso</h2>
                <form id="RecForm" method="POST">
                    <div class="form-group">
                        <label for="RecloginEmail" class="form-label">Email</label>
                        <input type="email" id="RecloginEmail" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="Reccpf" class="form-label">CPF</label>
                        <input type="text" id="Reccpf" name="cpf" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Recuperar</button>
                </form>
            </div>
        </div>

    <!-- JavaScript -->
    <script src="js/script.js"></script>

    <script>
    document.querySelector('form').addEventListener('submit', function (e) {
        // Exibir alerta de carregamento
        Swal.fire({
            title: 'Enviando...',
            text: 'Aguarde enquanto sua mensagem está sendo enviada.',
            icon: 'warning',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading(); // Mostra o spinner
            }
        });
    });
    </script>

</body>
</html>
