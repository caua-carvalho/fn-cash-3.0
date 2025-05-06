<?php
require 'contas/script.php';
?>

<!-- Link para FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Estilo personalizado para os modais -->
<style>
/* Contas Modal Styles - Based on transaction modal design */
@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

/* Utilize as variáveis CSS do sistema de design FNCASH */
.modal-content {
  background-color: var(--color-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--border-radius-lg);
  box-shadow: var(--shadow-lg);
  color: var(--color-text);
  font-family: var(--font-family-base);
  overflow: hidden;
}

.modal-header {
  background: linear-gradient(135deg, var(--color-primary-500), var(--color-primary-700));
  border-bottom: none;
  padding: var(--spacing-4);
  position: relative;
}

.modal-header .close {
  color: #fff;
  opacity: 1;
  position: absolute;
  right: var(--spacing-4);
  top: var(--spacing-4);
  transition: transform var(--transition-duration-fast) var(--transition-timing);
}

.modal-header .close:hover {
  transform: rotate(90deg);
}

.modal-title {
  font-size: var(--font-size-xl);
  font-weight: var(--font-weight-semibold);
  margin: 0;
  text-align: center;
  width: 100%;
  color: #fff;
}

.modal-body {
  padding: var(--spacing-6);
}

.modal-footer {
  border-top: 1px solid var(--color-border);
  justify-content: center;
  padding: var(--spacing-4);
  gap: var(--spacing-4);
}

/* Form inputs with floating labels */
.form-group {
  position: relative;
  margin-bottom: var(--spacing-5);
}

.form-control {
  background-color: var(--color-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--border-radius-md);
  color: var(--color-text);
  font-size: var(--font-size-base);
  height: auto;
  padding: var(--spacing-3) var(--spacing-4);
  transition: all var(--transition-duration-normal) var(--transition-timing);
}

.form-control:focus {
  background-color: var(--color-surface);
  border-color: var(--color-primary-500);
  box-shadow: 0 0 0 3px rgba(7, 163, 98, 0.25);
  color: var(--color-text);
}

.form-group label {
  background-color: var(--color-surface);
  color: var(--color-text-muted);
  font-size: var(--font-size-base);
  left: var(--spacing-3);
  padding: 0 var(--spacing-1);
  pointer-events: none;
  position: absolute;
  top: var(--spacing-3);
  transition: var(--transition-duration-fast) all var(--transition-timing);
}

.form-control:focus ~ label,
.form-control:not(:placeholder-shown) ~ label,
.form-control.has-value ~ label {
  background-color: var(--color-surface);
  color: var(--color-primary-500);
  font-size: var(--font-size-sm);
  left: var(--spacing-2);
  top: -0.5rem;
  font-weight: var(--font-weight-medium);
}

/* Buttons using FNCASH variables */
.btn {
  border-radius: var(--border-radius-pill);
  font-size: var(--font-size-base);
  font-weight: var(--font-weight-medium);
  padding: var(--spacing-3) var(--spacing-5);
  transition: all var(--transition-duration-fast) var(--transition-timing);
}

.btn-primary {
  background-color: var(--color-primary-500);
  border: none;
  color: #fff;
}

.btn-primary:hover {
  background-color: var(--color-primary-600);
}

.btn-secondary {
  background-color: transparent;
  border: 1px solid var(--color-border);
  color: var(--color-text);
}

.btn-secondary:hover {
  background-color: var(--color-background-alt);
}

.btn-danger {
  background-color: var(--color-danger);
  border: none;
  color: #fff;
}

.btn-danger:hover {
  background-color: #c0392b;
}

.btn-success {
  background-color: var(--color-success);
  border: none;
  color: #fff;
}

.btn-success:hover {
  background-color: var(--color-primary-600);
}

/* Tab container styling */
.tab-container {
  border-bottom: 1px solid var(--color-border);
  display: flex;
  justify-content: center;
  margin-bottom: var(--spacing-6);
  padding-bottom: var(--spacing-4);
}

.tab-btn {
  background: transparent;
  border: none;
  color: var(--color-text-muted);
  cursor: pointer;
  font-size: var(--font-size-base);
  margin: 0 var(--spacing-4);
  padding: var(--spacing-2) var(--spacing-4);
  position: relative;
  transition: all var(--transition-duration-normal) var(--transition-timing);
}

.tab-btn:hover {
  color: var(--color-text);
}

.tab-btn.active {
  color: var(--color-primary-500);
}

.tab-btn.active::after {
  background-color: var(--color-primary-500);
  bottom: calc(-1 * var(--spacing-4) - 1px);
  content: '';
  height: 3px;
  left: 0;
  position: absolute;
  width: 100%;
}

/* Custom Account Type Selector */
.type-selector {
  display: flex;
  justify-content: space-between;
  margin-bottom: var(--spacing-5);
}

.type-option {
  align-items: center;
  background-color: var(--color-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--border-radius-md);
  cursor: pointer;
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: var(--spacing-4);
  transition: all var(--transition-duration-normal) var(--transition-timing);
  width: 32%;
}

.type-option:hover {
  border-color: var(--color-primary-500);
}

.type-option.active {
  background-color: rgba(7, 163, 98, 0.1);
  border-color: var(--color-primary-500);
}

.dark-theme .type-option.active {
  background-color: rgba(7, 163, 98, 0.2);
}

.type-icon {
  color: var(--color-text-muted);
  font-size: var(--font-size-lg);
  margin-bottom: var(--spacing-2);
  transition: all var(--transition-duration-normal) var(--transition-timing);
}

.type-option.active .type-icon {
  color: var(--color-primary-500);
}

.type-name {
  color: var(--color-text-muted);
  font-size: var(--font-size-sm);
  transition: all var(--transition-duration-normal) var(--transition-timing);
}

.type-option.active .type-name {
  color: var(--color-text);
  font-weight: var(--font-weight-semibold);
}

/* Animations */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.modal.fade .modal-dialog {
  transform: scale(0.9);
  transition: transform var(--transition-duration-normal) var(--transition-timing);
}

.modal.show .modal-dialog {
  transform: scale(1);
}

/* Shake animation for validation errors */
@keyframes shake {
  0%, 100% { transform: translateX(0); }
  20%, 60% { transform: translateX(-5px); }
  40%, 80% { transform: translateX(5px); }
}

.shake {
  animation: shake 0.6s var(--transition-timing);
}

/* Value input with currency prefix */
.value-container {
  position: relative;
}

.value-container::before {
  color: var(--color-text-muted);
  content: 'R$';
  font-size: var(--font-size-base);
  left: var(--spacing-3);
  position: absolute;
  top: var(--spacing-3);
}

.value-container input {
  padding-left: var(--spacing-6);
}

/* Delete modal styling */
#excluirContaModal .modal-content {
  animation: shake 0.5s ease;
}

#excluirContaModal .fa-exclamation-triangle {
  color: var(--color-warning);
}

/* Form validation styling */
.was-validated .form-control:valid,
.form-control.is-valid {
  border-color: var(--color-success);
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2307A362' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='20 6 9 17 4 12'%3E%3C/polyline%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right var(--spacing-3) center;
  background-size: 16px;
}

.was-validated .form-control:invalid,
.form-control.is-invalid {
  border-color: var(--color-danger);
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23e74c3c' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cline x1='18' y1='6' x2='6' y2='18'%3E%3C/line%3E%3Cline x1='6' y1='6' x2='18' y2='18'%3E%3C/line%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right var(--spacing-3) center;
  background-size: 16px;
}

.invalid-feedback {
  display: none;
  width: 100%;
  margin-top: var(--spacing-1);
  font-size: var(--font-size-sm);
  color: var(--color-danger);
}

.was-validated .form-control:invalid ~ .invalid-feedback,
.form-control.is-invalid ~ .invalid-feedback {
  display: block;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .modal-body {
    padding: var(--spacing-4);
  }
  
  .type-option {
    padding: var(--spacing-3);
  }
  
  .btn {
    padding: var(--spacing-2) var(--spacing-4);
  }
  
  .tab-btn {
    margin: 0 var(--spacing-2);
    padding: var(--spacing-2);
  }
}

/* Dark mode support */
.dark-theme .modal-content {
  background-color: var(--color-surface);
  border-color: var(--color-border);
}

.dark-theme .form-control {
  background-color: var(--color-surface);
  color: var(--color-text);
  border-color: var(--color-border);
}

.dark-theme .form-control:focus ~ label,
.dark-theme .form-control:not(:placeholder-shown) ~ label,
.dark-theme .form-control.has-value ~ label {
  background-color: var(--color-surface);
}

.dark-theme .form-group label {
  background-color: var(--color-surface);
}

/* Animation for tab content */
.tab-content {
  display: none;
}

.tab-content.active {
  display: block;
  animation: fadeIn var(--transition-duration-normal) var(--transition-timing);
}
</style>

<!-- Nova Conta Modal -->
<div class="modal fade" id="contaModal" tabindex="-1" aria-labelledby="contaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contaModalLabel">Nova Conta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="tab-container">
                <button type="button" class="tab-btn active" data-tab="basic">Dados Básicos</button>
                <button type="button" class="tab-btn" data-tab="details">Detalhes</button>
            </div>

            <form action="contas.php" method="POST" class="needs-validation" novalidate>
                <input type="hidden" name="acao" value="cadastrarConta">
                
                <div class="modal-body">
                    <div class="tab-content" data-tab="basic">
                        <!-- Tipo de Conta -->
                        <h6 class="mb-3">Tipo de Conta</h6>
                        <div class="type-selector mb-4">
                            <div class="type-option active" data-type="Corrente">
                                <i class="fas fa-wallet type-icon"></i>
                                <span class="type-name">Corrente</span>
                            </div>
                            <div class="type-option" data-type="Poupança">
                                <i class="fas fa-piggy-bank type-icon"></i>
                                <span class="type-name">Poupança</span>
                            </div>
                            <div class="type-option" data-type="Investimento">
                                <i class="fas fa-chart-line type-icon"></i>
                                <span class="type-name">Investimento</span>
                            </div>
                        </div>
                        <input type="hidden" name="tipoConta" id="tipoConta" value="Corrente">

                        <!-- Nome da Conta -->
                        <div class="form-group">
                            <input type="text" class="form-control" id="nomeConta" name="nomeConta" placeholder=" " required>
                            <label for="nomeConta">Nome da Conta</label>
                        </div>

                        <!-- Saldo Inicial -->
                        <div class="form-group value-container">
                            <input type="number" class="form-control" id="saldoConta" name="saldoConta" step="0.01" placeholder=" " required>
                            <label for="saldoConta">Saldo Inicial</label>
                        </div>
                    </div>

                    <div class="tab-content" data-tab="details" style="display: none;">
                        <!-- Instituição Financeira -->
                        <div class="form-group">
                            <input type="text" class="form-control" id="instituicaoConta" name="instituicaoConta" placeholder=" " required>
                            <label for="instituicaoConta">Instituição Financeira</label>
                        </div>

                        <!-- Tipo Adicional (dropdown completo) -->
                        <div class="form-group">
                            <select class="form-control" id="tipoContaCompleto" name="tipoContaCompleto">
                                <option value="" disabled selected></option>
                                <option value="Corrente">Corrente</option>
                                <option value="Poupança">Poupança</option>
                                <option value="Cartão de Crédito">Cartão de Crédito</option>
                                <option value="VR/VA">VR/VA</option>
                                <option value="Investimento">Investimento</option>
                                <option value="Dinheiro">Dinheiro</option>
                                <option value="Outros">Outros</option>
                            </select>
                            <label for="tipoContaCompleto">Tipo Específico (opcional)</label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Editar Conta Modal -->
<div class="modal fade" id="editarContaModal" tabindex="-1" aria-labelledby="editarContaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarContaModalLabel">Editar Conta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="tab-container">
                <button type="button" class="tab-btn active" data-tab="basic">Dados Básicos</button>
                <button type="button" class="tab-btn" data-tab="details">Detalhes</button>
            </div>

            <form action="contas.php" method="POST" class="needs-validation" novalidate>
                <input type="hidden" name="acao" value="editarConta">
                <input type="hidden" id="editarContaId" name="contaId">
                
                <div class="modal-body">
                    <div class="tab-content" data-tab="basic">
                        <!-- Tipo de Conta -->
                        <h6 class="mb-3">Tipo de Conta</h6>
                        <div class="type-selector mb-4">
                            <div class="type-option" data-type="Corrente">
                                <i class="fas fa-wallet type-icon"></i>
                                <span class="type-name">Corrente</span>
                            </div>
                            <div class="type-option" data-type="Poupança">
                                <i class="fas fa-piggy-bank type-icon"></i>
                                <span class="type-name">Poupança</span>
                            </div>
                            <div class="type-option" data-type="Investimento">
                                <i class="fas fa-chart-line type-icon"></i>
                                <span class="type-name">Investimento</span>
                            </div>
                        </div>
                        <input type="hidden" name="tipoConta" id="editarTipoConta" value="Corrente">

                        <!-- Nome da Conta -->
                        <div class="form-group">
                            <input type="text" class="form-control" id="editarNomeConta" name="nomeConta" placeholder=" " required>
                            <label for="editarNomeConta">Nome da Conta</label>
                        </div>

                        <!-- Saldo -->
                        <div class="form-group value-container">
                            <input type="number" class="form-control" id="editarSaldoConta" name="saldoConta" step="0.01" placeholder=" " required>
                            <label for="editarSaldoConta">Saldo</label>
                        </div>
                    </div>

                    <div class="tab-content" data-tab="details" style="display: none;">
                        <!-- Instituição Financeira -->
                        <div class="form-group">
                            <input type="text" class="form-control" id="editarInstituicaoConta" name="instituicaoConta" placeholder=" " required>
                            <label for="editarInstituicaoConta">Instituição Financeira</label>
                        </div>

                        <!-- Tipo Adicional (dropdown completo) -->
                        <div class="form-group">
                            <select class="form-control" id="editarTipoContaCompleto" name="tipoContaCompleto">
                                <option value="" disabled selected></option>
                                <option value="Corrente">Corrente</option>
                                <option value="Poupança">Poupança</option>
                                <option value="Cartão de Crédito">Cartão de Crédito</option>
                                <option value="VR/VA">VR/VA</option>
                                <option value="Investimento">Investimento</option>
                                <option value="Dinheiro">Dinheiro</option>
                                <option value="Outros">Outros</option>
                            </select>
                            <label for="editarTipoContaCompleto">Tipo Específico (opcional)</label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Excluir Conta Modal -->
<div class="modal fade" id="excluirContaModal" tabindex="-1" aria-labelledby="excluirContaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="excluirContaModalLabel">Excluir Conta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="contas.php" method="POST" id="excluirContaForm">
                <input type="hidden" name="acao" value="excluirConta">
                <input type="hidden" id="excluirContaId" name="contaId">
                
                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p>Tem certeza que deseja excluir esta conta?</p>
                    <p class="small text-danger">Esta ação não pode ser desfeita.</p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript para funcionalidades dos modais -->
<script>
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
          
          // Set active type option based on tipo value
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
</script>