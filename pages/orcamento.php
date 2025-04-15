<?php

require_once 'header.php';
require_once 'nav.php';
require_once 'orcamentos/funcoes.php';
require_once 'orcamentos/script.php';
include_once 'orcamentos/modal.php';
require_once 'dialog.php';

?>

<div class="container">
    <h2>Orçamentos</h2>
    
    <!-- Botão para cadastrar novo orçamento -->
    <div class="mb-3">
        <button class="btn btn-success" data-toggle="modal" data-target="#orcamentoModal">Cadastrar Novo Orçamento</button>
    </div>

    <!-- Tabela para exibir os orçamentos -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Categoria</th>
                <th>Valor Planejado</th>
                <th>Valor Gasto</th>
                <th>Período</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $orcamentos = obterOrcamentos();
            foreach ($orcamentos as $orcamento) {
                echo "<tr>
                     <td>" . htmlspecialchars($orcamento['NomeCategoria']) . "</td>
                     <td>R$ " . number_format($orcamento['ValorPlanejado'], 2, ',', '.') . "</td>
                     <td>R$ " . number_format($orcamento['ValorGasto'], 2, ',', '.') . "</td>
                     <td>" . htmlspecialchars($orcamento['Periodo']) . "</td>
                     <td>" . ($orcamento['Ativo'] ? 'Ativo' : 'Inativo') . "</td>
                     <td>
                     <a class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editarOrcamentoModal'
                        data-id='" . $orcamento['ID_Orcamento'] . "'
                        data-categoria-id='" . $orcamento['ID_Categoria'] . "'
                        data-valor-planejado='" . $orcamento['ValorPlanejado'] . "'
                        data-periodo='" . htmlspecialchars($orcamento['Periodo']) . "'
                        data-status='" . ($orcamento['Ativo'] ? 'true' : 'false') . "'>
                        Editar
                     </a> 
                     <a class='btn btn-danger btn-sm' data-toggle='modal' data-target='#excluirOrcamentoModal'
                        data-id='" . $orcamento['ID_Orcamento'] . "'>
                        Excluir
                     </a>
                     </td>
                     </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
require_once 'footer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se a ação foi definida
    $acao = $_POST['acao'] ?? null;

    if (!$acao) {
        erro("Ação não definida.");
        exit;
    }

    // Obtém os dados enviados
    $id = $_POST['orcamentoId'] ?? null;
    $idCategoria = $_POST['categoriaId'] ?? null;
    $valorPlanejado = $_POST['valorPlanejado'] ?? 0.00;
    $periodo = $_POST['periodo'] ?? '';
    $status = $_POST['status'] ?? true;

    // Processa a ação solicitada
    switch ($acao) {
        case 'editarOrcamento':
            if (editarOrcamento($id, $idCategoria, $valorPlanejado, $periodo, $status)) {
                confirmar("Orçamento editado com sucesso!", "orcamentos.php");
            } else {
                erro("Erro ao editar orçamento. Verifique os dados e tente novamente.");
            }
            break;

        case 'cadastrarOrcamento':
            if (cadastrarOrcamento($idCategoria, $valorPlanejado, $periodo, $status)) {
                confirmar("Orçamento cadastrado com sucesso!", "orcamentos.php");
            } else {
                erro("Erro ao cadastrar orçamento. Verifique os dados e tente novamente.");
            }
            break;

        case 'excluirOrcamento':
            if (deletarOrcamento($id)) {
                confirmar("Orçamento excluído com sucesso!", "orcamentos.php");
            } else {
                erro("Erro ao excluir orçamento. Verifique os dados e tente novamente.");
            }
            break;

        default:
            erro("Ação inválida.");
            break;
    }
}
?>