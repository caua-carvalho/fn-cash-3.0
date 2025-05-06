// Contas Modal JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Utility function to format currency
    const formatCurrency = (value) => {
      return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
      }).format(value);
    };
  
    // Set default values for new accounts
    const setDefaultValues = () => {
      const today = new Date().toISOString().split('T')[0];
      
      // Default values for currency inputs
      document.querySelectorAll('input[type="number"]').forEach(input => {
        if (!input.value && input.id === 'saldoConta') {
          input.value = '0.00';
        }
      });
    };
  
    // Handle account type selection
    const setupTypeSelector = () => {
      const typeOptions = document.querySelectorAll('.type-option');
      
      typeOptions.forEach(option => {
        option.addEventListener('click', function() {
          const type = this.getAttribute('data-type');
          const form = this.closest('form');
          
          // Update visual state
          form.querySelectorAll('.type-option').forEach(opt => opt.classList.remove('active'));
          this.classList.add('active');
          
          // Update hidden input value
          if (form.querySelector('input[name="tipoConta"]')) {
            form.querySelector('input[name="tipoConta"]').value = type;
          }
          
          // Update dropdown as well if exists
          const tipoCompleto = form.querySelector('select[name="tipoContaCompleto"]');
          if (tipoCompleto) {
            tipoCompleto.value = type;
          }
          
          // Animate the change
          animateFields();
        });
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
  
    // Handle edit account modal
    const setupEditModal = () => {
      const editButtons = document.querySelectorAll('[data-target="#editarContaModal"]');
      
      editButtons.forEach(button => {
        button.addEventListener('click', function() {
          const id = this.getAttribute('data-id');
          const nome = this.getAttribute('data-nome');
          const tipo = this.getAttribute('data-tipo');
          const saldo = this.getAttribute('data-saldo');
          const instituicao = this.getAttribute('data-instituicao');
          
          // Fill form fields
          document.getElementById('editarContaId').value = id;
          document.getElementById('editarNomeConta').value = nome;
          document.getElementById('editarTipoConta').value = tipo;
          document.getElementById('editarSaldoConta').value = saldo;
          document.getElementById('editarInstituicaoConta').value = instituicao;
          
          if (document.getElementById('editarTipoContaCompleto')) {
            document.getElementById('editarTipoContaCompleto').value = tipo;
          }
          
          // Set active type
          const typeOption = document.querySelector(`#editarContaModal .type-option[data-type="${tipo}"]`);
          if (typeOption) {
            // Clear all active first
            document.querySelectorAll('#editarContaModal .type-option').forEach(opt => {
              opt.classList.remove('active');
            });
            typeOption.classList.add('active');
          } else {
            // If type is not in our simplified options, activate the first one and rely on dropdown
            document.querySelector('#editarContaModal .type-option').classList.add('active');
          }
          
          // Manually trigger floating labels
          document.querySelectorAll('#editarContaModal .form-control').forEach(input => {
            if (input.value) {
              input.classList.add('has-value');
            }
          });
          
          // Add entrance animation
          animateFields();
        });
      });
    };
  
    // Handle delete account modal
    const setupDeleteModal = () => {
      const deleteButtons = document.querySelectorAll('[data-target="#excluirContaModal"]');
      
      deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
          const id = this.getAttribute('data-id');
          document.getElementById('excluirContaId').value = id;
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
          if (this.id === 'contaModal') {
            this.querySelector('form').reset();
            setDefaultValues();
            
            // Set default type
            const corrente = this.querySelector('.type-option[data-type="Corrente"]');
            if (corrente) {
              // Clear all active first
              this.querySelectorAll('.type-option').forEach(opt => {
                opt.classList.remove('active');
              });
              corrente.classList.add('active');
              this.querySelector('input[name="tipoConta"]').value = 'Corrente';
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
      setDefaultValues();
      setupTypeSelector();
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