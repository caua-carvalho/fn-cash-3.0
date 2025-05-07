<?php
// Arquivo: pages/categorias/script.php
/**
 * Obtém o ícone correspondente ao tipo de categoria
 * @param string $tipo Tipo da categoria
 * @return string Classe do ícone FontAwesome
 */
function obterIconeTipoCategoria($tipo)
{
    switch ($tipo) {
        case 'Receita':
            return 'fa-arrow-up';
        case 'Despesa':
            return 'fa-arrow-down';
        default:
            return 'fa-tag';
    }
}

/**
 * Obtém a cor correspondente ao tipo de categoria
 * @param string $tipo Tipo da categoria
 * @return string Cor hexadecimal ou nome da variável CSS
 */
function obterCorTipoCategoria($tipo)
{
    switch ($tipo) {
        case 'Receita':
            return 'var(--color-income)';
        case 'Despesa':
            return 'var(--color-expense)';
        default:
            return 'var(--color-text-muted)';
    }
}

/**
 * Verifica se uma categoria pode ser excluída com segurança
 * @param int $idCategoria ID da categoria
 * @return bool True se for seguro excluir, False caso contrário
 */
function podeDeletarCategoria($idCategoria)
{
    global $conn;

    // Verifica se existem transações vinculadas a esta categoria
    $sql = "SELECT COUNT(*) as total FROM TRANSACAO WHERE ID_Categoria = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idCategoria);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return ($row['total'] == 0);
}
?>