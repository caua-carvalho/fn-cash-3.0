<?php

require_once 'transacoes/funcoes.php';
require_once 'header.php';
require_once 'sidebar.php';
require_once 'transacoes/modal.php';
require_once 'dialog.php';
require_once '../conexao.php';

// 1) Lê o “periodo” e define intervalo de datas no formato YYYY-MM-DD
$periodoSelecionado = $_GET['periodo'] ?? 'mes-atual';
switch ($periodoSelecionado) {
    case 'mes-anterior':
        $dataInicio = date('Y-m-01', strtotime('first day of last month'));
        $dataFim    = date('Y-m-t', strtotime('last day of last month'));
        break;
    case 'ano-atual':
        $dataInicio = date('Y-01-01');
        $dataFim    = date('Y-12-31');
        break;
    case 'customizado':
        // Espera formato ISO (YYYY-MM-DD) vindo do <input type="date">
        $dataInicio = $_GET['dataInicio'] ?? date('Y-m-01');
        $dataFim    = $_GET['dataFim']    ?? date('Y-m-d');
        break;
    case 'mes-atual':
    default:
        $dataInicio = date('Y-m-01');
        $dataFim    = date('Y-m-d');
        break;
}
$intervaloDatas = ['inicio' => $dataInicio, 'fim' => $dataFim];

// 2) Busca transações já filtradas
$transacoes = obterTransacoes($dataInicio, $dataFim);

$totalReceita = obterSaldoTipo(tipo: 'Receita');
$totalDespesa = obterSaldoTipo(tipo: 'Despesa');
$totalBalanco = $totalReceita - $totalDespesa;
?>

<script src='dashboard/dashboard_filtro.js'></script>

<div class="content">
    <!-- Cabeçalho da Página com Estatísticas -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-bold mb-1">Transações</h2>
                <p class="text-muted">Gerencie suas movimentações financeiras</p>
            </div>
            <button class="btn btn-primary btn-icon" data-toggle="modal" data-target="#transacaoModal" data-modal-open="#transacaoModal">
                <i class="fas fa-plus me-2"></i>
                Nova Transação
            </button>
        </div>
        
        <!-- Cards de Resumo -->
        <div class="d-flex justify-between gap-4 mt-5">
            <div class="summary-card income fade-in animation-delay-100 w-full">
                <span class="summary-label">Receitas</span>
                <div class="flex justify-between items-center">
                    <h3 class="summary-value income">
                        <?php echo 'R$ ' . number_format($totalReceita, 2, ',', '.'); ?>
                    </h3>
                    <div class="flex items-center gap-2">
                        <span class="badge badge-income"><i class="fas fa-arrow-up me-1"></i> 12%</span>
                    </div>
                </div>
            </div>
            
            <div class="summary-card expense fade-in animation-delay-200 w-full">
                <span class="summary-label">Despesas</span>
                <div class="flex justify-between items-center">
                    <h3 class="summary-value expense">
                        <?php echo 'R$ ' . number_format($totalDespesa, 2, ',', '.'); ?>
                    </h3>
                    <div class="flex items-center gap-2">
                        <span class="badge badge-expense"><i class="fas fa-arrow-down me-1"></i> 5%</span>
                    </div>
                </div>
            </div>
            
            <div class="summary-card balance fade-in animation-delay-300 w-full">
                <span class="summary-label">Balanço</span>
                <div class="flex justify-between items-center">
                    <h3 class="summary-value">
                        <?php echo 'R$ ' . number_format($totalBalanco, 2, ',', '.'); ?>
                    </h3>
                    <div class="flex items-center gap-2">
                        <span class="badge badge-info"><i class="fas fa-chart-line me-1"></i> 7%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtro de Período - Design Refinado sem Opções Avançadas -->
    <div class="card mb-6 fade-in animation-delay-100">
        <div class="card__header">
            <div class="flex justify-between items-center">
                <h4 class="card__title">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i> Período de Análise
                </h4>
                <button class="btn-action" id="togglePeriodFilter">
                    <i class="fas fa-chevron-down transition-fast"></i>
                </button>
            </div>
        </div>
        
        <div class="card__body" id="periodFilterContent" style="display: none;">
            <!-- Seletor de Tipo (Pills) para rápida seleção -->
            <div class="status-selector mb-5">
                <button type="button" class="status-option <?php echo $periodoSelecionado === 'mes-atual' ? 'active' : ''; ?>" data-period="mes-atual">Mês Atual</button>
                <button type="button" class="status-option <?php echo $periodoSelecionado === 'mes-anterior' ? 'active' : ''; ?>" data-period="mes-anterior">Mês Anterior</button>
                <button type="button" class="status-option <?php echo $periodoSelecionado === 'ano-atual' ? 'active' : ''; ?>" data-period="ano-atual">Ano Atual</button>
                <button type="button" class="status-option <?php echo $periodoSelecionado === 'customizado' ? 'active' : ''; ?>" data-period="customizado">Personalizado</button>
            </div>
            <input type="hidden" name="periodSelection" id="periodSelection" value="<?php echo $periodoSelecionado; ?>">
            
            <!-- Intervalo de datas personalizado (inicialmente oculto) -->
            <div id="customPeriodSection" class="fade-in-up" style="display: <?php echo $periodoSelecionado === 'customizado' ? 'block' : 'none'; ?>;">
                <div class="grid grid-cols-1 grid-md-cols-2 gap-4 mb-4">
                    <div class="form-floating">
                        <input type="date" class="form-control" id="startDate" placeholder=" " value="<?php echo $dataInicio ?? $intervaloDatas['inicio']; ?>">
                        <label for="startDate">Data Inicial</label>
                    </div>
                    
                    <div class="form-floating">
                        <input type="date" class="form-control" id="endDate" placeholder=" " value="<?php echo $dataFim ?? $intervaloDatas['fim']; ?>">
                        <label for="endDate">Data Final</label>
                    </div>
                </div>
            </div>
            
            <div class="card__footer flex justify-end gap-3 pt-4 mt-4 border-top">
                <button class="btn btn-secondary" id="clearPeriodFilter">
                    <i class="fas fa-undo me-2"></i> Limpar Filtros
                </button>
                <button class="btn btn-primary btn-icon" id="applyPeriodFilter">
                    <i class="fas fa-filter me-2"></i> Aplicar Filtros
                </button>
            </div>
        </div>
    </div>
    
    <!-- Tabela de Transações -->
    <div class="transaction-table-container fade-in-up">
        <div class="p-4 flex justify-between items-center border-bottom">
            <h4 class="font-semibold m-0">Histórico de Transações</h4>
            <div class="flex gap-2">
                <button class="btn-action" title="Exportar para Excel">
                    <i class="fas fa-file-excel"></i>
                </button>
                <button class="btn-action" title="Exportar para PDF">
                    <i class="fas fa-file-pdf"></i>
                </button>
                <button class="btn-action" title="Imprimir">
                    <i class="fas fa-print"></i>
                </button>
            </div>
        </div>
    
        <table class="transaction-table">
            <thead>
                <tr>
                    <th width="25%">Título</th>
                    <th width="10%">Valor</th>
                    <th width="12%">Data</th>
                    <th width="10%">Tipo</th>
                    <th width="10%">Status</th>
                    <th width="15%">Conta Origem</th>
                    <th width="10%">Conta Destino</th>
                    <th width="8%" class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($transacoes)) {
                    echo '<tr><td colspan="8">';
                    echo '<div class="empty-state my-5">';
                    echo '<i class="fas fa-receipt empty-state__icon"></i>';
                    echo '<h3 class="empty-state__title">Nenhuma transação encontrada</h3>';
                    echo '<p class="empty-state__description">Comece a registrar suas transações financeiras para visualizá-las aqui.</p>';
                    echo '<button class="btn btn-primary btn-icon" data-toggle="modal" data-target="#transacaoModal" data-modal-open="#transacaoModal">';
                    echo '<i class="fas fa-plus me-2"></i> Criar Primeira Transação';
                    echo '</button>';
                    echo '</div>';
                    echo '</td></tr>';
                } else {
                    $delay = 100;
                    foreach ($transacoes as $transacao) {
                        // Determina as classes para tipo de transação
                        $tipoBadgeClass = 'badge-transfer';
                        $valorClass = '';
                        
                        if ($transacao['Tipo'] === 'Receita') {
                            $tipoBadgeClass = 'badge-income';
                            $valorClass = 'text-income';
                        } elseif ($transacao['Tipo'] === 'Despesa') {
                            $tipoBadgeClass = 'badge-expense';
                            $valorClass = 'text-expense';
                        }
                        
                        // Determina as classes para status
                        $statusBadgeClass = 'badge-pending';
                        if ($transacao['Status'] === 'Efetivada') {
                            $statusBadgeClass = 'badge-completed';
                        } elseif ($transacao['Status'] === 'Cancelada') {
                            $statusBadgeClass = 'badge-canceled';
                        }
                        
                        echo "<tr class='fade-in-up' style='animation-delay: {$delay}ms'>";
                        echo "<td class='font-medium'>" . htmlspecialchars($transacao['Titulo']) . "</td>";
                        
                        // Formata valor com coloração positiva/negativa
                        echo "<td class='font-semibold {$valorClass}'>" . 
                             (($transacao['Tipo'] === 'Despesa') ? '- ' : '') . 
                             "R$ " . number_format($transacao['Valor'], 2, ',', '.') . 
                             "</td>";
                        
                        // Formata data
                        $data = new DateTime($transacao['Data']);
                        echo "<td>" . $data->format('d/m/Y') . "</td>";
                        
                        // Badges para tipo e status
                        echo "<td><span class='badge {$tipoBadgeClass}'>" . 
                             "<i class='fas fa-" . 
                             ($transacao['Tipo'] === 'Receita' ? 'arrow-up' : 
                             ($transacao['Tipo'] === 'Despesa' ? 'arrow-down' : 'exchange-alt')) . 
                             " me-1'></i>" . 
                             htmlspecialchars($transacao['Tipo']) . "</span></td>";
                             
                        echo "<td><span class='badge {$statusBadgeClass}'>" . htmlspecialchars($transacao['Status']) . "</span></td>";
                        
                        // Contas
                        echo "<td>" . htmlspecialchars($transacao['NomeContaRemetente']) . "</td>";
                        echo "<td>" . ($transacao['NomeContaDestinataria'] !== null ? htmlspecialchars($transacao['NomeContaDestinataria']) : '-') . "</td>";
                        
                        // Botões de ação
                        echo "<td>";
                        echo "<div class='flex justify-center gap-2'>";
                        
                        // Botão de visualizar
                        echo "<button class='btn-action view' title='Visualizar' data-toggle='modal' data-target='#visualizarTransacaoModal' 
                               data-id='" . $transacao['ID_Transacao'] . "'>
                              <i class='fas fa-eye'></i>
                              </button>";
                        
                        // Botão de editar
                        echo "<button class='btn-action edit' title='Editar' data-toggle='modal' data-target='#editarTransacaoModal' data-modal-open='#editarTransacaoModal''
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
                              <i class='fas fa-edit'></i>
                              </button>";
                        
                        // Botão de excluir
                        echo "<button class='btn-action delete' title='Excluir' data-toggle='modal' data-target='#excluirTransacaoModal' data-modal-open='#excluirTransacaoModal'
                               data-id='" . $transacao['ID_Transacao'] . "'
                               data-titulo='" . htmlspecialchars($transacao['Titulo']) . "'>
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
    
    <!-- Paginação -->
    <div class="flex justify-between items-center mt-5">
        <div class="text-muted">
            Mostrando <span class="font-semibold">1-10</span> de <span class="font-semibold">24</span> transações
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

<!-- JavaScript para funcionalidades adicionais -->
<script>
// Gerado pelo Copilot

// Função para ativar grupo de botões de filtro
function ativarFiltroGrupo(grupoId, inputId) {
    const grupo = document.getElementById(grupoId);
    const input = document.getElementById(inputId);
    if (!grupo || !input) return;
    grupo.querySelectorAll('.status-option-minimal').forEach(btn => {
        btn.addEventListener('click', function () {
            grupo.querySelectorAll('.status-option-minimal').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            input.value = this.getAttribute('data-filter');
        });
    });
}

// Função para limpar filtros
function limparFiltros() {
    document.getElementById('inputTipo').value = 'all';
    document.querySelectorAll('#filtroTipo .status-option-minimal').forEach((btn, idx) => {
        btn.classList.toggle('active', idx === 0);
    });
    document.getElementById('inputStatus').value = 'all';
    document.querySelectorAll('#filtroStatus .status-option-minimal').forEach((btn, idx) => {
        btn.classList.toggle('active', idx === 0);
    });
    document.getElementById('inputDataInicio').value = '';
    document.getElementById('inputDataFim').value = '';
}

// Função para remover campos vazios antes de submeter (evita poluir a URL)
function removerCamposVaziosDoForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    // Remove name dos inputs de data se estiverem vazios
    const dataInicio = document.getElementById('inputDataInicio');
    const dataFim = document.getElementById('inputDataFim');
    if (dataInicio && !dataInicio.value) dataInicio.removeAttribute('name');
    if (dataFim && !dataFim.value) dataFim.removeAttribute('name');
}

// Inicialização dos filtros ao carregar a página
document.addEventListener('DOMContentLoaded', function () {
    // Toggle para filtros minimalistas
    const toggleFilterBtn = document.getElementById('toggleFilter');
    const filterContent = document.querySelector('.filter-content-minimal');
    if (toggleFilterBtn && filterContent) {
        toggleFilterBtn.addEventListener('click', function () {
            if (filterContent.style.display === 'none' || filterContent.style.display === '') {
                filterContent.style.display = 'block';
            } else {
                filterContent.style.display = 'none';
            }
            toggleFilterBtn.querySelector('i').classList.toggle('fa-chevron-down');
            toggleFilterBtn.querySelector('i').classList.toggle('fa-chevron-up');
        });
    }

    ativarFiltroGrupo('filtroTipo', 'inputTipo');
    ativarFiltroGrupo('filtroStatus', 'inputStatus');

    // Submit do filtro ao clicar em "Filtrar"
    document.getElementById('btnFiltrar').addEventListener('click', function (e) {
        e.preventDefault();
        removerCamposVaziosDoForm('formFiltros'); // Só envia o que o usuário preencheu
        document.getElementById('formFiltros').submit();
    });

    // Limpar filtros e submeter
    document.getElementById('btnLimparFiltros').addEventListener('click', function () {
        limparFiltros();
        removerCamposVaziosDoForm('formFiltros');
        document.getElementById('formFiltros').submit();
    });
});
</script>

<?php
require_once 'footer.php';

// Processamento do formulário
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