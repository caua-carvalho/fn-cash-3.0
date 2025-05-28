<?php
require_once 'transacoes/funcoes.php';
require_once 'header.php';
require_once 'sidebar.php';
require_once 'transacoes/modal.php';
require_once 'dialog.php';
require_once '../conexao.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

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
                    <h3 class="summary-value income">R$ 6.235,80</h3>
                    <div class="flex items-center gap-2">
                        <span class="badge badge-income"><i class="fas fa-arrow-up me-1"></i> 12%</span>
                    </div>
                </div>
            </div>
            
            <div class="summary-card expense fade-in animation-delay-200 w-full">
                <span class="summary-label">Despesas</span>
                <div class="flex justify-between items-center">
                    <h3 class="summary-value expense">R$ 3.842,50</h3>
                    <div class="flex items-center gap-2">
                        <span class="badge badge-expense"><i class="fas fa-arrow-down me-1"></i> 5%</span>
                    </div>
                </div>
            </div>
            
            <div class="summary-card balance fade-in animation-delay-300 w-full">
                <span class="summary-label">Balanço</span>
                <div class="flex justify-between items-center">
                    <h3 class="summary-value">R$ 2.393,30</h3>
                    <div class="flex items-center gap-2">
                        <span class="badge badge-info"><i class="fas fa-chart-line me-1"></i> 7%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtros Avançados -->
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
                    <label class="form-label">Tipo</label>
                    <div class="status-selector mb-0">
                        <button type="button" class="status-option active" data-filter="all">Todos</button>
                        <button type="button" class="status-option income" data-filter="Receita">Receitas</button>
                        <button type="button" class="status-option expense" data-filter="Despesa">Despesas</button>
                        <button type="button" class="status-option transfer" data-filter="Transferência">Transferências</button>
                    </div>
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label">Status</label>
                    <div class="status-selector mb-0">
                        <button type="button" class="status-option active" data-filter="all">Todos</button>
                        <button type="button" class="status-option pending" data-filter="Pendente">Pendentes</button>
                        <button type="button" class="status-option completed" data-filter="Efetivada">Efetivadas</button>
                        <button type="button" class="status-option canceled" data-filter="Cancelada">Canceladas</button>
                    </div>
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label">Período</label>
                    <div class="flex gap-3">
                        <input type="date" class="form-control" placeholder="Data inicial">
                        <input type="date" class="form-control" placeholder="Data final">
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end mt-4">
                <button class="btn btn-secondary me-2">Limpar Filtros</button>
                <button class="btn btn-primary">Aplicar Filtros</button>
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
                $transacoes = obterTransacoes();
                
                if (empty($transacoes)) {
                    echo '<tr><td colspan="8">';
                    echo '<div class="empty-state my-5">';
                    echo '<i class="fas fa-receipt empty-state__icon"></i>';
                    echo '<h3 class="empty-state__title">Nenhuma transação encontrada</h3>';
                    echo '<p class="empty-state__description">Comece a registrar suas transações financeiras para visualizá-las aqui.</p>';
                    echo '<button class="btn btn-primary btn-icon" data-toggle="modal" data-target="#transacaoModal">';
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
document.addEventListener('DOMContentLoaded', function() {
    // Toggle para filtros
    const toggleFilterBtn = document.getElementById('toggleFilter');
    const filterContent = document.querySelector('.filter-content');
    
    toggleFilterBtn.addEventListener('click', function() {
        filterContent.style.display = filterContent.style.display === 'none' ? 'block' : 'none';
        toggleFilterBtn.querySelector('i').classList.toggle('fa-chevron-down');
        toggleFilterBtn.querySelector('i').classList.toggle('fa-chevron-up');
    });
    
    // Funcionalidades originais dos modais de edição e exclusão
    const editarButtons = document.querySelectorAll('[data-target="#editarTransacaoModal"]');
    editarButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Obter atributos do botão clicado
            const id = this.getAttribute('data-id');
            const titulo = this.getAttribute('data-titulo');
            const descricao = this.getAttribute('data-descricao');
            const valor = this.getAttribute('data-valor');
            const data = this.getAttribute('data-data');
            const tipo = this.getAttribute('data-tipo');
            const status = this.getAttribute('data-status');
            const contaRemetenteId = this.getAttribute('data-conta-remetente-id');
            const contaDestinatariaId = this.getAttribute('data-conta-destinataria-id');

            // Preencher os campos do modal
            document.getElementById('editarTransacaoId').value = id;
            document.getElementById('editarTituloTransacao').value = titulo;
            document.getElementById('editarDescricaoTransacao').value = descricao;
            document.getElementById('editarValorTransacao').value = valor;
            document.getElementById('editarDataTransacao').value = data;
            
            // Selecionar tipo de transação
            const tipoOptions = document.querySelectorAll('#editarTransacaoModal .type-option');
            tipoOptions.forEach(option => {
                if(option.getAttribute('data-type') === tipo) {
                    option.classList.add('active');
                } else {
                    option.classList.remove('active');
                }
            });
            document.getElementById('editarTipoTransacao').value = tipo;
            
            // Selecionar status
            const statusOptions = document.querySelectorAll('#editarTransacaoModal .status-option');
            statusOptions.forEach(option => {
                if(option.getAttribute('data-status') === status) {
                    option.classList.add('active');
                } else {
                    option.classList.remove('active');
                }
            });
            document.getElementById('editarStatusTransacao').value = status;
            
            // Selecionar contas
            document.getElementById('editarContaRemetente').value = contaRemetenteId;
            
            // Exibe ou oculta o campo "Conta Destinatária" com base no tipo de transação
            const contaDestinatariaGroup = document.getElementById('editarContaDestinataria').parentElement;
            if (tipo === 'Transferência') {
                contaDestinatariaGroup.style.display = 'block';
                document.getElementById('editarContaDestinataria').required = true;
                document.getElementById('editarContaDestinataria').value = contaDestinatariaId;
            } else {
                contaDestinatariaGroup.style.display = 'none';
                document.getElementById('editarContaDestinataria').required = false;
            }
        });
    });

    // Modal de exclusão
    const excluirButtons = document.querySelectorAll('[data-target="#excluirTransacaoModal"]');
    excluirButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const titulo = this.getAttribute('data-titulo');
            
            document.getElementById('excluirTransacaoId').value = id;
            document.getElementById('transacaoTituloExcluir').textContent = titulo;
        });
    });
    
    // Filtros de tipo de transação
    const filterButtons = document.querySelectorAll('[data-filter]');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            const parentGroup = this.closest('.form-group');
            
            // Remove a classe 'active' de todos os botões do mesmo grupo
            parentGroup.querySelectorAll('.status-option').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Adiciona a classe 'active' ao botão clicado
            this.classList.add('active');
            
            // Aqui implementaria a lógica de filtro
            console.log('Filtrar por:', filter);
        });
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