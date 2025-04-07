<div class="modal fade" id="contaModal" tabindex="-1" aria-labelledby="contaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Título do modal para cadastrar nova conta -->
                <h5 class="modal-title" id="modalCadastrarContaLabel">Cadastrar Nova Conta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Formulário para cadastrar nova conta -->
            <form action="contas.php" method="POST">
                <input type="hidden" name="acao" value="cadastrarConta">
                <div class="modal-body">
                    <!-- Campo para o nome da conta -->
                    <div class="form-group">
                        <label for="nomeConta">Nome da Conta</label>
                        <input type="text" class="form-control" id="nomeConta" name="nomeConta" required>
                    </div>
                    <!-- Campo para selecionar o tipo da conta -->
                    <div class="form-group">
                        <label for="tipoConta">Tipo</label>
                        <select class="form-control" id="tipoConta" name="tipoConta" required>
                            <option value="Corrente">Corrente</option>
                            <option value="Poupança">Poupança</option>
                            <option value="Cartão de Crédito">Cartão de Crédito</option>
                            <option value="Investimento">Investimento</option>
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>
                    <!-- Campo para o saldo inicial da conta -->
                    <div class="form-group">
                        <label for="saldoConta">Saldo Inicial</label>
                        <input type="number" class="form-control" id="saldoConta" name="saldoConta" step="0.01" required>
                    </div>
                    <!-- Campo para a instituição financeira -->
                    <div class="form-group">
                        <label for="instituicaoConta">Instituição</label>
                        <input type="text" class="form-control" id="instituicaoConta" name="instituicaoConta" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Botões para cancelar ou salvar -->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editarContaModal" tabindex="-1" aria-labelledby="editarContaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Título do modal para editar conta -->
                <h5 class="modal-title" id="editarContaModalLabel">Editar Conta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Formulário para editar conta -->
            <form action="contas.php" method="POST">
                <input type="hidden" name="acao" value="editarConta">
                <div class="modal-body">
                    <!-- Campo oculto para armazenar o ID da conta -->
                    <input type="hidden" id="editarContaId" name="contaId">
                    <!-- Campo para o nome da conta -->
                    <div class="form-group">
                        <label for="editarNomeConta">Nome da Conta</label>
                        <input type="text" class="form-control" id="editarNomeConta" name="nomeConta" required>
                    </div>
                    <!-- Campo para selecionar o tipo da conta -->
                    <div class="form-group">
                        <label for="editarTipoConta">Tipo</label>
                        <select class="form-control" id="editarTipoConta" name="tipoConta" required>
                            <option value="Corrente">Corrente</option>
                            <option value="Poupança">Poupança</option>
                            <option value="Cartão de Crédito">Cartão de Crédito</option>
                            <option value="Investimento">Investimento</option>
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>
                    <!-- Campo para o saldo da conta -->
                    <div class="form-group">
                        <label for="editarSaldoConta">Saldo</label>
                        <input type="number" class="form-control" id="editarSaldoConta" name="saldoConta" step="0.01" required>
                    </div>
                    <!-- Campo para a instituição financeira -->
                    <div class="form-group">
                        <label for="editarInstituicaoConta">Instituição</label>
                        <input type="text" class="form-control" id="editarInstituicaoConta" name="instituicaoConta" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Botões para cancelar ou salvar alterações -->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="excluirContaModal" tabindex="-1" aria-labelledby="excluirContaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Título do modal para confirmação de exclusão -->
                <h5 class="modal-title" id="excluirContaModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Formulário para exclusão de conta -->
            <form action="contas.php" method="POST">
                <input type="hidden" name="acao" value="excluirConta">
                <input type="hidden" id="excluirContaId" name="contaId">
                <div class="modal-body">
                    <!-- Mensagem de confirmação -->
                    <p>Tem certeza de que deseja excluir esta conta? Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <!-- Botões para cancelar ou confirmar exclusão -->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Seleciona todos os botões que abrem o modal de edição
        const editarButtons = document.querySelectorAll('[data-target="#editarContaModal"]');
        editarButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Obtém os atributos de dados do botão clicado
                const id = this.getAttribute('data-id');
                const nome = this.getAttribute('data-nome');
                const tipo = this.getAttribute('data-tipo');
                const saldo = this.getAttribute('data-saldo');
                const instituicao = this.getAttribute('data-instituicao');

                // Preenche os campos do modal com os valores obtidos
                document.getElementById('editarContaId').value = id;
                document.getElementById('editarNomeConta').value = nome;
                document.getElementById('editarTipoConta').value = tipo;
                document.getElementById('editarSaldoConta').value = saldo;
                document.getElementById('editarInstituicaoConta').value = instituicao;
            });
        });

        // Seleciona todos os botões que abrem o modal de exclusão
        const excluirButtons = document.querySelectorAll('[data-target="#excluirContaModal"]');
        excluirButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Obtém o ID da conta do botão clicado
                const id = this.getAttribute('data-id');
                // Preenche o campo oculto do modal com o ID da conta
                document.getElementById('excluirContaId').value = id;
            });
        });
    });
</script>