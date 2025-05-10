// JavaScript simplificado para o filtro de período
document.addEventListener('DOMContentLoaded', function() {
    // Código dos gráficos existentes permanece...
    
    // Filtro de Período - Funcionalidade
    const togglePeriodFilter = document.getElementById('togglePeriodFilter');
    const periodFilterContent = document.getElementById('periodFilterContent');
    const statusOptions = document.querySelectorAll('.status-option');
    const periodSelection = document.getElementById('periodSelection');
    const customPeriodSection = document.getElementById('customPeriodSection');
    const clearPeriodFilter = document.getElementById('clearPeriodFilter');
    const applyPeriodFilter = document.getElementById('applyPeriodFilter');
    
    // Toggle do filtro de período com animação suave
    togglePeriodFilter.addEventListener('click', function() {
        const isVisible = periodFilterContent.style.display !== 'none';
        
        // Animar o ícone de chevron
        const chevron = this.querySelector('i');
        chevron.style.transform = isVisible ? 'rotate(0deg)' : 'rotate(180deg)';
        
        // Toggle da exibição com animação
        if (isVisible) {
            periodFilterContent.classList.add('fade-out');
            setTimeout(() => {
                periodFilterContent.style.display = 'none';
                periodFilterContent.classList.remove('fade-out');
            }, 300);
        } else {
            periodFilterContent.style.display = 'block';
            periodFilterContent.classList.add('fade-in');
            setTimeout(() => {
                periodFilterContent.classList.remove('fade-in');
            }, 300);
        }
    });
    
    // Seleção de período (Pills)
    statusOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remover classe active de todas as opções
            statusOptions.forEach(opt => opt.classList.remove('active'));
            
            // Adicionar classe active à opção selecionada
            this.classList.add('active');
            
            // Atualizar o valor hidden
            const periodType = this.getAttribute('data-period');
            periodSelection.value = periodType;
            
            // Mostrar/ocultar seção de período personalizado
            customPeriodSection.style.display = periodType === 'custom' ? 'block' : 'none';
            
            // Se o período for personalizado e a seção estiver visível, animar entrada
            if (periodType === 'custom') {
                customPeriodSection.classList.add('fade-in-up');
                setTimeout(() => {
                    customPeriodSection.classList.remove('fade-in-up');
                }, 500);
            }
        });
    });
    
    // Limpar filtros
    clearPeriodFilter.addEventListener('click', function() {
        // Resetar seleção de período
        statusOptions.forEach(opt => {
            if (opt.getAttribute('data-period') === 'current-month') {
                opt.classList.add('active');
            } else {
                opt.classList.remove('active');
            }
        });
        periodSelection.value = 'current-month';
        
        // Ocultar período personalizado
        customPeriodSection.style.display = 'none';
        
        // Limpar datas
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
        
        // Feedback visual
        this.classList.add('pulse');
        setTimeout(() => {
            this.classList.remove('pulse');
        }, 500);
    });
    
    // Aplicar filtros
    applyPeriodFilter.addEventListener('click', function() {
        // Preparar notificação elegante
        const notification = document.createElement('div');
        notification.classList.add('notification', 'scale-on-load');
        notification.style.position = 'fixed';
        notification.style.bottom = '20px';
        notification.style.right = '20px';
        notification.style.backgroundColor = 'var(--color-primary-500)';
        notification.style.color = '#fff';
        notification.style.padding = '15px 20px';
        notification.style.borderRadius = 'var(--border-radius-md)';
        notification.style.boxShadow = 'var(--shadow-lg)';
        notification.style.zIndex = '9999';
        notification.style.maxWidth = '350px';
        
        // Preparar conteúdo da notificação
        const periodType = periodSelection.value;
        let periodText = '';
        
        switch(periodType) {
            case 'current-month':
                periodText = 'Mês Atual';
                break;
            case 'last-month':
                periodText = 'Mês Anterior';
                break;
            case 'current-year':
                periodText = 'Ano Atual';
                break;
            case 'custom':
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                periodText = `Período personalizado: ${startDate} até ${endDate}`;
                break;
        }
        
        // Criar conteúdo HTML da notificação
        notification.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle me-2" style="font-size: 20px;"></i>
                    <div>
                        <h5 style="margin: 0; font-weight: 600;">Filtros Aplicados</h5>
                        <p style="margin: 5px 0 0 0; font-size: 14px;">${periodText}</p>
                    </div>
                </div>
                <button style="background: none; border: none; color: white; cursor: pointer;" onclick="this.parentNode.parentNode.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        // Adicionar ao corpo da página
        document.body.appendChild(notification);
        
        // Remover após 4 segundos
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 4000);
        
        // Aqui seria implementada a lógica real para atualizar os gráficos e dados
        
        // Feedback visual de loading
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Aplicando...';
        this.disabled = true;
        
        // Simular o tempo de processamento
        setTimeout(() => {
            this.innerHTML = originalText;
            this.disabled = false;
            
            // Atualizar todas as seções do dashboard (simulação)
            document.querySelectorAll('.summary-card').forEach(card => {
                card.classList.add('pulse');
                setTimeout(() => card.classList.remove('pulse'), 500);
            });
            
            // Fechar o filtro após aplicar
            togglePeriodFilter.click();
        }, 1200);
    });
    
    // Inicializar com datas default para o período personalizado
    const today = new Date();
    const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    
    // Formatar as datas para o formato YYYY-MM-DD
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };
    
    document.getElementById('startDate').value = formatDate(firstDayOfMonth);
    document.getElementById('endDate').value = formatDate(today);
});