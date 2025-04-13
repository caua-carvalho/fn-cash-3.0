<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'transacoes/funcoes.php';
require_once 'header.php';
require_once 'nav.php';
require_once 'transacoes/modal.php';
require_once 'dialog.php';
require_once '../conexao.php';

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
             <td>" . htmlspecialchars($transacao['ContaRemetente']) . "</td>
             <td>" . ($transacao['Tipo'] === 'Transferência' ? htmlspecialchars($transacao['ContaDestinataria']) : '-') . "</td>
             <td>
             <a class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editarTransacaoModal'
            data-id='" . $transacao['ID_Transacao'] . "'
            data-titulo='" . htmlspecialchars($transacao['Titulo']) . "'
            data-valor='" . $transacao['Valor'] . "'
            data-data='" . $transacao['Data'] . "'
            data-tipo='" . htmlspecialchars($transacao['Tipo']) . "'
            data-status='" . $transacao['Status'] . "'
            data-conta-remetente='" . htmlspecialchars($transacao['ContaRemetente']) . "'
            data-conta-destinataria='" . ($transacao['Tipo'] === 'Transferência' ? htmlspecialchars($transacao['ContaDestinataria']) : '-') . "'>
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
    $titulo = $_POST['tituloTransacao'] ?? '';
    $descricao = $_POST['descricaoTransacao'] ?? '';
    $valor = $_POST['valorTransacao'] ?? 0.00;
    $data = $_POST['dataTransacao'] ?? '';
    $tipo = $_POST['tipoTransacao'] ?? '';
    $status = $_POST['statusTransacao'] ?? '';
    $idContaRemetente = $_POST['contaRemetente'] ?? null;
    $idContaDestinataria = ($tipo === 'Transferência') ? intval($_POST['contaDestinataria']) : null;
    $idCategoria = $_POST['categoriaTransacao'] ?? null;
    $id_usuario = $_SESSION['id_usuario'] ?? null;

    // Validação do tipo
    if (!in_array($tipo, ['Despesa', 'Receita', 'Transferência'])) {
        echo '<script>alert("Tipo de transação inválido.")</script>';
        exit;
    }

    // Validação do status
    if (!in_array($status, ['Pendente', 'Efetivada', 'Cancelada'])) {
        echo '<script>alert(" Status de transação inválido.")</script>';
        exit;
    }

    // Verifica se a conta remetente existe
    $stmt = $conn->prepare("SELECT COUNT(*) FROM CONTA WHERE ID_Conta = ?");
    $stmt->bind_param("i", $idContaRemetente);
    $stmt->execute();
    $stmt->bind_result($contaRemetenteExiste);
    $stmt->fetch();
    $stmt->close();

    if (!$contaRemetenteExiste) {
        echo '<script>alert("Conta remetente inválida.")</script>';
        exit;
    }

    // Verifica se a categoria existe (se fornecida)
    if ($idCategoria) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM CATEGORIA WHERE ID_Categoria = ?");
        $stmt->bind_param("i", $idCategoria);
        $stmt->execute();
        $stmt->bind_result($categoriaExiste);
        $stmt->fetch();
        $stmt->close();

        if (!$categoriaExiste) {
            echo '<script>alert("Categoria inválida.")</script>';
            exit;
        }
    }

    // Processa a ação solicitada
    switch ($acao) {
        case 'editarTransacao':
            if (editarTransacao($id_usuario, $titulo, $descricao, $valor, $data, $tipo, $status, $idCategoria, $idContaRemetente, $idContaDestinataria)) { 
                confirmar("Transação editada com sucesso!", "transacoes.php");
            } else {
                erro("Erro ao editar transação. Verifique os dados e tente novamente.");
            }
            break;

        case 'cadastrarTransacao':
            if (cadastrarTransacao($id_usuario, $titulo, $descricao, $valor, $data, $tipo, $status, $idCategoria, $idContaRemetente, $idContaDestinataria)) {
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
