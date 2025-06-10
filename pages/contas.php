<?php
// Processamento dos formulários – DEVE vir antes de qualquer saída HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Carrega as funções de conta (já existia require_once dentro do bloco)
    require_once __DIR__ . '/contas/funcoes.php';
    session_start(); // caso ainda não tenha sido iniciado

    // Verifica se a ação foi enviada
    $acao = $_POST['acao'] ?? null;
    if (!$acao) {
        $_SESSION['mensagem_erro'] = "Ação não definida.";
        header("Location: contas.php");
        exit;
    }

    // Obtém os dados do POST
    $id          = isset($_POST['contaId'])       ? (int) $_POST['contaId']       : null;
    $nome        = trim($_POST['nomeConta'] ?? '');
    $tipo        = trim($_POST['tipoConta'] ?? '');
    $saldo       = floatval($_POST['saldoConta'] ?? 0.00);
    $instituicao = trim($_POST['instituicaoConta'] ?? '');

    switch ($acao) {
        case 'editarConta':
            if (editarConta($id, $nome, $tipo, $saldo, $instituicao)) {
                $_SESSION['mensagem_sucesso'] = "Conta editada com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao editar conta. Verifique os dados e tente novamente.";
            }
            header("Location: contas.php");
            exit;

        case 'cadastrarConta':
            if (cadastrarConta($nome, $tipo, $saldo, $instituicao)) {
                $_SESSION['mensagem_sucesso'] = "Conta cadastrada com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao cadastrar conta. Verifique os dados e tente novamente.";
            }
            header("Location: contas.php");
            exit;

        case 'excluirConta':
            if (deletarConta($id)) {
                $_SESSION['mensagem_sucesso'] = "Conta excluída com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao excluir conta. Verifique se não existem transações associadas.";
            }
            header("Location: contas.php");
            exit;

        default:
            $_SESSION['mensagem_erro'] = "Ação inválida.";
            header("Location: contas.php");
            exit;
    }
}

// A partir daqui, nenhum header() será chamado sem gerar conflito.
require_once 'header.php';
require_once 'sidebar.php';
require_once 'contas/modal.php';
require_once 'contas/funcoes.php';
require_once 'dialog.php';

$contas = obterContas();
$totalSaldo = array_sum(array_column($contas, 'Saldo'));
$count = count($contas);
?>

<!-- Aqui começa o conteúdo principal -->
<div class="container py-4">
    <div class="d-flex justify-between items-center mb-4">
        <div>
            <h2 class="text-2xl font-bold mb-1">Contas</h2>
            <p class="text-muted">Gerencie suas contas e saldos</p>
        </div>
        <button class="btn btn-primary btn-icon" data-modal-open="#modalNovaConta">
            <i class="fas fa-plus me-2"></i>
            Nova Conta
        </button>
    </div>

    <!-- Cards de Resumo -->
    <div class="d-flex justify-between gap-4 mt-5">
        <?php
        $totalSaldo = 0;
        $contas = obterContas();
        foreach ($contas as $conta) {
            $totalSaldo += $conta['Saldo'];
        }
        ?>

        <div class="summary-card income fade-in animation-delay-100 w-full">
            <span class="summary-label">Saldo Total</span>
            <div class="d-flex justify-between items-center">
                <h3 class="summary-value income">
                    R$ <?php echo number_format($totalSaldo, 2, ',', '.'); ?>
                </h3>
            </div>
        </div>

        <div class="summary-card expense fade-in animation-delay-200 w-full">
            <span class="summary-label">Total de Contas</span>
            <div class="d-flex justify-between items-center">
                <h3 class="summary-value"><?php echo count($contas); ?></h3>
            </div>
        </div>

        <div class="summary-card balance fade-in animation-delay-300 w-full">
            <span class="summary-label">Saldo Médio</span>
            <div class="d-flex justify-between items-center">
                <h3 class="summary-value">
                    R$ <?php echo (count($contas) > 0) ? number_format($totalSaldo / count($contas), 2, ',', '.') : '0,00'; ?>
                </h3>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Contas -->
<div class="transaction-table-container fade-in-up">
    <div class="p-4 flex justify-between items-center border-bottom">
        <h4 class="font-semibold m-0">Suas Contas</h4>
        <div class="flex gap-2">
            <button class="btn-action" title="Exportar para Excel">
                <i class="fas fa-file-excel"></i>
            </button>
            <!-- Você pode incluir outras ações aqui -->
        </div>
    </div>

    <div id="contasGrid" class="contas-grid mt-3">
        <?php
        if (empty($contas)) {
            echo '<div class="empty-state my-5">';
            echo '<i class="fas fa-wallet empty-state__icon"></i>';
            echo '<h3 class="empty-state__title">Nenhuma conta encontrada</h3>';
            echo '<p class="empty-state__description">Comece a registrar suas contas financeiras para visualizá-las aqui.</p>';
            echo '<button class="btn btn-primary btn-icon" data-modal-open="#modalNovaConta">';
            echo '<i class="fas fa-plus me-2"></i> Criar Primeira Conta';
            echo '</button>';
            echo '</div>';
        } else {
            foreach ($contas as $conta) {
                $icone = obterIconeTipoConta($conta['Tipo']);
                echo "<div class=\"account-card\" data-tipo=\"{$conta['Tipo']}\">";
                echo "  <div class=\"account-card__header\">";
                echo "    <h5 class=\"account-card__title\">" . htmlspecialchars($conta['Nome']) . "</h5>";
                echo "    <div class=\"account-card__icon\"><i class=\"fas {$icone}\"></i></div>";
                echo "  </div>";
                echo "  <div class=\"account-card__balance\">R$ " . number_format($conta['Saldo'], 2, ',', '.') . "</div>";
                echo "  <div class=\"account-card__info\">" . htmlspecialchars($conta['Instituicao']) . "</div>";
                echo "  <div class=\"flex justify-end gap-2 mt-3\">";
                echo "    <button class=\"btn-action edit\" title=\"Editar\" data-modal-open=\"#editarContaModal\" "
                    . "data-id=\"{$conta['ID_Conta']}\" "
                    . "data-nome=\"" . htmlspecialchars($conta['Nome']) . "\" "
                    . "data-tipo=\"{$conta['Tipo']}\" "
                    . "data-saldo=\"{$conta['Saldo']}\" "
                    . "data-instituicao=\"" . htmlspecialchars($conta['Instituicao']) . "\">";
                echo "        <i class=\"fas fa-edit\"></i>";
                echo "    </button>";
                echo "    <button class=\"btn-action delete\" title=\"Excluir\" data-modal-open=\"#excluirContaModal\" "
                    . "data-id=\"{$conta['ID_Conta']}\" "
                    . "data-nome=\"" . htmlspecialchars($conta['Nome']) . "\">";
                echo "        <i class=\"fas fa-trash-alt\"></i>";
                echo "    </button>";
                echo "  </div>";
                echo "</div>";
            }
        }
        ?>
    </div>
</div>

<script src="contas.js"></script>

<?php require_once 'footer.php'; ?>
