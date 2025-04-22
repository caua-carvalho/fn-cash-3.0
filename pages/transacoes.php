<?php
require_once 'transacoes/funcoes.php';
require_once 'header.php';
require_once 'nav.php';
require_once 'transacoes/modal.php';
require_once 'dialog.php';
require_once '../conexao.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
            <th>Valor</th>
            <th>Data</th>
            <th>Tipo</th>
            <th>Status</th>
            <th>Conta Remetente</th>
            <th>Conta Destinatária</th>
            <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $transacoes = obterTransacoes();
            foreach ($transacoes as $transacao) {
            echo "<tr>
             <td>" . htmlspecialchars($transacao['Titulo']) . "</td>
             <td>R$ " . number_format($transacao['Valor'], 2, ',', '.') . "</td>
             <td>" . htmlspecialchars($transacao['Data']) . "</td>
             <td><span style='background-color: " . ($transacao['Tipo'] === 'Receita' ? 'green' : ($transacao['Tipo'] === 'Despesa' ? 'red' : 'blue')) . "; color: white; padding: 2px 8px; border-radius: 5px; font-size: 12px;'>" . htmlspecialchars($transacao['Tipo']) . "</span></td>
             <td>" . htmlspecialchars($transacao['Status']) . "</td>
             <td>" . htmlspecialchars($transacao['NomeContaRemetente']) . "</td>
             <td>" . ($transacao['NomeContaDestinataria'] !== null ? htmlspecialchars($transacao['NomeContaDestinataria']) : '-') . "</td>
             <td>
             <a class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editarTransacaoModal'
                data-id='" . $transacao['ID_Transacao'] . "'
                data-titulo='" . htmlspecialchars($transacao['Titulo']) . "'
                data-descricao='" . htmlspecialchars($transacao['Descricao']) . "'
                data-valor='" . $transacao['Valor'] . "'
                data-data='" . $transacao['Data'] . "'
                data-tipo='" . htmlspecialchars($transacao['Tipo']) . "'
                data-status='" . $transacao['Status'] . "'
                data-conta-remetente-id='" . htmlspecialchars($transacao['ID_ContaRemetente']) . "'
                data-conta-remetente-nome='" . htmlspecialchars($transacao['NomeContaRemetente']) . "'
                data-conta-destinataria-id='" . ($transacao['ID_ContaDestinataria'] !== null ? htmlspecialchars($transacao['ID_ContaDestinataria']) : '') . "'
                data-conta-destinataria-nome='" . ($transacao['NomeContaDestinataria'] !== null ? htmlspecialchars($transacao['NomeContaDestinataria']) : '-') . "'>
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
    $acao = $_POST['acao'] ?? null;

    if (!$acao) {
        erro("Ação não definida.");
        exit;
    }

    if ($acao === 'excluirTransacao') {
        if (empty($_POST['transacaoId'])) {
            erro("ID da transação não fornecido.");
            exit;
        }

        deletarTransacao($_POST['transacaoId'])
            ? confirmar("Transação excluída com sucesso!", "transacoes.php")
            : erro("Erro ao excluir transação. Verifique os dados e tente novamente.");
        exit;
    }

    if (!in_array($_POST['tipoTransacao'], ['Despesa', 'Receita', 'Transferência']) || !in_array($_POST['statusTransacao'], ['Pendente', 'Efetivada', 'Cancelada'])) {
        erro("Dados inválidos fornecidos.");
        exit;
    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM CONTA WHERE ID_Conta = ?");
    $stmt->bind_param("i", $_POST['ContaRemetente']);
    $stmt->execute();
    $stmt->bind_result($contaRemetenteExiste);
    $stmt->fetch();
    $stmt->close();

    if (!empty($_POST['categoriaTransacao'])) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM CATEGORIA WHERE ID_Categoria = ?");
        $stmt->bind_param("i", $_POST['categoriaTransacao']);
        $stmt->execute();
        $stmt->bind_result($categoriaExiste);
        $stmt->fetch();
        $stmt->close();

        if (!$categoriaExiste) {
            erro("Categoria inválida.");
            exit;
        }
    }

    $success = false;
    if ($acao === 'editarTransacao') {
        $success = editarTransacao(
            $_SESSION['id_usuario'] ?? null,
            $_POST['tituloTransacao'],
            $_POST['descricaoTransacao'],
            $_POST['valorTransacao'],
            $_POST['dataTransacao'],
            $_POST['tipoTransacao'],
            $_POST['statusTransacao'],
            $_POST['categoriaTransacao'] ?? null,
            $_POST['contaRemetente'],
            ($_POST['tipoTransacao'] === 'Transferência') ? intval($_POST['contaDestinataria']) : null,
            $_POST['idTransacao']
        );
    } elseif ($acao === 'cadastrarTransacao') {
        $success = cadastrarTransacao(
            $_SESSION['id_usuario'] ?? null,
            $_POST['tituloTransacao'],
            $_POST['descricaoTransacao'],
            $_POST['valorTransacao'],
            $_POST['formaPagamento'],
            $_POST['dataTransacao'],
            $_POST['tipoTransacao'],
            $_POST['statusTransacao'],
            $_POST['categoriaTransacao'] ?? null,
            $_POST['contaRemetente'],
            ($_POST['tipoTransacao'] === 'Transferência') ? intval($_POST['contaDestinataria']) : null
        );
    }

    $success
        ? confirmar("Operação realizada com sucesso!", "transacoes.php")
        : erro("Erro ao processar a operação. Verifique os dados e tente novamente.");
}
?>
