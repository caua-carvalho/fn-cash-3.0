<?php
require_once 'header.php';
require_once 'sidebar.php';
require_once 'contas/funcoes.php';
require_once 'contas/modal.php';
require_once 'dialog.php';
require_once 'dashboard/funcoes.php'; // Inclui as funções do dashboard

// Processa o filtro de período se enviado
$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : 'mes-atual';
$dataInicio = isset($_GET['dataInicio']) ? $_GET['dataInicio'] : null;
$dataFim = isset($_GET['dataFim']) ? $_GET['dataFim'] : null;

// Obtém o intervalo de datas com base no período selecionado
$intervalo = obterIntervaloDatas($periodo, $dataInicio, $dataFim);

// Prepara os dados para os gráficos
$dadosGraficoReceitasDespesas = obterDadosGraficoReceitasDespesas();
$dadosGraficoCategorias = obterDadosGraficoCategorias($intervalo);

// Obtém os dados para os cards de resumo
$saldoTotal = obterSaldoTotal();
$receitasPeriodo = obterReceita($intervalo);
$despesasPeriodo = obterDespesa($intervalo);
$saldoMensal = obterSaldoMensal($intervalo);

// Calcula as variações percentuais
$variacaoSaldo = calcularVariacaoPercentual('saldo', $intervalo);
$variacaoReceita = calcularVariacaoPercentual('receita', $intervalo);
$variacaoDespesa = calcularVariacaoPercentual('despesa', $intervalo);
$variacaoSaldoMensal = calcularVariacaoPercentual('saldo_mensal', $intervalo);

// Obtém as contas e transações recentes
$contas = obterContasUsuario(2);
$transacoesRecentes = obterTransacoesRecentes(4, $intervalo);

// Converte os dados para JSON para uso no JavaScript
$dadosGraficoReceitasDespesasJSON = json_encode($dadosGraficoReceitasDespesas);
$dadosGraficoCategoriasJSON = json_encode($dadosGraficoCategorias);
?>

<!-- Scripts para Filtros -->
<script src='dashboard/dashboard_filtro.js'></script>

<!-- Passa os dados dinâmicos para o JavaScript -->
<script>
    // Dados para os gráficos - preenchidos com PHP
    const dadosReceitasDespesas = <?php echo $dadosGraficoReceitasDespesasJSON; ?>;
    const dadosCategorias = <?php echo $dadosGraficoCategoriasJSON; ?>;
</script>

<div class="content">
    <div class="container-fluid">
        <!-- Cabeçalho e botão Nova Transação -->
        <div class="flex justify-between items-center my-4">
            <div>
                <h2><?php echo 'Olá, ' . $_SESSION['usuario'] . '!' ?></h2>
                <p class="text-muted">Aqui está um resumo da sua situação financeira atual.</p>
            </div>
            <button class="btn btn-primary btn-icon">
                <i class="fas fa-plus me-2"></i> Nova Transação
            </button>
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
                    <button type="button" class="status-option <?php echo $periodo === 'mes-atual' ? 'active' : ''; ?>" data-period="mes-atual">Mês Atual</button>
                    <button type="button" class="status-option <?php echo $periodo === 'mes-anterior' ? 'active' : ''; ?>" data-period="mes-anterior">Mês Anterior</button>
                    <button type="button" class="status-option <?php echo $periodo === 'ano-atual' ? 'active' : ''; ?>" data-period="ano-atual">Ano Atual</button>
                    <button type="button" class="status-option <?php echo $periodo === 'customizado' ? 'active' : ''; ?>" data-period="customizado">Personalizado</button>
                </div>
                <input type="hidden" name="periodSelection" id="periodSelection" value="<?php echo $periodo; ?>">
                
                <!-- Intervalo de datas personalizado (inicialmente oculto) -->
                <div id="customPeriodSection" class="fade-in-up" style="display: <?php echo $periodo === 'customizado' ? 'block' : 'none'; ?>;">
                    <div class="grid grid-cols-1 grid-md-cols-2 gap-4 mb-4">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="startDate" placeholder=" " value="<?php echo $dataInicio ?? $intervalo['inicio']; ?>">
                            <label for="startDate">Data Inicial</label>
                        </div>
                        
                        <div class="form-floating">
                            <input type="date" class="form-control" id="endDate" placeholder=" " value="<?php echo $dataFim ?? $intervalo['fim']; ?>">
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
            <div class="summary-card balance animation-delay-100 fade-in w-50 h-min">
                <span class="summary-label">Saldo Total</span>
                <div class="flex justify-between flex-col">
                    <h3 class="summary-value balance"><?php echo 'R$ ' . number_format($saldoTotal, 2, ',', '.'); ?></h3>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="badge badge-<?php echo $variacaoSaldo >= 0 ? 'transfer' : 'expense'; ?>">
                            <i class="fas fa-arrow-<?php echo $variacaoSaldo >= 0 ? 'up' : 'down'; ?> me-1"></i> 
                            <?php echo abs($variacaoSaldo); ?>%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Receitas -->
            <div class="summary-card income animation-delay-200 fade-in w-50 h-min">
                <span class="summary-label">Receitas do Período</span>
                <div class="flex justify-between flex-col">
                    <h3 class="summary-value income"><?php echo 'R$ ' . number_format($receitasPeriodo, 2, ',', '.'); ?></h3>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="badge badge-<?php echo $variacaoReceita >= 0 ? 'income' : 'expense'; ?>">
                            <i class="fas fa-arrow-<?php echo $variacaoReceita >= 0 ? 'up' : 'down'; ?> me-1"></i> 
                            <?php echo abs($variacaoReceita); ?>%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Despesas -->
            <div class="summary-card expense animation-delay-300 fade-in w-50 h-min">
                <span class="summary-label">Despesas do Período</span>
                <div class="flex justify-between flex-col">
                    <h3 class="summary-value expense"><?php echo 'R$ ' . number_format($despesasPeriodo, 2, ',', '.'); ?></h3>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="badge badge-<?php echo $variacaoDespesa <= 0 ? 'income' : 'expense'; ?>">
                            <i class="fas fa-arrow-<?php echo $variacaoDespesa <= 0 ? 'down' : 'up'; ?> me-1"></i> 
                            <?php echo abs($variacaoDespesa); ?>%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Saldo Mensal -->
            <div class="summary-card balance animation-delay-400 fade-in w-50 h-min">
                <span class="summary-label">Saldo do Período</span>
                <div class="flex justify-between flex-col">
                    <h3 class="summary-value balance"><?php echo 'R$ ' . number_format($saldoMensal, 2, ',', '.'); ?></h3>
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
                <p class="card__subtitle">Últimos 6 meses</p>
            </div>
            <div class="card__body">
                <div class="chart-area" style="height: 400px;">
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
                <div class="card__header">
                    <h4 class="card__title">Despesas por Categoria</h4>
                    <p class="card__subtitle"><?php echo $periodo === 'mes-atual' ? 'Mês atual' : ($periodo === 'mes-anterior' ? 'Mês anterior' : ($periodo === 'ano-atual' ? 'Ano atual' : 'Período personalizado')); ?></p>
                </div>
                <div class="card__body">
                    <div class="chart-area" style="height: 250px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
                <div class="card__footer">
                    <div class="text-muted text-sm">
                        <i class="fas fa-chart-pie"></i> Distribuição de despesas
                    </div>
                </div>
            </div>

            <!-- Minhas Contas -->
            <div class="card fade-in-up animation-delay-300">
                <div class="card__header">
                    <h4 class="card__title">Minhas Contas</h4>
                    <p class="card__subtitle">Saldos atuais</p>
                </div>
                <div class="card__body">
                    <?php if (empty($contas)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-piggy-bank fa-3x mb-3"></i>
                            <p>Você ainda não possui contas cadastradas.</p>
                            <button class="btn btn-secondary btn-icon mt-3">
                                <i class="fas fa-plus me-2"></i> Adicionar Conta
                            </button>
                        </div>
                    <?php else: ?>
                        <?php foreach ($contas as $conta): ?>
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
                    <button class="btn btn-sm btn-secondary active">Todas</button>
                    <button class="btn btn-sm btn-secondary">Receitas</button>
                    <button class="btn btn-sm btn-secondary">Despesas</button>
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
                                            <button class="btn-action view" title="Visualizar"><i class="fas fa-eye"></i></button>
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
    // Configuração do gráfico de Receitas vs Despesas com dados dinâmicos
    const financeCtx = document.getElementById('financeChart').getContext('2d');

    const despesaGradient = financeCtx.createLinearGradient(0, 0, 0, 350);
    despesaGradient.addColorStop(0, 'rgba(231, 43, 22, 0.88)');
    despesaGradient.addColorStop(1, 'rgba(231, 76, 60, 0)');

    const receitaGradient = financeCtx.createLinearGradient(0, 0, 0, 350);
    receitaGradient.addColorStop(0, 'rgba(7, 163, 98, 0.6)');
    receitaGradient.addColorStop(1, 'rgba(7, 163, 98, 0)');

    const financeChart = new Chart(financeCtx, {
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
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: dadosCategorias.labels,
            datasets: [{
                data: dadosCategorias.data,
                backgroundColor: dadosCategorias.backgroundColor,
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>