<?php
require('../config/database.php');

date_default_timezone_set('America/Sao_Paulo');

// Data de hoje
$hoje = new DateTime(); // ou new DateTime(); se quiser a data real

$result_check_config = $conexao->query("SELECT * FROM configuracoes WHERE id > -3");
while ($select_check_config = $result_check_config->fetch(PDO::FETCH_ASSOC)) {
    $token_config = $select_check_config['token_emp'];

    // Busca os lançamentos recorrentes
    $result_check = $conexao->prepare("SELECT * FROM lancamentos_recorrentes WHERE token_emp = :token_emp");
    $result_check->execute(array('token_emp' => $token_config));

    while ($select = $result_check->fetch(PDO::FETCH_ASSOC)) {
        $data_lancamento = new DateTime($select['data_lancamento']);
        $repeticoes = (int)$select['repeticoes'];
        $periodo = $select['periodo']; // 'diario', 'mensal', 'anual'
        $conta_id = $select['conta_id'];
        $descricao = $select['descricao'];
        $valor = $select['valor'];
        $observacoes = $select['observacoes'];

        // Dia original do lançamento (usado para tratar meses com menos dias)
        $dia_original = (int)$data_lancamento->format('d');

        for ($i = 0; $i < $repeticoes; $i++) {
            $data_repetida = clone $data_lancamento;

            if ($periodo === 'Diario') {
                $data_repetida->modify("+{$i} days");

            } elseif ($periodo === 'Mensal') {
                // Calcula o mês e ano futuros considerando o incremento
                $ano = (int)$data_lancamento->format('Y');
                $mes = (int)$data_lancamento->format('m') + $i;

                // Ajuste para estourar o ano se ultrapassar 12 meses
                $ano += intdiv($mes - 1, 12);
                $mes = (($mes - 1) % 12) + 1;

                // Descobre o último dia válido do mês alvo
                $ultimo_dia = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
                $dia_final = min($dia_original, $ultimo_dia);

                $data_repetida = DateTime::createFromFormat('Y-m-d', "$ano-$mes-$dia_final");

            } elseif ($periodo === 'Anual') {
                $data_repetida->modify("+{$i} years");

            } else {
                continue; // Pula se o período for inválido
            }

            // Verifica se a data gerada é igual a hoje
            if ($data_repetida->format('Y-m-d') === $hoje->format('Y-m-d')) {
                // Verifica se esse lançamento já existe hoje
                $verifica = $conexao->prepare("
                    SELECT 1 FROM lancamentos 
                    WHERE token_emp = ? 
                    AND data_lancamento = ? 
                    AND conta_id = ? 
                    AND descricao = ? 
                    AND recorrente = 'sim'
                ");
                $verifica->execute([
                    $token_config,
                    $hoje->format('Y-m-d'),
                    $conta_id,
                    $descricao
                ]);
            
                if ($verifica->rowCount() == 0) {
                    $stmt = $conexao->prepare("
                        INSERT INTO lancamentos (token_emp, data_lancamento, conta_id, descricao, recorrente, valor, observacoes)
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $token_config,
                        $hoje->format('Y-m-d'),
                        $conta_id,
                        $descricao,
                        'sim',
                        $valor,
                        $observacoes
                    ]);
                }
            }            
        }
    }
}
