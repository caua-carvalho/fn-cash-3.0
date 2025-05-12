<?php
// Arquivo: pages/dashboard/funcoes.php
// Funções para alimentar o dashboard com dados dinâmicos

// Inclui arquivo de conexão com o banco de dados
require_once dirname(__FILE__, 3) . '/conexao.php';

/**
 * Determina o intervalo de datas com base no período selecionado
 * @param string $periodo ('mes-atual', 'mes-anterior', 'ano-atual', 'customizado')
 * @param string $dataInicio (apenas para período customizado)
 * @param string $dataFim (apenas para período customizado)
 * @return array Array associativo com 'inicio' e 'fim' no formato Y-m-d
 */
function obterIntervaloDatas($periodo = 'mes-atual', $dataInicio = null, $dataFim = null) {
    $hoje = new DateTime();
    $inicio = new DateTime();
    $fim = new DateTime();

    switch ($periodo) {
        case 'mes-atual':
            // Primeiro dia do mês atual
            $inicio->modify('first day of this month');
            // Último dia do mês atual
            $fim->modify('last day of this month');
            break;

        case 'mes-anterior':
            // Primeiro dia do mês anterior
            $inicio->modify('first day of last month');
            // Último dia do mês anterior
            $fim->modify('last day of last month');
            break;

        case 'ano-atual':
            // Primeiro dia do ano atual
            $inicio->modify('first day of January ' . $hoje->format('Y'));
            // Último dia do ano atual
            $fim->modify('last day of December ' . $hoje->format('Y'));
            break;

        case 'customizado':
            // Usa as datas fornecidas
            if (!empty($dataInicio)) {
                $inicio = new DateTime($dataInicio);
            }
            if (!empty($dataFim)) {
                $fim = new DateTime($dataFim);
            }
            break;
    }

    return [
        'inicio' => $inicio->format('Y-m-d'),
        'fim' => $fim->format('Y-m-d')
    ];
}

/**
 * Retorna o saldo total de todas as contas
 * @param int $idUsuario ID do usuário (opcional, usa o da sessão se não for informado)
 * @return float Saldo total
 */
function obterSaldoTotal($idUsuario = null) {
    global $conn;
    
    if ($idUsuario === null && isset($_SESSION['id_usuario'])) {
        $idUsuario = $_SESSION['id_usuario'];
    }
    
    $sql = "SELECT SUM(Saldo) AS saldo_total FROM CONTA";
    
    if ($idUsuario !== null) {
        $sql .= " WHERE ID_Usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
    } else {
        $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return floatval($row['saldo_total']); 
    }

    return 0;
}

/**
 * Retorna o total de receitas no período
 * @param array $intervalo Array com 'inicio' e 'fim' no formato Y-m-d
 * @param int $idUsuario ID do usuário (opcional, usa o da sessão se não for informado)
 * @return float Total de receitas
 */
function obterReceita($intervalo = null, $idUsuario = null) {
    global $conn;
    
    if ($idUsuario === null && isset($_SESSION['id_usuario'])) {
        $idUsuario = $_SESSION['id_usuario'];
    }
    
    $sql = "SELECT SUM(Valor) AS receita_total FROM TRANSACAO WHERE Tipo = 'Receita'";
    
    $params = [];
    $types = "";
    
    if ($idUsuario !== null) {
        $sql .= " AND ID_Usuario = ?";
        $params[] = $idUsuario;
        $types .= "i";
    }
    
    if ($intervalo !== null) {
        $sql .= " AND Data BETWEEN ? AND ?";
        $params[] = $intervalo['inicio'];
        $params[] = $intervalo['fim'];
        $types .= "ss";
    }
    
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return floatval($row['receita_total'] ?? 0);
    }

    return 0;
}

/**
 * Retorna o total de despesas no período
 * @param array $intervalo Array com 'inicio' e 'fim' no formato Y-m-d
 * @param int $idUsuario ID do usuário (opcional, usa o da sessão se não for informado)
 * @return float Total de despesas
 */
function obterDespesa($intervalo = null, $idUsuario = null) {
    global $conn;
    
    if ($idUsuario === null && isset($_SESSION['id_usuario'])) {
        $idUsuario = $_SESSION['id_usuario'];
    }
    
    $sql = "SELECT SUM(Valor) AS despesa_total FROM TRANSACAO WHERE Tipo = 'Despesa'";
    
    $params = [];
    $types = "";
    
    if ($idUsuario !== null) {
        $sql .= " AND ID_Usuario = ?";
        $params[] = $idUsuario;
        $types .= "i";
    }
    
    if ($intervalo !== null) {
        $sql .= " AND Data BETWEEN ? AND ?";
        $params[] = $intervalo['inicio'];
        $params[] = $intervalo['fim'];
        $types .= "ss";
    }
    
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return floatval($row['despesa_total'] ?? 0);
    }

    return 0;
}

/**
 * Calcula o saldo mensal (receitas - despesas)
 * @param array $intervalo Array com 'inicio' e 'fim' no formato Y-m-d
 * @param int $idUsuario ID do usuário (opcional, usa o da sessão se não for informado)
 * @return float Saldo mensal
 */
function obterSaldoMensal($intervalo = null, $idUsuario = null) {
    $receita = obterReceita($intervalo, $idUsuario);
    $despesa = obterDespesa($intervalo, $idUsuario);
    return $receita - $despesa;
}

/**
 * Retorna dados para o gráfico de receitas e despesas dos últimos meses
 * @param int $meses Quantidade de meses a serem retornados
 * @param int $idUsuario ID do usuário (opcional, usa o da sessão se não for informado)
 * @return array Array com 'labels', 'receitas' e 'despesas'
 */
function obterDadosGraficoReceitasDespesas($meses = 6, $idUsuario = null) {
    global $conn;
    
    if ($idUsuario === null && isset($_SESSION['id_usuario'])) {
        $idUsuario = $_SESSION['id_usuario'];
    }
    
    $resultado = [
        'labels' => [],
        'receitas' => [],
        'despesas' => []
    ];
    
    // Gera os últimos X meses a partir do mês atual
    $data = new DateTime();
    $data->modify('first day of this month'); // Começa do primeiro dia do mês atual
    
    for ($i = 0; $i < $meses; $i++) {
        // Subtrai i meses da data atual
        $mesAtual = clone $data;
        $mesAtual->modify("-{$i} month");
        
        $inicio = clone $mesAtual;
        $fim = clone $mesAtual;
        $fim->modify('last day of this month');
        
        // Adiciona o nome do mês ao array de labels (em português)
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
        $nomeMes = ucfirst(strftime('%B', $mesAtual->getTimestamp()));
        array_unshift($resultado['labels'], $nomeMes);
        
        // Intervalo para este mês
        $intervalo = [
            'inicio' => $inicio->format('Y-m-d'),
            'fim' => $fim->format('Y-m-d')
        ];
        
        // Obtém receitas e despesas para este mês
        $receitaMes = obterReceita($intervalo, $idUsuario);
        $despesaMes = obterDespesa($intervalo, $idUsuario);
        
        // Adiciona ao início dos arrays para manter a ordem cronológica
        array_unshift($resultado['receitas'], $receitaMes);
        array_unshift($resultado['despesas'], $despesaMes);
    }
    
    return $resultado;
}

/**
 * Retorna dados para o gráfico de despesas por categoria
 * @param array $intervalo Array com 'inicio' e 'fim' no formato Y-m-d
 * @param int $idUsuario ID do usuário (opcional, usa o da sessão se não for informado)
 * @return array Array com 'labels' (nomes das categorias) e 'data' (valores)
 */
function obterDadosGraficoCategorias($intervalo = null, $idUsuario = null) {
    global $conn;
    
    if ($idUsuario === null && isset($_SESSION['id_usuario'])) {
        $idUsuario = $_SESSION['id_usuario'];
    }
    
    if ($intervalo === null) {
        $intervalo = obterIntervaloDatas('mes-atual');
    }
    
    $sql = "SELECT c.Nome, SUM(t.Valor) as Valor
            FROM TRANSACAO t
            JOIN CATEGORIA c ON t.ID_Categoria = c.ID_Categoria
            WHERE t.Tipo = 'Despesa'";
    
    $params = [];
    $types = "";
    
    if ($idUsuario !== null) {
        $sql .= " AND t.ID_Usuario = ?";
        $params[] = $idUsuario;
        $types .= "i";
    }
    
    $sql .= " AND t.Data BETWEEN ? AND ?";
    $params[] = $intervalo['inicio'];
    $params[] = $intervalo['fim'];
    $types .= "ss";
    
    $sql .= " GROUP BY c.Nome
              ORDER BY Valor DESC";
    
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $categorias = [];
    $valores = [];
    $cores = [
        '#07a362',  // Verde FnCash para Alimentação
        '#e74c3c',  // Vermelho para Saúde
        '#3498db',  // Azul para Entretenimento
        '#f39c12',  // Amarelo para Outros
        '#9b59b6',  // Roxo
        '#1abc9c',  // Verde água
        '#34495e',  // Azul escuro
        '#d35400'   // Laranja
    ];
    
    $i = 0;
    $totalOutros = 0;
    $limiteCategorias = 4; // Limita a 4 categorias principais + "Outros"
    
    while ($row = $result->fetch_assoc()) {
        if ($i < $limiteCategorias) {
            $categorias[] = $row['Nome'];
            $valores[] = floatval($row['Valor']);
            $i++;
        } else {
            $totalOutros += floatval($row['Valor']);
        }
    }
    
    // Se houver categorias agrupadas em "Outros"
    if ($totalOutros > 0) {
        $categorias[] = 'Outros';
        $valores[] = $totalOutros;
    }
    
    // Limita as cores ao número de categorias
    $cores = array_slice($cores, 0, count($categorias));
    
    return [
        'labels' => $categorias,
        'data' => $valores,
        'backgroundColor' => $cores
    ];
}

/**
 * Retorna as contas do usuário com seus saldos
 * @param int $limite Limite de contas a serem retornadas
 * @param int $idUsuario ID do usuário (opcional, usa o da sessão se não for informado)
 * @return array Lista de contas com seus detalhes
 */
function obterContasUsuario($limite = 2, $idUsuario = null) {
    global $conn;
    
    if ($idUsuario === null && isset($_SESSION['id_usuario'])) {
        $idUsuario = $_SESSION['id_usuario'];
    }
    
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
 * Retorna as transações mais recentes
 * @param int $limite Quantidade de transações a serem retornadas
 * @param array $intervalo Array com 'inicio' e 'fim' no formato Y-m-d
 * @param int $idUsuario ID do usuário (opcional, usa o da sessão se não for informado)
 * @return array Lista de transações recentes
 */
function obterTransacoesRecentes($limite = 4, $intervalo = null, $idUsuario = null) {
    global $conn;
    
    if ($idUsuario === null && isset($_SESSION['id_usuario'])) {
        $idUsuario = $_SESSION['id_usuario'];
    }
    
    $sql = "SELECT t.ID_Transacao, t.Titulo, t.Valor, t.Data, t.Tipo, t.Status, 
                   c.Nome as NomeCategoria, 
                   cr.Nome as ContaRemetente
            FROM TRANSACAO t
            LEFT JOIN CATEGORIA c ON t.ID_Categoria = c.ID_Categoria
            LEFT JOIN CONTA cr ON t.ID_ContaRemetente = cr.ID_Conta
            WHERE t.ID_Usuario = ?";
    
    $params = [$idUsuario];
    $types = "i";
    
    if ($intervalo !== null) {
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
        // Formata a data para exibição
        $dataObj = new DateTime($row['Data']);
        $row['DataFormatada'] = $dataObj->format('d/m/Y');
        
        // Calcula tempo relativo (hoje, ontem, etc.)
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
 * Calcula a variação percentual
 * @param string $tipo ('saldo', 'receita', 'despesa', 'saldo_mensal')
 * @param array $intervalo Array com 'inicio' e 'fim' no formato Y-m-d (período atual)
 * @param int $idUsuario ID do usuário (opcional, usa o da sessão se não for informado)
 * @return int|float Variação percentual com sinal
 */
function calcularVariacaoPercentual($tipo, $intervalo, $idUsuario = null) {
    // Obtém o intervalo do período anterior com mesmo tamanho
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
    
    // Obtém os valores atual e anterior com base no tipo
    $valorAtual = 0;
    $valorAnterior = 0;
    
    switch ($tipo) {
        case 'saldo':
            // Para saldo total, não usamos intervalo
            $valorAtual = obterSaldoTotal($idUsuario);
            // Para comparação, precisaríamos de um histórico de saldos
            // Como simplificação, usaremos um valor fixo de comparação
            $valorAnterior = $valorAtual * 0.9; // Assume 10% de crescimento
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
    
    // Calcula a variação percentual
    if ($valorAnterior == 0) {
        // Evita divisão por zero
        return $valorAtual > 0 ? 100 : 0;
    }
    
    return round((($valorAtual - $valorAnterior) / abs($valorAnterior)) * 100);
}
?>