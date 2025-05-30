<?php
// Gerado pelo Copilot
require_once 'header.php';
require_once 'sidebar.php';
require_once 'transacoes/funcoes.php';
require_once 'dialog.php';
?>

<div class="content">
    <div class="container-fluid">
        <!-- Cabeçalho e botão Nova Transação -->
        <div class="flex justify-between items-center my-4">
            <div>
                <h2>Transações</h2>
                <p class="text-muted">Gerencie suas transações financeiras</p>
            </div>
            <button class="btn btn-primary btn-icon" data-toggle="modal" data-target="#transacaoModal">
                <i class="fas fa-plus me-2"></i> Nova Transação
            </button>
        </div>

        <!-- Cards de Resumo -->
        <div class="grid grid-cols-4 gap-4 mb-6">
            <!-- Receitas -->
            <div class="summary-card income animation-delay-100 fade-in">
                <span class="summary-label">Receitas Totais</span>
                <div class="flex justify-between flex-col">
                    <h3 class="summary-value income">R$ <?php echo number_format(obterTotalReceitas(), 2, ',', '.'); ?></h3>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="badge badge-income">
                            <i class="fas fa-arrow-up me-1"></i> Receitas
                        </span>
                    </div>
                </div>
            </div>

            <!-- Despesas -->
            <div class="summary-card expense animation-delay-200 fade-in">
                <span class="summary-label">Despesas Totais</span>
                <div class="flex justify-between flex-col">
                    <h3 class="summary-value expense">R$ <?php echo number_format(obterTotalDespesas(), 2, ',', '.'); ?></h3>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="badge badge-expense">
                            <i class="fas fa-arrow-down me-1"></i> Despesas
                        </span>
                    </div>
                </div>
            </div>

            <!-- Transferências -->
            <div class="summary-card transfer animation-delay-300 fade-in">
                <span class="summary-label">Transferências</span>
                <div class="flex justify-between flex-col">
                    <h3 class="summary-value transfer">R$ <?php echo number_format(obterTotalTransferencias(), 2, ',', '.'); ?></h3>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="badge badge-transfer">
                            <i class="fas fa-exchange-alt me-1"></i> Transferências
                        </span>
                    </div>
                </div>
            </div>

            <!-- Saldo -->
            <div class="summary-card balance animation-delay-400 fade-in">
                <span class="summary-label">Saldo Líquido</span>
                <div class="flex justify-between flex-col">
                    <h3 class="summary-value balance">R$ <?php echo number_format(obterSaldoLiquido(), 2, ',', '.'); ?></h3>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="badge badge-completed">
                            <i class="fas fa-chart-line me-1"></i> Balanço
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Área de Gráficos -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <!-- Gráfico de Evolução -->
            <div class="card fade-in-up">
                <div class="card__header">
                    <h4 class="card__title">Evolução Mensal</h4>
                    <p class="card__subtitle">Receitas vs Despesas</p>
                </div>
                <div class="card__body">
                    <div class="chart-area" style="height: 300px;">
                        <canvas id="evolutionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Distribuição -->
            <div class="card fade-in-up animation-delay-200">
                <div class="card__header">
                    <h4 class="card__title">Distribuição por Categoria</h4>
                    <p class="card__subtitle">Últimos 30 dias</p>
                </div>
                <div class="card__body">
                    <div class="chart-area" style="height: 300px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Transações -->
        <div class="transaction-table-container scale-on-load">
            <div class="flex justify-between items-center p-4 border-bottom">
                <h4 class="m-0">Lista de Transações</h4>
                <div class="flex gap-2">
                    <button class="btn btn-sm btn-secondary active filter-btn" data-filter="todas">Todas</button>
                    <button class="btn btn-sm btn-secondary filter-btn" data-filter="receitas">Receitas</button>
                    <button class="btn btn-sm btn-secondary filter-btn" data-filter="despesas">Despesas</button>
                    <button class="btn btn-sm btn-secondary filter-btn" data-filter="transferencias">Transferências</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="transaction-table">
                    <!-- ... código existente da tabela ... -->
                </table>
            </div>

            <!-- Paginação -->
            <div class="flex justify-between items-center p-4 border-top">
                <div class="text-muted">
                    Mostrando <span id="itemsShowing">1-10</span> de <span id="totalItems">100</span> transações
                </div>
                <nav aria-label="Navegação de página">
                    <ul class="pagination mb-0">
                        <!-- ... código existente da paginação ... -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'transacoes/modal.php';
require_once 'footer.php';
?>

<!-- Scripts específicos da página -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="transacoes/charts.js"></script>
