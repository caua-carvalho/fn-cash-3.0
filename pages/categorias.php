<?php
// Processamento dos formulários - DEVE vir antes de qualquer saída HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once dirname(__FILE__, 1) . '/categorias/funcoes.php';
    session_start(); // garante que a sessão esteja ativa para utilizar $_SESSION
    
    // Verifica se a ação foi definida
    $acao = $_POST['acao'] ?? null;

    if (!$acao) {
        $_SESSION['mensagem_erro'] = "Ação não definida.";
        header("Location: categorias.php");
        exit;
    }

    // Obtém os dados enviados
    $id = isset($_POST['categoriaId']) ? (int)$_POST['categoriaId'] : null;
    $nome = trim($_POST['nomeCategoria'] ?? '');
    $tipo = trim($_POST['tipoCategoria'] ?? '');
    $descricao = trim($_POST['descricaoCategoria'] ?? '');
    $status = isset($_POST['statusCategoria']) && $_POST['statusCategoria'] === 'true' ? 1 : 0;

    // Processa a ação solicitada
    switch ($acao) {
        case 'editarCategoria':
            if (editarCategoria($id, $nome, $tipo, $descricao, $status)) {
                $_SESSION['mensagem_sucesso'] = "Categoria editada com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao editar categoria. Verifique os dados e tente novamente.";
            }
            header("Location: categorias.php");
            exit;

        case 'cadastrarCategoria':
            if (cadastrarCategoria($nome, $tipo, $descricao, $status)) {
                $_SESSION['mensagem_sucesso'] = "Categoria cadastrada com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao cadastrar categoria. Verifique os dados e tente novamente.";
            }
            header("Location: categorias.php");
            exit;

        case 'excluirCategoria':
            if (deletarCategoria($id)) {
                $_SESSION['mensagem_sucesso'] = "Categoria excluída com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao excluir categoria. Verifique se não existem transações associadas.";
            }
            header("Location: categorias.php");
            exit;

        default:
            $_SESSION['mensagem_erro'] = "Ação inválida.";
            header("Location: categorias.php");
            exit;
    }
}

// Inclui os arquivos necessários
require_once 'header.php';
require_once 'sidebar.php';
require_once 'categorias/funcoes.php';
require_once 'dialog.php';
require_once 'categorias/modal/modal.php';

// Exibe mensagens de sucesso ou erro
if (isset($_SESSION['mensagem_sucesso'])) {
    alerta($_SESSION['mensagem_sucesso']);
    unset($_SESSION['mensagem_sucesso']);
}

if (isset($_SESSION['mensagem_erro'])) {
    erro($_SESSION['mensagem_erro'], "Erro");
    unset($_SESSION['mensagem_erro']);
}

// Filtros recebidos via GET ou POST
$tipoFiltro = $_POST['tipoCategoria'] ?? $_GET['tipoCategoria'] ?? 'todos';
$statusFiltro = $_POST['statusCategoria'] ?? $_GET['statusCategoria'] ?? 'todos';
$buscaFiltro = $_POST['filtroBusca'] ?? $_GET['filtroBusca'] ?? '';

// Obtém as categorias considerando os filtros
$categorias = obterCategorias($tipoFiltro, $statusFiltro, $buscaFiltro);
?>

<div class="content">
    <!-- Cabeçalho da Página com Estatísticas -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-bold mb-1">Categorias</h2>
                <p class="text-muted">Gerencie suas categorias de receitas e despesas</p>
            </div>
            <button class="btn btn-primary btn-icon" data-toggle="modal" data-target="#categoriaModal" data-modal-open="#categoriaModal">
                <i class="fas fa-plus me-2"></i>
                Nova Categoria
            </button>
        </div>

        <!-- Cards de Resumo -->
        <div class="d-flex justify-between gap-4 mt-5">
            <?php
            $totalCategorias = count($categorias);
            $categoriasReceita = 0;
            $categoriasDespesa = 0;
            $categoriasAtivas = 0;

            foreach ($categorias as $categoria) {
                if ($categoria['Tipo'] == 'Receita') {
                    $categoriasReceita++;
                } else {
                    $categoriasDespesa++;
                }

                if ($categoria['Ativa'] == 1) {
                    $categoriasAtivas++;
                }
            }
            ?>
            <div class="summary-card income fade-in animation-delay-100 w-full">
                <span class="summary-label">Categorias de Receita</span>
                <div class="d-flex justify-between items-center">
                    <h3 class="summary-value income"><?php echo $categoriasReceita; ?></h3>
                </div>
            </div>

            <div class="summary-card expense fade-in animation-delay-200 w-full">
                <span class="summary-label">Categorias de Despesa</span>
                <div class="d-flex justify-between items-center">
                    <h3 class="summary-value expense"><?php echo $categoriasDespesa; ?></h3>
                </div>
            </div>

            <div class="summary-card balance fade-in animation-delay-300 w-full">
                <span class="summary-label">Categorias Ativas</span>
                <div class="d-flex justify-between items-center">
                    <h3 class="summary-value"><?php echo $categoriasAtivas; ?> de <?php echo $totalCategorias; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtro rápido -->
    <div class="filter-container slide-in-left mb-4">
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
                    <label for="filtroTipo">Tipo</label>
                    <select class="form-control" id="filtroTipo" name="tipoCategoria">
                        <option value="todos" <?php echo $tipoFiltro === 'todos' ? 'selected' : ''; ?>>Todos os tipos</option>
                        <option value="Receita" <?php echo $tipoFiltro === 'Receita' ? 'selected' : ''; ?>>Receitas</option>
                        <option value="Despesa" <?php echo $tipoFiltro === 'Despesa' ? 'selected' : ''; ?>>Despesas</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="filtroStatus">Status</label>
                    <select class="form-control" id="filtroStatus" name="statusCategoria">
                        <option value="todos" <?php echo $statusFiltro === 'todos' ? 'selected' : ''; ?>>Todos os status</option>
                        <option value="ativas" <?php echo $statusFiltro === 'ativas' ? 'selected' : ''; ?>>Ativas</option>
                        <option value="inativas" <?php echo $statusFiltro === 'inativas' ? 'selected' : ''; ?>>Inativas</option>
                    </select>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="filtroBusca">Buscar</label>
                <input type="text" class="form-control" id="filtroBusca" name="filtroBusca" value="<?php echo htmlspecialchars($buscaFiltro); ?>" placeholder=" ">
            </div>

            <div class="flex justify-end mt-3">
                <button class="btn btn-secondary me-2" id="limparFiltros">Limpar Filtros</button>
                <button class="btn btn-primary" id="aplicarFiltros">Aplicar Filtros</button>
            </div>
        </div>
    </div>
    
    <!-- Tabela de Categorias -->
    <div class="transaction-table-container fade-in-up">
        <div class="p-4 flex justify-between items-center border-bottom">
            <h4 class="font-semibold m-0">Suas Categorias</h4>
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
                    <th width="30%">Nome</th>
                    <th width="15%">Tipo</th>
                    <th width="30%">Descrição</th>
                    <th width="10%">Status</th>
                    <th width="15%" class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody id="tabelaCategorias">
                <?php
                if (empty($categorias)) {
                    echo '<tr><td colspan="5">';
                    echo '<div class="empty-state my-5">';
                    echo '<i class="fas fa-tags empty-state__icon"></i>';
                    echo '<h3 class="empty-state__title">Nenhuma categoria encontrada</h3>';
                    echo '<p class="empty-state__description">Comece a criar categorias para organizar suas receitas e despesas.</p>';
                    echo '<button class="btn btn-primary btn-icon" data-toggle="modal" data-target="#categoriaModal" data-modal-open="#categoriaModal">';
                    echo '<i class="fas fa-plus me-2"></i> Criar Primeira Categoria';
                    echo '</button>';
                    echo '</div>';
                    echo '</td></tr>';
                } else {
                    $delay = 100;
                    foreach ($categorias as $categoria) {
                        // Determina as classes para tipo de categoria
                        $tipoBadgeClass = $categoria['Tipo'] === 'Receita' ? 'badge-income' : 'badge-expense';
                        $tipoIcone = $categoria['Tipo'] === 'Receita' ? 'fa-arrow-up' : 'fa-arrow-down';

                        // Determina classes para status
                        $statusBadgeClass = $categoria['Ativa'] ? 'badge-success' : 'badge-canceled';
                        $statusTexto = $categoria['Ativa'] ? 'Ativa' : 'Inativa';
                        $statusIcone = $categoria['Ativa'] ? 'fa-check-circle' : 'fa-times-circle';

                        $dataStatus = $categoria['Ativa'] ? 'ativa' : 'inativa';
                        $dataNome = htmlspecialchars($categoria['Nome'], ENT_QUOTES);
                        $descricaoCompleta = !empty($categoria['Descricao']) ? $categoria['Descricao'] : '-';
                        $dataDescricao = htmlspecialchars($descricaoCompleta, ENT_QUOTES);
                        echo "<tr class='fade-in-up categoria' style='animation-delay: {$delay}ms' data-nome='{$dataNome}' data-tipo='{$categoria['Tipo']}' data-status='{$dataStatus}' data-descricao='{$dataDescricao}'>";
                        echo "<td class='font-medium'>" . htmlspecialchars($categoria['Nome']) . "</td>";

                        // Badge para o tipo de categoria
                        echo "<td><span class='badge {$tipoBadgeClass}'>" .
                            "<i class='fas {$tipoIcone} me-1'></i>" .
                            htmlspecialchars($categoria['Tipo']) . "</span></td>";

                        // Descrição (limitada a 50 caracteres)
                        $descricao = $descricaoCompleta;
                        if (strlen($descricao) > 50) {
                            $descricao = substr($descricao, 0, 50) . '...';
                        }
                        echo "<td>" . htmlspecialchars($descricao) . "</td>";

                        // Status
                        echo "<td><span class='badge {$statusBadgeClass}'>" .
                            "<i class='fas {$statusIcone} me-1'></i>" .
                            $statusTexto . "</span></td>";

                        // Botões de ação
                        echo "<td>";
                        echo "<div class='flex justify-center gap-2'>";

                        // Botão de editar
                        echo "<button class='btn-action edit' title='Editar' data-toggle='modal' data-target='#editarCategoriaModal' data-modal-open='#editarCategoriaModal'
                               data-id='" . $categoria['ID_Categoria'] . "'
                               data-nome='" . htmlspecialchars($categoria['Nome']) . "'
                               data-tipo='" . $categoria['Tipo'] . "'
                               data-descricao='" . htmlspecialchars($categoria['Descricao']) . "'
                               data-status='" . $categoria['Ativa'] . "'>
                              <i class='fas fa-edit'></i>
                              </button>";

                        // Botão de excluir
                        echo "<button class='btn-action delete' title='Excluir' data-toggle='modal' data-target='#excluirCategoriaModal' data-modal-open='#excluirCategoriaModal'
                               data-id='" . $categoria['ID_Categoria'] . "'
                               data-nome='" . htmlspecialchars($categoria['Nome']) . "'>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
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
