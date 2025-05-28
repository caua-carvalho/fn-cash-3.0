<!-- /*
 * Gera o HTML do modal de nova conta, permitindo definir o caminho do form dinamicamente.
 * @param string $caminhoForm Caminho para onde o formulário será enviado.
 * @return string HTML do modal.
 * Gerado pelo Copilot
*/ -->
<?php
function gerarModalNovaConta(string $caminhoForm): string
{
    // Gerado pelo Copilot
    return '
    <!-- Modal de Nova Conta -->
    <div class="modal fade" id="contaModal" tabindex="-1" aria-labelledby="contaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contaModalLabel">Nova Conta</h5>
                    <button type="button" class="close" data-modal-close aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="' . htmlspecialchars($caminhoForm) . '" method="POST" autocomplete="off">
                    <input type="hidden" name="acao" value="cadastrarConta">
                    <div class="modal-body">
                        <!-- Tipo de Conta -->
                        <div class="form-group">
                            <label class="form-label" for="tipoConta">Tipo de Conta</label>
                            <select class="form-control" id="tipoConta" name="tipoConta" required>
                                <option value="Corrente">Corrente</option>
                                <option value="Poupança">Poupança</option>
                                <option value="Cartão de Crédito">Cartão de Crédito</option>
                                <option value="Investimento">Investimento</option>
                                <option value="Outros">Outros</option>
                            </select>
                        </div>
                        <!-- Nome da Conta -->
                        <div class="form-group">
                            <label class="form-label" for="nomeConta">Nome da Conta</label>
                            <input type="text" class="form-control" id="nomeConta" name="nomeConta" required placeholder="Digite o nome da conta">
                        </div>
                        <!-- Saldo Inicial -->
                        <div class="form-group value-container">
                            <label class="form-label" for="saldoConta">Saldo Inicial</label>
                            <input type="number" class="form-control" id="saldoConta" name="saldoConta" step="0.01" required placeholder="0,00">
                        </div>
                        <!-- Instituição Financeira -->
                        <div class="form-group">
                            <label class="form-label" for="instituicaoConta">Instituição Financeira</label>
                            <input type="text" class="form-control" id="instituicaoConta" name="instituicaoConta" required placeholder="Ex: Nubank, Itaú...">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-modal-close>Cancelar</button>
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    ';
}
// Exemplo de uso:
// echo gerarModalNovaConta('../pages/contas/contas.php');

?>

<!-- Editar Conta Modal -->
<div class="modal fade" id="editarContaModal" tabindex="-1" aria-labelledby="editarContaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarContaModalLabel">Editar Conta</h5>
                <button type="button" class="close" data-modal-close aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="contas.php" method="POST" autocomplete="off">
                <input type="hidden" name="acao" value="editarConta">
                <input type="hidden" id="editarContaId" name="contaId">
                <div class="modal-body">
                    <!-- Tipo de Conta -->
                    <div class="form-group">
                        <label class="form-label" for="editarTipoConta">Tipo de Conta</label>
                        <select class="form-control" id="editarTipoConta" name="tipoConta" required>
                            <option value="Corrente">Corrente</option>
                            <option value="Poupança">Poupança</option>
                            <option value="Cartão de Crédito">Cartão de Crédito</option>
                            <option value="Investimento">Investimento</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>
                    <!-- Nome da Conta -->
                    <div class="form-group">
                        <label class="form-label" for="editarNomeConta">Nome da Conta</label>
                        <input type="text" class="form-control" id="editarNomeConta" name="nomeConta" required placeholder="Digite o nome da conta">
                    </div>
                    <!-- Saldo -->
                    <div class="form-group value-container">
                        <label class="form-label" for="editarSaldoConta">Saldo</label>
                        <input type="number" class="form-control" id="editarSaldoConta" name="saldoConta" step="0.01" required placeholder="0,00">
                    </div>
                    <!-- Instituição Financeira -->
                    <div class="form-group">
                        <label class="form-label" for="editarInstituicaoConta">Instituição Financeira</label>
                        <input type="text" class="form-control" id="editarInstituicaoConta" name="instituicaoConta" required placeholder="Ex: Nubank, Itaú...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-modal-close>Cancelar</button>
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
                <button type="button" class="close" data-modal-close aria-label="Close">
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
                    <button type="button" class="btn btn-secondary" data-modal-close>Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </div>
            </form>
        </div>
    </div>
</div>