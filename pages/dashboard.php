<?php
// Gerado pelo Copilot

// Exibe todos os erros do PHP para facilitar o debug (NUNCA use isso em produção!)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'header.php';
require_once 'sidebar.php';
require_once 'contas/modal.php';
require_once 'dialog.php';
require_once 'dashboard/funcoes.php';
require_once 'transacoes/modal.php';

// Interpreta corretamente o período customizado vindo da URL
$periodoSelecionado = $_GET['periodo'] ?? 'mes-atual';
$dataInicio = $_GET['dataInicio'] ?? null;
$dataFim = $_GET['dataFim'] ?? null;

// Suporte para periodo=YYYY-MM-DD_YYYY-MM-DD (caso antigo)
if (
    $periodoSelecionado !== 'mes-atual' &&
    $periodoSelecionado !== 'mes-anterior' &&
    $periodoSelecionado !== 'ano-atual' &&
    $periodoSelecionado !== 'customizado' &&
    strpos($periodoSelecionado, '_') !== false
) {
    list($dataInicio, $dataFim) = explode('_', $periodoSelecionado);
    $periodoSelecionado = 'customizado';
}

// Obtém o intervalo de datas com base no período selecionado
$intervaloDatas = obterIntervaloDatas($periodoSelecionado, $dataInicio, $dataFim);

// Decide o modo do gráfico (diário ou mensal) conforme o filtro
switch ($periodoSelecionado) {
    case 'ano-atual':
        $dadosGraficoReceitasDespesas = obterDadosGraficoReceitasDespesasPorMes($intervaloDatas);
        $modoGrafico = 'mensal';
        break;
    case 'mes-atual':
    case 'mes-anterior':
    case 'customizado':
        $dadosGraficoReceitasDespesas = obterDadosGraficoReceitasDespesasPorDia($intervaloDatas);
        $modoGrafico = 'diario';
        break;
    default:
        $dadosGraficoReceitasDespesas = [
            'labels' => [],
            'receitas' => [],
            'despesas' => []
        ];
        $modoGrafico = 'indefinido';
        break;
}
$dadosGraficoCategorias = obterDadosGraficoCategorias($intervaloDatas);

// Dados para os cards de resumo
$saldoTotal = obterSaldoTotal();
$receitasPeriodo = obterReceita($intervaloDatas);
$despesasPeriodo = obterDespesa($intervaloDatas);
$saldoMensal = obterSaldoMensal($intervaloDatas);

// Variações percentuais
$variacaoSaldo = calcularVariacaoPercentual('saldo', $intervaloDatas);
$variacaoReceita = calcularVariacaoPercentual('receita', $intervaloDatas);
$variacaoDespesa = calcularVariacaoPercentual('despesa', $intervaloDatas);
$variacaoSaldoMensal = calcularVariacaoPercentual('saldo_mensal', $intervaloDatas);

// Contas e transações recentes
$contasUsuario = obterContasUsuario(2);
$transacoesRecentes = obterTransacoesRecentes(10, null); // sem filtro de data, pega 20 últimas

// Converte os dados para JSON para uso no JavaScript
$dadosGraficoReceitasDespesasJSON = json_encode($dadosGraficoReceitasDespesas);
$dadosGraficoCategoriasJSON = json_encode($dadosGraficoCategorias);
?>

<!-- Scripts para Filtros -->
<script src='dashboard/dashboard_filtro.js'></script>

<!-- Passa os dados dinâmicos para o JavaScript -->
<script>
    // Gerado pelo Copilot
    const dadosReceitasDespesas = <?php echo $dadosGraficoReceitasDespesasJSON; ?>;
    const dadosCategorias = <?php echo $dadosGraficoCategoriasJSON; ?>;
    const modoGrafico = '<?php echo $modoGrafico; ?>';
</script>

<div class="content">
    <div class="container-fluid">
        <!-- Cabeçalho e botão Nova Transação -->
        <div class="flex justify-between items-center my-4">
            <div>
                <h2><?php echo 'Olá, ' . $_SESSION['usuario'] . '!' ?></h2>
                <p class="text-muted">Aqui está um resumo da sua situação financeira atual.</p>
            </div>
            <button class="btn btn-primary btn-icon" data-toggle="modal" data-target="#transacaoModal" data-modal-open="#transacaoModal">
                <i class="fas fa-plus me-2"></i> Nova Transação
            </button>
        </div>

        <!-- Filtro de Período - Design Ajustado -->
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
                <div class="status-selector mb-4">
                    <button type="button" class="status-option <?php echo $periodoSelecionado === 'mes-atual' ? 'active' : ''; ?>" data-period="mes-atual">Mês Atual</button>
                    <button type="button" class="status-option <?php echo $periodoSelecionado === 'mes-anterior' ? 'active' : ''; ?>" data-period="mes-anterior">Mês Anterior</button>
                    <button type="button" class="status-option <?php echo $periodoSelecionado === 'ano-atual' ? 'active' : ''; ?>" data-period="ano-atual">Ano Atual</button>
                    <button type="button" class="status-option <?php echo $periodoSelecionado === 'customizado' ? 'active' : ''; ?>" data-period="customizado">Personalizado</button>
                </div>

                <input type="hidden" name="periodSelection" id="periodSelection" value="<?php echo $periodoSelecionado; ?>">

                <!-- Intervalo de datas personalizado -->
                <div id="customPeriodSection" class="fade-in-up" style="display: <?php echo $periodoSelecionado === 'customizado' ? 'block' : 'none'; ?>;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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

        <!-- Stats Row -->
        <div class="grid grid-cols-4 gap-4 mb-6">
            <!-- Saldo Total -->
            <div class="summary-card balance animation-delay-100 fade-in">
                <span class="summary-label">Saldo Total</span>
                <div class="flex justify-between flex-col">
                    <h3 class="summary-value balance">R$ <?php echo number_format($saldoTotal, 2, ',', '.'); ?></h3>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="badge badge-<?php echo $variacaoSaldo >= 0 ? 'transfer' : 'expense'; ?>">
                            <i class="fas fa-arrow-<?php echo $variacaoSaldo >= 0 ? 'up' : 'down'; ?> me-1"></i>
                            <?php echo abs($variacaoSaldo); ?>%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Receitas -->
            <div class="summary-card income animation-delay-200 fade-in">
                <span class="summary-label">Receitas do Período</span>
                <div class="flex justify-between flex-col">
                    <h3 class="summary-value income">R$ <?php echo number_format($receitasPeriodo, 2, ',', '.'); ?></h3>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="badge badge-<?php echo $variacaoReceita >= 0 ? 'income' : 'expense'; ?>">
                            <i class="fas fa-arrow-<?php echo $variacaoReceita >= 0 ? 'up' : 'down'; ?> me-1"></i>
                            <?php echo abs($variacaoReceita); ?>%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Despesas -->
            <div class="summary-card expense animation-delay-300 fade-in">
                <span class="summary-label">Despesas do Período</span>
                <div class="flex justify-between flex-col">
                    <h3 class="summary-value expense">R$ <?php echo number_format($despesasPeriodo, 2, ',', '.'); ?></h3>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="badge badge-<?php echo $variacaoDespesa <= 0 ? 'income' : 'expense'; ?>">
                            <i class="fas fa-arrow-<?php echo $variacaoDespesa <= 0 ? 'down' : 'up'; ?> me-1"></i>
                            <?php echo abs($variacaoDespesa); ?>%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Saldo Mensal -->
            <div class="summary-card balance animation-delay-400 fade-in">
                <span class="summary-label">Saldo do Período</span>
                <div class="flex justify-between flex-col">
                    <h3 class="summary-value balance">R$ <?php echo number_format($saldoMensal, 2, ',', '.'); ?></h3>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="badge badge-<?php echo $variacaoSaldoMensal >= 0 ? 'transfer' : 'expense'; ?>">
                            <i class="fas fa-arrow-<?php echo $variacaoSaldoMensal >= 0 ? 'up' : 'down'; ?> me-1"></i>
                            <?php echo abs($variacaoSaldoMensal); ?>%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico principal ocupando toda a largura -->
        <div class="card col-span-12 mb-6 fade-in-up">
            <div class="card__header">
                <h4 class="card__title">Receitas vs Despesas</h4>
                <p class="card__subtitle">
                <?php
                    // Subtítulo explícito para o usuário
                    switch ($modoGrafico) {
                        case 'mensal':
                            echo 'Evolução mensal (por mês)';
                            break;
                        case 'diario':
                            echo 'Evolução diária (por dia)';
                            break;
                        default:
                            echo 'Evolução financeira';
                            break;
                    }
                ?>
                </p>
            </div>
            <div class="card__body">
                <div class="chart-area" style="height:400px;">
                <canvas id="financeChart"></canvas>
                </div>
            </div>
            <div class="card__footer">
                <div class="text-muted text-sm">
                <i class="fas fa-calendar"></i> Atualizado hoje
                </div>
            </div>
        </div>

        <!-- Despesas por Categoria e Minhas Contas -->
        <div class="grid grid-cols-1 grid-md-cols-2 gap-4 mb-6">
            <!-- Gráfico de Categorias -->
            <div class="card fade-in-up animation-delay-200">
                <div class="card__header flex justify-between items-center">
                    <h4 class="card__title">Categorias</h4>
                    <button class="btn btn-secondary btn-sm" id="toggleCategoryMode">
                        Alternar para Receitas
                    </button>
                </div>
                <div class="card__body p-0 d-flex justify-center" style="align-items: center;">
                    <div class="chart-area" style="height:300px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
                <div class="card__footer text-muted text-sm">
                    <i class="fas fa-info-circle me-2"></i> Clique no botão para alternar entre receitas e despesas.
                </div>
            </div>

            <!-- Minhas Contas -->
            <div class="card fade-in-up animation-delay-300">
                <div class="card__header">
                    <h4 class="card__title">Minhas Contas</h4>
                    <p class="card__subtitle">Saldos atuais</p>
                </div>
                <div class="card__body">
                    <?php if (empty($contasUsuario)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-piggy-bank fa-3x mb-3"></i>
                            <p>Você ainda não possui contas cadastradas.</p>
                            <button class="btn btn-secondary btn-icon mt-3">
                                <i class="fas fa-plus me-2"></i> Adicionar Conta
                            </button>
                        </div>
                    <?php else: ?>
                        <?php foreach ($contasUsuario as $conta): ?>
                            <!-- Conta dinâmica -->
                            <div class="account-card mb-4">
                                <div class="account-card__header">
                                    <h5 class="account-card__title"><?php echo htmlspecialchars($conta['Nome']); ?></h5>
                                    <div class="account-card__icon">
                                        <i class="fas <?php 
                                            // Determina o ícone com base no tipo de conta
                                            switch ($conta['Tipo']) {
                                                case 'Corrente': echo 'fa-wallet'; break;
                                                case 'Poupança': echo 'fa-piggy-bank'; break;
                                                case 'Cartão de Crédito': echo 'fa-credit-card'; break;
                                                case 'Investimento': echo 'fa-chart-line'; break;
                                                default: echo 'fa-landmark';
                                            }
                                        ?>"></i>
                                    </div>
                                </div>
                                <div class="account-card__balance">R$ <?php echo number_format($conta['Saldo'], 2, ',', '.'); ?></div>
                                <div class="account-card__info"><?php echo htmlspecialchars($conta['Instituicao']); ?></div>
                            </div>
                        <?php endforeach; ?>

                        <div class="flex justify-center mt-4">
                            <button class="btn btn-secondary btn-icon" data-toggle="modal" data-target="#contaModal">
                                <i class="fas fa-plus me-2"></i> Adicionar Conta
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Transações Recentes -->
        <div class="transaction-table-container scale-on-load">
            <div class="flex justify-between items-center p-4 border-bottom">
                <h4 class="m-0">Transações Recentes</h4>
                <div class="flex gap-2">
                    <button id="filter-all" class="btn btn-sm btn-secondary active">Todas</button>
                    <button id="filter-income" class="btn btn-sm btn-secondary">Receitas</button>
                    <button id="filter-expense" class="btn btn-sm btn-secondary">Despesas</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="transaction-table">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Categoria</th>
                            <th>Data</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($transacoesRecentes)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Nenhuma transação encontrada no período selecionado.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($transacoesRecentes as $transacao): ?>
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span class="badge badge-<?php echo $transacao['Tipo'] === 'Receita' ? 'income' : ($transacao['Tipo'] === 'Despesa' ? 'expense' : 'transfer'); ?>">
                                                <i class="fas fa-arrow-<?php echo $transacao['Tipo'] === 'Receita' ? 'up' : ($transacao['Tipo'] === 'Despesa' ? 'down' : 'right'); ?>"></i>
                                            </span>
                                            <span><?php echo htmlspecialchars($transacao['Titulo']); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo !empty($transacao['NomeCategoria']) ? htmlspecialchars($transacao['NomeCategoria']) : '-'; ?></td>
                                    <td><?php echo $transacao['TempoRelativo']; ?></td>
                                    <td class="text-<?php echo $transacao['Tipo'] === 'Receita' ? 'income' : ($transacao['Tipo'] === 'Despesa' ? 'expense' : ''); ?>">
                                        <?php echo ($transacao['Tipo'] === 'Despesa' ? '-' : '') . 'R$ ' . number_format($transacao['Valor'], 2, ',', '.'); ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php
                                            echo $transacao['Status'] === 'Efetivada' ? 'completed' : 
                                                ($transacao['Status'] === 'Pendente' ? 'pending' : 'canceled'); 
                                        ?>">
                                            <?php echo $transacao['Status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex gap-2">
                                            <button class="btn-action edit" title="Editar"><i class="fas fa-edit"></i></button>
                                            <button class="btn-action delete" title="Excluir"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Adicionar script para atualizar os gráficos com dados dinâmicos -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gerado pelo Copilot

    // Proteção: se não vier dados, não tenta criar gráfico
    if (!dadosReceitasDespesas || !Array.isArray(dadosReceitasDespesas.labels) || dadosReceitasDespesas.labels.length === 0) {
        document.getElementById('financeChart').parentElement.innerHTML = '<div class="text-center text-muted py-4">Nenhum dado para exibir neste período.</div>';
        return;
    }

    const financeCtx = document.getElementById('financeChart').getContext('2d');
    const despesaGradient = financeCtx.createLinearGradient(0, 0, 0, 350);
    despesaGradient.addColorStop(0, 'rgba(231, 43, 22, 0.88)');
    despesaGradient.addColorStop(1, 'rgba(231, 76, 60, 0)');
    const receitaGradient = financeCtx.createLinearGradient(0, 0, 0, 350);
    receitaGradient.addColorStop(0, 'rgba(7, 163, 98, 0.6)');
    receitaGradient.addColorStop(1, 'rgba(7, 163, 98, 0)');

    new Chart(financeCtx, {
        type: 'line',
        data: {
            labels: dadosReceitasDespesas.labels,
            datasets: [
                {
                    label: 'Despesas',
                    data: dadosReceitasDespesas.despesas,
                    backgroundColor: despesaGradient,
                    borderColor: '#e74c3c',
                    borderWidth: 2,
                    pointBackgroundColor: '#e74c3c',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Receitas',
                    data: dadosReceitasDespesas.receitas,
                    backgroundColor: receitaGradient,
                    borderColor: '#07a362',
                    borderWidth: 2,
                    pointBackgroundColor: '#07a362',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Configuração do gráfico de Categorias
    let modoCategoria = 'despesas'; // Modo inicial
    const dadosCategorias = <?php echo $dadosGraficoCategoriasJSON; ?>;

    const atualizarGraficoCategorias = () => {
        const chartData = modoCategoria === 'despesas' ? dadosCategorias.despesas : dadosCategorias.receitas;
        const chartLabel = modoCategoria === 'despesas' ? 'Despesas' : 'Receitas';
        categoryChart.data.datasets[0].data = chartData;
        categoryChart.data.datasets[0].label = chartLabel;
        categoryChart.update();
    };

    document.getElementById('toggleCategoryMode').addEventListener('click', () => {
        modoCategoria = modoCategoria === 'despesas' ? 'receitas' : 'despesas';
        document.getElementById('toggleCategoryMode').textContent = modoCategoria === 'despesas' ? 'Alternar para Receitas' : 'Alternar para Despesas';
        atualizarGraficoCategorias();
    });

    const ctx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: dadosCategorias.labels,
            datasets: [{
                label: 'Despesas',
                data: dadosCategorias.despesas,
                backgroundColor: dadosCategorias.backgroundColor,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 12,
                            family: 'Arial, sans-serif'
                        },
                        color: '#fff'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': R$ ' + tooltipItem.raw.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                        }
                    }
                }
            }
        }
    });
});
</script>

<?php
require_once 'footer.php';
?>