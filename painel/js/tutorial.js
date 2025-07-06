function iniciarTutorial() {

    const tutorial = introJs.tour();

    tutorial.setOptions({
        exitOnOverlayClick: false,
        steps: [
            { element: document.querySelector('[data-step="1"]'), intro: "Agora que você já terminou a <b>Configuração Inicial</b>, vamos lhe mostrar como nosso portal funciona!" },
            { element: document.querySelector('[data-step="2"]'), intro: "Este é o <b>Dashboard</b>. Aqui você tem a visão da sua <b>Agenda</b> e das <b>Solicitações Pendentes</b>!" },
            { element: document.querySelector('[data-step="3"]'), intro: "Este são os <b>Cadastros</b>. Aqui você <b>Consulta os Cadastros</b> e <b>Cadastra Novos Pacientes</b>, além de gerenciar seu <b>CRM do WhatsApp</b>!" },
            { element: document.querySelector('[data-step="4"]'), intro: "Esta são as <b>Consultas</b>. Aqui você pesquisa todas as suas consultas, além de <b>Cadastrar Novas Consultas</b> e <b>Enviar Lembretes</b>. <br><br>Os lembretes são mensagens enviadas para as suas consultas do dia seguinte, ou seja, de <b>amanhã</b>!" },
            { element: document.querySelector('[data-step="5"]'), intro: "Esta são os <b>Modelos</b>. Aqui você <b>cadastra, edita e exclui</b>, todos os modelos de <b>Fichas de Anamnese</b> e de <b>Fichas de Prontuários</b>!" },
            { element: document.querySelector('[data-step="6"]'), intro: "Esta é a <b>Disponibilidade</b>. Aqui você pode <b>Abrir</b> e <b>Fechar</b> a sua agenda, de acordo com a sua necessidade, além de conseguir ver seus horários disponíveis de forma rápida!" },
            { element: document.querySelector('[data-step="7"]'), intro: "Este é o <b>Financeiro</b>. Aqui você acessa sua <b>Contabilidade</b>, onde faz o registro de suas <b>Despesas</b> e <b>Receitas</b>, assim como lançamentos <b>recorrentes</b>. Extrai <b>Relatórios</b> e consegue entender a saúde financeira de sua empresa." },
            { element: document.querySelector('[data-step="7"]'), intro: "No <b>Financeiro</b>, você consegue ver todas as <b>Transações/Pagamentos</b> feitos para o ChronoClick, alem de ter acesso a <b>Relatórios Gerenciais</b> e <b>Renovar seu Plano</b> antes que ele expire." },
            { element: document.querySelector('[data-step="8"]'), intro: "Este são os <b>Serviços</b>. Aqui você <b>Cadastra</b> os seus Serviços, colocando os Custos para que ele sugira os valores de venda. Você so consegue lançar qualquer cobrança na conta dos seus pacientes, Serviços Cadastrados anteriormente." },
            { element: document.querySelector('[data-step="9"]'), intro: "Este é o <b>Estoque</b>. Aqui você gerencia seu <b>Saldo de Estoque</b> alem de <b>Cadastrar</b> os seus Produtos, com nº de Lote, Validade e Valores. Dá <b>Entrada</b> e <b>Saída</b> de Estoque. Este campo permite você vender <b>Produtos</b> nas contas de seus Pacientes." },
            { element: document.querySelector('[data-step="10"]'), intro: "Este é o <b>Videos</b>. Aqui você cadastra qualquer vídeo do Youtube, que seus pacientes terão acesso caso você configure para eles terem uma conta em nosso portal!" },
            { element: document.querySelector('[data-step="11"]'), intro: "Esta são as <b>Configurações</b>. Aqui você <b>Gerencia</b> todas as informações de sua empresa. <b>Horários de funcionamento</b>, <b>Mensagens Automáticas</b>, <b>Agenda</b>, <b>Whatsapp</b>, <b>Página de Acesso Externo</b>, entre outros." },
            { element: document.querySelector('[data-step="12"]'), intro: "Esta é o <b>Suporte</b>. Aqui você pode reiniciar o sistema de <b>Tutorial</b>, assim como ir para a nossa página de <b>Vídeos Tutoriais</b>, além de poder abrir <b>Tickets</b> com <b>Duvidas</b>, <b>Sugestões</b>, <b>Feedbacks</b> e até mesmo <b>Reclamações</b>!" },
            { element: document.querySelector('[data-step="13"]'), intro: "Este é o <b>Perfil</b>. Aqui você <b>Altera</b> os seus <b>Dados Cadastrais</b>, <b>Renova</b> o seu plano antes do mesmo acabar e <b>Altera sua Senha</b>!" },
            { element: document.querySelector('[data-step="1"]'), intro: "Parabéns!!! <b>Você completou o nosso Tutorial</b>!! Se quiser ver novamente, só voltar aqui na aba <b>Ajuda</b> e clicar em <b>Tutorial</b>!" }
        ],
        doneLabel: "Finalizar",
        showButtons: true,
        showBullets: false,
        nextLabel: "Próximo",
        prevLabel: "Anterior",
        skipLabel: "X"
    });

    tutorial.onchange(function (targetElement) {
        if (targetElement) {
            // Garante que o item esteja visível no centro da tela
            targetElement.scrollIntoView({
                behavior: 'smooth',
                block: 'center',
                inline: 'nearest'
            });
    
            // Adiciona um destaque visual temporário no item
            targetElement.classList.add('intro-highlight');
            setTimeout(() => {
                targetElement.classList.remove('intro-highlight');
            }, 1500);
        }
    })
     
    tutorial.onexit(function () {
        fetch('encerrar_tutorial.php?id=3', { method: 'POST' });
    });

    tutorial.start();
}


//Demais Tutoriais
function iniciarAjuda(steps, stepPrefix = '') {
    const finalStep = {
        element: document.querySelector(`[data-step="${stepPrefix ? stepPrefix + '.' : ''}1"]`),
        intro: "Caso tenha mais dúvidas, procure na aba <b style='color:red;'>Ajuda</b> os nossos vídeos explicativos."
    };

    const formatSteps = steps.map(step => ({
        element: document.querySelector(`[data-step="${stepPrefix ? stepPrefix + '.' : ''}${step.id}"]`),
        intro: step.intro
    }));

    // Adiciona o passo final automaticamente
    formatSteps.push(finalStep);

    introJs.tour().setOptions({
        exitOnOverlayClick: false,
        steps: formatSteps,
        doneLabel: "Finalizar",
        showButtons: true,
        showBullets: false,
        nextLabel: "Próximo",
        prevLabel: "Anterior",
        skipLabel: "X"
    }).start();
}

// === AJUDAS ===
function ajudaAutorizacao() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você encontra todas as <b>Solicitações de Alterações</b> e <b>Cancelamentos</b> feitos com menos de <b>24h</b> de antecedência da <b>Consulta Original</b>."},
        { id: "2", intro: "Veja as Consultas abaixo e você pode selecionar <b>Aceitar</b> ou <b>Recusar</b> a solicitação."},
        { id: "3", intro: "Em ambos os casos, o seu paciente irá receber a notificação!"}
    ]);
}

function ajudaAgenda() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você encontra a sua <b>Agenda</b>, com visão geral de todas as consultas."},
        { id: "2", intro: "Altere <b>Mês e Ano</b> ou use <b>Setas/Arraste</b> para navegar."},
        { id: "3", intro: "Estas são as <b>Legendas de Cores</b> para facilitar a visualização."},
        { id: "4", intro: "Clique em um dia para ver os <b>Detalhes do Dia</b>."}
    ]);
}

function ajudaAgendaDia() {
    iniciarAjuda([
        { id: "1", intro: "Detalhes das consultas do dia selecionado."},
        { id: "2", intro: "Informações de <b>Data, Horário e Local</b> da consulta."},
        { id: "3", intro: "Acesse ações como <b>Lembretes</b> e <b>Confirmações</b>."},
        { id: "4", intro: "Acesse o <b>Cadastro Completo</b> do paciente."},
        { id: "5", intro: "Botões de ação: <b>Finalizar, Alterar, Cancelar</b>."}
    ]);
}

function ajudaConsulta() {
    iniciarAjuda([
        { id: "1", intro: "Detalhes da <b>Consulta</b> atual."},
        { id: "2", intro: "Data, Horário, Local e dados do paciente."},
        { id: "3", intro: "Acesse o <b>Cadastro</b> completo do paciente."},
        { id: "4", intro: "Ações disponíveis: <b>Alterar, Cadastrar, Confirmar, Lembrar, Cancelar, Finalizar</b>."}
    ]);
}

function ajudaCadastro() {
    iniciarAjuda([
        { id: "1", intro: "Informações completas do paciente e histórico clínico."},
        { id: "2", intro: "<b>Editar</b> dados do paciente."},
        { id: "3", intro: "Veja mais detalhes como <b>CPF, E-mail, Endereço</b> etc."},
        { id: "4", intro: "Gerencie <b>Consultas, Arquivos, Contratos</b> etc."},
        { id: "5", intro: "Acesse Anamnese, Prontuários, Evoluções e mais."}
    ]);
}

function ajudaCadastroConsultas() {
    iniciarAjuda([
        { id: "1", intro: "Histórico de <b>Consultas</b> e seus status."},
        { id: "2", intro: "<b>Cadastre</b> nova consulta."},
        { id: "3", intro: "Veja <b>Mais Detalhes</b> da consulta."},
        { id: "4", intro: "Data, Horário, Local e Status da consulta."}
    ], "2");
}

function ajudaCadastroSessoes() {
    iniciarAjuda([
        { id: "1", intro: "Histórico de <b>Sessões</b> e ações como <b>Finalizar</b> ou <b>Excluir</b>."},
        { id: "2", intro: "Cadastrar nova <b>Sessão</b> para este paciente."},
        { id: "3", intro: "Auxílio visual do andamento de sessões."},
        { id: "4", intro: "Veja <b>Descrição, Status e Controle</b> das sessões."},
        { id: "5", intro: "Cadastre cada sessão do plano."},
        { id: "6", intro: "Finalize o tratamento."},
        { id: "7", intro: "Exclua o tratamento, se necessário."}
    ], "3");
}

function ajudaCadastroLancamentos() {
    iniciarAjuda([
        { id: "1", intro: "Histórico de <b>Lançamentos</b> do paciente."},
        { id: "2", intro: "Lance <b>Produtos</b>, <b>Serviços</b> ou <b>Pagamentos</b>."},
        { id: "2.1", intro: "Cadastre produtos em <b>Estoque > Produtos</b>. A baixa é automática."},
        { id: "2.1", intro: "Cadastre serviços em <b>Financeiro > Serviços</b>. Configure <b>custos fixos</b> antes."},
        { id: "3", intro: "<b>Estorne ou exclua</b> lançamentos, se necessário."}
    ], "4");
}

function ajudaCadastroArquivos() {
    iniciarAjuda([
        { id: "1", intro: "Histórico de <b>Arquivos</b> enviados para o paciente."},
        { id: "2", intro: "Envie arquivos em <b>PDF</b> ou exclua os existentes."},
        { id: "2", intro: "Você pode criar seu próprio PDF personalizado e enviar."}
    ], "5");
}

function ajudaCadastroContratos() {
    iniciarAjuda([
        { id: "1", intro: "Veja e gerencie os <b>Contratos</b> do paciente."},
        { id: "2", intro: "Cadastrar ou Excluir um novo contrato."},
        { id: "2", intro: "Preencha todos os dados do paciente antes de cadastrar."},
        { id: "3", intro: "Informações sobre assinatura do contrato."},
        { id: "4", intro: "Excluir contrato selecionado."}
    ], "6");
}

function ajudaCadastroAnamnese() {
    iniciarAjuda([
        { id: "1", intro: "Veja as <b>Anamneses</b> deste paciente."},
        { id: "2", intro: "Cadastrar novo modelo de anamnese."},
        { id: "3", intro: "Informações do modelo preenchido."},
        { id: "4", intro: "Visualizar ou Preencher a anamnese."}
    ], "7");
}

function ajudaCadastroProntuario() {
    iniciarAjuda([
        { id: "1", intro: "Visualize os <b>Prontuários</b> do paciente."},
        { id: "2", intro: "Cadastre novos modelos de prontuário."},
        { id: "3", intro: "Informações preenchidas do prontuário."},
        { id: "4", intro: "Preencher ou visualizar este prontuário."}
    ], "8");
}

function ajudaCadastroEvolucao() {
    iniciarAjuda([
        { id: "1", intro: "Visualize as <b>Evoluções</b> do paciente."},
        { id: "2", intro: "Cadastre novas <b>Evoluções</b> deste paciente."},
        { id: "1", intro: "Abaixo você conseguira ver todas as evoluções e anotações deste paciente."},
        { id: "3", intro: "Para Excluir uma Evolução, basta selecionar o botão <b>Excluir</b>."}
    ], "9");
}

function ajudaCadastroReceitas() {
    iniciarAjuda([
        { id: "1", intro: "Visualize as <b>Receitas</b> do paciente."},
        { id: "2", intro: "Cadastre novas <b>Receitas</b> deste paciente."},
        { id: "1", intro: "Abaixo você conseguira ver todas as receitas feitas para este paciente."},
        { id: "3", intro: "Para Excluir uma Receita, basta selecionar o botão <b>Excluir</b>."},
        { id: "4", intro: "Para Imprimir uma Receita, basta selecionar o botão <b>Imprimir</b>."}
    ], "10");
}

function ajudaCadastroAtestado() {
    iniciarAjuda([
        { id: "1", intro: "Visualize os <b>Atestados</b> do paciente."},
        { id: "2", intro: "Cadastre novos <b>Atestados</b> deste paciente."},
        { id: "1", intro: "Abaixo você conseguira ver todos os atestados feitos para este paciente."},
        { id: "3", intro: "Para Excluir uma Receita, basta selecionar o botão <b>Excluir</b>."},
        { id: "4", intro: "Para Imprimir uma Receita, basta selecionar o botão <b>Imprimir</b>."}
    ], "11");
}

function ajudaCadastroEvolucaoAdd() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você <b>Cadastra</b> todas as evoluções deste paciente."},
        { id: "2", intro: "Preencha as <b>Anotações</b> do dia deste paciente."},
        { id: "3", intro: "Apos ter preenchido as anotações, clique em <b>Salvar Evolução</b>."}
    ], "12");
}

function ajudaCadastroContratoAdd() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você <b>Cadastra</b> um novo Contrato para este paciente."},
        { id: "2", intro: "Aqui são os <b>Termos de Pagamento</b> deste contrato. Você ira colocar o valor total, e se parcelou e em quantas vezes, ou qualquer informação relevante sobre os termos de pagamento."},
        { id: "3", intro: "Aqui é o <b>Intervalo em Dias</b> entre cada atendimento. Caso seja apenas um atendimento unico, pode deixar em 0."},
        { id: "4", intro: "Aqui é a <b>Descrição</b> dos procedimentos os quais serão realizados com este paciente."},
        { id: "5", intro: "Apos ter preenchido tudo, clique em <b>Enviar Contrato</b>."}
    ], "13");
}

function ajudaConsultaCadastrar() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Cadastrar</b> uma nova consulta. Preencha as informações referente a consulta em questão, e apos clicar em <b>Confirmar Consulta</b>."},
        { id: "1", intro: "Para fazer uma nova consulta para este paciente, você tambem consegue ir diretamento no cadastro do mesmo, indo em <b>Cadastros > Clientes > Selecione o paciente em questão</b> clicando no <b>Email</b> do mesmo.<br><br>Apos isso va ate a parte de <b>Consultas > Nova Consulta</b>, que ja ira migrar com todas as informações do seu paciente."},
        { id: "2", intro: "Preencha a <b>Data</b> da consulta."},
        { id: "3", intro: "Preencha o <b>Horário</b> da consulta."},
        { id: "4", intro: "Preencha o <b>Email</b> do paciente. Caso ja tenha cadastro, ele ira preencher automaticamente com as demais informações e caso não tenha cadastro, você sera levado para a area de <b>Cadastro</b>"},
        { id: "5", intro: "Preencha o <b>CPF</b> do paciente. Caso ja tenha cadastro, ele ira preencher automaticamente com as demais informações e caso não tenha cadastro, você sera levado para a area de <b>Cadastro</b>"},
        { id: "6", intro: "Preencha o <b>Nome</b> do paciente."},
        { id: "7", intro: "Preencha o <b>Telefone</b> do paciente."},
        { id: "8", intro: "Selecione o <b>Tipo de Consulta</b>."},
        { id: "9", intro: "Preencha o <b>Local</b> da consulta. Pode ser a cidade, nome do Empreendimento ou algo para saber onde sera este atendimento."},
        { id: "10", intro: "Selecione em <b>Forçar Overbook</b>, para que se ja existir alguma consulta neste mesmo <b>dia</b>, neste mesmo <b>horario</b> ele ira confirmar.<br><br>Recomendado deixar <b style='color:red;'>desmarcado</b> para evitar <b>Conflito de Agenda</b>."},
        { id: "11", intro: "Selecione em <b>Forçar Data/Horario</b>, para que se a data e horário selecionado for diferente do que você cadastrou na parte de <b>Configurações > Agenda > Hora Inicial de Atendimento e Dias da Semana</b> ele ira confirmar.<br><br>Recomendado deixar <b style='color:red;'>desmarcado</b> para evitar <b>Consultas fora do expediente</b>."},
        { id: "12", intro: "Apos ter confirmado todas as informações acima, clique em <b>Confirmar Consulta</b>."}
    ]);
}


function ajudaConsultaFinalizar() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Finalizar</b> o atendimento abaixo. Preencha as informações referente a consulta em questão, e apos clicar em <b>Finalizar</b>. Selecione se você deseja enviar ou não a <b>Mensagem de Finalização</b>."},
        { id: "1", intro: "<b>Mensagem de Finalização</b> você pode encontrar e editar ela na Aba <b>Configurações > Mensagens > Mensagem de Finalização</b>."},
        { id: "2", intro: "Aqui você encontra as <b>Informações</b> do seu paciente."},
        { id: "3", intro: "Aqui você encontra as informações <b>Originais</b> da consulta."},
        { id: "4", intro: "Preencha com o <b>Telefone</b> que ira receber a mensagem de lembrete."},
        { id: "5", intro: "Clique em <b>Finalizar</b>, para confirmar os dados."},
        { id: "5", intro: "Selecione se deseja enviar ou não a <b>Mensagem de Finalização</b>. Lembre-se que você precisa ter conectado o seu <b>WhatsAapp</b> na plataforma e ter ativado a opção de envio na Aba <b>Configurações > Mensagens > Formas de Comunicação</b>."}
    ]);
}

function ajudaConsultaAlterar() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Alterar</b> o atendimento abaixo. Preencha as informações referente a consulta em questão, e apos clicar em <b>Alterar Consulta</b>."},
        { id: "2", intro: "Aqui você encontra as <b>Informações</b> do seu paciente."},
        { id: "3", intro: "Aqui você encontra as informações <b>Originais</b> da consulta."},
        { id: "4", intro: "Preencha a <b>Nova Data</b> da consulta."},
        { id: "5", intro: "Preencha o <b>Novo Horário</b> da consulta."},
        { id: "6", intro: "Preencha o <b>Novo Local</b> da consulta, caso tenha alterado."},
        { id: "7", intro: "Selecione em <b>Forçar Overbook</b>, para que se ja existir alguma consulta neste mesmo <b>dia</b>, neste mesmo <b>horario</b> ele ira confirmar.<br><br>Recomendado deixar <b style='color:red;'>desmarcado</b> para evitar <b>Conflito de Agenda</b>."},
        { id: "8", intro: "Selecione em <b>Forçar Data/Horario</b>, para que se a data e horário selecionado for diferente do que você cadastrou na parte de <b>Configurações > Agenda > Hora Inicial de Atendimento e Dias da Semana</b> ele ira confirmar.<br><br>Recomendado deixar <b style='color:red;'>desmarcado</b> para evitar <b>Consultas fora do expediente</b>."},
        { id: "9", intro: "Apos ter confirmado todas as informações acima e ter cadastrado a nova data, horário e local da conslta, clique em <b>Alterar Consulta</b>."}
    ]);
}

function ajudaConsultaCancelar() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Cancelar</b> o atendimento abaixo. Confirme as informações referente a consulta em questão, e apos clicar em <b>Cancelar</b>."},
        { id: "2", intro: "Aqui você encontra as <b>Informações</b> do seu paciente."},
        { id: "3", intro: "Aqui você encontra as informações <b>Originais</b> da consulta."},
        { id: "4", intro: "<b>Aviso Importante</b> esta ação não tem volta. A consulta sera cancelada e a agenda sera liberada para esta data e horário."},
        { id: "5", intro: "Apos ter confirmado todas as informações acima e ter certeza de que deseja prosseguir, clique em <b>Cancelar</b>."}
    ]);
}

function ajudaConsultaBuscar() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Buscar</b> todas as suas consultas, independente dos status, Confirmada, Em Andamento, Finalizada, Cancelada, NoShow."},
        { id: "2", intro: "Para procurar por um <b>Email</b> especifico, digite aqui. Caso contrario, e queria buscar todas as consultas de todos os cadastros, deixe em branco."},
        { id: "3", intro: "Selecione a <b>Data do INICIO</b> da Busca, que ele ira buscar A Partir desta data."},
        { id: "4", intro: "Selecione a <b>Data do FIM</b> da Busca, que ele ira buscar Até esta data."},
        { id: "5", intro: "Apos ter confirmado todas as informações acima, clique em <b>Buscar</b>."}
    ]);
}

function ajudaConsultaConfirmacao() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Enviar a Confirmação</b> desta consulta, tanto por WhatsApp quanto por E-mail."},
        { id: "2", intro: "Aqui você encontra as <b>Informações</b> deste paciente."},
        { id: "3", intro: "Aqui você encontra as <b>Informações</b> desta consulta."},
        { id: "4", intro: "Preencha com o <b>Telefone</b> que ira receber a mensagem de confirmação."},
        { id: "5", intro: "Selecione se deseja enviar a Mensagem de Confirmação via <b>WhatsApp</b>.<br><br>Lembre-se que você precisa ter conectado o seu <b>WhatsAapp</b> na plataforma e ter ativado a opção de envio de whatsapp na Aba <b>Configurações > Mensagens > Formas de Comunicação</b>."},
        { id: "6", intro: "Selecione se deseja enviar a Mensagem de Confirmação via <b>E-mail</b>.<br><br>Lembre-se que você precisa ter ativado a opção de envio de email na Aba <b>Configurações > Mensagens > Formas de Comunicação</b>."},
        { id: "7", intro: "Após ter confirmado todas as informações acima, clique em <b>Enviar</b>."}
    ]);
}

function ajudaConsultaLembrete() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Enviar o Lembrete</b> desta consulta, tanto por WhatsApp quanto por E-mail."},
        { id: "2", intro: "Aqui você encontra as <b>Informações</b> deste paciente."},
        { id: "3", intro: "Aqui você encontra as <b>Informações</b> desta consulta."},
        { id: "4", intro: "Preencha com o <b>Telefone</b> que ira receber a mensagem de lembrete."},
        { id: "5", intro: "Selecione se deseja enviar a Mensagem de Lembrete via <b>WhatsApp</b>.<br><br>Lembre-se que você precisa ter conectado o seu <b>WhatsAapp</b> na plataforma e ter ativado a opção de envio de whatsapp na Aba <b>Configurações > Mensagens > Formas de Comunicação</b>."},
        { id: "6", intro: "Selecione se deseja enviar a Mensagem de Lembrete via <b>E-mail</b>.<br><br>Lembre-se que você precisa ter ativado a opção de envio de email na Aba <b>Configurações > Mensagens > Formas de Comunicação</b>."},
        { id: "7", intro: "Após ter confirmado todas as informações acima, clique em <b>Enviar</b>."}
    ]);
}

function ajudaConsultaNoShow() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Cadastrar o No Show</b> desta consulta.<br><br>No Show é quando uma consulta não acontece e o paciente não cancela e nem remarca."},
        { id: "2", intro: "Aqui você encontra as <b>Informações</b> deste paciente."},
        { id: "3", intro: "Aqui você encontra as <b>Informações</b> desta consulta."},
        { id: "4", intro: "Após ter confirmado todas as informações acima, clique em <b>No-Show</b>."}
    ]);
}

function ajudaAnamnese() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Cadastrar</b> um modelo de <b>Anamnese</b>.<br><br>Escolha o TItulo da sua Anamnese, e as perguntas da mesma."},
        { id: "2", intro: "Aqui você coloca o <b>Titulo</b> desta Anamnese."},
        { id: "3", intro: "Aqui você cadastra cada <b>Pergunta</b> e <b>Resposta</b> desta Anamnese."},
        { id: "4", intro: "Nas <b>Perguntas</b>, você vai cadastrando, sem limites, quantas perguntas quiser para esta Anamnese."},
        { id: "5", intro: "Nas <b>Respostas</b> você vai colocar o tipo de resposta esperada.<br><br><b>Texto</b> - A resposta sera em tipo de texto escrito.<br><br><b>Numéro</b> - A resposta so ira aceitar numeros digitados.<br><br><b>Escolha Unica</b> - Você ira colocar uma lista de opções e podera marcar apenas 01 opção. Separe cada opção com ;. Ex.: opcao1;opcao2;opcao3."},
        { id: "5", intro: "<b>Multipla Seleção</b> - Você ira criar uma lista de possiveis respostas e podera selecionar mais de uma opção. Separe cada opção com ;. Ex.: opcao1;opcao2;opcao3.<br><br><b>Lista</b> - Você ira colocar uma lista de opções e podera marcar apenas 01 opção. Separe cada opção com ;. Ex.: opcao1;opcao2;opcao3."},
        { id: "5", intro: "Você consegue colocar Imagens nos tipo <b>Escolha Unica e Multipla Seleção</b>. Quando terminar de cadastrar todas as perguntas, clica em <b>Salvar Modelo</b> e volte que ira liberar o campo para adicionar a imagem.<br><br>Selecione a imagem por Ordem das Respostas, Ex.: opçao1;opção2;opção3, ira ser imagem1;imagem2;imagem3. Selecione as 03 imagens de vêz."},
        { id: "6", intro: "Aqui você <b>Adiciona</b> mais perguntas e tipo de resposta desta Anamnese."},
        { id: "7", intro: "Após ter preenchido com todas as Perguntas e respostas esperadas, clique em <b>Salvar Modelo</b>."}
    ]);
}

function ajudaAnamneses() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você encontra todos os <b>Modelos de Anamneses</b> cadastrados."},
        { id: "2", intro: "Você pode ver a Data que foi criado, o Nome, assim como consegue Editar para adicionar e/ou Excluir perguntas, assim como Excluir a Anamnese em si."},
        { id: "3", intro: "Aqui você <b>Edita</b> as informações desta Anamnese. Podendo adicionar/excluir as Perguntas e Tipos de Resposta, adicionar Imagens em alguns tipos de respostas, e alterar o Tiulo desta Anamnese."},
        { id: "4", intro: "Aqui você <b>Exclui</b> esta Anamnese. Cuidado pois esta ação é irreversivel e com isso todas as Perguntas e Respostas ja preenchidas, serão deletadas."}
    ]);
}

function ajudaAnamnesePreencher() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Preencher</b> este modelo de <b>Anamnese</b>."},
        { id: "2", intro: "Aqui são as <b>Perguntas</b> deste modelo."},
        { id: "3", intro: "Aqui você preenche com as <b>Respostas</b> deste modelo."},
        { id: "4", intro: "Após ter preenchido com todas as respostas esperadas, clique em <b>Salvar Respostas</b>.<br><br>Não é obrigatório responder todas as perguntas, você pode Salvar e voltar depois."}
    ]);
}

function ajudaProntuario() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Cadastrar</b> um modelo de <b>Prontuário</b>.<br><br>Escolha o TItulo do seu Prontuário, e as perguntas do mesmo."},
        { id: "2", intro: "Aqui você coloca o <b>Titulo</b> deste Prontuário."},
        { id: "3", intro: "Aqui você cadastra cada <b>Pergunta</b> e <b>Resposta</b> deste Prontuário."},
        { id: "4", intro: "Nas <b>Perguntas</b>, você vai cadastrando, sem limites, quantas perguntas quiser para este Prontuário."},
        { id: "5", intro: "Nas <b>Respostas</b> você vai colocar o tipo de resposta esperada.<br><br><b>Texto</b> - A resposta sera em tipo de texto escrito.<br><br><b>Numéro</b> - A resposta so ira aceitar numeros digitados.<br><br><b>Escolha Unica</b> - Você ira colocar uma lista de opções e podera marcar apenas 01 opção. Separe cada opção com ;. Ex.: opcao1;opcao2;opcao3."},
        { id: "5", intro: "<b>Multipla Seleção</b> - Você ira criar uma lista de possiveis respostas e podera selecionar mais de uma opção. Separe cada opção com ;. Ex.: opcao1;opcao2;opcao3.<br><br><b>Lista</b> - Você ira colocar uma lista de opções e podera marcar apenas 01 opção. Separe cada opção com ;. Ex.: opcao1;opcao2;opcao3."},
        { id: "5", intro: "Você consegue colocar Imagens nos tipo <b>Escolha Unica e Multipla Seleção</b>. Quando terminar de cadastrar todas as perguntas, clica em <b>Salvar Modelo</b> e volte que ira liberar o campo para adicionar a imagem.<br><br>Selecione a imagem por Ordem das Respostas, Ex.: opçao1;opção2;opção3, ira ser imagem1;imagem2;imagem3. Selecione as 03 imagens de vêz."},
        { id: "6", intro: "Aqui você <b>Adiciona</b> mais perguntas e tipo de resposta deste Prontuário."},
        { id: "7", intro: "Após ter preenchido com todas as Perguntas e respostas esperadas, clique em <b>Salvar Modelo</b>."}
    ]);
}

function ajudaProntuarios() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você encontra todos os <b>Modelos de Prontuários</b> cadastrados."},
        { id: "2", intro: "Você pode ver a Data que foi criado, o Nome, assim como consegue Editar para adicionar e/ou Excluir perguntas, assim como Excluir o Prontuário em si."},
        { id: "3", intro: "Aqui você <b>Edita</b> as informações deste Prontuário. Podendo adicionar/excluir as Perguntas e Tipos de Resposta, adicionar Imagens em alguns tipos de respostas, e alterar o Tiulo deste Prontuário."},
        { id: "4", intro: "Aqui você <b>Exclui</b> este Prontuário. Cuidado pois esta ação é irreversivel e com isso todas as Perguntas e Respostas ja preenchidas, serão deletadas."}
    ]);
}

function ajudaProntuarioPreencher() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Preencher</b> este modelo de <b>Prontuário</b>."},
        { id: "2", intro: "Aqui são as <b>Perguntas</b> deste modelo."},
        { id: "3", intro: "Aqui você preenche com as <b>Respostas</b> deste modelo."},
        { id: "4", intro: "Após ter preenchido com todas as respostas esperadas, clique em <b>Salvar Respostas</b>.<br><br>Não é obrigatório responder todas as perguntas, você pode Salvar e voltar depois."}
    ]);
}

function ajudaArquivoAdd() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Salvar</b> um arquivo para este paciente."},
        { id: "2", intro: "Aqui você <b>Seleciona</b> o arquivo."},
        { id: "3", intro: "Aqui você preenche com o <b>Nome</b> que gostaria que aparecesse deste arquivo."},
        { id: "4", intro: "Aqui você seleciona o <b>Tipo</b> deste arquivo, ou seja, a pasta de onde ele ira ficar salvo."},
        { id: "5", intro: "Após ter preenchido com todas as informações, clique em  <b>Salvar PDF</b>."}
    ]);
}

function ajudaAtestadoAdd() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Cadastrar</b> um atestado para este paciente."},
        { id: "2", intro: "Aqui você encontra as <b>Informações</b> sobre este paciente."},
        { id: "3", intro: "Aqui você preenche com o <b>Titulo</b>, se é Atestado, Atestado Médico, Atestado de Saude Fisica ou algo neste sentido."},
        { id: "4", intro: "Aqui você preenche com os <b>Detalhes</b> deste Atestado.<br><br>Ira puxar algumas informações basicas suas e do paciente, mas você pode alterar de forma livre."},
        { id: "5", intro: "Após ter preenchido com todas as informações, clique em  <b>Registrar Atestado</b>."}
    ]);
}

function ajudaReceitaAdd() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Cadastrar</b> uma Receita para este paciente."},
        { id: "2", intro: "Aqui você encontra as <b>Informações</b> sobre este paciente."},
        { id: "3", intro: "Aqui você preenche com o <b>Titulo</b>, se é Receita, Receita Médica ou algo neste sentido."},
        { id: "4", intro: "Aqui você preenche com os <b>Detalhes</b> desta Receita.<br><br>Ira puxar algumas informações basicas suas e do paciente, mas você pode alterar de forma livre."},
        { id: "5", intro: "Após ter preenchido com todas as informações, clique em  <b>Registrar Receita</b>."}
    ]);
}

function ajudaNovaSessao() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Cadastrar</b> uma Sessão para este paciente."},
        { id: "2", intro: "Aqui você preenche com o <b>Descrição</b> desta sessão."},
        { id: "3", intro: "Aqui você preenche com o <b>Total</b> de sessões deste atendimento."},
        { id: "4", intro: "Aqui você preenche com a <b>Data</b> de inicio deste atendimento."},
        { id: "5", intro: "Após ter preenchido com todas as informações, clique em  <b>Cadastrar</b>."}
    ]);
}

function ajudaNovaSessaoAdd() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você <b>Cadastra</b> todas as sessões deste atendimento."},
        { id: "2", intro: "Aqui é a <b>Descrição</b> deste atendimento."},
        { id: "3", intro: "Aqui é a <b>Progressão</b> deste atendimento."},
        { id: "4", intro: "Aqui é a <b>Data</b> que foi realizada esta sessão deste atendimento."},
        { id: "5", intro: "Aqui é a <b>Quantidade</b> de sessões realizadas deste atendimento nesta mesma data."},
        { id: "6", intro: "Aqui é a <b>Descrição</b> do que foi realizado nesta sessão deste atendimento."},
        { id: "7", intro: "Apos ter preenchido tudo, clique em <b>Cadastrar Sessão</b>."}
    ], "1");
}

function ajudaNovaSessaoFinalizar() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você <b>Finaliza</b> todas as sessões deste atendimento."},
        { id: "2", intro: "Aqui é a <b>Descrição</b> deste atendimento."},
        { id: "3", intro: "Aqui é a <b>Progressão</b> deste atendimento."},
        { id: "4", intro: "Aqui é a <b>Data</b> que foi iniciada este atendimento."},
        { id: "4", intro: "Para que seja possivel <b>Finalizar</b> este atendimento, você precisa ter cadastrado todas as sessões."},
        { id: "5", intro: "Apos ter preenchido tudo, clique em <b>Finalizar</b>."}
    ], "2");
}

function ajudaLancamentoProduto() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Lançar</b> um Produto na conta deste paciente."},
        { id: "2", intro: "Aqui você encontra o <b>Nome</b> deste paciente."},
        { id: "3", intro: "Aqui você seleciona o <b>Produto</b> para lançar.<br><br><b style='color:red;'>Observação</b>: Caso você não tenha nenhum produto nesta parte, é porque você não cadastrou o seu estoque. Va ate a Aba <b>Estoque > Produtos</b> e cadastre um produto. Apos isso você vai em <b>Estoque > Entradas</b> e registra a Entrada deste produto."},
        { id: "3", intro: "Veja que tera sempre ao lado do <b>Produto</b> o Saldo do mesmo. E você so consegue lançar algo que tenha estoque. Para dar entrada, você vai em <b>Estoque > Entradas</b> e registra a Entrada deste produto."},
        { id: "4", intro: "Aqui você preenche com a <b>Quantidade</b> deste produto. Lembrando que precisa ter estoque total para isso e a menor quantidade é 1."},
        { id: "5", intro: "Aqui você preenche com o <b>Valor Unitário</b> deste produto. Lembrando que ele ira multiplicar a Quantidade vs Valor na hora do lançamento."},
        { id: "6", intro: "Após ter preenchido com todas as informações, clique em  <b>Lançar</b>."},
        { id: "1", intro: "Apos <b>Lançar</b>, sera dado baixa no Estoque, assim como ira lançar a receita na aba <b>Financeiro > Painel Adm > Lançamentos</b>, automaticamente para ambos os casos, garantindo e agilizando todos os procedimentos."},
    ]);
}

function ajudaLancamentoServico() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Lançar</b> um Serviço na conta deste paciente."},
        { id: "2", intro: "Aqui você encontra o <b>Nome</b> deste paciente."},
        { id: "1.3", intro: "Aqui você seleciona o <b>Serviço</b> para lançar.<br><br><b style='color:red;'>Observação</b>: Caso você não tenha nenhum serviço nesta parte, é porque você não cadastrou o seu serviço ou custo do mesmo. Va ate a Aba <b>Financeiro > Serviços</b> e cadastre e depois edite um serviço. Lembre-se que você precisa cadastrar um Custo Fixo, pra adicionar ao seu serviço e lhe auxiliar com os valores a serem cobrados, para isso va ate <b>Financeiro > Custos Fixos</b> e cadastre agora mesmo!"},
        { id: "1.3", intro: "Veja que tera sempre ao lado do <b>Serviço</b> o Valor Sugerido do mesmo. Você consegue editar o valor a qualquer momento, apenas coloque abaixo o valor desejado ou mantenha o valor sugerido."},
        { id: "1.4", intro: "Aqui você preenche com o <b>Valor Total</b> deste serviço. Ele ja vem preenchido automaticamente com o Valor Sugerido, mas sinta-se a vontade para preencher com qualquer valor."},
        { id: "6", intro: "Após ter preenchido com todas as informações, clique em  <b>Lançar</b>."},
        { id: "1", intro: "Apos <b>Lançar</b>, ira lançar a receita na aba <b>Financeiro > Painel Adm > Lançamentos</b>, automaticamente, garantindo e agilizando todos os procedimentos."},
    ]);
}

function ajudaLancamentoEstoque() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Lançar</b> um Produto na conta deste paciente, para manter a rastreabilidade dos atendimentos mas tambem dar Baixa em seu Estoque."},
        { id: "2", intro: "Aqui você encontra o <b>Nome</b> deste paciente."},
        { id: "2.3", intro: "Aqui você seleciona o <b>Produto</b> para lançar e realizar a Baixa de Estoque.<br><br><b style='color:red;'>Observação</b>: Caso você não tenha nenhum produto nesta parte, é porque você não cadastrou o seu estoque. Va ate a Aba <b>Estoque > Produtos</b> e cadastre um produto. Apos isso você vai em <b>Estoque > Entradas</b> e registra a Entrada deste produto."},
        { id: "2.3", intro: "Veja que tera sempre ao lado do <b>Produto</b> o Saldo do mesmo. E você so consegue lançar algo que tenha estoque. Para dar entrada, você vai em <b>Estoque > Entradas</b> e registra a Entrada deste produto."},
        { id: "2.4", intro: "Aqui você preenche com a <b>Quantidade</b> deste produto. Lembrando que precisa ter estoque total para isso e a menor quantidade é 1."},
        { id: "6", intro: "Após ter preenchido com todas as informações, clique em  <b>Lançar</b>."},
        { id: "1", intro: "Apos <b>Lançar</b>, sera dado baixa no Estoque automaticamente, garantindo e agilizando todos os procedimentos."},
    ]);
}

function ajudaVideosAdd() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Adicionar</b> videos para os seus pacientes. Lembre-se que você precisa ter autorizado o acesso dos mesmos no seu Painel de Controle. Va até a aba <b>Configurações > Landing Page > Habilitar Painel Administrativo para Pacientes</b>."},
        { id: "2", intro: "Aqui você preenche com o <b>Titulo</b> deste video."},
        { id: "3", intro: "Aqui você preenche com o <b>Link</b> deste video. Lembrano que é apenas aceito videos do Youtube."},
        { id: "4", intro: "Apos você ter preenchido todas as informações, clique em <b>Adicionar Link</b>."},
        { id: "5", intro: "Aqui você encontra todos os <b>Links Cadastrados</b>. Onde você pode Editar e/ou Excluir o mesmo."},
        { id: "6", intro: "Para <b>Editar</b>, basta clicar aqui."},
        { id: "7", intro: "Para <b>Excluir</b>, basta clicar aqui."},
    ]);
}

function ajudaVideosEditar() {
    iniciarAjuda([
        { id: "1", intro: "Esta parte é para você <b>Editar</b> o Titulo/Link deste video. Lembre-se que você precisa ter autorizado o acesso dos mesmos no seu Painel de Controle. Va até a aba <b>Configurações > Landing Page > Habilitar Painel Administrativo para Pacientes</b>."},
        { id: "2", intro: "Aqui você edita o <b>Titulo</b> deste video."},
        { id: "3", intro: "Aqui você edita o <b>Link</b> deste video. Lembrano que é apenas aceito videos do Youtube."},
        { id: "4", intro: "Apos você ter editado todas as informações, clique em <b>Editar Link</b>."}
    ]);
}

function ajudaDisponibilidade() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue ver a sua <b>Disponibilidade</b> com agilidade. Simplesmente selecione o periodo que você deseja, e clique no dia especifico. Então ele ira abrir uma janela abaixo com as datas disponiveis."},
        { id: "2", intro: "Aqui você seleciona a <b>Data</b> para gerar seu calendario. Apos selecionar, clique em <b>Atualizar</b>"},
        { id: "3", intro: "Clique em cima de cada <b>Dia</b>, para abrir uma janela abaixo com as datas disponiveis."},
        { id: "4", intro: "Apos você tem todos os seus horários disponiveis de acordo ao registrado na aba <b>Configurações > Agenda</b> excluindo ja as consultas confirmadas e Fechamento de Agenda manual."}
    ]);
}

function ajudaDisponibilidadeAbrir() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue ver <b>Abrir sua Agenda</b>. Simplesmente selecione o periodo que você deseja, e clique no dia especifico. Então ele ira abrir a sua agenda nestes dias selecionados."},
        { id: "2", intro: "Aqui você seleciona a <b>Data Inicio</b>, que seria a Partir deste dia que a sua agenda sera aberta."},
        { id: "3", intro: "Aqui você seleciona a <b>Data Fim</b>, que seria ate este dia que a sua agenda sera aberta."},
        { id: "4", intro: "Aqui você seleciona o <b>Horário Inicio</b>, que seria a Partir deste horário que a sua agenda sera aberta."},
        { id: "5", intro: "Aqui você seleciona o <b>Horário Fim</b>, que seria ate este horário que a sua agenda sera aberta."},
        { id: "6", intro: "Aqui você seleciona os <b>Dias da Semana</b>, que deseja que sejam afetados pela mudança. Ex.: Se você selecionar do mês de Janeiro a Dezembro, e selecionar apenas as Segundas e Quartas, então esta ação sera apenas para as Segundas e Quartas. Para que altere tudo, mantenha todos os dias selecionados."},
        { id: "7", intro: "Após ter selecionado todas as suas opções, clique em <b>Abrir Agenda</b>."}
    ]);
}

function ajudaDisponibilidadeFechar() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue ver <b>Fechar sua Agenda</b>. Simplesmente selecione o periodo que você deseja, e clique no dia especifico. Então ele ira fechar a sua agenda nestes dias selecionados."},
        { id: "2", intro: "Aqui você seleciona a <b>Data Inicio</b>, que seria a Partir deste dia que a sua agenda sera fechada."},
        { id: "3", intro: "Aqui você seleciona a <b>Data Fim</b>, que seria ate este dia que a sua agenda sera fechada."},
        { id: "4", intro: "Aqui você seleciona o <b>Horário Inicio</b>, que seria a Partir deste horário que a sua agenda sera fechada."},
        { id: "5", intro: "Aqui você seleciona o <b>Horário Fim</b>, que seria ate este horário que a sua agenda sera fechada."},
        { id: "6", intro: "Aqui você seleciona os <b>Dias da Semana</b>, que deseja que sejam afetados pela mudança. Ex.: Se você selecionar do mês de Janeiro a Dezembro, e selecionar apenas as Segundas e Quartas, então esta ação sera apenas para as Segundas e Quartas. Para que altere tudo, mantenha todos os dias selecionados."},
        { id: "7", intro: "Após ter selecionado todas as suas opções, clique em <b>Fechar Agenda</b>."}
    ]);
}

function ajudaHistoricoVer() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue ver <b>Historico de Alterações</b> de tudo que foi feito em seu portal. Alteração de agendas, fechamento de disponibilidade, mudança de nome, lançamentos, cadastros e etc. É a sua parte de auditoria de açõe."},
        { id: "2", intro: "Aqui você encontra os <b>Detalhes</b> destas alterações."}
    ]);
}

function ajudaHistoricoBuscar() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue ver <b>Historico de Alterações</b> de tudo que foi feito em seu portal. Alteração de agendas, fechamento de disponibilidade, mudança de nome, lançamentos, cadastros e etc. É a sua parte de auditoria de açõe."},
        { id: "2", intro: "Preencha com o alguma informação tipo, <b>Nome, Ação, Email</b>, ou para procurar todas as alterações no periodo, deixe em <b>Branco</b>."},
        { id: "3", intro: "Aqui você preenche com a <b>Data Inicial</b> da sua busca, que sera a partir deste dia que o sistema ira procurar as alterações."},
        { id: "4", intro: "Aqui você preenche com a <b>Data Fim</b> da sua busca, que sera ate deste dia que o sistema ira procurar as alterações."},
        { id: "5", intro: "Após preencher com os filtro necessários, clique em <b>Buscar</b>."}
    ]);
}

function ajudaContabilidadeDashboard() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue gerenciar a sua <b>Contabilidade</b>. Cadastre suas receitas, suas despesas, sejam elas pontuais ou ate mesmo recorrentes. Acesse a parte de Relatórios e saiba a saude financeira de sua empresa."},
        { id: "2", intro: "Aqui você encontra a <b>Receita Total</b> gerada da sua empresa, desde a data de sua abertura."},
        { id: "3", intro: "Aqui você encontra a <b>Despesa Total</b> gerada da sua empresa, desde a data de sua abertura."},
        { id: "4", intro: "Aqui você encontra o <b>Saldo Atual</b>. Veja como esta a saude financeira da sua empresa."},
        { id: "5", intro: "Aqui você encontra os <b>Ultimos 10 Lançamentos</b>."},
        { id: "6", intro: "Aqui você encontra os <b>Resumos por Categoria</b> de todos os lançamentos feitos."}
    ]);
}

function ajudaContabilidadeLançamentos() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue gerenciar todos os <b>Lançamentos</b>. Cadastre suas receitas, suas despesas. Acesse a parte de Relatórios e saiba a saude financeira de sua empresa."},
        { id: "2", intro: "Aqui você encontra os <b>Filtros</b> caso deseje encontrar algum tipo de lançamento especifico ou uma data especifica. Selecione a Data de Inicio e Data Fim, se você quer ver <b>Despesas</b> ou <b>Receitas</b> e o tipo de <b>Conta Contabil</b>."},
        { id: "3", intro: "Aqui você consegue realizar <b>Novos Lançamentos</b>. Preencha as informações necessárias e clique em <b>Salvar</b>"},
        { id: "4", intro: "Aqui você consegue gerenciar todos os <b>Lançamentos</b>. Conseguindo Excluir e Editar."},
        { id: "5", intro: "Aqui você consegue <b>Excluir e/ou Editar</b> os lançamentos feitos."}
    ]);
}

function ajudaContabilidadeRecorrentes() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue gerenciar todos os <b>Lançamentos Recorrentes</b>. Cadastre suas receitas, suas despesas que se repetem."},
        { id: "2", intro: "Aqui você encontra os <b>Filtros</b> caso deseje encontrar algum tipo de lançamento recorrente especifico ou uma data especifica. Selecione a Data de Inicio e Data Fim, se você quer ver <b>Despesas</b> ou <b>Receitas</b> e o tipo de <b>Conta Contabil</b>."},
        { id: "3", intro: "Aqui você consegue realizar <b>Novos Lançamentos Recorrentes</b>. Preencha as informações necessárias, incluindo o periodo de recorrencia, e clique em <b>Salvar</b>"},
        { id: "4", intro: "Aqui você consegue gerenciar todos os <b>Lançamentos Recorrentes</b>. Conseguindo Excluir e Editar."},
        { id: "5", intro: "Aqui você consegue <b>Excluir e/ou Editar</b> os lançamentos recorrentes cadastrados."}
    ]);
}

function ajudaContabilidadeRelatórios() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue gerar <b>Relatórios</b> da sua empresa. Faça o comparativo por mês/ano das suas Receitas vs Despesas."},
        { id: "2", intro: "Aqui você encontra os <b>Filtros</b> para melhor lhe atender conforme sua necessidade de visualização."},
        { id: "3", intro: "Aqui você encontra a <b>Receita Total</b> gerada da sua empresa, de acordo a data selecionada."},
        { id: "4", intro: "Aqui você encontra a <b>Despesa Total</b> gerada da sua empresa, de acordo a data selecionada."},
        { id: "5", intro: "Aqui você encontra o <b>Saldo Atual</b> de acordo a data selecionada."},
        { id: "6", intro: "Aqui você encontra os <b>Resumos por Categoria</b> de todos os lançamentos feitos de acordo a data selecionada."},
        { id: "7", intro: "Aqui você encontra os <b>Maiores 10 Lançamentos</b> de todos os lançamentos feitos de acordo a data selecionada."},
        { id: "8", intro: "Aqui você encontra um <b>Gráfico com a sua Evolução</b> de acordo a data selecionada, entre Receitas vs Despesas."}
    ]);
}

function ajudaRelatorios() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue gerar <b>Relatórios</b> da sua empresa. Veja abaixo a lista de todos os relatórios disponiveis, em PDF e Excel."},
        { id: "2", intro: "Selecone aqui o <b>Relatorio</b> especifico que deseja ver."},
        { id: "3", intro: "Selecone aqui o <b>Formato do Relatorio</b> que deseja ver, se em PDF ou Excel."},
        { id: "4", intro: "Preencha aqui a <b>Data Fim</b> do relatorio. Ex.: Se você deseja ver todos os lançamentos de um mês, coloque o ultimo dia daquele mês (31/01)."},
        { id: "5", intro: "Apos ter preenchido e selecionado seus filtros, clique em  <b>Gerar Relatório</b>."}
    ]);
}

function ajudaServicos() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você encontra todos os <b>Serviços</b> da sua empresa. Veja abaixo os detalhes de cada serviço, incluindo os valores sugeridos de acordo aos custos fixos cadastrados."},
        { id: "2", intro: "Aqui é o <b>Nome</b> do serviço em questão."},
        { id: "3", intro: "Aqui são os <b>Custos</b> atrelados a serviço em questão"},
        { id: "4", intro: "Aqui é o <b>Total</b> dos custos atrelados a este serviço em questão."},
        { id: "5", intro: "Caso você tenha cadastrado uma <b>Margem de Lucro</b> sobre o serviço, aqui você ira ver a <b>Porcentagem</b> sobre o <b>Custo Total</b> do serviço."},
        { id: "6", intro: "Caso você tenha cadastrado as <b>Taxas</b> sobre o serviço, aqui você ira ver a <b>Porcentagem</b> sobre o <b>Custo Total</b> do serviço. Taxas de cartão, taxas de comissão, de indicação entre outras."},
        { id: "7", intro: "Caso você tenha cadastrado um <b>Imposto</b> sobre o serviço, aqui você ira ver a <b>Porcentagem</b> sobre o <b>Custo Total</b> do serviço. Impostos como ISS, ICMS, IRRF entre outros."},
        { id: "8", intro: "Aqui é o <b>Total Sugerido</b>de venda, dos Custos, Margens, Taxas e Impostos atrelados a este serviço em questão."}
    ]);
}

function ajudaServicosCadastrar() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue <b>Cadastrar os Serviços</b> da sua empresa. Assim como os detalhes de cada serviço, incluindo os valores sugeridos de acordo aos custos fixos cadastrados."},
        { id: "2", intro: "Preencha a <b>Descrição</b> do serviço em questão."},
        { id: "3", intro: "Após ter preenchido a Descrição, clique em <b>Cadastrar Serviço</b>"},
        { id: "4", intro: "Agora que você ja cadastrou o seu serviço, você pode Editar ele, atrelando Custos ao mesmo, ou ate Excluir este serviço."},
        { id: "5", intro: "Aqui você consegue <b>Inserir e/ou Deletar</b> os custos deste serviço. Lembre-se que para conseguir atrelar custos neste parte, você precisa anter ter <b>Cadastrado Custos</b>."},
        { id: "6", intro: "Caso você deseja Excluir este serviço, <b>Clique Aqui</b>."}
    ]);
}

function ajudaServicosCadastrarCustos() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue <b>Adicionar Custos</b> neste Seviço especifico."},
        { id: "2", intro: "Aqui você consegue <b>Selecionar</b> os custos deste serviço. Lembre-se que para conseguir atrelar custos neste parte, você precisa anter ter <b>Cadastrado Custos</b>."},
        { id: "3", intro: "Aqui você escolhe a <b>Quantidade</b>. Lembrando que ira multiplicar o valor individual pela quantidade digitada."},
        { id: "4", intro: "Após ter selecionado o custo em questão, clique em <b>Incluir Custo<b>."},
        { id: "5", intro: "Aqui você consegue <b>Ver e/ou Deletar</b> os custos deste serviço."},
        { id: "6", intro: "Caso você deseja Excluir este custo, <b>Clique Aqui</b>."}
    ]);
}

function ajudaServicosCadastrarCustosFixos() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue <b>Cadastrar Custos Fixos</b> para poder atrelar a um Seviço especifico."},
        { id: "2", intro: "Aqui você preenche o <b>Valor</b> deste custo fixo."},
        { id: "3", intro: "Aqui você seleciona o <b>Tipo</b> deste custo fixo."},
        { id: "4", intro: "Aqui você preenche a <b>Descrição</b> deste custo fixo."},
        { id: "5", intro: "Após ter preenchido as informações, clique em <b>Cadastrar Custo Fixo<b>."},
        { id: "6", intro: "Aqui você consegue <b>Ver e/ou Deletar e/ou Editar</b> os custos fixos cadastrados."},
        { id: "7", intro: "Caso você deseje Editar este custo fixo, <b>Clique Aqui</b>. Lembrando que todas as alterações, são automaticas e irão mudar automaticamente os valores sugeridos. Não se preocupe, ele não alterar os serviços já lançados nas contas de seus pacientes e nem na Contabilidade."},
        { id: "8", intro: "Caso você deseja Excluir este custo fixo, <b>Clique Aqui</b>."},
        { id: "1", intro: "Aqui vai uma <b>Dica Extra</b>. Você pode cadastrar um Custo Fixo de R$1,00 e chamar de DIVERSO tipo OUTROS, que quando você for atrelar o mesmo a um serviço, você pode colocar a quantidade desejada e ele ira multiplicar automaticamente."}
    ]);
}

function ajudaServicosCadastrarCustosFixosEditar() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue <b>Editar Custos Fixos</b> para poder atrelar a um Seviço especifico."},
        { id: "2", intro: "Aqui você edita o <b>Valor</b> deste custo fixo."},
        { id: "3", intro: "Aqui você seleciona o <b>Tipo</b> deste custo fixo."},
        { id: "4", intro: "Aqui você edita a <b>Descrição</b> deste custo fixo."},
        { id: "5", intro: "Após ter preenchido as informações, clique em <b>Editar Custo<b>."},
        { id: "1", intro: "Lembrando que todas as alterações, são automaticas e irão mudar automaticamente os valores sugeridos. Não se preocupe, ele não alterar os serviços já lançados nas contas de seus pacientes e nem na Contabilidade."},
        { id: "1", intro: "Aqui vai uma <b>Dica Extra</b>. Você pode cadastrar um Custo Fixo de R$1,00 e chamar de DIVERSO tipo OUTROS, que quando você for atrelar o mesmo a um serviço, você pode colocar a quantidade desejada e ele ira multiplicar automaticamente."}
    ]);
}

function ajudaEstoqueVer() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue ver seu <b>Saldo Atual de Estoque</b> para poder gerenciar sua empresa."},
        { id: "2", intro: "Aqui é o <b>Nome</b> do Produto."},
        { id: "3", intro: "Aqui é o <b>Saldo Atual</b> do Produto."},
        { id: "4", intro: "Aqui é o <b>Estoque Minimo</b> do Produto."},
        { id: "1", intro: "Caso tenha um item com <b>Saldo Atual</b> menor/igual ao <b>Estoque Minimo</b> do mesmo, ele ira ficar com um simbolo de alerta '⚠️' e ira aparecer logo na frente, para facilitar a sua gestão."}
    ]);
}

function ajudaEstoqueProdutos() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue <b>Cadastrar Produtos</b> para poder lançar na conta dos seus pacientes e gerenciar a sua lojinha."},
        { id: "2", intro: "Aqui você preenche o <b>Nome</b> do produto."},
        { id: "3", intro: "Aqui você preenche o <b>Etoque Minimo</b> deste produto. Onde ira gerar alertas caso o Saldo Atual esteja menor/igual do Estoque Minimo."},
        { id: "4", intro: "Aqui você preenche a <b>Unidade de Medida</b> deste produto."},
        { id: "5", intro: "Após ter preenchido as informações, clique em <b>Cadastrar Produto<b>."},
        { id: "6", intro: "Aqui você consegue <b>Ver e/ou Deletar e/ou Editar</b> os produtos cadastrados."},
        { id: "7", intro: "Caso você deseje Editar este produto, <b>Clique Aqui</b>."},
        { id: "8", intro: "Caso você deseja Excluir este produto, <b>Clique Aqui</b>."}
    ]);
}

function ajudaEstoqueProdutosEditar() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue <b>Editar Produtos</b> para poder lançar na conta dos seus pacientes e gerenciar a sua lojinha."},
        { id: "2", intro: "Aqui você edita o <b>Nome</b> do produto."},
        { id: "3", intro: "Aqui você edita o <b>Etoque Minimo</b> deste produto. Onde ira gerar alertas caso o Saldo Atual esteja menor/igual do Estoque Minimo."},
        { id: "4", intro: "Aqui você edita a <b>Unidade de Medida</b> deste produto."},
        { id: "5", intro: "Após ter editado as informações, clique em <b>Editar Produto<b>."}
    ]);
}

function ajudaEstoqueProdutosEntradas() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue cadastrar <b>Entradas de Estoque</b> para poder lançar na conta dos seus pacientes e gerenciar a sua lojinha."},
        { id: "2", intro: "Aqui você preenche a <b>Data</b> de entrada deste produto."},
        { id: "3", intro: "Aqui você selecione o <b>Produto</b> para cadastrar a entrada."},
        { id: "4", intro: "Aqui você preenche a <b>Quantidade</b> deste produto."},
        { id: "5", intro: "Aqui você preenche o <b>Valor Total</b> do custo deste produto. Atenção, não é o valor unitário e sim o total."},
        { id: "6", intro: "Aqui você preenche o <b>Lote</b> deste produto, caso não tenha, deixar em branco."},
        { id: "7", intro: "Aqui você preenche a <b>Validade</b> deste produto, caso não tenha, deixar com a data de hoje."},
        { id: "8", intro: "Selecione se você quer <b>Lançar na Despesa</b> este produto de acordo ao valor digitado ou não. Caso você opte por sim, ira ser lançado automaticamente esta despesa na parte da Contabilidade."},
        { id: "9", intro: "Após ter preenchido as informações, clique em <b>Registrar Entrada<b>."},
        { id: "10", intro: "Aqui você consegue ver todas as <b>Entradas Registradas<b>, sejam elas manuais ou automaticas."}
    ]);
}

function ajudaEstoqueProdutosSaidas() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue cadastrar <b>Saidas de Estoque</b> para poder atualizar seu estoque e gerenciar a sua lojinha."},
        { id: "2", intro: "Aqui você selecione o <b>Produto</b> para cadastrar a saida."},
        { id: "3", intro: "Aqui você preenche a <b>Quantidade</b> deste produto."},
        { id: "4", intro: "Aqui você preenche o <b>Lote</b> deste produto, caso não tenha, deixar em branco."},
        { id: "5", intro: "Aqui você preenche a <b>Validade</b> deste produto, caso não tenha, deixar com a data de hoje."},
        { id: "6", intro: "Após ter preenchido as informações, clique em <b>Registrar Saida<b>."},
        { id: "7", intro: "Aqui você consegue ver todas as <b>Saidas Registradas<b>, sejam elas manuais ou automaticas."}
    ]);
}

function ajudaConfigEmpresa() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue Gerenciar as configuração da sua <b>Empresa</b>. Lembre-se que toda e qualquer alteração tem efeito automatico, e todas as informações relacionadas a estas configurações, serão alteradas."},
        { id: "2", intro: "Aqui você preenche o <b>Nome do Profissional/Empresa</b> a qual ira aparecer nas mensagens, relatorios, atestados, receitas e etc."},
        { id: "3", intro: "Aqui você preenche o <b>Email</b> da sua empresa."},
        { id: "4", intro: "Aqui você preenche o <b>Telefone</b> da sua empresa."},
        { id: "5", intro: "Aqui você preenche a <b>CPF/CNPJ</b> dua sua empresa, o qual ira aparecer em seus Contratos e Relatórios."},
        { id: "6", intro: "Aqui você preenche o <b>Endereço</b> da sua empresa, o qual ira aparecer em seus Contratos e Relatórios."},
        { id: "7", intro: "Após ter preenchido as informações, clique em <b>Atualizar Dados<b>."}
    ]);
}

function ajudaConfigMsg() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue Gerenciar as configuração da sua <b>Empresa</b>. Lembre-se que toda e qualquer alteração tem efeito automatico, e todas as informações relacionadas a estas configurações, serão alteradas."},
        { id: "2", intro: "Aqui você seleciona as <b>Variaveis</b> as quais serão enviadas nas mensagens abaixo."},
        { id: "3", intro: "Aqui você preenche a <b>Mensagem Confirmação</b>, que sera enviada para os seus pacientes apos você confirmar as consultas dos mesmos ou envios manuais pelo Painel."},
        { id: "4", intro: "Aqui você preenche a <b>Mensagem Cancelamento</b>, que sera enviada para os seus pacientes apos você cancelar as consultas dos mesmos."},
        { id: "5", intro: "Aqui você preenche a <b>Mensagem Finalização</b>, que sera enviada para os seus pacientes apos você finalizar as consultas dos mesmos, caso opte por enviar na hora."},
        { id: "6", intro: "Aqui você preenche a <b>Mensagem Lembrete</b>, que sera enviada para os seus pacientes nos envios automaticos de Lembretes de Consulta, assim como envios manuais pelo Painel."},
        { id: "7", intro: "Aqui você preenche a <b>Mensagem Aniversario</b>, que sera enviada para os seus pacientes nos envios automaticos no dia de aniversário dos mesmos."},
        { id: "8", intro: "Aqui você seleciona o <b>Tipo de Comunicação</b> que deseja deixar ativada. Lembrando que você precisa ter conectado o seu WhatsApp em nossa plataforma, para que as mensagens sejam enviadas diretamente e automaicamente do seu numero.<br><br>Para conectar o seu WhatsApp va ate a parte de <b>Whatsapp</b> e siga os passo-a-passo. Caso demore para abrir o QR Code, simplesmente carregue a pagina novamente."},
        { id: "9", intro: "Aqui você seleciona qual <b>Horário</b> as mensagens de <b>Envio de Lembretes Automaticos</b> serão enviadas diariamente."},
        { id: "10", intro: "Aqui você seleciona quais <b>Dias da Semana</b> as mensagens de <b>Envio de Lembretes Automaticos</b> serão enviadas."},
        { id: "11", intro: "Após ter preenchido as informações, clique em <b>Atualizar Dados</b>."}
    ]);
}

function ajudaConfigAgenda() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue Gerenciar as configuração da sua <b>Empresa</b>. Lembre-se que toda e qualquer alteração tem efeito automatico, e todas as informações relacionadas a estas configurações, serão alteradas."},
        { id: "2", intro: "Aqui você seleciona o <b>Horario Inicial</b> que seus atendimentos começam diariamente."},
        { id: "3", intro: "Aqui você seleciona o <b>Horario Final</b> que seus atendimentos terminam diariamente."},
        { id: "4", intro: "Aqui você preenche o <b>Intervalo entre Atendimentos</b> para impedir multiplos atendimentos no mesmo horário."},
        { id: "5", intro: "Aqui você seleciona a <b>Data Maxima</b> que seus atendimentos poderão ser agendados."},
        { id: "6", intro: "Aqui você preenche a <b>Mensagem Lembrete</b>, que sera enviada para os seus pacientes nos envios automaticos de Lembretes de Consulta, assim como envios manuais pelo Painel."},
        { id: "7", intro: "Após ter preenchido as informações, clique em <b>Atualizar Dados</b>."}
    ]);
}

function ajudaConfigLanding() {
    iniciarAjuda([
        { id: "1", intro: "Aqui você consegue Gerenciar as configuração da sua <b>Empresa</b>. Lembre-se que toda e qualquer alteração tem efeito automatico, e todas as informações relacionadas a estas configurações, serão alteradas."},
        { id: "2", intro: "Aqui você é o <b>Link</b> da sua Pagina para compartilhar em suas redes sociais e Whatsapp. Onde você coloca as suas informações e onde pode permitir ou não, que seus clientes façam agendamentos sem você, de acordo as configurações de sua agenda."},
        { id: "3", intro: "Aqui você permite o <b>Landing Page</b>, que é a sua pagina pessoalpara compartilhar nas redes sociais (Link)."},
        { id: "4", intro: "Aqui você permite o <b>Agendamento Externo</b> onde os seus clientes conseguem marcar consultas contigo, sem você intervir, de acordo a sua agenda."},
        { id: "5", intro: "Aqui você permite o <b>Acesso ao Painel Administrativo</b> dos seus pacientes. Onde eles terão acesso a historicos de sessões, contratos, atestados, receitas, arquivos e videos, todos disponibilizados por você na parte de Cadastro."},
        { id: "6", intro: "Aqui você preenche o <b>Nome da Pagina</b>, que ira aparecer na pagina acima (Link)."},
        { id: "7", intro: "Aqui você preenche o <b>Link da Pagina</b>, que voce ira compartilhar nas suas redes sociais (Link)."},
        { id: "8", intro: "Aqui você preenche a sua <b>Especialidade</b>, que ira aparecer na pagina acima (Link)."},
        { id: "9", intro: "Aqui você preenche a <b>Descrição da Pagina</b>, que ira aparecer na pagina acima (Link). Parte mais importante da sua pagina, onde você ira colocar todas as suas informações e detalhes."},
        { id: "10", intro: "Aqui você preenche o <b>@ do Instagram</b>, que ira aparecer na pagina acima (Link)."},
        { id: "11", intro: "Aqui você preenche o <b>@ do Facebook</b>, que ira aparecer na pagina acima (Link)."},
        { id: "12", intro: "Aqui você preenche o seu <b>Endereço</b>, que ira aparecer na pagina acima (Link)."},
        { id: "13", intro: "Aqui você seleciona seu <b>Avatar</b>, que ira aparecer na pagina acima (Link)."},
        { id: "14", intro: "Após ter preenchido as informações, clique em <b>Atualizar Dados</b>."}
    ]);
}
