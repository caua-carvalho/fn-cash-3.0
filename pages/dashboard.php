<?php
require_once 'header.php';
require_once 'sidebar.php';
require_once 'contas/funcoes.php';
require_once 'contas/modal.php';
require_once 'dialog.php';
require_once 'dashboard/funcoes.php'; // Inclui as funções do dashboard
?>

<!-- Scripts para Filtros -->
<script src='dashboard/dashboard_filtro.js'></script>

<!-- Scripts para os gráficos -->
<script src="dashboard/dashboard_content.js"></script>

<div class="content">
    <div class="container-fluid">
        <!-- Cabeçalho e botão Nova Transação -->
        <div class="flex justify-between items-center my-4">
            <div>
                <h2><?php echo 'Ola, ' . $_SESSION['usuario'] . '!' ?></h2>
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
                    <button type="button" class="status-option active" data-period="current-month">Mês Atual</button>
                    <button type="button" class="status-option" data-period="last-month">Mês Anterior</button>
                    <button type="button" class="status-option" data-period="current-year">Ano Atual</button>
                    <button type="button" class="status-option" data-period="custom">Personalizado</button>
                </div>
                <input type="hidden" name="periodSelection" id="periodSelection" value="current-month">
                
                <!-- Intervalo de datas personalizado (inicialmente oculto) -->
                <div id="customPeriodSection" class="fade-in-up" style="display: none;">
                    <div class="grid grid-cols-1 grid-md-cols-2 gap-4 mb-4">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="startDate" placeholder=" ">
                            <label for="startDate">Data Inicial</label>
                        </div>
                        
                        <div class="form-floating">
                            <input type="date" class="form-control" id="endDate" placeholder=" ">
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
                <div class="flex justify-between items-center">
                    <h3 class="summary-value balance"><?php echo 'R$ ' . number_format(obterSaldoTotal(), 2); ?></h3>
                    <div class="flex items-center gap-2">
                        <span class="badge badge-success"><i class="fas fa-arrow-up me-1"></i> 15%</span>
                    </div>
                </div>
                <p class="text-muted text-sm mt-2">vs mês anterior</p>
            </div>

            <!-- Receitas -->
            <div class="summary-card income animation-delay-200 fade-in">
                <span class="summary-label">Receitas do Mês</span>
                <div class="flex justify-between items-center">
                    <h3 class="summary-value income"><?php echo 'R$ ' . number_format(obterReceita(), 2); ?></h3>
                    <div class="flex items-center gap-2">
                        <span class="badge badge-income"><i class="fas fa-arrow-up me-1"></i> 5%</span>
                    </div>
                </div>
                <p class="text-muted text-sm mt-2">vs mês anterior</p>
            </div>

            <!-- Despesas -->
            <div class="summary-card expense animation-delay-300 fade-in">
                <span class="summary-label">Despesas do Mês</span>
                <div class="flex justify-between items-center">
                    <h3 class="summary-value expense"><?php echo 'R$ ' . number_format(obterDespesa(), 2); ?></h3>
                    <div class="flex items-center gap-2">
                        <span class="badge badge-expense"><i class="fas fa-arrow-down me-1"></i> 3%</span>
                    </div>
                </div>
                <p class="text-muted text-sm mt-2">vs mês anterior</p>
            </div>

            <!-- Saldo Mensal -->
            <div class="summary-card balance animation-delay-400 fade-in">
                <span class="summary-label">Saldo Mensal</span>
                <div class="flex justify-between items-center">
                    <h3 class="summary-value balance"><?php echo 'R$ ' . number_format(obterSaldoMensal(), 2); ?></h3>
                    <div class="flex items-center gap-2">
                        <span class="badge badge-success"><i class="fas fa-arrow-up me-1"></i> 77%</span>
                    </div>
                </div>
                <p class="text-muted text-sm mt-2">da receita total</p>
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
                    <p class="card__subtitle">Mês atual</p>
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
                    <!-- Conta Corrente -->
                    <div class="account-card mb-4">
                        <div class="account-card__header">
                            <h5 class="account-card__title">Conta Corrente João</h5>
                            <div class="account-card__icon">
                                <i class="fas fa-landmark"></i>
                            </div>
                        </div>
                        <div class="account-card__balance">R$ 1.000,00</div>
                        <div class="account-card__info">Banco A</div>
                    </div>

                    <!-- Poupança -->
                    <div class="account-card mb-4">
                        <div class="account-card__header">
                            <h5 class="account-card__title">Poupança Maria</h5>
                            <div class="account-card__icon">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                        </div>
                        <div class="account-card__balance">R$ 5.000,00</div>
                        <div class="account-card__info">Banco B</div>
                    </div>

                    <div class="flex justify-center mt-4">
                        <button class="btn btn-secondary btn-icon">
                            <i class="fas fa-plus me-2"></i> Adicionar Conta
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transações Recentes -->
        <div class="transaction-table-container scale-on-load">
            <div class="flex justify-between items-center p-4 border-bottom">
                <h4 class="m-0">Transações Recentes</h4>
                <div class="flex gap-2">
                    <button class="btn btn-sm btn-secondary">Todas</button>
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
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="badge badge-expense"><i class="fas fa-arrow-down"></i></span>
                                    <span>Compra Supermercado</span>
                                </div>
                            </td>
                            <td>Alimentação</td>
                            <td>Hoje</td>
                            <td class="text-expense">R$ 200,00</td>
                            <td><span class="badge badge-completed">Efetivada</span></td>
                            <td>
                                <div class="flex gap-2">
                                    <button class="btn-action view"><i class="fas fa-eye"></i></button>
                                    <button class="btn-action edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn-action delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="badge badge-income"><i class="fas fa-arrow-up"></i></span>
                                    <span>Recebimento Salário</span>
                                </div>
                            </td>
                            <td>Salário</td>
                            <td>Hoje</td>
                            <td class="text-income">R$ 3.000,00</td>
                            <td><span class="badge badge-completed">Efetivada</span></td>
                            <td>
                                <div class="flex gap-2">
                                    <button class="btn-action view"><i class="fas fa-eye"></i></button>
                                    <button class="btn-action edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn-action delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="badge badge-expense"><i class="fas fa-arrow-down"></i></span>
                                    <span>Assinatura Streaming</span>
                                </div>
                            </td>
                            <td>Entretenimento</td>
                            <td>Ontem</td>
                            <td class="text-expense">R$ 50,00</td>
                            <td><span class="badge badge-completed">Efetivada</span></td>
                            <td>
                                <div class="flex gap-2">
                                    <button class="btn-action view"><i class="fas fa-eye"></i></button>
                                    <button class="btn-action edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn-action delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="badge badge-expense"><i class="fas fa-arrow-down"></i></span>
                                    <span>Plano de Saúde</span>
                                </div>
                            </td>
                            <td>Saúde</td>
                            <td>3 dias atrás</td>
                            <td class="text-expense">R$ 300,00</td>
                            <td><span class="badge badge-completed">Efetivada</span></td>
                            <td>
                                <div class="flex gap-2">
                                    <button class="btn-action view"><i class="fas fa-eye"></i></button>
                                    <button class="btn-action edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn-action delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

