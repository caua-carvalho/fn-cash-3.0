// Transaction Modal JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Utility function to format currency
    const formatCurrency = (value) => {
      return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
      }).format(value);
    };
  
    // Utility function to format date
    const formatDateForDisplay = (dateStr) => {
      if (!dateStr) return '';
      const date = new Date(dateStr);
      return date.toLocaleDateString('pt-BR');
    };
  
    // Set today's date as default for new transactions
    const setDefaultDate = () => {
      const dateInputs = document.querySelectorAll('input[type="date"]');
      const today = new Date().toISOString().split('T')[0];
      dateInputs.forEach(input => {
        if (!input.value) {
          input.value = today;
        }
      });
    };
  
    /**
     * Atualiza o tipo de transação e a forma de pagamento conforme o tipo selecionado.
     * Gerado pelo Copilot
     */
    const setupTypeSelector = () => {
      const typeOptions = document.querySelectorAll('.type-option');
      
      typeOptions.forEach(option => {
        option.addEventListener('click', function() {
          const type = this.getAttribute('data-type');
          const form = this.closest('form');
          
          // Atualiza visual
          typeOptions.forEach(opt => opt.classList.remove('active'));
          this.classList.add('active');
          
          // Atualiza o input hidden do tipo de transação
          const tipoInput = form.querySelector('input[name="tipoTransacao"]');
          if (tipoInput) {
            tipoInput.value = type;
          }

          // Atualiza o select de forma de pagamento conforme o tipo
          const formaPagamentoSelect = form.querySelector('select[name="formaPagamento"]');
          if (formaPagamentoSelect) {
            if (type === 'Despesa') {
              formaPagamentoSelect.value = 'debito';
            } else if (type === 'Receita') {
              formaPagamentoSelect.value = 'dinheiro';
            } else if (type === 'Transferência') {
              formaPagamentoSelect.value = 'transferencia';
              formaPagamentoSelect.required = false; // Não precisa de forma de pagamento em transferência
            }
            // Dispara o evento change pra garantir atualização visual
            formaPagamentoSelect.dispatchEvent(new Event('change'));
          }

          // Mostra/esconde campos conforme o tipo
          if (type === 'Transferência') {
            form.classList.add('transfer');
            // Remove forma de pagamento pra transferência
            const formaPagamentoSelect = form.querySelector('select[name="formaPagamento"]');
            if (formaPagamentoSelect) {
                formaPagamentoSelect.value = 'transferencia';
                formaPagamentoSelect.required = false; // Não precisa de forma de pagamento em transferência
            }
            // Garante que contaDestinataria está required
            const contaDestinataria = form.querySelector('[name="contaDestinataria"]');
            if (contaDestinataria) {
                contaDestinataria.required = true;
                form.querySelector('.transfer-only').style.display = 'block';
            }
          } else {
            form.classList.remove('transfer');
            // Restaura required da forma de pagamento
            const formaPagamentoSelect = form.querySelector('select[name="formaPagamento"]');
            if (formaPagamentoSelect) {
                formaPagamentoSelect.required = true;
            }
            // Remove required do contaDestinataria
            const contaDestinataria = form.querySelector('[name="contaDestinataria"]');
            if (contaDestinataria) {
                contaDestinataria.required = false;
                contaDestinataria.setCustomValidity('');
                contaDestinataria.value = '';
                form.querySelector('.transfer-only').style.display = 'none';
            }
          }

          // Animação
          animateFields();
        });
      });
      
      // Estado inicial
      document.querySelectorAll('form').forEach(form => {
        const tipoInput = form.querySelector('input[name="tipoTransacao"]');
        if (tipoInput) {
          const type = tipoInput.value;
          const option = form.querySelector(`.type-option[data-type="${type}"]`);
          if (option) {
            option.classList.add('active');
            // Garante que os campos certos aparecem no load
            if (type === 'Transferência') {
              form.classList.add('transfer');
            } else {
              form.classList.remove('transfer');
            }
            // Atualiza forma de pagamento no load também
            const formaPagamentoSelect = form.querySelector('select[name="formaPagamento"]');
            if (formaPagamentoSelect) {
              if (type === 'Despesa') {
                formaPagamentoSelect.value = 'debito';
              } else if (type === 'Receita') {
                formaPagamentoSelect.value = 'dinheiro';
              } else if (type === 'Transferência') {
                formaPagamentoSelect.value = 'transferencia';
              }
              formaPagamentoSelect.dispatchEvent(new Event('change'));
            }
            // Garante required correto no load
            const contaDestinataria = form.querySelector('[name="contaDestinataria"]');
            if (contaDestinataria) {
              contaDestinataria.required = (type === 'Transferência');
            }
          }
        }
      });
    };
  
    /**
     * Atualiza o status da transação no input hidden ao clicar no botão de status.
     * Gerado pelo Copilot
     */
    const setupStatusSelector = () => {
      const statusOptions = document.querySelectorAll('.status-option');
      
      statusOptions.forEach(option => {
        option.addEventListener('click', function() {
          const status = this.getAttribute('data-status');
          const form = this.closest('form');
          
          // Atualiza visual
          form.querySelectorAll('.status-option').forEach(opt => opt.classList.remove('active'));
          this.classList.add('active');
          
          // Atualiza o input hidden do status
          const statusInput = form.querySelector('input[name="statusTransacao"]');
          if (statusInput) {
            statusInput.value = status;
          }
        });
      });

      // Estado inicial
      document.querySelectorAll('form').forEach(form => {
        const statusInput = form.querySelector('input[name="statusTransacao"]');
        if (statusInput) {
          const status = statusInput.value;
          const option = form.querySelector(`.status-option[data-status="${status}"]`);
          if (option) {
            option.classList.add('active');
          }
        }
      });
    };
  
    // Create floating labels effect
    const setupFloatingLabels = () => {
      const formControls = document.querySelectorAll('.form-control');
      
      formControls.forEach(input => {
        const updateLabelState = () => {
          if (input.value) {
            input.classList.add('has-value');
          } else {
            input.classList.remove('has-value');
          }
        };
        
        // Initial state
        updateLabelState();
        
        // Event listeners
        input.addEventListener('focus', updateLabelState);
        input.addEventListener('blur', updateLabelState);
        input.addEventListener('change', updateLabelState);
      });
    };
  
    // Add fade in animation
    const animateFields = () => {
      const fields = document.querySelectorAll('.form-group');
      fields.forEach((field, index) => {
        field.style.animation = 'none';
        setTimeout(() => {
          field.style.animation = `fadeIn 0.3s ease forwards ${index * 0.05}s`;
        }, 10);
      });
    };
  
    // Handle edit transaction modal
    const setupEditModal = () => {
      const editButtons = document.querySelectorAll('[data-target="#editarTransacaoModal"]');
      
      editButtons.forEach(button => {
        button.addEventListener('click', function() {
          const id = this.getAttribute('data-id');
          const titulo = this.getAttribute('data-titulo');
          const descricao = this.getAttribute('data-descricao');
          const valor = this.getAttribute('data-valor');
          const data = this.getAttribute('data-data');
          const tipo = this.getAttribute('data-tipo');
          const status = this.getAttribute('data-status');
          const contaRemetente = this.getAttribute('data-conta-remetente');
          const contaDestinataria = this.getAttribute('data-conta-destinataria');
          const categoria = this.getAttribute('data-categoria');
          const formaPagamento = this.getAttribute('data-forma-pagamento');

          // Basic fields
          document.getElementById('editarTransacaoId').value = id;
          document.getElementById('editarTituloTransacao').value = titulo;
          document.getElementById('editarDescricaoTransacao').value = descricao;
          document.getElementById('editarValorTransacao').value = valor;
          document.getElementById('editarDataTransacao').value = data;

          // Trigger UI updates first so default handlers don't overwrite values
          const typeOption = document.querySelector(`#editarTransacaoModal .type-option[data-type="${tipo}"]`);
          if (typeOption) {
            typeOption.click();
          }

          const statusOption = document.querySelector(`#editarTransacaoModal .status-option[data-status="${status}"]`);
          if (statusOption) {
            statusOption.click();
          }

          // Now set values that may be reset by the click handlers
          document.getElementById('editarTipoTransacao').value = tipo;
          document.getElementById('editarStatusTransacao').value = status;

          if (document.getElementById('editarContaRemetente')) {
            document.getElementById('editarContaRemetente').value = contaRemetente;
          }

          if (document.getElementById('editarContaDestinataria')) {
            document.getElementById('editarContaDestinataria').value = contaDestinataria;
          }

          if (document.getElementById('editarCategoriaTransacao')) {
            document.getElementById('editarCategoriaTransacao').value = categoria;
          }

          if (document.getElementById('editarFormaPagamento')) {
            document.getElementById('editarFormaPagamento').value = formaPagamento;
          }

          // Update floating labels after setting values
          setupFloatingLabels();

          // Entrance animation
          animateFields();
        });
      });
    };
  
    // Handle delete transaction modal
    const setupDeleteModal = () => {
      const deleteButtons = document.querySelectorAll('[data-target="#excluirTransacaoModal"]');
      
      deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
          const id = this.getAttribute('data-id');
          const titulo = this.getAttribute('data-titulo');
          
          document.getElementById('excluirTransacaoId').value = id;
          document.getElementById('transacaoTituloExcluir').textContent = titulo;
        });
      });
    };
  
    // Initialize card flip effect for the modal tabs
    const setupModalTabs = () => {
      const tabButtons = document.querySelectorAll('.tab-btn');
      
      tabButtons.forEach(button => {
        button.addEventListener('click', function() {
          const tabType = this.getAttribute('data-tab');
          const modal = this.closest('.modal');
          
          // Update tab buttons
          modal.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
          });
          this.classList.add('active');
          
          // Update content visibility
          modal.querySelectorAll('.tab-content').forEach(content => {
            content.style.display = 'none';
          });
          modal.querySelector(`.tab-content[data-tab="${tabType}"]`).style.display = 'block';
          
          // Add animation
          animateFields();
        });
      });
      
      // Activate first tab by default
      document.querySelectorAll('.modal').forEach(modal => {
        const firstTab = modal.querySelector('.tab-btn');
        if (firstTab) {
          firstTab.click();
        }
      });
    };

    // Validate required fields before leaving the "Dados Básicos" tab
    const setupTabValidation = () => {
      const tabButtons = document.querySelectorAll('.tab-btn');

      tabButtons.forEach(button => {
        button.addEventListener('click', function(event) {
          const currentTabType = document.querySelector('.tab-btn.active').getAttribute('data-tab');
          const targetTabType = this.getAttribute('data-tab');
          const modal = this.closest('.modal');
          const form = modal.querySelector('form');

          if (currentTabType === 'basic' && targetTabType === 'details') {
            const basicFields = form.querySelectorAll('.tab-content[data-tab="basic"] [required]');
            let hasInvalid = false;

            basicFields.forEach(field => {
              if (!field.checkValidity()) {
                field.closest('.form-group').classList.add('shake');
                hasInvalid = true;
                setTimeout(() => {
                  field.closest('.form-group').classList.remove('shake');
                }, 600);
              }
            });

            if (hasInvalid) {
              event.preventDefault();
              event.stopPropagation();
              const firstInvalidField = form.querySelector('.tab-content[data-tab="basic"] :invalid');
              if (firstInvalidField) {
                setTimeout(() => {
                  firstInvalidField.focus();
                }, 100);
              }
              return false;
            }
          }
        });
      });
    };

    // Warn if transfer uses same source and destination accounts
    const setupTransferAccountCheck = () => {
      document.querySelectorAll('form').forEach(form => {
        const tipoInput = form.querySelector('input[name="tipoTransacao"]');
        const contaRem = form.querySelector('[name="contaRemetente"]');
        const contaDest = form.querySelector('[name="contaDestinataria"]');

        if (!contaRem || !contaDest) return;

        const validateAccounts = () => {
          const tipo = tipoInput ? tipoInput.value : '';
          if (tipo === 'Transferência' && contaRem.value && contaDest.value && contaRem.value === contaDest.value) {
            const msg = 'Conta de origem e destino devem ser diferentes.';
            contaRem.setCustomValidity(msg);
            contaDest.setCustomValidity(msg);
            if (typeof _exibeToast === 'function') {
              _exibeToast(msg, 'danger', 4000);
            }
            contaDest.reportValidity();
          } else {
            contaRem.setCustomValidity('');
            contaDest.setCustomValidity('');
          }
        };

        contaRem.addEventListener('change', validateAccounts);
        contaDest.addEventListener('change', validateAccounts);
      });
    };
  
    // Initialize modals when opened
    const initializeModalOpening = () => {
      const modals = document.querySelectorAll('.modal');
      
      modals.forEach(modal => {
        modal.addEventListener('show.bs.modal', function() {
          // Reset form if it's the create modal
          if (this.id === 'transacaoModal') {
            this.querySelector('form').reset();
            setDefaultDate();
            
            // Set default type to Expense
            const expenseOption = this.querySelector('.type-option[data-type="Despesa"]');
            if (expenseOption) {
              expenseOption.click();
            }
            
            // Set default status to Pending
            const pendingOption = this.querySelector('.status-option[data-status="Pendente"]');
            if (pendingOption) {
              pendingOption.click();
            }
          }
          
          // Add entrance animation
          animateFields();
        });
      });
    };
  
    // Initialize form validation
    const setupFormValidation = () => {
      const forms = document.querySelectorAll('form');

      forms.forEach(form => {
        form.addEventListener('submit', function(event) {
          const tipo = form.querySelector('input[name="tipoTransacao"]')?.value;
          const contaRem = form.querySelector('[name="contaRemetente"]');
          const contaDest = form.querySelector('[name="contaDestinataria"]');

          // Custom check for transfer accounts being different
          if (tipo === 'Transferência' && contaRem && contaDest && contaRem.value === contaDest.value) {
            event.preventDefault();
            event.stopPropagation();
            const msg = 'Conta de origem e destino devem ser diferentes.';
            contaRem.setCustomValidity(msg);
            contaDest.setCustomValidity(msg);

            [contaRem, contaDest].forEach(field => {
              field.closest('.form-group').classList.add('shake');
              setTimeout(() => field.closest('.form-group').classList.remove('shake'), 600);
            });

            // Ensure details tab is visible
            const tabButton = form.closest('.modal').querySelector('.tab-btn[data-tab="details"]');
            if (tabButton) tabButton.click();

            contaDest.focus();
            return;
          } else {
            if (contaRem) contaRem.setCustomValidity('');
            if (contaDest) contaDest.setCustomValidity('');
          }

          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();

            const basicTabHasInvalidFields = !!form.querySelector('.tab-content[data-tab="basic"] :invalid');
            const detailsTabHasInvalidFields = !!form.querySelector('.tab-content[data-tab="details"] :invalid');
            let tabToShow = 'basic';
            if (!basicTabHasInvalidFields && detailsTabHasInvalidFields) {
              tabToShow = 'details';
            }

            const tabBtn = form.closest('.modal').querySelector(`.tab-btn[data-tab="${tabToShow}"]`);
            if (tabBtn) tabBtn.click();

            const invalidFields = form.querySelectorAll(':invalid');
            invalidFields.forEach(field => {
              field.closest('.form-group').classList.add('shake');
              setTimeout(() => field.closest('.form-group').classList.remove('shake'), 600);
            });

            setTimeout(() => {
              if (invalidFields[0]) invalidFields[0].focus();
            }, 100);
          }

          form.classList.add('was-validated');
        });
      });
    };
  
    // Add responsive behavior
    const setupResponsiveness = () => {
      const adjustModalSize = () => {
        const isMobile = window.innerWidth < 768;
        const modals = document.querySelectorAll('.modal-dialog');
        
        modals.forEach(modal => {
          if (isMobile) {
            modal.classList.add('modal-fullscreen-sm-down');
          } else {
            modal.classList.remove('modal-fullscreen-sm-down');
          }
        });
      };
      
      // Initial check
      adjustModalSize();
      
      // Listen for window resize
      window.addEventListener('resize', adjustModalSize);
    };
  
    // Initialize all features
    const initializeAll = () => {
      setDefaultDate();
      setupTypeSelector();
      setupStatusSelector();
      setupFloatingLabels();
      setupEditModal();
      setupDeleteModal();
      setupModalTabs();
      setupTabValidation();
      setupTransferAccountCheck();
      initializeModalOpening();
      setupFormValidation();
      setupResponsiveness();
    };
  
    // Run initialization
    initializeAll();
  });