document.addEventListener('DOMContentLoaded', function() {
    // --- ELEMENTOS DOM ---
    const filterToggle = document.getElementById('toggleFilter');
    const filterContent = document.getElementById('filterContent');
    const filterButtons = document.querySelectorAll('.status-option[data-filter]');
    const filterCategorySelect = document.getElementById('filtroCategoriaId');
    const filterSearchInput = document.getElementById('filtroBusca');
    const clearFilterButton = document.getElementById('limparFiltros');
    const applyFilterButton = document.getElementById('aplicarFiltros');
    const table = document.getElementById('tabelaOrcamentos');
    
    // --- GERENCIAMENTO DE FILTROS ---
    function initializeFilters() {
        // Toggle de filtros com animação suave
        filterToggle.addEventListener('click', function() {
            const isVisible = filterContent.style.display !== 'none';
            const filterIcon = this.querySelector('i');
            
            if (isVisible) {
                // Animar fechamento
                filterContent.style.opacity = '0';
                filterIcon.style.transform = 'rotate(0deg)';
                setTimeout(() => {
                    filterContent.style.display = 'none';
                }, 300);
            } else {
                // Animar abertura
                filterContent.style.display = 'block';
                filterIcon.style.transform = 'rotate(180deg)';
                setTimeout(() => {
                    filterContent.style.opacity = '1';
                }, 10);
            }
        });
        
        // Botões de filtro de status
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove classe ativa de todos os botões do mesmo grupo
                const group = this.closest('.form-group');
                group.querySelectorAll('.status-option').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Adiciona classe ativa ao botão clicado
                this.classList.add('active');
            });
        });
        
        // Limpar filtros
        clearFilterButton.addEventListener('click', resetFilters);
        
        // Aplicar filtros com animação
        applyFilterButton.addEventListener('click', function() {
            applyFilters();
            
            // Feedback visual
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Aplicando...';
            this.disabled = true;
            
            // Simula processamento e restaura o botão
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-filter me-2"></i> Aplicar Filtros';
                this.disabled = false;
                
                // Anima os resultados
                animateFilteredResults();
            }, 500);
        });
    }
    
    function resetFilters() {
        // Reseta todos os controles de filtro para valores padrão
        filterButtons.forEach(button => {
            if(button.getAttribute('data-filter') === 'todos' || 
               button.getAttribute('data-filter') === 'ativos') {
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }
        });
        
        filterCategorySelect.value = 'todas';
        filterSearchInput.value = '';
        
        // Aplica os filtros resetados
        applyFilters();
        
        // Feedback visual
        clearFilterButton.classList.add('pulse');
        setTimeout(() => {
            clearFilterButton.classList.remove('pulse');
        }, 500);
    }
    
    function applyFilters() {
        // Recupera os valores de filtro atuais
        const statusFilter = document.querySelector('.status-option.active[data-filter]').getAttribute('data-filter');
        const categoryFilter = filterCategorySelect.value;
        const searchFilter = filterSearchInput.value.toLowerCase().trim();
        
        // Aplica os filtros às linhas da tabela
        const rows = table.querySelectorAll('tr:not(.empty-state-row)');
        let anyVisible = false;
        
        rows.forEach(row => {
            if(row.classList.contains('empty-state-row')) return;
            
            // Extrai dados da linha
            const status = row.querySelector('.badge-success, .badge-canceled')?.textContent.trim().toLowerCase() || '';
            const category = row.querySelector('td:nth-child(2)')?.textContent.trim().toLowerCase() || '';
            const title = row.querySelector('td:nth-child(1)')?.textContent.trim().toLowerCase() || '';
            
            // Verifica se a linha passa pelos filtros
            const passesStatus = statusFilter === 'todos' || 
                               (statusFilter === 'ativos' && status.includes('ativo')) ||
                               (statusFilter === 'inativos' && status.includes('inativo'));
                               
            const passesCategory = categoryFilter === 'todas' || 
                                row.getAttribute('data-category-id') === categoryFilter;
                                
            const passesSearch = searchFilter === '' || 
                              title.includes(searchFilter);
            
            // Exibe ou oculta a linha
            if(passesStatus && passesCategory && passesSearch) {
                row.style.display = 'table-row';
                anyVisible = true;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Mostra mensagem de "nenhum resultado" se necessário
        handleEmptyState(anyVisible);
    }
    
    function handleEmptyState(anyVisible) {
        // Remove mensagem existente, se houver
        const existingEmpty = document.querySelector('.empty-filter-message');
        if(existingEmpty) {
            existingEmpty.remove();
        }
        
        // Se não houver resultados visíveis, mostra mensagem
        if(!anyVisible) {
            const emptyRow = document.createElement('tr');
            emptyRow.className = 'empty-filter-message';
            emptyRow.innerHTML = `
                <td colspan="7" class="text-center py-4">
                    <i class="fas fa-filter fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Nenhum orçamento corresponde aos filtros selecionados.</p>
                    <button class="btn btn-secondary btn-sm mt-2" id="clearFiltersInline">
                        <i class="fas fa-undo me-1"></i> Limpar Filtros
                    </button>
                </td>
            `;
            table.appendChild(emptyRow);
            
            // Adiciona evento ao botão inline
            document.getElementById('clearFiltersInline').addEventListener('click', resetFilters);
        }
    }
    
    function animateFilteredResults() {
        // Anima os resultados filtrados com efeito escalonado
        const visibleRows = Array.from(table.querySelectorAll('tr')).filter(row => 
            row.style.display !== 'none' && !row.classList.contains('empty-filter-message')
        );
        
        visibleRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(10px)';
            
            setTimeout(() => {
                row.style.transition = 'all 0.3s ease-out';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, 50 * index);
        });
    }
    
    // --- GRÁFICOS E VISUALIZAÇÕES ---
    function initializeProgressBars() {
        // Adiciona barras de progresso visual para cada orçamento
        const rows = table.querySelectorAll('tr:not(.empty-state-row)');
        
        rows.forEach(row => {
            const plannedValueCell = row.querySelector('td:nth-child(3)');
            const usedValueCell = row.querySelector('td:nth-child(4)');
            
            if (!plannedValueCell || !usedValueCell) return;
            
            // Extrai os valores numéricos
            const plannedText = plannedValueCell.textContent;
            const usedText = usedValueCell.textContent.split('(')[0]; // Remove percentual
            
            const plannedValue = parseFloat(plannedText.replace(/[^\d,]/g, '').replace(',', '.'));
            const usedValue = parseFloat(usedText.replace(/[^\d,]/g, '').replace(',', '.'));
            
            // Calcula percentual
            const percentage = (usedValue / plannedValue) * 100;
            const formattedPercentage = percentage.toFixed(1);
            
            // Determina a classe de cor com base no percentual
            let colorClass = 'success';
            if (percentage > 90) colorClass = 'danger';
            else if (percentage > 70) colorClass = 'warning';
            
            // Cria a barra de progresso
            const progressBar = document.createElement('div');
            progressBar.className = 'budget-progress mt-2';
            progressBar.innerHTML = `
                <div class="budget-progress-bar">
                    <div class="budget-progress-fill bg-${colorClass}" style="width: ${Math.min(100, percentage)}%"></div>
                </div>
                <span class="budget-progress-text text-${colorClass} text-sm">${formattedPercentage}%</span>
            `;
            
            // Adiciona a barra de progresso à célula
            usedValueCell.appendChild(progressBar);
        });
    }
    
    // --- OPERAÇÕES CRUD VIA AJAX ---
    function setupAjaxForms() {
        // Configura todos os formulários para submissão via AJAX
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                // Previne submissão padrão
                e.preventDefault();
                
                // Validação do formulário
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return;
                }
                
                // Recupera dados do formulário
                const formData = new FormData(form);
                const submitButton = form.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                const modalElement = form.closest('.modal');
                
                // Feedback visual de loading
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';
                submitButton.disabled = true;
                
                // Simula submissão AJAX (em um ambiente real, usaria fetch ou XMLHttpRequest)
                setTimeout(() => {
                    // Simula resposta bem-sucedida
                    submitButton.innerHTML = '<i class="fas fa-check"></i> Concluído!';
                    submitButton.classList.remove('btn-primary', 'btn-success', 'btn-danger');
                    submitButton.classList.add('btn-success');
                    
                    // Fecha o modal após breve delay
                    setTimeout(() => {
                        // Restaura o botão para estado original
                        submitButton.innerHTML = originalButtonText;
                        submitButton.disabled = false;
                        
                        // Fecha o modal usando Bootstrap
                        $(modalElement).modal('hide');
                        
                        // Exibe notificação de sucesso temporária
                        showNotification('Operação realizada com sucesso!', 'success');
                        
                        // Simula atualização da tabela (em vez de recarregar a página)
                        simulateTableUpdate(formData.get('acao'));
                    }, 1000);
                }, 1500);
            });
        });
    }
    
    function showNotification(message, type = 'success') {
        // Cria elemento de notificação
        const notification = document.createElement('div');
        notification.className = `notification notification-${type} fade-in`;
        notification.innerHTML = `
            <div class="notification-icon">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            </div>
            <div class="notification-content">
                <p>${message}</p>
            </div>
            <button class="notification-close">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Adiciona ao DOM
        document.body.appendChild(notification);
        
        // Configura evento de fechar
        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.classList.add('fade-out');
            setTimeout(() => notification.remove(), 300);
        });
        
        // Auto-remove após 5 segundos
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
    
    function simulateTableUpdate(action) {
        // Simula atualização da tabela após operação CRUD
        // Em uma implementação real, isso faria uma nova consulta AJAX
        
        // Animação de atualização para toda a tabela
        table.style.opacity = '0.6';
        
        setTimeout(() => {
            table.style.opacity = '1';
            
            // Adiciona efeito de destaque em linhas novas/atualizadas
            if (action === 'cadastrarOrcamento') {
                // Simularia adicionar uma nova linha
                console.log('Simulando adição de nova linha');
            } else if (action === 'editarOrcamento') {
                // Simularia atualizar uma linha existente
                console.log('Simulando atualização de linha');
            } else if (action === 'excluirOrcamento') {
                // Simularia remover uma linha
                console.log('Simulando remoção de linha');
            }
            
            // Atualiza os contadores de resumo
            updateSummaryCards();
        }, 800);
    }
    
    function updateSummaryCards() {
        // Atualiza os cards de resumo com animação
        const summaryCards = document.querySelectorAll('.summary-card');
        
        summaryCards.forEach(card => {
            card.classList.add('pulse');
            setTimeout(() => {
                card.classList.remove('pulse');
            }, 1000);
        });
    }
    
    // --- ACESSIBILIDADE ---
    function enhanceAccessibility() {
        // Adiciona atributos ARIA e melhora navegação por teclado
        
        // Adiciona rótulos ARIA aos controles interativos
        const interactiveElements = document.querySelectorAll('button, a, input, select');
        interactiveElements.forEach(el => {
            if (!el.getAttribute('aria-label') && !el.getAttribute('aria-labelledby')) {
                // Adiciona descrição com base no conteúdo ou atributos existentes
                const text = el.textContent.trim() || el.getAttribute('title') || el.getAttribute('placeholder');
                if (text) {
                    el.setAttribute('aria-label', text);
                }
            }
        });
        
        // Melhora navegação da tabela para leitores de tela
        if (table) {
            // Adiciona cabeçalho com escopo para melhoria em leitores de tela
            const headerCells = table.querySelectorAll('thead th');
            headerCells.forEach(cell => {
                cell.setAttribute('scope', 'col');
            });
            
            // Marca a tabela como uma grade ARIA
            table.setAttribute('role', 'grid');
            table.setAttribute('aria-label', 'Lista de orçamentos');
            
            // Configura cada linha para navegação adequada
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                row.setAttribute('role', 'row');
                row.setAttribute('aria-rowindex', index + 1);
                
                // Configura células para acessibilidade
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, cellIndex) => {
                    cell.setAttribute('role', 'gridcell');
                    cell.setAttribute('aria-colindex', cellIndex + 1);
                });
            });
        }
        
        // Melhora acessibilidade dos modais
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.setAttribute('role', 'dialog');
            modal.setAttribute('aria-modal', 'true');
            
            const title = modal.querySelector('.modal-title');
            if (title) {
                const titleId = title.getAttribute('id') || `modal-title-${Math.random().toString(36).substr(2, 9)}`;
                title.setAttribute('id', titleId);
                modal.setAttribute('aria-labelledby', titleId);
            }
        });
    }
    
    // --- INICIALIZAÇÃO ---
    function initialize() {
        // Inicializa todos os componentes
        initializeFilters();
        initializeProgressBars();
        setupAjaxForms();
        enhanceAccessibility();
        
        // Adiciona listener para janela de tamanho
        window.addEventListener('resize', handleResponsiveLayout);
        handleResponsiveLayout();
    }
    
    function handleResponsiveLayout() {
        // Ajusta layout com base no tamanho da tela
        const isMobile = window.innerWidth < 768;
        
        // Ajusta tamanho das células da tabela em dispositivos móveis
        const tableCells = document.querySelectorAll('.transaction-table td, .transaction-table th');
        if (isMobile) {
            tableCells.forEach(cell => {
                cell.style.padding = '8px';
                cell.style.fontSize = '0.875rem';
            });
        } else {
            tableCells.forEach(cell => {
                cell.style.padding = '';
                cell.style.fontSize = '';
            });
        }
    }
    
    // Inicializa tudo quando o DOM estiver pronto
    initialize();
});