<?php
// Processamento dos formulários - DEVE vir antes de qualquer saída HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once dirname(__FILE__, 1) . '/contas/funcoes.php';
    
    // Verifica se a ação foi definida
    $acao = $_POST['acao'] ?? null;

    if (!$acao) {
        $_SESSION['mensagem_erro'] = "Ação não definida.";
        header("Location: contas.php");
        exit;
    }

    // Obtém os dados enviados
    $id = isset($_POST['contaId']) ? (int)$_POST['contaId'] : null;
    $nome = trim($_POST['nomeConta'] ?? '');
    $tipo = trim($_POST['tipoConta'] ?? '');
    $saldo = floatval($_POST['saldoConta'] ?? 0.00);
    $instituicao = trim($_POST['instituicaoConta'] ?? '');

    // Processa a ação solicitada
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

// Inclui os arquivos necessários
require_once 'header.php';
require_once 'sidebar.php';
require_once 'contas/funcoes.php';
require_once 'dialog.php';
?>

<!-- Aqui começa o conteúdo principal -->
<div class="content">
    <!-- Cabeçalho da Página com Estatísticas -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-bold mb-1">Contas</h2>
                <p class="text-muted">Gerencie suas contas e saldos</p>
            </div>
            <button class="btn btn-primary btn-icon" data-toggle="modal" data-target="#contaModal">
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
                    <h3 class="summary-value income">R$ <?php echo number_format($totalSaldo, 2, ',', '.'); ?></h3>
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
                    <h3 class="summary-value">R$
                        <?php echo count($contas) > 0 ? number_format($totalSaldo / count($contas), 2, ',', '.') : '0,00'; ?>
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
                <button class="btn-action" title="Imprimir">
                    <i class="fas fa-print"></i>
                </button>
            </div>
        </div>

        <table class="transaction-table">
            <thead>
                <tr>
                    <th width="25%">Nome</th>
                    <th width="15%">Tipo</th>
                    <th width="20%">Saldo</th>
                    <th width="25%">Instituição</th>
                    <th width="15%" class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody id="tabelaContas">
                <?php
                if (empty($contas)) {
                    echo '<tr><td colspan="5">';
                    echo '<div class="empty-state my-5">';
                    echo '<i class="fas fa-wallet empty-state__icon"></i>';
                    echo '<h3 class="empty-state__title">Nenhuma conta encontrada</h3>';
                    echo '<p class="empty-state__description">Comece a registrar suas contas financeiras para visualizá-las aqui.</p>';
                    echo '<button class="btn btn-primary btn-icon" data-toggle="modal" data-target="#contaModal">';
                    echo '<i class="fas fa-plus me-2"></i> Criar Primeira Conta';
                    echo '</button>';
                    echo '</div>';
                    echo '</td></tr>';
                } else {
                    $delay = 100;
                    foreach ($contas as $conta) {
                        // Determina as classes para tipo de conta
                        $tipoBadgeClass = 'badge-info';
                        if ($conta['Tipo'] === 'Corrente') {
                            $tipoBadgeClass = 'badge-primary';
                        } elseif ($conta['Tipo'] === 'Poupança') {
                            $tipoBadgeClass = 'badge-income';
                        } elseif ($conta['Tipo'] === 'Cartão de Crédito') {
                            $tipoBadgeClass = 'badge-expense';
                        }

                        $icone = obterIconeTipoConta($conta['Tipo']);

                        echo "<tr class='fade-in-up' style='animation-delay: {$delay}ms'>";
                        echo "<td class='font-medium'>" . htmlspecialchars($conta['Nome']) . "</td>";

                        // Badge para o tipo de conta
                        echo "<td><span class='badge {$tipoBadgeClass}'>" .
                            "<i class='fas {$icone} me-1'></i>" .
                            htmlspecialchars($conta['Tipo']) . "</span></td>";

                        // Formata saldo
                        echo "<td class='font-semibold'>" .
                            "R$ " . number_format($conta['Saldo'], 2, ',', '.') .
                            "</td>";

                        echo "<td>" . htmlspecialchars($conta['Instituicao']) . "</td>";

                        // Botões de ação
                        echo "<td>";
                        echo "<div class='flex justify-center gap-2'>";

                        // Botão de editar
                        echo "<button class='btn-action edit' title='Editar' data-toggle='modal' data-target='#editarContaModal'
                            data-id='" . $conta['ID_Conta'] . "'
                            data-nome='" . htmlspecialchars($conta['Nome']) . "'
                            data-tipo='" . $conta['Tipo'] . "'
                            data-saldo='" . $conta['Saldo'] . "'
                            data-instituicao='" . htmlspecialchars($conta['Instituicao']) . "'>
                            <i class='fas fa-edit'></i>
                            </button>";

                        // Botão de excluir
                        echo "<button class='btn-action delete' title='Excluir' data-toggle='modal' data-target='#excluirContaModal'
                            data-id='" . $conta['ID_Conta'] . "'
                            data-nome='" . htmlspecialchars($conta['Nome']) . "'>
                            <i class='fas fa-trash-alt'></i>
                            </button>";

                        echo "</div>";
                        echo "</td>";
                        echo "</tr>";

                        $delay += 50; // Incrementa o delay para o próximo item
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Aqui termina o conteúdo principal -->

<?php 
// Agora vamos incluir os modais
require_once 'contas/modal.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle filtro
        const toggleBtn = document.getElementById('toggleFilter');
        const filterContent = document.querySelector('.filter-content');

        if (toggleBtn && filterContent) {
            toggleBtn.addEventListener('click', function () {
                const isVisible = filterContent.style.display !== 'none';
                filterContent.style.display = isVisible ? 'none' : 'block';
                toggleBtn.querySelector('i').classList.toggle('fa-chevron-down', isVisible);
                toggleBtn.querySelector('i').classList.toggle('fa-chevron-up', !isVisible);
            });
        }
    });
</script>

<?php require_once 'footer.php'; ?>