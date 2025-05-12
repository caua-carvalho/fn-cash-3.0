<?php
// Importações necessárias
require_once 'header.php';
require_once 'sidebar.php';
require_once 'orcamentos/funcoes.php';
include_once 'orcamentos/modal.php';
require_once 'dialog.php';
?>

<script src="orcamento/orcamento.js"></script>

<div class="container py-6">
    <!-- Cabeçalho da Página com Estatísticas -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-bold mb-1">Orçamentos</h2>
                <p class="text-muted">Gerencie seus limites de gastos por categoria</p>
            </div>
            <button class="btn btn-primary btn-icon" data-toggle="modal" data-target="#orcamentoModal">
                <i class="fas fa-plus me-2"></i>
                Novo Orçamento
            </button>
        </div>

        <!-- Cards de Resumo -->
        <div class="d-flex justify-between gap-4 mt-5">
            <?php
            $orcamentos = obterOrcamentos();
            $totalOrcamentos = count($orcamentos);
            $totalPlanejado = 0;
            $totalGasto = 0;
            $orcamentosAtivos = 0;

            foreach ($orcamentos as $orcamento) {
                $totalPlanejado += $orcamento['Valor'];
                $totalGasto += $orcamento['GastoAtual'];
                
                if ($orcamento['Ativo'] == 1) {
                    $orcamentosAtivos++;
                }
            }
            
            $percentualGasto = $totalPlanejado > 0 ? ($totalGasto / $totalPlanejado) * 100 : 0;
            ?>

            <div class="summary-card income fade-in animation-delay-100 w-full">
                <span class="summary-label">Total Planejado</span>
                <div class="d-flex justify-between items-center">
                    <h3 class="summary-value income">R$ <?php echo number_format($totalPlanejado, 2, ',', '.'); ?></h3>
                </div>
            </div>

            <div class="summary-card expense fade-in animation-delay-200 w-full">
                <span class="summary-label">Total Utilizado</span>
                <div class="d-flex justify-between items-center">
                    <h3 class="summary-value expense">R$ <?php echo number_format($totalGasto, 2, ',', '.'); ?></h3>
                </div>
            </div>

            <div class="summary-card balance fade-in animation-delay-300 w-full">
                <span class="summary-label">Orçamentos Ativos</span>
                <div class="d-flex justify-between items-center">
                    <h3 class="summary-value"><?php echo $orcamentosAtivos; ?> de <?php echo $totalOrcamentos; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtro rápido -->
    <div class="filter-container scale-on-load mb-5">
        <div class="filter-header">
            <h3 class="filter-title">
                <i class="fas fa-filter me-2"></i> Filtros
            </h3>
            <button class="btn-action" id="toggleFilter">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        
        <div class="filter-content mt-4" style="display: none;">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-group mb-3">
                    <label class="form-label">Status</label>
                    <div class="status-selector mb-0">
                        <button type="button" class="status-option active" data-filter="todos">Todos</button>
                        <button type="button" class="status-option completed" data-filter="ativos">Ativos</button>
                        <button type="button" class="status-option canceled" data-filter="inativos">Inativos</button>
                    </div>
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label">Categoria</label>
                    <select class="form-control" id="filtroCategoriaId">
                        <option value="todas">Todas as categorias</option>
                        <?php
                        $categorias = obterCategorias();
                        foreach ($categorias as $categoria) {
                            echo "<option value='" . $categoria['ID_Categoria'] . "'>" . htmlspecialchars($categoria['Nome']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="filtroBusca" placeholder=" ">
                </div>
            </div>
            
            <div class="flex justify-end mt-4">
                <button class="btn btn-secondary me-2" id="limparFiltros">Limpar Filtros</button>
                <button class="btn btn-primary" id="aplicarFiltros">Aplicar Filtros</button>
            </div>
        </div>
    </div>

    <!-- Tabela de Orçamentos -->
    <div class="transaction-table-container fade-in-up">
        <div class="p-4 flex justify-between items-center border-bottom">
            <h4 class="font-semibold m-0">Seus Orçamentos</h4>
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
                    <th width="20%">Título</th>
                    <th width="15%">Categoria</th>
                    <th width="15%">Valor Planejado</th>
                    <th width="15%">Valor Utilizado</th>
                    <th width="15%">Período</th>
                    <th width="10%">Status</th>
                    <th width="10%" class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody id="tabelaOrcamentos">
                <?php
                if (empty($orcamentos)) {
                    echo '<tr><td colspan="7">';
                    echo '<div class="empty-state my-5">';
                    echo '<i class="fas fa-calculator empty-state__icon"></i>';
                    echo '<h3 class="empty-state__title">Nenhum orçamento encontrado</h3>';
                    echo '<p class="empty-state__description">Comece a criar orçamentos para controlar seus gastos por categoria.</p>';
                    echo '<button class="btn btn-primary btn-icon" data-toggle="modal" data-target="#orcamentoModal">';
                    echo '<i class="fas fa-plus me-2"></i> Criar Primeiro Orçamento';
                    echo '</button>';
                    echo '</div>';
                    echo '</td></tr>';
                } else {
                    $delay = 100;
                    foreach ($orcamentos as $orcamento) {
                        // Calcula o percentual de uso do orçamento
                        $percentualUso = $orcamento['Valor'] > 0 ? ($orcamento['GastoAtual'] / $orcamento['Valor']) * 100 : 0;
                        
                        // Determina classes para os valores
                        $classePercentual = '';
                        if ($percentualUso >= 100) {
                            $classePercentual = 'text-danger';
                        } elseif ($percentualUso >= 80) {
                            $classePercentual = 'text-warning';
                        } else {
                            $classePercentual = 'text-success';
                        }
                        
                        // Determina classes para status
                        $statusBadgeClass = $orcamento['Ativo'] ? 'badge-success' : 'badge-canceled';
                        $statusTexto = $orcamento['Ativo'] ? 'Ativo' : 'Inativo';
                        $statusIcone = $orcamento['Ativo'] ? 'fa-check-circle' : 'fa-times-circle';

                        echo "<tr class='fade-in-up' style='animation-delay: {$delay}ms'>";
                        echo "<td class='font-medium'>" . htmlspecialchars($orcamento['Titulo']) . "</td>";
                        echo "<td>" . htmlspecialchars($orcamento['NomeCategoria']) . "</td>";
                        
                        // Valor planejado
                        echo "<td class='font-semibold'>R$ " . number_format($orcamento['Valor'], 2, ',', '.') . "</td>";
                        
                        // Valor utilizado com percentual
                        echo "<td>";
                        echo "R$ " . number_format($orcamento['GastoAtual'], 2, ',', '.');
                        echo " <span class='{$classePercentual}'>";
                        echo "(" . number_format($percentualUso, 1) . "%)";
                        echo "</span>";
                        echo "</td>";
                        
                        // Período
                        echo "<td>" . $orcamento['Inicio'] . " a " . $orcamento['Fim'] . "</td>";
                        
                        // Status
                        echo "<td><span class='badge {$statusBadgeClass}'>" .
                             "<i class='fas {$statusIcone} me-1'></i>" .
                             $statusTexto . "</span></td>";
                        
                        // Botões de ação
                        echo "<td>";
                        echo "<div class='flex justify-center gap-2'>";
                        
                        // Botão de editar
                        echo "<button class='btn-action edit' title='Editar' data-toggle='modal' data-target='#editarOrcamentoModal'
                               data-id='" . $orcamento['ID_Orcamento'] . "'
                               data-titulo='" . htmlspecialchars($orcamento['Titulo']) . "'
                               data-categoria-id='" . $orcamento['ID_Categoria'] . "'
                               data-valor='" . $orcamento['Valor'] . "'
                               data-inicio='" . $orcamento['Inicio'] . "'
                               data-fim='" . $orcamento['Fim'] . "'
                               data-descricao='" . htmlspecialchars($orcamento['Descricao']) . "'
                               data-status='" . $orcamento['Ativo'] . "'>
                              <i class='fas fa-edit'></i>
                              </button>";
                        
                        // Botão de excluir
                        echo "<button class='btn-action delete' title='Excluir' data-toggle='modal' data-target='#excluirOrcamentoModal'
                               data-id='" . $orcamento['ID_Orcamento'] . "'
                               data-titulo='" . htmlspecialchars($orcamento['Titulo']) . "'>
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
    $id = $_POST['orcamentoId'] ?? null;
    $titulo = $_POST['titulo'] ?? '';
    $idCategoria = $_POST['categoriaId'] ?? null;
    $valorPlanejado = $_POST['valorPlanejado'] ?? 0.00;
    $inicio = $_POST['periodoInicio'] ?? '';
    $fim = $_POST['periodoFim'] ?? '';
    $descricao = $_POST['descricaoOrcamento'] ?? '-';
    $status = $_POST['status'] ?? true;

    // Processa a ação solicitada
    switch ($acao) {
        case 'editarOrcamento':
            if (editarOrcamento($id, $idCategoria, $titulo, $valorPlanejado, $inicio, $fim, $status)) {
                confirmar("Orçamento editado com sucesso!", "orcamento.php");
            } else {
                erro("Erro ao editar orçamento. Verifique os dados e tente novamente.");
            }
            break;

        case 'cadastrarOrcamento':
            if (cadastrarOrcamento($_SESSION["id_usuario"], $idCategoria, $titulo, $valorPlanejado, $inicio, $fim, $status)) {
                confirmar("Orçamento cadastrado com sucesso!", "orcamento.php");
            } else {
                erro("Erro ao cadastrar orçamento. Verifique os dados e tente novamente.");
            }
            break;

        case 'excluirOrcamento':
            if (deletarOrcamento($id)) {
                confirmar("Orçamento excluído com sucesso!", "orcamento.php");
            } else {
                erro("Erro ao excluir orçamento. Verifique os dados e tente novamente.");
            }
            break;

        default:
            erro("Ação inválida.");
            break;
    }
}
?>