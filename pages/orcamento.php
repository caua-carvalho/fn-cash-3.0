<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'header.php';
require_once 'nav.php';
require_once 'orcamentos/funcoes.php';
require_once 'orcamentos/script.php';
include_once 'orcamentos/modal.php';
require_once 'dialog.php';


// /opt/lampp/htdocs/fn-cash-3.0/pages/orcamento.php
// on line
// 99


// Fatal error
// : Uncaught mysqli_sql_exception: Column count doesn't match value count at row 1 in /opt/lampp/htdocs/fn-cash-3.0/pages/orcamentos/funcoes.php:23 Stack trace: #0 /opt/lampp/htdocs/fn-cash-3.0/pages/orcamentos/funcoes.php(23): mysqli->prepare('INSERT INTO ORC...') #1 /opt/lampp/htdocs/fn-cash-3.0/pages/orcamento.php(99): cadastrarOrcamento('5', 'teste', '10000', NULL, '', '1') #2 {main} thrown in
// /opt/lampp/htdocs/fn-cash-3.0/pages/orcamentos/funcoes.php

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
                <th>Título</th>
                <th>Categoria</th>
                <th>Valor Planejado</th>
                <th>Período</th>
                <th>Descrição</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $orcamentos = obterOrcamentos(); // Função que busca os dados da tabela ORCAMENTO
            foreach ($orcamentos as $orcamento) {
                echo "<tr>
                     <td>" . htmlspecialchars($orcamento['Titulo']) . "</td>
                     <td>" . htmlspecialchars($orcamento['NomeCategoria']) . "</td>
                     <td>R$ " . number_format($orcamento['Valor'], 2, ',', '.') . "</td>
                     <td>" . htmlspecialchars($orcamento['Inicio']) . " a " . htmlspecialchars($orcamento['Fim']) . "</td>
                     <td>" . htmlspecialchars($orcamento['Descricao']) . "</td>
                     <td>" . ($orcamento['Ativo'] ? 'Ativo' : 'Inativo') . "</td>
                     <td>
                     <a class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editarOrcamentoModal'
                        data-id='" . $orcamento['ID_Orcamento'] . "'
                        data-titulo='" . htmlspecialchars($orcamento['Titulo']) . "'
                        data-categoria-id='" . $orcamento['ID_Categoria'] . "'
                        data-valor='" . $orcamento['Valor'] . "'
                        data-inicio='" . htmlspecialchars($orcamento['Inicio']) . "'
                        data-fim='" . htmlspecialchars($orcamento['Fim']) . "'
                        data-descricao='" . htmlspecialchars($orcamento['Descricao']) . "'
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
    $titulo = $_POST['titulo'] ?? '';
    $idCategoria = $_POST['categoriaId'] ?? null;
    $valorPlanejado = $_POST['valorPlanejado'] ?? 0.00;
    $inicio = $_POST['periodoInicio'] ?? '';
    $fim = $_POST['periodoFim'] ?? '';
    $status = $_POST['status'] ?? true;

    // Processa a ação solicitada
    switch ($acao) {
        case 'editarOrcamento':
            if (editarOrcamento($id, $idCategoria, $titulo, $valorPlanejado, $inicio, $fim, $status)) {
                confirmar("Orçamento editado com sucesso!", "orcamento.php");
            } else {
                erro("Erro ao editar orçamento. Verifique os dados e tente novamente.");
            }
            break;

        case 'cadastrarOrcamento':
            if (cadastrarOrcamento($_SESSION["id_usuario"] ,$idCategoria, $titulo, $valorPlanejado, $inicio, $fim, $status)) {
                confirmar("Orçamento cadastrado com sucesso!", "orcamento.php");
            } else {
                erro("Erro ao cadastrar orçamento. Verifique os dados e tente novamente.");
            }
            break;

        case 'excluirOrcamento':
            if (deletarOrcamento($id)) {
                confirmar("Orçamento excluído com sucesso!", "orcamento.php");
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