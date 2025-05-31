<?php
require './contas/funcoes.php';
require './categorias/funcoes.php';
?>

<script src="transacoes/script.js"></script>
<script>
// Função para criar e mostrar toast
function showToast(message, type = 'success', duration = 5000, callback = null) {
    // Gerado pelo Copilot
    const container = document.querySelector('.toast-container') || (() => {
        const div = document.createElement('div');
        div.className = 'toast-container';
        document.body.appendChild(div);
        return div;
    })();

    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `
        <div class="toast-header">
            <h4 class="toast-title">${type === 'success' ? 'Sucesso!' : 'Erro!'}</h4>
            <button class="toast-close">&times;</button>
        </div>
        <p class="toast-message">${message}</p>
        <div class="toast-progress">
            <div class="toast-progress-bar"></div>
        </div>
    `;

    container.appendChild(toast);

    // Configura a barra de progresso
    const progressBar = toast.querySelector('.toast-progress-bar');
    progressBar.style.transition = `width ${duration}ms linear`;
    
    // Inicia a animação da barra
    setTimeout(() => progressBar.style.width = '0%', 100);

    // Configura o botão de fechar
    toast.querySelector('.toast-close').onclick = () => {
        toast.style.animation = 'slideOut 0.3s forwards';
        setTimeout(() => toast.remove(), 300);
    };

    // Remove o toast automaticamente
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s forwards';
        setTimeout(() => toast.remove(), 300);
        // Removido o reload da página aqui! UX agradece.
        if (typeof callback === 'function') callback(); // Gerado pelo Copilot
    }, duration);

    // Gerado pelo Copilot
    if (typeof callback !== 'function') {
        callback = () => window.location.reload();
    }
}

/**
 * Adiciona uma nova transação na tabela sem reload.
 * Fecha o modal antes de montar a linha e exibir o toast.
 * Gerado pelo Copilot
 */
function adicionarTransacaoNaTabela(dados, form) {
    // Fecha o modal antes de tudo
    const modal = form ? form.closest('.modal') : null;
    if (modal) {
        if (typeof $ !== 'undefined' && $.fn.modal) {
            $(modal).modal('hide');
        } else {
            modal.style.display = 'none';
        }
    }

    // Monta a linha da tabela
    const tbody = document.querySelector('.transaction-table tbody');
    if (!tbody) return;

    // Define classes de tipo e status
    let tipoBadgeClass = 'badge-transfer', valorClass = '';
    if (dados.tipoTransacao === 'Receita') {
        tipoBadgeClass = 'badge-income';
        valorClass = 'text-income';
    } else if (dados.tipoTransacao === 'Despesa') {
        tipoBadgeClass = 'badge-expense';
        valorClass = 'text-expense';
    }
    let statusBadgeClass = 'badge-pending';
    if (dados.statusTransacao === 'Efetivada') statusBadgeClass = 'badge-completed';
    else if (dados.statusTransacao === 'Cancelada') statusBadgeClass = 'badge-canceled';

    // Formata valor e data
    const valorFormatado = (dados.tipoTransacao === 'Despesa' ? '- ' : '') + 'R$ ' + Number(dados.valorTransacao).toLocaleString('pt-BR', {minimumFractionDigits: 2});
    const dataFormatada = new Date(dados.dataTransacao).toLocaleDateString('pt-BR');

    // Monta a linha
    const tr = document.createElement('tr');
    tr.className = 'fade-in-up';
    tr.innerHTML = `
        <td class="font-medium">${dados.tituloTransacao}</td>
        <td class="font-semibold ${valorClass}">${valorFormatado}</td>
        <td>${dataFormatada}</td>
        <td><span class="badge ${tipoBadgeClass}">
            <i class="fas fa-${dados.tipoTransacao === 'Receita' ? 'arrow-up' : (dados.tipoTransacao === 'Despesa' ? 'arrow-down' : 'exchange-alt')} me-1"></i>
            ${dados.tipoTransacao}
        </span></td>
        <td><span class="badge ${statusBadgeClass}">${dados.statusTransacao}</span></td>
        <td>${dados.nomeContaRemetente || '-'}</td>
        <td>${dados.nomeContaDestinataria || '-'}</td>
        <td>
            <div class="flex justify-center gap-2">
                <!-- Aqui você pode adicionar os botões de ação se quiser -->
            </div>
        </td>
    `;
    // Adiciona no topo da tabela
    tbody.prepend(tr);
}

/**
 * Busca o nome da conta pelo ID no select (front-end only).
 * Gerado pelo Copilot
 */
function getNomeContaById(selectId, contaId) {
    const select = document.getElementById(selectId);
    if (!select) return '';
    const opt = select.querySelector(`option[value="${contaId}"]`);
    return opt ? opt.textContent : '';
}

/**
 * Fecha o modal de forma confiável, seja pelo X, cancelar ou via JS.
 * Agora com checagem extra para evitar erro de null.
 * Gerado pelo Copilot
 */
function fecharModal(modal) {
    if (!modal) {
        // Só loga o erro, não tenta acessar classList de null
        console.warn('fecharModal: modal não encontrado para fechar.'); // Gerado pelo Copilot
        return;
    }
    if (typeof $ !== 'undefined' && $.fn.modal) {
        $(modal).modal('hide');
    } else {
        modal.classList.remove('show');
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
        // Remove backdrop se existir
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) backdrop.remove();
    }
}

// Modifica o código do submit para usar o toast e atualizar a tabela
document.addEventListener('DOMContentLoaded', function() {
    // Verifica se tem mensagem pendente no localStorage
    const mensagemPendente = localStorage.getItem('toast_message');
    const tipoMensagem = localStorage.getItem('toast_type');
    
    if (mensagemPendente) {
        showToast(mensagemPendente, tipoMensagem || 'success');
        localStorage.removeItem('toast_message');
        localStorage.removeItem('toast_type');
    }

    // Fecha modal ao clicar no X ou em qualquer botão com data-modal-close
    document.querySelectorAll('[data-modal-close]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const modalSelector = this.getAttribute('data-modal-close');
            const modal = document.querySelector(modalSelector);
            fecharModal(modal); // Gerado pelo Copilot
        });
    });

    document.querySelectorAll('form[action="transacoes.php"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault(); // Previne envio só se inválido
                form.classList.add('was-validated');

                // Pega o primeiro campo inválido
                let primeiroInvalido = Array.from(form.elements)
                    .find(el => !el.checkValidity() && el.offsetParent !== null);

                if (primeiroInvalido) {
                    let aba = primeiroInvalido.closest('.tab-content')?.dataset.tab === 'details' 
                        ? 'details' 
                        : 'basic';
                    
                    trocarAba(form, aba);
                    primeiroInvalido.classList.add('shake');
                    setTimeout(() => primeiroInvalido.classList.remove('shake'), 600);
                    primeiroInvalido.focus();
                }
            } else {
                // Impede submit padrão
                e.preventDefault();

                // Pega os dados do form
                const formData = new FormData(form);
                const dados = {};
                formData.forEach((v, k) => dados[k] = v);

                // DEBUG: Mostra os dados enviados no console
                console.warn('DEBUG - Dados enviados no cadastro:', JSON.stringify(dados, null, 2)); // Gerado pelo Copilot

                // Busca nomes das contas (remetente/destinataria)
                dados.nomeContaRemetente = getNomeContaById(form.id === 'editarTransacaoForm' ? 'editarContaRemetente' : 'contaRemetente', dados.contaRemetente);
                dados.nomeContaDestinataria = dados.contaDestinataria ? getNomeContaById(form.id === 'editarTransacaoForm' ? 'editarContaDestinataria' : 'contaDestinataria', dados.contaDestinataria) : '';

                // Envia via AJAX
                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(resp => resp.text())
                .then(respHtml => {
                    // Sucesso: fecha modal, adiciona na tabela e mostra toast
                    if (respHtml.includes('Operação realizada com sucesso')) {
                        fecharModal(form.closest('.modal')); // Fecha modal de forma confiável
                        adicionarTransacaoNaTabela(dados, form);
                        showToast('Transação cadastrada com sucesso!', 'success', 2500);
                        form.reset();
                    } else {
                        showToast('Erro ao cadastrar transação. Verifique os dados.', 'danger');
                    }
                })
                .catch(() => {
                    showToast('Erro ao cadastrar transação. Verifique sua conexão.', 'danger');
                });
            }
            // Se válido, deixa o form ser enviado naturalmente
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
                            <select class="form-control" id="contaDestinataria" name="contaDestinataria" required> <!-- Adiciona required aqui -->
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

<!-- Adiciona CSS do toast -->
<link rel="stylesheet" href="/assets/css/toast.css">