<?php
require_once 'header.php';
require_once 'nav.php';
require_once 'contas/funcoes.php';
require_once 'contas/modal.php';
require_once 'dialog.php';
?>

<div class="container">
    <h2>Contas</h2>
    
    <!-- Botão para cadastrar nova conta -->
    <div class="mb-3">
        <button class="btn btn-success" data-toggle="modal" data-target="#contaModal">Cadastrar Nova Conta</button>
    </div>

    <!-- Tabela para exibir as contas -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Saldo</th>
                <th>Instituição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $contas = obterContas();
            foreach ($contas as $conta) {
                echo "<tr>
                     <td>" . htmlspecialchars($conta['Nome']) . "</td>
                     <td>" . htmlspecialchars($conta['Tipo']) . "</td>
                     <td>" . number_format($conta['Saldo'], 2, ',', '.') . "</td>
                     <td>" . htmlspecialchars($conta['Instituicao']) . "</td>
                     <td>
                     <a class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editarContaModal'
                        data-id='" . $conta['ID_Conta'] . "'
                        data-nome='" . htmlspecialchars($conta['Nome']) . "'
                        data-tipo='" . $conta['Tipo'] . "'
                        data-saldo='" . $conta['Saldo'] . "'
                        data-instituicao='" . htmlspecialchars($conta['Instituicao']) . "'>
                        Editar
                     </a> 
                     <a class='btn btn-danger btn-sm' data-toggle='modal' data-target='#excluirContaModal'
                        data-id='" . $conta['ID_Conta'] . "'>
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
    $id = $_POST['contaId'] ?? null;
    $nome = $_POST['nomeConta'] ?? '';
    $tipo = $_POST['tipoConta'] ?? '';
    $saldo = $_POST['saldoConta'] ?? 0.00;
    $instituicao = $_POST['instituicaoConta'] ?? '';

    // Processa a ação solicitada
    switch ($acao) {
        case 'editarConta':
            if (editarConta($id, $nome, $tipo, $saldo, $instituicao)) {
                confirmar("Conta editada com sucesso!", "contas.php");
            } else {
                erro("Erro ao editar conta. Verifique os dados e tente novamente.");
            }
            break;

        case 'cadastrarConta':
            if (cadastrarConta($nome, $tipo, $saldo, $instituicao)) {
                confirmar("Conta cadastrada com sucesso!", "contas.php");
            } else {
                erro("Erro ao cadastrar conta. Verifique os dados e tente novamente.");
            }
            break;

        case 'excluirConta':
            if (deletarConta($id)) {
                confirmar("Conta excluída com sucesso!", "contas.php");
            } else {
                erro("Erro ao excluir conta. Verifique os dados e tente novamente.");
            }
            break;

        default:
            erro("Ação inválida.");
            break;
    }
}
?>
