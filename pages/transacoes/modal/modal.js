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
  
    // Handle transaction type selection
    const setupTypeSelector = () => {
      const typeOptions = document.querySelectorAll('.type-option');
      const typeInputs = document.querySelectorAll('select[name="tipoTransacao"]');
      
      typeOptions.forEach(option => {
        option.addEventListener('click', function() {
          const type = this.getAttribute('data-type');
          
          // Update visual state
          typeOptions.forEach(opt => opt.classList.remove('active'));
          this.classList.add('active');
          
          // Update form state
          typeInputs.forEach(input => {
            input.value = type;
            
            // Show/hide relevant fields
            const form = input.closest('form');
            if (type === 'Transferência') {
              form.classList.add('transfer');
            } else {
              form.classList.remove('transfer');
            }
            
            // Set payment method defaults based on type
            const paymentMethod = form.querySelector('select[name="formaPagamento"]');
            if (paymentMethod) {
              if (type === 'Despesa') {
                paymentMethod.value = 'debito';
              } else if (type === 'Receita') {
                paymentMethod.value = 'dinheiro';
              } else {
                paymentMethod.value = 'transferencia';
              }
            }
          });
          
          // Animate the change
          animateFields();
        });
      });
      
      // Set initial state
      typeInputs.forEach(input => {
        const type = input.value;
        const form = input.closest('form');
        const option = form.querySelector(`.type-option[data-type="${type}"]`);
        
        if (option) {
          option.click();
        }
      });
    };
  
    // Handle status selection
    const setupStatusSelector = () => {
      const statusOptions = document.querySelectorAll('.status-option');
      const statusInputs = document.querySelectorAll('select[name="statusTransacao"]');
      
      statusOptions.forEach(option => {
        option.addEventListener('click', function() {
          const status = this.getAttribute('data-status');
          const form = this.closest('form');
          
          // Update visual state
          form.querySelectorAll('.status-option').forEach(opt => opt.classList.remove('active'));
          this.classList.add('active');
          
          // Update form state
          form.querySelector('select[name="statusTransacao"]').value = status;
        });
      });
      
      // Set initial state
      statusInputs.forEach(input => {
        const status = input.value;
        const form = input.closest('form');
        const option = form.querySelector(`.status-option[data-status="${status}"]`);
        
        if (option) {
          option.click();
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
          
          // Fill form fields
          document.getElementById('editarTransacaoId').value = id;
          document.getElementById('editarTituloTransacao').value = titulo;
          document.getElementById('editarDescricaoTransacao').value = descricao;
          document.getElementById('editarValorTransacao').value = valor;
          document.getElementById('editarDataTransacao').value = data;
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
          
          // Trigger UI updates
          const typeOption = document.querySelector(`#editarTransacaoModal .type-option[data-type="${tipo}"]`);
          if (typeOption) {
            typeOption.click();
          }
          
          const statusOption = document.querySelector(`#editarTransacaoModal .status-option[data-status="${status}"]`);
          if (statusOption) {
            statusOption.click();
          }
          
          // Update all input states for floating labels
          setupFloatingLabels();
          
          // Add entrance animation
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
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            
            // Add visual feedback for invalid fields
            const invalidFields = form.querySelectorAll(':invalid');
            invalidFields.forEach(field => {
              field.closest('.form-group').classList.add('shake');
              setTimeout(() => {
                field.closest('.form-group').classList.remove('shake');
              }, 600);
            });
            
            // Focus on first invalid field
            invalidFields[0].focus();
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
      initializeModalOpening();
      setupFormValidation();
      setupResponsiveness();
    };
  
    // Run initialization
    initializeAll();
  });