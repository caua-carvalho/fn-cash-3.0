<?php
require_once 'header.php';
require_once 'nav.php';
require_once 'transacoes/funcoes.php';
require_once 'transacoes/modal.php';
require_once 'dialog.php';
?>

<div class="container">
    <h2>Transações</h2>
    
    <!-- Botão para cadastrar nova transação -->
    <div class="mb-3">
        <button class="btn btn-success" data-toggle="modal" data-target="#transacaoModal">Cadastrar Nova Transação</button>
    </div>

    <!-- Tabela para exibir as transações -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Título</th>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Data</th>
                <th>Tipo</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $transacoes = obterTransacoes();
            foreach ($transacoes as $transacao) {
                echo "<tr>
                     <td>" . htmlspecialchars($transacao['Titulo']) . "</td>
                     <td>" . htmlspecialchars($transacao['Descricao']) . "</td>
                     <td>R$ " . number_format($transacao['Valor'], 2, ',', '.') . "</td>
                     <td>" . htmlspecialchars($transacao['Data']) . "</td>
                     <td><span style='background-color: " . ($transacao['Tipo'] === 'Receita' ? 'green' : ($transacao['Tipo'] === 'Despesa' ? 'red' : 'blue')) . "; color: white; padding: 2px 8px; border-radius: 5px; font-size: 12px;'>" . htmlspecialchars($transacao['Tipo']) . "</span></td>
                     <td>" . htmlspecialchars($transacao['Status']) . "</td>
                     <td>
                     <a class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editarTransacaoModal'
                        data-id='" . $transacao['ID_Transacao'] . "'
                        data-titulo='" . htmlspecialchars($transacao['Titulo']) . "'
                        data-descricao='" . htmlspecialchars($transacao['Descricao']) . "'
                        data-valor='" . $transacao['Valor'] . "'
                        data-data='" . $transacao['Data'] . "'
                        data-tipo='" . $transacao['Tipo'] . "'
                        data-status='" . $transacao['Status'] . "'>
                        Editar
                     </a> 
                     <a class='btn btn-danger btn-sm' data-toggle='modal' data-target='#excluirTransacaoModal'
                        data-id='" . $transacao['ID_Transacao'] . "'>
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
    $id = $_POST['transacaoId'] ?? null;
    $titulo = $_POST['tituloTransacao'] ?? '';
    $descricao = $_POST['descricaoTransacao'] ?? '';
    $valor = $_POST['valorTransacao'] ?? 0.00;
    $data = $_POST['dataTransacao'] ?? '';
    $tipo = $_POST['tipoTransacao'] ?? '';
    $status = $_POST['statusTransacao'] ?? '';

    // Processa a ação solicitada
    switch ($acao) {
        case 'editarTransacao':
            if (editarTransacao($id, $titulo, $descricao, $valor, $data, $tipo, $status)) {
                confirmar("Transação editada com sucesso!", "transacoes.php");
            } else {
                erro("Erro ao editar transação. Verifique os dados e tente novamente.");
            }
            break;

        case 'cadastrarTransacao':
            if (cadastrarTransacao($titulo, $descricao, $valor, $data, $tipo, $status)) {
                confirmar("Transação cadastrada com sucesso!", "transacoes.php");
            } else {
                erro("Erro ao cadastrar transação. Verifique os dados e tente novamente.");
            }
            break;

        case 'excluirTransacao':
            if (deletarTransacao($id)) {
                confirmar("Transação excluída com sucesso!", "transacoes.php");
            } else {
                erro("Erro ao excluir transação. Verifique os dados e tente novamente.");
            }
            break;

        default:
            erro("Ação inválida.");
            break;
    }
}
?>
