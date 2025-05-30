<?php
// Gerado pelo Copilot
header('Content-Type: application/json');
require_once '../../conexao.php';

function obterDadosGraficos() {
    global $conn;
    
    // Dados para o gráfico de evolução
    $sqlEvolucao = "SELECT 
        DATE_FORMAT(Data, '%Y-%m') as mes,
        SUM(CASE WHEN Tipo = 'Receita' THEN Valor ELSE 0 END) as receitas,
        SUM(CASE WHEN Tipo = 'Despesa' THEN Valor ELSE 0 END) as despesas
        FROM TRANSACAO 
        WHERE ID_Usuario = 1
        AND Data >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)
        GROUP BY DATE_FORMAT(Data, '%Y-%m')
        ORDER BY mes ASC";
    
    $resultEvolucao = $conn->query($sqlEvolucao);
    $dadosEvolucao = [
        'labels' => [],
        'receitas' => [],
        'despesas' => []
    ];

    while ($row = $resultEvolucao->fetch_assoc()) {
        $mesFormatado = date('M/Y', strtotime($row['mes'] . '-01'));
        $dadosEvolucao['labels'][] = $mesFormatado;
        $dadosEvolucao['receitas'][] = floatval($row['receitas']);
        $dadosEvolucao['despesas'][] = floatval($row['despesas']);
    }

    // Dados para o gráfico de categorias
    $sqlCategorias = "SELECT 
        c.Nome as categoria,
        SUM(t.Valor) as total,
        COALESCE(c.Cor, '#07A362') as cor
        FROM TRANSACAO t
        LEFT JOIN CATEGORIA c ON t.ID_Categoria = c.ID_Categoria
        WHERE t.ID_Usuario = 1 AND t.Tipo = 'Despesa'
        AND t.Data >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
        GROUP BY c.ID_Categoria
        ORDER BY total DESC
        LIMIT 5";
    
    $resultCategorias = $conn->query($sqlCategorias);
    $dadosCategorias = [
        'labels' => [],
        'data' => [],
        'backgroundColor' => []
    ];

    while ($row = $resultCategorias->fetch_assoc()) {
        $dadosCategorias['labels'][] = $row['categoria'] ?? 'Sem categoria';
        $dadosCategorias['data'][] = floatval($row['total']);
        $dadosCategorias['backgroundColor'][] = $row['cor'];
    }

    return [
        'evolution' => $dadosEvolucao,
        'categories' => $dadosCategorias
    ];
}

try {
    $dados = obterDadosGraficos();
    echo json_encode($dados);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
