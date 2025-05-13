<?php
/**
 * Funções auxiliares para a página de categorias
 */

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
?>