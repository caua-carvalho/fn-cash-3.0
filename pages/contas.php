<?php
// Processamento dos formulários - DEVE vir antes de qualquer saída HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once dirname(__FILE__, 1) . '/contas/funcoes.php';
    session_start();

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
require_once 'contas/modal.php';
require_once 'contas/funcoes.php';
require_once 'dialog.php';

$contas = obterContas();
$totalSaldo = array_sum(array_column($contas, 'Saldo'));
$count = count($contas);
?>
<div class="content">
    <!-- Cabeçalho da Página com Estatísticas -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
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
                    <h3 class="summary-value"><?php echo $count; ?></h3>
                </div>
            </div>

            <div class="summary-card balance fade-in animation-delay-300 w-full">
                <span class="summary-label">Saldo Médio</span>
                <div class="d-flex justify-between items-center">
                    <h3 class="summary-value">
                        R$ <?php echo $count > 0 ? number_format($totalSaldo / $count, 2, ',', '.') : '0,00'; ?>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtro rápido -->
    <div class="filter-container slide-in-left mb-6">
        <div class="filter-header">
            <h3 class="filter-title">
                <i class="fas fa-filter me-2"></i> Filtros
            </h3>
            <button class="btn-action" id="toggleFilter">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>

        <div class="filter-content mt-3" style="display: none;">
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div class="form-group mb-3">
                    <label for="searchConta">Buscar</label>
                    <input type="text" id="searchConta" class="form-control" placeholder=" ">
                </div>

                <div class="form-group mb-3">
                    <label for="filterTipo">Tipo</label>
                    <select id="filterTipo" class="form-control">
                        <option value="">Todos os tipos</option>
                        <option value="Corrente">Corrente</option>
                        <option value="Poupança">Poupança</option>
                        <option value="Cartão de Crédito">Cartão de Crédito</option>
                        <option value="Investimento">Investimento</option>
                        <option value="Outros">Outros</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end mt-3">
                <button class="btn btn-secondary me-2" id="limparFiltros">Limpar Filtros</button>
                <button class="btn btn-primary" id="aplicarFiltros">Aplicar Filtros</button>
            </div>
        </div>
    </div>

    <!-- Grid de Cards de Contas -->
    <div class="fade-in-up">
        <div class="p-4 flex justify-between items-center border-bottom">
            <h4 class="font-semibold m-0">Suas Contas</h4>
            <div class="flex gap-2">
                <button class="btn-action" title="Exportar para Excel">
                    <i class="fas fa-file-excel"></i>
                </button>
                <button class="btn-action" id="exportPDF" title="Exportar para PDF">
                    <i class="fas fa-file-pdf"></i>
                </button>
            </div>
        </div>

        <?php if (empty($contas)): ?>
            <div class="empty-state my-5 text-center">
                <i class="fas fa-wallet empty-state__icon"></i>
                <h3 class="empty-state__title">Nenhuma conta encontrada</h3>
                <p class="empty-state__description">
                    Comece a registrar suas contas financeiras para visualizá-las aqui.
                </p>
                <button class="btn btn-primary btn-icon" data-modal-open="#contaModal">
                    <i class="fas fa-plus me-2"></i> Criar Primeira Conta
                </button>
            </div>
        <?php else: ?>
            <div id="contasGrid" class="grid grid-cols-3 sm:grid-cols-1 lg:grid-cols-1 gap-6 p-4">
                <?php
                $delay = 100;
                foreach ($contas as $conta):
                    // Classes de borda e badge conforme Tipo de Conta
                    switch ($conta['Tipo']) {
                        case 'Corrente':
                            $borderClass = 'border-corrente';
                            $badgeClass  = 'badge-corrente';
                            break;
                        case 'Poupança':
                            $borderClass = 'border-poupanca';
                            $badgeClass  = 'badge-poupanca';
                            break;
                        case 'Cartão de Crédito':
                            $borderClass = 'border-credit-card';
                            $badgeClass  = 'badge-credit-card';
                            break;
                        case 'Investimento':
                            $borderClass = 'border-investimento';
                            $badgeClass  = 'badge-investimento';
                            break;
                        default:
                            $borderClass = 'border-outros';
                            $badgeClass  = 'badge-outros';
                    }
                ?>
                <div
                  class="account-card <?= $borderClass ?> fade-in-up animation-delay-<?= $delay ?> p-4"
                  data-tipo="<?= htmlspecialchars($conta['Tipo']) ?>"
                >
                  <!-- Cabeçalho do card: badge de tipo + nome -->
                  <div class="account-card__header">
                    <span class="badge-type <?= $badgeClass ?>">
                      <?= htmlspecialchars($conta['Tipo']) ?>
                    </span>
                    <h3 class="account-card__title">
                      <?= htmlspecialchars($conta['Nome']) ?>
                    </h3>
                  </div>

                  <!-- Detalhes ocultos por padrão -->
                  <div class="account-card__details">
                    <div class="account-card__balance">
                      <strong>Saldo:</strong> R$ <?= number_format($conta['Saldo'], 2, ',', '.') ?>
                    </div>
                    <p class="account-card__info">
                      <strong>Instituição:</strong> <?= htmlspecialchars($conta['Instituicao']) ?>
                    </p>
                    <div class="flex justify-end gap-2 mt-4">
                      <button
                        class="btn-action download"
                        title="Baixar PDF"
                        onclick="downloadSingleAccount(this)"
                        data-nome="<?= htmlspecialchars($conta['Nome']) ?>"
                        data-tipo="<?= $conta['Tipo'] ?>"
                        data-saldo="<?= $conta['Saldo'] ?>"
                        data-instituicao="<?= htmlspecialchars($conta['Instituicao']) ?>"
                      >
                        <i class="fas fa-download"></i>
                      </button>
                      <button
                        class="btn-action edit"
                        data-modal-open="#editarContaModal"
                        data-id="<?= $conta['ID_Conta'] ?>"
                        data-nome="<?= htmlspecialchars($conta['Nome']) ?>"
                        data-tipo="<?= $conta['Tipo'] ?>"
                        data-saldo="<?= $conta['Saldo'] ?>"
                        data-instituicao="<?= htmlspecialchars($conta['Instituicao']) ?>"
                      >
                        <i class="fas fa-edit"></i>
                      </button>
                      <button
                        class="btn-action delete"
                        data-modal-open="#excluirContaModal"
                        data-id="<?= $conta['ID_Conta'] ?>"
                        data-nome="<?= htmlspecialchars($conta['Nome']) ?>"
                      >
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <?php
                    $delay += 50;
                endforeach;
                ?>
            </div>
        <?php endif; ?>

        <!-- Paginação semelhante a Transações -->
        <div class="flex justify-between items-center mt-5 px-4">
          <div class="text-muted">
            Mostrando 1-10 de <?= $count ?> contas
          </div>
          <nav aria-label="Navegação de páginas">
            <ul class="pagination">
              <li class="page-item disabled">
                <a class="page-link" href="#" aria-label="Anterior">
                  <i class="fas fa-chevron-left"></i>
                </a>
              </li>
              <li class="page-item active"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item">
                <a class="page-link" href="#" aria-label="Próxima">
                  <i class="fas fa-chevron-right"></i>
                </a>
              </li>
            </ul>
          </nav>
        </div>
    </div>
</div>

<?php
echo modalCreateConta();
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Abrir modais ao clicar nos botões
        document.querySelectorAll('[data-modal-open]').forEach(btn => {
            btn.addEventListener('click', e => {
                e.stopPropagation();
                const modal = document.querySelector(btn.getAttribute('data-modal-open'));
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
            });
        });

        // Filtragem por nome e tipo
        const searchInput  = document.getElementById('searchConta');
        const filterSelect = document.getElementById('filterTipo');
        const cards        = document.querySelectorAll('.account-card');

        function filtrarContas() {
            const termo           = searchInput.value.toLowerCase();
            const tipoSelecionado = filterSelect.value;
            cards.forEach(card => {
                const nome        = card.querySelector('.account-card__title').textContent.toLowerCase();
                const instituicao = card.querySelector('.account-card__info')?.textContent.toLowerCase() ?? '';
                const tipoConta   = card.getAttribute('data-tipo');
                const passaTexto  = nome.includes(termo) || instituicao.includes(termo);
                const passaTipo   = !tipoSelecionado || tipoConta === tipoSelecionado;
                card.style.display = (passaTexto && passaTipo) ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filtrarContas);
        filterSelect.addEventListener('change', filtrarContas);

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

        const aplicarFiltros = document.getElementById('aplicarFiltros');
        const limparFiltros  = document.getElementById('limparFiltros');

        aplicarFiltros?.addEventListener('click', filtrarContas);

        limparFiltros?.addEventListener('click', () => {
            searchInput.value = '';
            filterSelect.value = '';
            filtrarContas();
        });

        searchInput.addEventListener('keyup', e => {
            if (e.key === 'Enter') filtrarContas();
        });

        // Accordion: expande/retrai detalhes ao clicar no card
        cards.forEach(card => {
            card.addEventListener('click', function(e) {
                if (e.target.closest('[data-modal-open]')) return;
                card.classList.toggle('expanded');
            });
        });

        // PDF Export functionality
        document.getElementById('exportPDF').addEventListener('click', function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(18);
            doc.text('Relatório de Contas', 14, 20);

            const tableData = Array.from(document.querySelectorAll('.account-card')).map(card => [
                card.querySelector('.account-card__title').textContent.trim(),
                card.getAttribute('data-tipo'),
                card.querySelector('.account-card__balance').textContent.trim(),
                card.querySelector('.account-card__info').textContent.trim()
            ]);

            doc.autoTable({
                head: [['Nome', 'Tipo', 'Saldo', 'Instituição']],
                body: tableData,
                startY: 30,
                theme: 'grid',
                styles: { fontSize: 8 },
                headStyles: { fillColor: [41, 128, 185] }
            });

            doc.save('contas-relatorio.pdf');
        });

        window.downloadSingleAccount = function (button) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            const nome = button.dataset.nome;
            const tipo = button.dataset.tipo;
            const saldo = button.dataset.saldo;
            const instituicao = button.dataset.instituicao;

            doc.setFontSize(18);
            doc.text('Detalhes da Conta', 14, 20);

            doc.autoTable({
                head: [['Campo', 'Valor']],
                body: [
                    ['Nome', nome],
                    ['Tipo', tipo],
                    ['Saldo', `R$ ${parseFloat(saldo).toFixed(2)}`],
                    ['Instituição', instituicao]
                ],
                startY: 30,
                theme: 'grid',
                styles: { fontSize: 10 },
                headStyles: { fillColor: [41, 128, 185] }
            });

            doc.save(`conta-${nome.toLowerCase().replace(/\s+/g, '-')}.pdf`);
        };
    });
</script>

<?php require_once 'footer.php'; ?>
