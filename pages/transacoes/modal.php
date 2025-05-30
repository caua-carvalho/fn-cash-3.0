<?php
require './contas/funcoes.php';
require './categorias/funcoes.php';
?>

<script src="transacoes/script.js"></script>
<script>
// Gerado pelo Copilot
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form[action="transacoes.php"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            // DEBUG: Mostra os dados do form
            let debugData = [];
            for (let [key, value] of new FormData(form).entries()) {
                debugData.push(key + '=' + value);
            }
            alert('DEBUG: Dados enviados:\n' + debugData.join('\n')); // Só pra debug

            // Validação manual dos campos obrigatórios
            if (!form.checkValidity()) {
                e.preventDefault(); // Só previne se for inválido!
                form.classList.add('was-validated');

                // Pega o primeiro campo inválido
                let primeiroInvalido = Array.from(form.elements).find(el => !el.checkValidity() && el.offsetParent !== null);

                // Se não achou, tenta achar mesmo que esteja oculto (ou seja, em outra aba)
                if (!primeiroInvalido) {
                    primeiroInvalido = Array.from(form.elements).find(el => !el.checkValidity());
                }

                // Descobre em qual aba está o campo inválido
                if (primeiroInvalido) {
                    let aba = 'basic';
                    let tabContent = primeiroInvalido.closest('.tab-content');
                    if (tabContent && tabContent.dataset.tab === 'details') {
                        aba = 'details';
                    }
                    trocarAba(form, aba);

                    // Dá shake no campo inválido
                    primeiroInvalido.classList.add('shake');
                    setTimeout(() => primeiroInvalido.classList.remove('shake'), 600);
                    primeiroInvalido.focus();
                }

                // Dá shake nos outros campos inválidos também (só pra reforçar)
                Array.from(form.elements).forEach(function(el) {
                    if (!el.checkValidity()) {
                        el.classList.add('shake');
                        setTimeout(() => el.classList.remove('shake'), 600);
                    }
                });

                return; // Não envia nada se inválido
            }

            // Se chegou aqui, deixa o submit seguir normalmente!
            // Se quiser continuar usando fetch, tira o e.preventDefault() daqui!
            // Se quiser usar o fetch, precisa fechar o modal manualmente e recarregar a página no .then

            // Se for usar fetch:
            e.preventDefault(); // Só aqui, se for usar fetch!
            const formData = new FormData(form);
            fetch(form.action, {
                method: form.method,
                body: formData
            })
            .then(async response => {
                const erroHeader = response.headers.get('X-Transacao-Erro');
                if (erroHeader) {
                    alert('Erro ao cadastrar transação: ' + decodeURIComponent(erroHeader));
                    return;
                }
                location.reload();
            })
            .catch(err => {
                alert('Erro inesperado ao cadastrar transação.');
            });
        });
    });
});
</script>
<!-- Nova Transação Modal -->
<div class="modal fade" id="transacaoModal" tabindex="-1" aria-labelledby="transacaoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transacaoModalLabel">Nova Transação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-modal-close="#transacaoModal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="tab-container">
                <button type="button" class="tab-btn active" data-tab="basic">Dados Básicos</button>
                <button type="button" class="tab-btn" data-tab="details">Detalhes</button>
            </div>

            <form action="transacoes.php" method="POST" class="needs-validation" novalidate>
                <input type="hidden" name="acao" value="cadastrarTransacao">
                
                <div class="modal-body">
                    <div class="tab-content" data-tab="basic">
                        <!-- Tipo de Transação -->
                        <h6 class="mb-3">Tipo de Transação</h6>
                        <div class="type-selector mb-4">
                            <div class="type-option expense" data-type="Despesa">
                                <i class="fas fa-arrow-down type-icon"></i>
                                <span class="type-name">Despesa</span>
                            </div>
                            <div class="type-option income" data-type="Receita">
                                <i class="fas fa-arrow-up type-icon"></i>
                                <span class="type-name">Receita</span>
                            </div>
                            <div class="type-option transfer" data-type="Transferência">
                                <i class="fas fa-exchange-alt type-icon"></i>
                                <span class="type-name">Transferência</span>
                            </div>
                        </div>
                        <input type="hidden" name="tipoTransacao" value="Despesa">

                        <!-- Título da Transação -->
                        <div class="form-group">
                            <input type="text" class="form-control" id="tituloTransacao" name="tituloTransacao" placeholder=" " required>
                            <label for="tituloTransacao">Título</label>
                        </div>

                        <!-- Valor da Transação -->
                        <div class="form-group value-container">
                            <input type="number" class="form-control" id="valorTransacao" name="valorTransacao" step="0.01" placeholder=" " required>
                            <label for="valorTransacao">Valor</label>
                        </div>

                        <!-- Data da Transação -->
                        <div class="form-group">
                            <input type="date" class="form-control" id="dataTransacao" name="dataTransacao" placeholder=" " required>
                            <label for="dataTransacao">Data</label>
                        </div>

                        <!-- Status da Transação -->
                        <h6 class="mb-3">Status</h6>
                        <div class="status-selector mb-4">
                            <button type="button" class="status-option pending" data-status="Pendente" value='Pendente'>Pendente</button>
                            <button type="button" class="status-option completed" data-status="Efetivada" value='Efetivada'>Efetivada</button>
                            <button type="button" class="status-option canceled" data-status="Cancelada" value='Cancelada'>Cancelada</button>
                        </div>
                        <input type="hidden" name="statusTransacao" value="Pendente">
                    </div>

                    <div class="tab-content" data-tab="details" style="display: none;">
                        <!-- Descrição da Transação -->
                        <div class="form-group">
                            <textarea class="form-control" id="descricaoTransacao" name="descricaoTransacao" placeholder=" " rows="3" required></textarea> <!-- Gerado pelo Copilot: agora é required -->
                            <label for="descricaoTransacao">Descrição</label>
                        </div>

                        <!-- Forma de Pagamento -->
                        <div class="form-group expense-income-only">
                            <select class="form-control" id="formaPagamento" name="formaPagamento" required>
                                <option value="" disabled selected></option>
                                <option value="dinheiro">Dinheiro</option>
                                <option value="debito">Débito</option>
                                <option value="credito">Crédito</option>
                                <option value="pix">PIX</option>
                                <option value="boleto">Boleto</option>
                            </select>
                            <label for="formaPagamento">Forma de Pagamento</label>
                        </div>

                        <!-- Conta Remetente -->
                        <div class="form-group">
                            <select class="form-control" id="contaRemetente" name="contaRemetente" required>
                                <option value="" disabled selected></option>
                                <?php
                                $contas = obterContas();
                                if ($contas) {
                                    foreach ($contas as $conta) {
                                        $saldo = number_format($conta['Saldo'], 2, ',', '.');
                                        echo '<option value="' . $conta['ID_Conta'] . '">' . htmlspecialchars($conta['Nome']) . ' - R$ ' . $saldo . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <label for="contaRemetente">Conta <span class="transfer-only">Origem</span></label>
                        </div>

                        <!-- Conta Destinatária (apenas para transferências) -->
                        <div class="form-group transfer-only">
                            <select class="form-control" id="contaDestinataria" name="contaDestinataria">
                                <option value="" disabled selected></option>
                                <?php
                                if ($contas) {
                                    foreach ($contas as $conta) {
                                        $saldo = number_format($conta['Saldo'], 2, ',', '.');
                                        echo '<option value="' . $conta['ID_Conta'] . '">' . htmlspecialchars($conta['Nome']) . ' - R$ ' . $saldo . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <label for="contaDestinataria">Conta Destino</label>
                        </div>

                        <!-- Categoria -->
                        <div class="form-group expense-income-only">
                            <select class="form-control" id="categoriaTransacao" name="categoriaTransacao">
                                <option value="" disabled selected></option>
                                <?php
                                $categorias = obterCategorias();
                                if ($categorias) {
                                    foreach ($categorias as $categoria) {
                                        echo '<option value="' . $categoria['ID_Categoria'] . '">' . htmlspecialchars($categoria['Nome']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <label for="categoriaTransacao">Categoria</label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-modal-close="#transacaoModal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Editar Transação Modal -->
<div class="modal fade" id="editarTransacaoModal" tabindex="-1" aria-labelledby="editarTransacaoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarTransacaoModalLabel">Editar Transação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-modal-close="#editarTransacaoModal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="tab-container">
                <button type="button" class="tab-btn active" data-tab="basic">Dados Básicos</button>
                <button type="button" class="tab-btn" data-tab="details">Detalhes</button>
            </div>

            <form action="transacoes.php" method="POST" class="needs-validation" novalidate>
                <input type="hidden" name="acao" value="editarTransacao">
                <input type="hidden" id="editarTransacaoId" name="idTransacao">
                
                <div class="modal-body">
                    <div class="tab-content" data-tab="basic">
                        <!-- Tipo de Transação -->
                        <h6 class="mb-3">Tipo de Transação</h6>
                        <div class="type-selector mb-4">
                            <div class="type-option expense" data-type="Despesa">
                                <i class="fas fa-arrow-down type-icon"></i>
                                <span class="type-name">Despesa</span>
                            </div>
                            <div class="type-option income" data-type="Receita">
                                <i class="fas fa-arrow-up type-icon"></i>
                                <span class="type-name">Receita</span>
                            </div>
                            <div class="type-option transfer" data-type="Transferência">
                                <i class="fas fa-exchange-alt type-icon"></i>
                                <span class="type-name">Transferência</span>
                            </div>
                        </div>
                        <input type="hidden" name="tipoTransacao" id="editarTipoTransacao" value="Despesa">

                        <!-- Título da Transação -->
                        <div class="form-group">
                            <input type="text" class="form-control" id="editarTituloTransacao" name="tituloTransacao" placeholder=" " required>
                            <label for="editarTituloTransacao">Título</label>
                        </div>

                        <!-- Valor da Transação -->
                        <div class="form-group value-container">
                            <input type="number" class="form-control" id="editarValorTransacao" name="valorTransacao" step="0.01" placeholder=" " required>
                            <label for="editarValorTransacao">Valor</label>
                        </div>

                        <!-- Data da Transação -->
                        <div class="form-group">
                            <input type="date" class="form-control" id="editarDataTransacao" name="dataTransacao" placeholder=" " required>
                            <label for="editarDataTransacao">Data</label>
                        </div>

                        <!-- Status da Transação -->
                        <h6 class="mb-3">Status</h6>
                        <div class="status-selector mb-4">
                            <button type="button" class="status-option pending" data-status="Pendente">Pendente</button>
                            <button type="button" class="status-option completed" data-status="Efetivada">Efetivada</button>
                            <button type="button" class="status-option canceled" data-status="Cancelada">Cancelada</button>
                        </div>
                        <input type="hidden" name="statusTransacao" id="editarStatusTransacao" value="Pendente">
                    </div>

                    <div class="tab-content" data-tab="details" style="display: none;">
                        <!-- Descrição da Transação -->
                        <div class="form-group">
                            <textarea class="form-control" id="editarDescricaoTransacao" name="descricaoTransacao" placeholder=" " rows="3" required></textarea> <!-- Gerado pelo Copilot: agora é required -->
                            <label for="editarDescricaoTransacao">Descrição</label>
                        </div>

                        <!-- Forma de Pagamento -->
                        <div class="form-group expense-income-only">
                            <select class="form-control" id="editarFormaPagamento" name="formaPagamento" required>
                                <option value="" disabled selected></option>
                                <option value="dinheiro">Dinheiro</option>
                                <option value="debito">Débito</option>
                                <option value="credito">Crédito</option>
                                <option value="pix">PIX</option>
                                <option value="boleto">Boleto</option>
                            </select>
                            <label for="editarFormaPagamento">Forma de Pagamento</label>
                        </div>

                        <!-- Conta Remetente -->
                        <div class="form-group">
                            <select class="form-control" id="editarContaRemetente" name="contaRemetente" required>
                                <option value="" disabled selected></option>
                                <?php
                                $contas = obterContas();
                                if ($contas) {
                                    foreach ($contas as $conta) {
                                        $saldo = number_format($conta['Saldo'], 2, ',', '.');
                                        echo '<option value="' . $conta['ID_Conta'] . '">' . htmlspecialchars($conta['Nome']) . ' - R$ ' . $saldo . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <label for="editarContaRemetente">Conta <span class="transfer-only">Origem</span></label>
                        </div>

                        <!-- Conta Destinatária (apenas para transferências) -->
                        <div class="form-group transfer-only">
                            <select class="form-control" id="editarContaDestinataria" name="contaDestinataria">
                                <option value="" disabled selected></option>
                                <?php
                                if ($contas) {
                                    foreach ($contas as $conta) {
                                        $saldo = number_format($conta['Saldo'], 2, ',', '.');
                                        echo '<option value="' . $conta['ID_Conta'] . '">' . htmlspecialchars($conta['Nome']) . ' - R$ ' . $saldo . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <label for="editarContaDestinataria">Conta Destino</label>
                        </div>

                        <!-- Categoria -->
                        <div class="form-group expense-income-only">
                            <select class="form-control" id="editarCategoriaTransacao" name="categoriaTransacao">
                                <option value="" disabled selected></option>
                                <?php
                                $categorias = obterCategorias();
                                if ($categorias) {
                                    foreach ($categorias as $categoria) {
                                        echo '<option value="' . $categoria['ID_Categoria'] . '">' . htmlspecialchars($categoria['Nome']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <label for="editarCategoriaTransacao">Categoria</label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-modal-close='#editarTransacaoModal'>Cancelar</button>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Excluir Transação Modal -->
<div class="modal fade" id="excluirTransacaoModal" tabindex="-1" aria-labelledby="excluirTransacaoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="excluirTransacaoModalLabel">Excluir Transação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-modal-close='#excluirTransacaoModal'>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="transacoes.php" method="POST" id="excluirTransacaoForm">
                <input type="hidden" name="acao" value="excluirTransacao">
                <input type="hidden" id="excluirTransacaoId" name="transacaoId">
                
                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p>Tem certeza que deseja excluir a transação:</p>
                    <p><strong id="transacaoTituloExcluir">Nome da Transação</strong>?</p>
                    <p class="small text-danger">Esta ação não pode ser desfeita.</p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-modal-close='#excluirTransacaoModal'>Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Root CSS Variables (Adicionar no Head da página) -->
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

<!-- Inclua FontAwesome para ícones (Adicionar no Head) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Incorpore as folhas de estilo e scripts -->
<link rel="stylesheet" href="transacoes/modal/modal.css">
<script src="transacoes/modal/modal.js"></script>