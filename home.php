<?php

session_start();
require('conexao.php');
require('verifica_login.php');
 
$query = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_configuracoes'];
    $unico_usuario = $select['unico'];
    $nome = $select['nome'];
}
?>

<!DOCTYPE html>
<html lang="Pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style_home.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <title><?php echo $config_empresa ?></title>
    <script src="js/script.js" defer></script>
</head>
<body>
    <header>
    <?php echo $menu_site_logado ?>
    </header>
    <div class="visao-desktop">
    <main>
        <section class="home">
            <div class="home-text">
            <h4 class="text-h4">Você Está Prestes a ter a SOLUÇÃO do Seu Problema Capilar!</h4>
            <center><img src="images/logo_inicio.jpg" alt="Logo"><center>
                <br>
                <p>Especialista em Queda, Calvície e Crescimento</p>
            </div>
            <div class="home-img">
                <img src="images/carol.jpg" alt="Logo">
            </div>
        </section>
    </main>
    <main>
    <section class="home">
            <div class="home-text">
                <h4 class="text-h4">Eu posso te ajudar se...</h4><br>
                <b>1.</b> Está passando por uma queda de cabelo acentuada
                <br><b>2.</b> Percebe que seu cabelo está ficando fino e o couro cabeludo aparente
                <br><b>3.</b> Sente que seu cabelo está sumindo aos poucos
                <br><b>4.</b> Percebe que seu cabelo está frágil e quebradiço
                <br><b>5.</b> Tem coceira, descamação e dor no couro cabeludo.
                <br><br>Não perca <b>DINHEIRO</b> nem <b>TEMPO</b> com tratamentos que não funcionam. Conheça as modalidades de atendimento e agende um horário.
            </div>
            <div class="home-text">
                <div class="carrossel-desktop">
                <h4 class="text-h4">O QUE FALAM DE NÓS</h4><br>
                    <div class="container-desktop" id="img-desktop">
                        <img src="images/comentario_01.jpg" alt="Comentario 01">
                        <img src="images/comentario_02.jpg" alt="Comentario 02">
                        <img src="images/comentario_03.jpg" alt="Comentario 03">
                        <img src="images/comentario_04.jpg" alt="Comentario 04">
                        <img src="images/comentario_05.jpg" alt="Comentario 05">
                    </div>
                </div>
            </div>
        </section>
    </main>
    <main>
    <center><section class="home-center">
                <div class="carrossel2-desktop">
                <p><b>ALGUNS DOS NOSSOS RESULTADOS</b></p><br>
                    <div class="container2-desktop" id="img2-desktop">
                        <img src="images/resultado_01.jpg" alt="Resultado 01">
                        <img src="images/resultado_02.jpg" alt="Resultado 02">
                        <img src="images/resultado_03.jpg" alt="Resultado 03">
                        <img src="images/resultado_04.jpg" alt="Resultado 04">
                        <img src="images/resultado_05.jpg" alt="Resultado 05">
                    </div>
                </div>
        </section></center>
    </main>
    <br><br><br><br><br><br><br><br><br>
    <main>
    <section class="home-center">
            <center><p><b>Quer saber um pouco mais sobre quem vai te atender?</b></p><br>
            <p>Então deixa eu me apresentar!</p></center>
            <br>Me chamo Caroline Chagas Ferraz, sou casada, mãe de dois pets (dois lobinhos bagunceiros), louca por doce e que ama ficar em casa assistindo filme.
            <br>Sou Farmacêutica, e passei os últimos 12 anos na área hospitalar mas especificamente, na área da Oncologia.
            <br>
            <br>Mas como a Tricologia entrou na minha vida?
            <br>Pra falar a verdade, cuidar de cabelos sempre foi um prazer, por isso vivia buscando informação sobre os cuidados capilares, receitas caseiras e inclusive fiz curso de cabeleireira com o objetivo de cuidar melhor dos meus cabelos.
            <br>Apesar de gostar muito do assunto, considerava um hobbie que me ajudava a manter a autoestima em dia.
            <br>
            <br>Mas em 2019 tudo mudou, iniciei a transição capilar pela 2x e tive muita dificuldade com esse mundo novo dos cabelos naturais. Pra me ajudar, comecei a seguir dicas controvérsias e sem fundamentos nenhum de blogueiras cacheadas na internet. Um dia, resolvi buscar informações mais confiáveis e assim que descobrir por acaso a TRICOLOGIA e me APAIXONEI pelos resultados e impacto que causa na vida das pessoas.
            <br>
            <br>Desde então, me aprofundo em estudos diariamente, a fim de poder dar o meu melhor para cada pessoa que atendo.
            <br>
            <br>Acredito que o cabelo  é o atributo de beleza mais importante para a grande maioria das pessoas, e que ele reflete diretamente na autoestima.
            <br>
            <br>Agora que você já me conhece, conte comigo pra te ajudar a RECUPERAR seus cabelos e sua autoestima.
        </section>
    </main>
    <br>
    <footer>
        <br><b>Onde ficamos?</b>
        <br>Ficamos localizado no bairro de Villas do Atlântico na cidade de Lauro de Freitas-BA, no predio Infinity Empresarial, Sala 501 - na Rua Leonardo Rodrigues da Silva, nº 248.
        <br>
        <br><b>Nossos Horários?</b>
        <br>Nosso atendimento ocorre mediante a um agendamento prévio de Segunda à Sexta de 14 ás 19hs e aos Sábados das 8hs ás 18hs 
    <br><br>
      </footer>
      </div>

      <div class="visao-mobile">
    <main>
        <section class="home-center">
            <br><br>
        <center><b>Você Está Prestes a ter a SOLUÇÃO do Seu Problema Capilar!</b>
        <br><br>
            <img src="images/logo_inicio.jpg" alt="Logo"><center>
                <br>
                <p>Especialista em Queda, Calvície e Crescimento</p>
            <div class="home-img">
                <img src="images/carol.jpg" alt="Logo">
            </div>
    <br><br><br><br>
                <h4 class="text-h4">Eu posso te ajudar se...</h4><br>
                <b>1.</b> Está passando por uma queda de cabelo acentuada
                <br><b>2.</b> Percebe que seu cabelo está ficando fino e o couro cabeludo aparente
                <br><b>3.</b> Sente que seu cabelo está sumindo aos poucos
                <br><b>4.</b> Percebe que seu cabelo está frágil e quebradiço
                <br><b>5.</b> Tem coceira, descamação e dor no couro cabeludo.
                <br><br>Não perca <b>DINHEIRO</b> nem <b>TEMPO</b> com tratamentos que não funcionam. Conheça as modalidades de atendimento e agende um horário.
    <br><br><br><br>
                <center><p><b>Feedbacks</b></p><br></center>
                <div class="carrossel-mobile">
                    <div class="container-mobile" id="img-mobile">
                        <img src="images/comentario_01_mobile.jpg" alt="Comentario 01">
                        <img src="images/comentario_02_mobile.jpg" alt="Comentario 02">
                        <img src="images/comentario_03_mobile.jpg" alt="Comentario 03">
                        <img src="images/comentario_04_mobile.jpg" alt="Comentario 04">
                        <img src="images/comentario_05_mobile.jpg" alt="Comentario 05">
                    </div>
                </div>
    <br><br><br><br>
                <center><p><b>Resultados</b></p><br></center>
                <div class="carrossel2-mobile">
                    <div class="container2-mobile" id="img2-mobile">
                        <img src="images/resultado_01_mobile.jpg" alt="Resultado 01">
                        <img src="images/resultado_02_mobile.jpg" alt="Resultado 02">
                        <img src="images/resultado_03_mobile.jpg" alt="Resultado 03">
                        <img src="images/resultado_04_mobile.jpg" alt="Resultado 04">
                        <img src="images/resultado_05_mobile.jpg" alt="Resultado 05">
                    </div>
                </div>
    <br><br><br><br>
            <center><p><b>Quer saber um pouco mais sobre quem vai te atender?</b></p><br>
            <p>Então deixa eu me apresentar!</p></center>
            <br>Me chamo Caroline Chagas Ferraz, sou casada, mãe de dois pets (dois lobinhos bagunceiros), louca por doce e que ama ficar em casa assistindo filme.
            <br>Sou Farmacêutica, e passeios os últimos 12 anos na área hospitalar mas especificamente, na área da Oncologia.
            <br>
            <br>Mas como a Tricologia entrou na minha vida?
            <br>Pra falar a verdade, cuidar de cabelos sempre foi um prazer, por isso vivia buscando informação sobre os cuidados capilares, receitas caseiras e inclusive fiz curso de cabeleireira com o objetivo de cuidar melhor dos meus cabelos.
            <br>Apesar de gostar muito do assunto, considerava um hobbie que me ajudava a manter a autoestima em dia.
            <br>
            <br>Mas em 2019 tudo mudou, iniciei a transição capilar pela 2x e tive muita dificuldade com esse mundo novo dos cabelos naturais. Pra me ajudar comecei a seguir dicas controvérsias e sem fundamentos nenhum de blogueiras cacheadas na internet. Um dia, resolvi buscar informações mais confiáveis e assim que descobrir por acaso a TRICOLOGIA e me APAIXONEI pelos resultados e impacto que causa na vida das pessoas.
            <br>
            <br>Desde então, me aprofundo em estudos diariamente, a fim de poder dar o meu melhor para cada pessoa que atendo.
            <br>
            <br>Acredito que o cabelo  é o atributo de beleza mais importante para a grande maioria das pessoas, e que ele reflete diretamente na autoestima.
            <br>
            <br>Agora que você já me conhece, conte comigo pra te ajudar a RECUPERAR seus cabelos e sua autoestima.
        </section>
    </main>
    <br>
    <footer>
        <br><b>Onde ficamos?</b>
        <br>Ficamos localizado no bairro de Villas do Atlântico na cidade de Lauro de Freitas-BA, no predio Infinity Empresarial, Sala 501 - na Rua Leonardo Rodrigues da Silva, nº 248.
        <br>
        <br><b>Nossos Horários?</b>
        <br>Nosso atendimento ocorre mediante a um agendamento prévio de Segunda à Sexta de 14 ás 19hs e aos Sábados das 8hs ás 18hs 
    <br><br>
      </footer>
      </div>

      <script>
        $(function(){ 
           $('i.fas').click(function(){
               var listaMenu = $('nav.mobile ul');
               if(listaMenu.is(':hidden') == true){
                   var icone = $('.botao-menu-mobile').find('i');
                   icone.removeClass('fas fa-bars');
                   icone.addClass('far fa-times-circle');
                   listaMenu.slideToggle();
               }else{
                var icone = $('.botao-menu-mobile').find('i');
                   icone.removeClass('far fa-times-circle');
                   icone.addClass('fas fa-bars');
                   listaMenu.slideToggle(); 
               }
           }) 
        })
    </script> 

</body>
</html>
