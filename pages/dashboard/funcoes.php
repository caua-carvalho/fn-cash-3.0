<?php
// Gerado pelo Copilot

require_once dirname(__FILE__, 3) . '/conexao.php';

// Constantes para limites e cores
const LIMITE_CATEGORIAS = 4;
const CORES_CATEGORIAS = [
    '#07a362', '#e74c3c', '#3498db', '#f39c12',
    '#9b59b6', '#1abc9c', '#34495e', '#d35400'
];

/**
 * Retorna o intervalo de datas baseado no período selecionado.
 */
function obterIntervaloDatas($periodo = 'mes-atual', $dataInicio = null, $dataFim = null) {
    $hoje = new DateTime();
    $inicio = new DateTime();
    $fim = new DateTime();

    switch ($periodo) {
        case 'mes-atual':
            $inicio->modify('first day of this month');
            $fim->modify('last day of this month');
            break;
        case 'mes-anterior':
            $inicio->modify('first day of last month');
            $fim->modify('last day of last month');
            break;
        case 'ano-atual':
            $inicio->modify('first day of January ' . $hoje->format('Y'));
            $fim->modify('last day of December ' . $hoje->format('Y'));
            break;
        case 'customizado':
            if (!empty($dataInicio)) $inicio = new DateTime($dataInicio);
            if (!empty($dataFim)) $fim = new DateTime($dataFim);
            break;
    }

    return [
        'inicio' => $inicio->format('Y-m-d'),
        'fim' => $fim->format('Y-m-d')
    ];
}

/**
 * Retorna o saldo total de todas as contas do usuário.
 */
function obterSaldoTotal($idUsuario = null) {
    global $conn;
    $idUsuario = $idUsuario ?? ($_SESSION['id_usuario'] ?? null);
    if (!$idUsuario) return 0;

    $sql = "SELECT SUM(Saldo) AS saldo_total FROM CONTA WHERE ID_Usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return floatval($row['saldo_total'] ?? 0);
}

/**
 * Retorna o saldo total das contas do usuário em uma data específica.
 */
function obterSaldoTotalEmData($data, $idUsuario = null) {
    global $conn;
    $idUsuario = $idUsuario ?? ($_SESSION['id_usuario'] ?? null);
    if (!$idUsuario) return 0;

    $saldoAtual = obterSaldoTotal($idUsuario);

    $sql = "SELECT SUM(CASE
                    WHEN Tipo = 'Receita' THEN Valor
                    WHEN Tipo = 'Despesa' THEN -Valor
                    ELSE 0 END) AS ajuste
            FROM TRANSACAO
            WHERE ID_Usuario = ?
              AND Data > ?
              AND Status <> 'Cancelada'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $idUsuario, $data);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $ajuste = floatval($row['ajuste'] ?? 0);

    return $saldoAtual - $ajuste;
}

/**
 * Retorna o total de receitas no período.
 */
function obterReceita($intervalo = null, $idUsuario = null) {
    return obterTotalPorTipo('Receita', $intervalo, $idUsuario);
}

/**
 * Retorna o total de despesas no período.
 */
function obterDespesa($intervalo = null, $idUsuario = null) {
    return obterTotalPorTipo('Despesa', $intervalo, $idUsuario);
}

/**
 * Função utilitária para obter total por tipo (Receita/Despesa).
 */
function obterTotalPorTipo($tipo, $intervalo = null, $idUsuario = null) {
    global $conn;
    $idUsuario = $idUsuario ?? ($_SESSION['id_usuario'] ?? null);
    if (!$idUsuario) return 0;

    $sql = "SELECT SUM(Valor) AS total FROM TRANSACAO WHERE Tipo = ? AND ID_Usuario = ?";
    $params = [$tipo, $idUsuario];
    $types = "si";

    if ($intervalo) {
        $sql .= " AND Data BETWEEN ? AND ?";
        $params[] = $intervalo['inicio'];
        $params[] = $intervalo['fim'];
        $types .= "ss";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    return floatval($res['total'] ?? 0);
}

/**
 * Retorna o saldo mensal (receitas - despesas).
 */
function obterSaldoMensal($intervalo = null, $idUsuario = null) {
    return obterReceita($intervalo, $idUsuario) - obterDespesa($intervalo, $idUsuario);
}

/**
 * Retorna dados para o gráfico de receitas e despesas dos últimos meses.
 * Sempre retorna dados mensais.
 */
function obterDadosGraficoReceitasDespesas($meses = 6, $idUsuario = null) {
    global $conn;
    $idUsuario = $idUsuario ?? ($_SESSION['id_usuario'] ?? null);

    $resultado = ['labels'=>[], 'receitas'=>[], 'despesas'=>[]];
    $data = new DateTime();
    $data->modify('first day of this month');
    setlocale(LC_TIME, 'pt_BR.utf-8');

    for ($i = 0; $i < $meses; $i++) {
        $mesAtual = (clone $data)->modify("-{$i} month");
        $inicio = clone $mesAtual;
        $fim = (clone $mesAtual)->modify('last day of this month');
        array_unshift($resultado['labels'], ucfirst(strftime('%B', $mesAtual->getTimestamp())));
        $intervalo = ['inicio'=>$inicio->format('Y-m-d'),'fim'=>$fim->format('Y-m-d')];
        array_unshift($resultado['receitas'], obterReceita($intervalo, $idUsuario));
        array_unshift($resultado['despesas'], obterDespesa($intervalo, $idUsuario));
    }
    return $resultado;
}

/**
 * Retorna dados diários para o gráfico de Receitas vs Despesas,
 * mostrando apenas os dias que tiveram movimentação.
 * Gerado pelo Copilot
 */
function obterDadosGraficoReceitasDespesasPorDia($intervalo, $idUsuario = null) {
    global $conn;
    $idUsuario = $idUsuario ?? ($_SESSION['id_usuario'] ?? null);
    if (!$idUsuario) return ['labels'=>[], 'receitas'=>[], 'despesas'=>[]];

    $sql = "
        SELECT Data, 
            SUM(CASE WHEN Tipo = 'Receita' THEN Valor ELSE 0 END) AS total_receita,
            SUM(CASE WHEN Tipo = 'Despesa' THEN Valor ELSE 0 END) AS total_despesa
        FROM TRANSACAO
        WHERE ID_Usuario = ?
          AND Data BETWEEN ? AND ?
        GROUP BY Data
        ORDER BY Data ASC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $idUsuario, $intervalo['inicio'], $intervalo['fim']);
    $stmt->execute();
    $result = $stmt->get_result();

    $labels = [];
    $receitas = [];
    $despesas = [];
    while ($row = $result->fetch_assoc()) {
        $labels[] = date('d/m', strtotime($row['Data']));
        $receitas[] = floatval($row['total_receita']);
        $despesas[] = floatval($row['total_despesa']);
    }
    return [
        'labels' => $labels,
        'receitas' => $receitas,
        'despesas' => $despesas
    ];
}

/**
 * Retorna dados mensais para o gráfico de Receitas vs Despesas (ano atual),
 * mostrando todos os meses do ano, preenchendo com zero onde não houver movimentação.
 * Gerado pelo Copilot
 */
function obterDadosGraficoReceitasDespesasPorMes($intervalo, $idUsuario = null) {
    global $conn;
    $idUsuario = $idUsuario ?? ($_SESSION['id_usuario'] ?? null);

    $ano = (new DateTime($intervalo['inicio']))->format('Y');
    $meses = [];
    for ($m = 1; $m <= 12; $m++) {
        $mesStr = str_pad($m, 2, '0', STR_PAD_LEFT) . '/' . $ano;
        $meses[$mesStr] = ['receita' => 0, 'despesa' => 0];
    }

    $sql = "
        SELECT DATE_FORMAT(Data, '%m/%Y') AS mes_ano,
            SUM(CASE WHEN Tipo = 'Receita' THEN Valor ELSE 0 END) AS total_receita,
            SUM(CASE WHEN Tipo = 'Despesa' THEN Valor ELSE 0 END) AS total_despesa
        FROM TRANSACAO
        WHERE ID_Usuario = ?
          AND Data BETWEEN ? AND ?
        GROUP BY mes_ano
        ORDER BY MIN(Data) ASC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $idUsuario, $intervalo['inicio'], $intervalo['fim']);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $mes = $row['mes_ano'];
        if (isset($meses[$mes])) {
            $meses[$mes]['receita'] = floatval($row['total_receita']);
            $meses[$mes]['despesa'] = floatval($row['total_despesa']);
        }
    }

    $labels = [];
    $receitas = [];
    $despesas = [];
    foreach ($meses as $mes => $valores) {
        $labels[] = $mes;
        $receitas[] = $valores['receita'];
        $despesas[] = $valores['despesa'];
    }
    return [
        'labels' => $labels,
        'receitas' => $receitas,
        'despesas' => $despesas
    ];
}

/**
 * Retorna dados para o gráfico de categorias, incluindo receitas e despesas.
 */
function obterDadosGraficoCategorias($intervalo = null, $idUsuario = null) {
    global $conn;
    $idUsuario = $idUsuario ?? ($_SESSION['id_usuario'] ?? null);
    $intervalo = $intervalo ?? obterIntervaloDatas('mes-atual');

    $sql = "SELECT c.Nome, t.Tipo, SUM(t.Valor) as Valor
            FROM TRANSACAO t
            JOIN CATEGORIA c ON t.ID_Categoria = c.ID_Categoria
            WHERE t.ID_Usuario = ?
            AND t.Data BETWEEN ? AND ?
            GROUP BY c.Nome, t.Tipo
            ORDER BY Valor DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $idUsuario, $intervalo['inicio'], $intervalo['fim']);
    $stmt->execute();
    $result = $stmt->get_result();

    $categorias = [];
    $valoresReceita = [];
    $valoresDespesa = [];
    $totalOutrosReceita = 0;
    $totalOutrosDespesa = 0;
    $i = 0;

    while ($row = $result->fetch_assoc()) {
        if ($i < LIMITE_CATEGORIAS) {
            if (!isset($categorias[$row['Nome']])) {
                $categorias[$row['Nome']] = true;
                $valoresReceita[$row['Nome']] = 0;
                $valoresDespesa[$row['Nome']] = 0;
                $i++;
            }
            if ($row['Tipo'] === 'Receita') {
                $valoresReceita[$row['Nome']] += floatval($row['Valor']);
            } else {
                $valoresDespesa[$row['Nome']] += floatval($row['Valor']);
            }
        } else {
            if ($row['Tipo'] === 'Receita') {
                $totalOutrosReceita += floatval($row['Valor']);
            } else {
                $totalOutrosDespesa += floatval($row['Valor']);
            }
        }
    }

    if ($totalOutrosReceita > 0 || $totalOutrosDespesa > 0) {
        $categorias['Outros'] = true;
        $valoresReceita['Outros'] = $totalOutrosReceita;
        $valoresDespesa['Outros'] = $totalOutrosDespesa;
    }

    $cores = array_slice(CORES_CATEGORIAS, 0, count($categorias));
    return [
        'labels' => array_keys($categorias),
        'receitas' => array_values($valoresReceita),
        'despesas' => array_values($valoresDespesa),
        'backgroundColor' => $cores
    ];
}

/**
 * Retorna as contas do usuário com seus saldos.
 */
function obterContasUsuario($limite = 2, $idUsuario = null) {
    global $conn;
    $idUsuario = $idUsuario ?? ($_SESSION['id_usuario'] ?? null);
    $sql = "SELECT ID_Conta, Nome, Tipo, Saldo, Instituicao 
            FROM CONTA 
            WHERE ID_Usuario = ? 
            ORDER BY Saldo DESC 
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $idUsuario, $limite);
    $stmt->execute();
    $result = $stmt->get_result();
    $contas = [];
    while ($row = $result->fetch_assoc()) {
        $contas[] = $row;
    }
    return $contas;
}

/**
 * Retorna as transações mais recentes.
 */
function obterTransacoesRecentes($limite = 4, $intervalo = null, $idUsuario = null) {
    global $conn;
    $idUsuario = $idUsuario ?? ($_SESSION['id_usuario'] ?? null);
    $sql = "SELECT 
                t.*, 
                cr.ID_Conta AS ID_ContaRemetente, 
                cr.Nome AS NomeContaRemetente, 
                cd.ID_Conta AS ID_ContaDestinataria, 
                cd.Nome AS NomeContaDestinataria,
                c.Nome AS NomeCategoria
            FROM TRANSACAO t
            LEFT JOIN CONTA cr ON t.ID_ContaRemetente = cr.ID_Conta
            LEFT JOIN CONTA cd ON t.ID_ContaDestinataria = cd.ID_Conta
            LEFT JOIN CATEGORIA c ON t.ID_Categoria = c.ID_Categoria
            WHERE t.ID_Usuario = ?";
    $params = [$idUsuario];
    $types = "i";
    if ($intervalo) {
        $sql .= " AND t.Data BETWEEN ? AND ?";
        $params[] = $intervalo['inicio'];
        $params[] = $intervalo['fim'];
        $types .= "ss";
    }
    $sql .= " ORDER BY t.Data DESC, t.ID_Transacao DESC LIMIT ?";
    $params[] = $limite;
    $types .= "i";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $transacoes = [];
    while ($row = $result->fetch_assoc()) {
        $dataObj = new DateTime($row['Data']);
        $row['DataFormatada'] = $dataObj->format('d/m/Y');
        $hoje = new DateTime();
        $diff = $hoje->diff($dataObj);
        if ($diff->days == 0) {
            $row['TempoRelativo'] = 'Hoje';
        } elseif ($diff->days == 1) {
            $row['TempoRelativo'] = 'Ontem';
        } elseif ($diff->days < 7) {
            $row['TempoRelativo'] = $diff->days . ' dias atrás';
        } else {
            $row['TempoRelativo'] = $dataObj->format('d/m/Y');
        }
        $transacoes[] = $row;
    }
    return $transacoes;
}

/**
 * Calcula a variação percentual entre períodos.
 */
function calcularVariacaoPercentual($tipo, $intervalo, $idUsuario = null) {
    $dataInicio = new DateTime($intervalo['inicio']);
    $dataFim = new DateTime($intervalo['fim']);
    $diferenca = $dataInicio->diff($dataFim)->days + 1;
    $dataInicioAnterior = clone $dataInicio;
    $dataInicioAnterior->modify("-{$diferenca} days");
    $dataFimAnterior = clone $dataFim;
    $dataFimAnterior->modify("-{$diferenca} days");
    $intervaloAnterior = [
        'inicio' => $dataInicioAnterior->format('Y-m-d'),
        'fim' => $dataFimAnterior->format('Y-m-d')
    ];
    $valorAtual = 0;
    $valorAnterior = 0;
    switch ($tipo) {
        case 'saldo':
            $valorAtual = obterSaldoTotalEmData($intervalo['fim'], $idUsuario);
            $valorAnterior = obterSaldoTotalEmData($intervaloAnterior['fim'], $idUsuario);
            break;
        case 'receita':
            $valorAtual = obterReceita($intervalo, $idUsuario);
            $valorAnterior = obterReceita($intervaloAnterior, $idUsuario);
            break;
        case 'despesa':
            $valorAtual = obterDespesa($intervalo, $idUsuario);
            $valorAnterior = obterDespesa($intervaloAnterior, $idUsuario);
            break;
        case 'saldo_mensal':
            $valorAtual = obterSaldoMensal($intervalo, $idUsuario);
            $valorAnterior = obterSaldoMensal($intervaloAnterior, $idUsuario);
            break;
    }
    if ($valorAnterior == 0) return $valorAtual > 0 ? 100 : 0;
    return round((($valorAtual - $valorAnterior) / abs($valorAnterior)) * 100);
}
?>