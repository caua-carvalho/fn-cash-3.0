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
    return "
    <!-- Botão para abrir o modal -->
    <button class='btn btn-primary' onclick='document.getElementById('modalNovaConta').classList.add('show'); document.body.classList.add('modal-open');'>
    Nova Conta
    </button>

    <!-- Modal -->
    <div class='modal fade' id='modalNovaConta' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered' role='document'>
        <div class='modal-content'>

        <div class='modal-header'>
            <h5 class='modal-title'>Cadastrar Nova Conta</h5>
            <button type='button' class='close' onclick='document.getElementById('modalNovaConta').classList.remove('show'); document.body.classList.remove('modal-open');'>&times;</button>
        </div>

        <form method='POST' action='cadastrar_conta.php' class='needs-validation' novalidate>
            <div class='modal-body'>

            <div class='form-floating'>
                <input type='text' class='form-control' id='nome' name='nome' placeholder=' ' required>
                <label for='nome'>Nome da Conta</label>
            </div>

            <div class='form-floating mt-4'>
                <select class='form-control' id='tipo' name='tipo' required>
                <option value='' disabled selected hidden></option>
                <option value='Corrente'>Corrente</option>
                <option value='Poupança'>Poupança</option>
                <option value='Cartão de Crédito'>Cartão de Crédito</option>
                <option value='Investimento'>Investimento</option>
                <option value='Dinheiro'>Dinheiro</option>
                <option value='Outros'>Outros</option>
                </select>
                <label for='tipo'>Tipo de Conta</label>
            </div>

            <div class='form-floating mt-4'>
                <input type='number' step='0.01' class='form-control' id='saldo' name='saldo' placeholder=' ' required>
                <label for='saldo'>Saldo Inicial</label>
            </div>

            <div class='form-floating mt-4'>
                <input type='text' class='form-control' id='instituicao' name='instituicao' placeholder=' ' required>
                <label for='instituicao'>Instituição</label>
            </div>

            <div class='form-floating mt-4'>
                <input type='date' class='form-control' id='dataCriacao' name='dataCriacao' required>
                <label for='dataCriacao'>Data de Criação</label>
            </div>

            <input type='hidden' name='id_usuario' value='<!-- ID do usuário logado aqui -->'>

            </div>

            <div class='modal-footer'>
            <button type='button' class='btn btn-secondary' onclick='document.getElementById('modalNovaConta').classList.remove('show'); document.body.classList.remove('modal-open');'>Cancelar</button>
            <button type='submit' class='btn btn-success'>Salvar Conta</button>
            </div>
        </form>

        </div>
    </div>
    </div>

    ";
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