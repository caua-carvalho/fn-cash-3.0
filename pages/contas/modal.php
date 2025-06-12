<?php
function modalCreateConta(): string {
  return '
          <!-- Novo Conta Modal -->
          <div class="modal fade" id="modalNovaConta" tabindex="-1" aria-labelledby="modalNovaContaLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                
                <div class="modal-header">
                  <h5 class="modal-title" id="modalNovaContaLabel">Cadastrar Nova Conta</h5>
                  <button type="button" class="close" aria-label="Fechar" data-bs-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <form action="contas.php" method="POST" class="needs-validation" novalidate>
                  <input type="hidden" name="acao" value="cadastrarConta">
                  <!-- guarda o tipo selecionado -->
                  <input type="hidden" name="tipoConta" id="tipoContaHidden" value="Corrente">

                  <div class="modal-body">
                    <!-- Seletor visual de tipo -->
                    <div class="type-selector mb-4">
                      <div class="type-option" data-type="Corrente">
                        <i class="fas fa-university type-icon"></i>
                        <span class="type-name">Corrente</span>
                      </div>
                      <div class="type-option" data-type="Poupança">
                        <i class="fas fa-piggy-bank type-icon"></i>
                        <span class="type-name">Poupança</span>
                      </div>
                      <div class="type-option" data-type="Cartão de Crédito">
                        <i class="fas fa-credit-card type-icon"></i>
                        <span class="type-name">Cartão</span>
                      </div>
                      <div class="type-option" data-type="Investimento">
                        <i class="fas fa-chart-line type-icon"></i>
                        <span class="type-name">Investimento</span>
                      </div>
                    </div>

                    <!-- Nome da Conta -->
                    <div class="form-group mb-3">
                      <input
                        type="text"
                        class="form-control"
                        id="novaNomeConta"
                        name="nomeConta"
                        placeholder=" "
                        required
                      >
                      <label for="novaNomeConta">Nome da Conta</label>
                    </div>

                    <!-- Saldo Inicial -->
                    <div class="form-group mb-3 value-container">
                      <input
                        type="number"
                        class="form-control"
                        id="novaSaldoConta"
                        name="saldoConta"
                        placeholder=" "
                        step="0.01"
                        required
                      >
                      <label for="novaSaldoConta">Saldo Inicial</label>
                    </div>

                    <!-- Instituição -->
                    <div class="form-group mb-3">
                      <input
                        type="text"
                        class="form-control"
                        id="novaInstituicaoConta"
                        name="instituicaoConta"
                        placeholder=" "
                        required
                      >
                      <label for="novaInstituicaoConta">Instituição</label>
                    </div>
                  </div>
                  
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Salvar Conta</button>
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
                  <button type="button" class="close" aria-label="Fechar" data-bs-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <form action="contas.php" method="POST" class="needs-validation" novalidate>
                  <input type="hidden" name="acao" value="editarConta">
                  <input type="hidden" id="editarContaId" name="contaId">
                  <!-- guarda o tipo selecionado -->
                  <input type="hidden" name="tipoConta" id="editarTipoContaHidden">

                  <div class="modal-body">
                    <!-- Seletor visual de tipo -->
                    <div class="type-selector mb-4">
                      <div class="type-option" data-type="Corrente">
                        <i class="fas fa-university type-icon"></i>
                        <span class="type-name">Corrente</span>
                      </div>
                      <div class="type-option" data-type="Poupança">
                        <i class="fas fa-piggy-bank type-icon"></i>
                        <span class="type-name">Poupança</span>
                      </div>
                      <div class="type-option" data-type="Cartão de Crédito">
                        <i class="fas fa-credit-card type-icon"></i>
                        <span class="type-name">Cartão</span>
                      </div>
                      <div class="type-option" data-type="Investimento">
                        <i class="fas fa-chart-line type-icon"></i>
                        <span class="type-name">Investimento</span>
                      </div>
                    </div>

                    <!-- Nome da Conta -->
                    <div class="form-group mb-3">
                      <input
                        type="text"
                        class="form-control"
                        id="editarNomeConta"
                        name="nomeConta"
                        placeholder=" "
                        required
                      >
                      <label for="editarNomeConta">Nome da Conta</label>
                    </div>

                    <!-- Saldo -->
                    <div class="form-group mb-3 value-container">
                      <input
                        type="number"
                        class="form-control"
                        id="editarSaldoConta"
                        name="saldoConta"
                        placeholder=" "
                        step="0.01"
                        required
                      >
                      <label for="editarSaldoConta">Saldo</label>
                    </div>

                    <!-- Instituição -->
                    <div class="form-group mb-3">
                      <input
                        type="text"
                        class="form-control"
                        id="editarInstituicaoConta"
                        name="instituicaoConta"
                        placeholder=" "
                        required
                      >
                      <label for="editarInstituicaoConta">Instituição</label>
                    </div>
                  </div>
                  
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
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
                  <button type="button" class="close" aria-label="Fechar" data-bs-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form action="contas.php" method="POST" autocomplete="off">
                  <input type="hidden" name="acao" value="excluirConta">
                  <input type="hidden" id="excluirContaId" name="contaId">

                  <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p>Tem certeza que deseja excluir esta conta?</p>
                    <p class="small text-danger">Esta ação não pode ser desfeita.</p>
                  </div>
                  
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir</button>
                  </div>
                </form>
                
              </div>
            </div>
          </div>
  ';
}

?>

<!-- JS para ativar seleção de tipo -->
<script>
  document.querySelectorAll('#modalNovaConta .type-option').forEach(opt => {
    opt.addEventListener('click', () => {
      document
        .querySelectorAll('#modalNovaConta .type-option')
        .forEach(x => x.classList.remove('active'));
      opt.classList.add('active');
      document.getElementById('tipoContaHidden').value = opt.dataset.type;
    });
  });
</script>

<!-- CSS -->
<style>
:root {
  --base-clr: #0a0a0a;
  --line-clr: #42434a;
  --hover-clr: #053F27;
  --text-clr: #e6e6ef;
  --accent-clr: #07A362;
  --secondary-text-clr: #b0b3c1;
  --background-clr: #f5f5f5;
  --input-bg-clr: #f5f5f5;
  --box-shadow-clr: rgba(10, 10, 10, 0.5);
  --hover-overlay-clr: rgba(10, 10, 10, 0.2);
}

/* Adicionar classe para animação de shake em campos com erro */
@keyframes shake {
  0%, 100% { transform: translateX(0); }
  20%, 60% { transform: translateX(-5px); }
  40%, 80% { transform: translateX(5px); }
}

.shake {
  animation: shake 0.6s ease-in-out;
}
</style>




<!-- Editar Conta Modal -->
<div class="modal fade" id="editarContaModal" tabindex="-1" aria-labelledby="editarContaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="editarContaModalLabel">Editar Conta</h5>
        <button type="button" class="close" aria-label="Fechar" data-modal-close="#editarContaModal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <form action="contas.php" method="POST" class="needs-validation" novalidate>
        <input type="hidden" name="acao" value="editarConta">
        <input type="hidden" id="editarContaId" name="contaId">
        <!-- guarda o tipo selecionado -->
        <input type="hidden" name="tipoConta" id="editarTipoContaHidden">

        <div class="modal-body">
          <!-- Seletor visual de tipo -->
          <div class="type-selector mb-4">
            <div class="type-option" data-type="Corrente">
              <i class="fas fa-university type-icon"></i>
              <span class="type-name">Corrente</span>
            </div>
            <div class="type-option" data-type="Poupança">
              <i class="fas fa-piggy-bank type-icon"></i>
              <span class="type-name">Poupança</span>
            </div>
            <div class="type-option" data-type="Cartão de Crédito">
              <i class="fas fa-credit-card type-icon"></i>
              <span class="type-name">Cartão</span>
            </div>
            <div class="type-option" data-type="Investimento">
              <i class="fas fa-chart-line type-icon"></i>
              <span class="type-name">Investimento</span>
            </div>
          </div>

          <!-- Nome da Conta -->
          <div class="form-group mb-3">
            <input
              type="text"
              class="form-control"
              id="editarNomeConta"
              name="nomeConta"
              placeholder=" "
              required
            >
            <label for="editarNomeConta">Nome da Conta</label>
          </div>

          <!-- Saldo -->
          <div class="form-group mb-3 value-container">
            <input
              type="number"
              class="form-control"
              id="editarSaldoConta"
              name="saldoConta"
              placeholder=" "
              step="0.01"
              required
            >
            <label for="editarSaldoConta">Saldo</label>
          </div>

          <!-- Instituição -->
          <div class="form-group mb-3">
            <input
              type="text"
              class="form-control"
              id="editarInstituicaoConta"
              name="instituicaoConta"
              placeholder=" "
              required
            >
            <label for="editarInstituicaoConta">Instituição</label>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-modal-close="#editarContaModal">Cancelar</button>
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
        <button type="button" class="close" aria-label="Fechar" data-modal-close="#excluirContaModal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="contas.php" method="POST" autocomplete="off">
        <input type="hidden" name="acao" value="excluirConta">
        <input type="hidden" id="excluirContaId" name="contaId">

        <div class="modal-body text-center">
          <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
          <p>Tem certeza que deseja excluir esta conta?</p>
          <p class="small text-danger">Esta ação não pode ser desfeita.</p>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-modal-close="#excluirContaModal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Excluir</button>
        </div>
      </form>
      
    </div>
  </div>
</div>

<style>
:root {
  --base-clr: #0a0a0a;
  --line-clr: #42434a;
  --hover-clr: #053F27;
  --text-clr: #e6e6ef;
  --accent-clr: #07A362;
  --secondary-text-clr: #b0b3c1;
  --background-clr: #f5f5f5;
  --input-bg-clr: #f5f5f5;
  --box-shadow-clr: rgba(10, 10, 10, 0.5);
  --hover-overlay-clr: rgba(10, 10, 10, 0.2);
}

/* Adicionar classe para animação de shake em campos com erro */
@keyframes shake {
  0%, 100% { transform: translateX(0); }
  20%, 60% { transform: translateX(-5px); }
  40%, 80% { transform: translateX(5px); }
}

.shake {
  animation: shake 0.6s ease-in-out;
}
</style>

<link rel="stylesheet" href="contas/modal/modal.css">
<script src="contas/modal/modal.js"></script>
