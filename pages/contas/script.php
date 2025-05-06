<?php
// Funções auxiliares específicas para a página de contas
// Este arquivo pode ser ampliado conforme necessário

/**
 * Formata um valor numérico para o formato brasileiro (R$ 0.000,00)
 * @param float $valor Valor a ser formatado
 * @return string Valor formatado
 */
function formatarMoeda($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

/**
 * Obtém o ícone correspondente ao tipo de conta
 * @param string $tipo Tipo da conta
 * @return string Classe do ícone FontAwesome
 */
function obterIconeTipoConta($tipo) {
    switch ($tipo) {
        case 'Corrente':
            return 'fa-wallet';
        case 'Poupança':
            return 'fa-piggy-bank';
        case 'Cartão de Crédito':
            return 'fa-credit-card';
        case 'VR/VA':
            return 'fa-utensils';
        case 'Investimento':
            return 'fa-chart-line';
        case 'Dinheiro':
            return 'fa-money-bill-wave';
        default:
            return 'fa-university';
    }
}

/**
 * Obtém a cor correspondente ao tipo de conta
 * @param string $tipo Tipo da conta
 * @return string Cor hexadecimal ou nome da variável CSS
 */
function obterCorTipoConta($tipo) {
    switch ($tipo) {
        case 'Corrente':
            return 'var(--color-primary-500)';
        case 'Poupança':
            return 'var(--color-income)';
        case 'Cartão de Crédito':
            return 'var(--color-expense)';
        case 'Investimento':
            return 'var(--color-info)';
        default:
            return 'var(--color-text-muted)';
    }
}

/**
 * Verifica se uma conta pode ser excluída com segurança
 * @param int $idConta ID da conta
 * @return bool True se for seguro excluir, False caso contrário
 */
function podeDeletarConta($idConta) {
    global $conn;
    
    // Verifica se existem transações vinculadas a esta conta
    $sql = "SELECT COUNT(*) as total FROM TRANSACAO WHERE ID_ContaRemetente = ? OR ID_ContaDestinataria = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $idConta, $idConta);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Se não existirem transações, pode excluir com segurança
    return ($row['total'] == 0);
}