<?php
require 'script.php';
require './contas/funcoes.php';
require './categorias/funcoes.php';
?>

<div class="modal fade" id="transacaoModal" tabindex="-1" aria-labelledby="transacaoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Título do modal para cadastrar nova transação -->
                <h5 class="modal-title" id="modalCadastrarTransacaoLabel">Cadastrar Nova Transação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Formulário para cadastrar nova transação -->
            <form action="teste.php" method="POST">
                <input type="hidden" name="acao" value="cadastrarTransacao">
                <div class="modal-body">
                    

                    <!-- Campo para o título da transação -->
                    <div class="form-group">
                        <label for="tituloTransacao">Título</label>
                        <input type="text" class="form-control" id="tituloTransacao" name="tituloTransacao" required>
                    </div>

                    <!-- Campo para a descrição da transação -->
                    <div class="form-group">
                        <label for="descricaoTransacao">Descrição</label>
                        <textarea class="form-control" id="descricaoTransacao" name="descricaoTransacao" required></textarea>
                    </div>

                    <!-- Campo para o valor da transação -->
                    <div class="form-group">
                        <label for="valorTransacao">Valor</label>
                        <input type="number" class="form-control" id="valorTransacao" name="valorTransacao" step="0.01" required>
                    </div>

                    <!-- Campo para a data da transação -->
                    <div class="form-group">
                        <label for="dataTransacao">Data</label>
                        <input type="date" class="form-control" id="dataTransacao" name="dataTransacao" required>
                    </div>

                    <!-- Campo para selecionar o tipo da transação -->
                    <div class="form-group">
                        <label for="tipoTransacao">Tipo</label>
                        <select class="form-control" id="tipoTransacao" name="tipoTransacao" required>
                            <option value="Despesa">Despesa</option>
                            <option value="Receita">Receita</option>
                            <option value="Transferência">Transferência</option>
                        </select>
                    </div>

                    <!-- Campo para selecionar o status da transação -->
                    <div class="form-group">
                        <label for="statusTransacao">Status</label>
                        <select class="form-control" id="statusTransacao" name="statusTransacao" required>
                            <option value="Pendente">Pendente</option>
                            <option value="Efetivada">Efetivada</option>
                            <option value="Cancelada">Cancelada</option>
                        </select>
                    </div>

                    <!-- Conta remetente -->
                    <div class="form-group">
                        <label for="contaRemetente">Conta Remetente</label>
                        <?php
                        $contas = obterContas();
                        if ($contas) {
                            echo '<select class="form-control" id="contaRemetente" name="contaRemetente" required>';
                            echo '<option value="">Selecione uma conta...</option>';
                            foreach ($contas as $conta) {
                                echo '<option value="' . $conta['ID_Conta'] . '">' . htmlspecialchars($conta['Nome']) . '</option>';
                            }
                            echo '</select>';
                        } else {
                            echo '<p class="text-danger">Nenhuma conta encontrada.</p>';
                        }
                        ?>
                    </div>

                    <!-- Conta destinatária (exibida apenas para transferências) -->
                    <div class="form-group">
                        <label for="contaDestinataria">Conta destinatária</label>
                        <?php
                        $contas = obterContas();
                        if ($contas) {
                            echo '<select class="form-control" id="contaDestinataria" name="contaDestinataria" required>';
                            echo '<option value="">Selecione uma conta...</option>';
                            foreach ($contas as $conta) {
                                echo '<option value="' . $conta['ID_Conta'] . '">' . htmlspecialchars($conta['Nome']) . '</option>';
                            }
                            echo '</select>';
                        } else {
                            echo '<p class="text-danger">Nenhuma conta encontrada.</p>';
                        }
                        ?>
                    </div>

                    <!-- Campo para selecionar a categoria da transação -->
                    <div class="form-group">
                        <label for="categoriaTransacao">Categoria</label>
                        <?php
                        $categorias = obterCategorias();
                        if ($categorias) {
                            echo '<select class="form-control" id="categoriaTransacao" name="categoriaTransacao">';
                            echo '<option value="">Selecione uma Categoria...</option>';
                            foreach ($categorias as $categoria) {
                                echo '<option value="' . $categoria['ID_Categoria'] . '">' . htmlspecialchars($categoria['Nome']) . '</option>';
                            }
                            echo '</select>';
                        } else {
                            echo '<p class="text-danger">Nenhuma categoria encontrada.</p>';
                        }
                        ?>
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

<div class="modal fade" id="editarTransacaoModal" tabindex="-1" aria-labelledby="editarTransacaoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Título do modal para editar transação -->
                <h5 class="modal-title" id="editarTransacaoModalLabel">Editar Transação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Formulário para editar transação -->
            <form action="transacoes.php" method="POST">
                <input type="hidden" name="acao" value="editarTransacao">
                <div class="modal-body">
                    <!-- Campo oculto para armazenar o ID da transação -->
                    <input type="hidden" id="editarTransacaoId" name="transacaoId">
                    <!-- Campo para o título da transação -->
                    <div class="form-group">
                        <label for="editarTituloTransacao">Título</label>
                        <input type="text" class="form-control" id="editarTituloTransacao" name="tituloTransacao" required>
                    </div>
                    <!-- Campo para a descrição da transação -->
                    <div class="form-group">
                        <label for="editarDescricaoTransacao">Descrição</label>
                        <textarea class="form-control" id="editarDescricaoTransacao" name="descricaoTransacao" required></textarea>
                    </div>
                    <!-- Campo para o valor da transação -->
                    <div class="form-group">
                        <label for="editarValorTransacao">Valor</label>
                        <input type="number" class="form-control" id="editarValorTransacao" name="valorTransacao" step="0.01" required>
                    </div>
                    <!-- Campo para a data da transação -->
                    <div class="form-group">
                        <label for="editarDataTransacao">Data</label>
                        <input type="date" class="form-control" id="editarDataTransacao" name="dataTransacao" required>
                    </div>
                    <!-- Campo para selecionar o tipo da transação -->
                    <div class="form-group">
                        <label for="editarTipoTransacao">Tipo</label>
                        <select class="form-control" id="editarTipoTransacao" name="tipoTransacao" required>
                            <option value="Despesa">Despesa</option>
                            <option value="Receita">Receita</option>
                            <option value="Transferência">Transferência</option>
                        </select>
                    </div>
                    <!-- Campo para selecionar o status da transação -->
                    <div class="form-group">
                        <label for="editarStatusTransacao">Status</label>
                        <select class="form-control" id="editarStatusTransacao" name="statusTransacao" required>
                            <option value="Pendente">Pendente</option>
                            <option value="Efetivada">Efetivada</option>
                            <option value="Cancelada">Cancelada</option>
                        </select>
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

<div class="modal fade" id="excluirTransacaoModal" tabindex="-1" aria-labelledby="excluirTransacaoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- Título do modal para confirmação de exclusão -->
                <h5 class="modal-title" id="excluirTransacaoModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Formulário para exclusão de transação -->
            <form action="transacoes.php" method="POST">
                <input type="hidden" name="acao" value="excluirTransacao">
                <input type="hidden" id="excluirTransacaoId" name="transacaoId">
                <div class="modal-body">
                    <!-- Mensagem de confirmação -->
                    <p>Tem certeza de que deseja excluir esta transação? Esta ação não pode ser desfeita.</p>
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
        const editarButtons = document.querySelectorAll('[data-target="#editarTransacaoModal"]');
        editarButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Obtém os atributos de dados do botão clicado
                const id = this.getAttribute('data-id');
                const titulo = this.getAttribute('data-titulo');
                const descricao = this.getAttribute('data-descricao');
                const valor = this.getAttribute('data-valor');
                const data = this.getAttribute('data-data');
                const tipo = this.getAttribute('data-tipo');
                const status = this.getAttribute('data-status');

                // Preenche os campos do modal com os valores obtidos
                document.getElementById('editarTransacaoId').value = id;
                document.getElementById('editarTituloTransacao').value = titulo;
                document.getElementById('editarDescricaoTransacao').value = descricao;
                document.getElementById('editarValorTransacao').value = valor;
                document.getElementById('editarDataTransacao').value = data;
                document.getElementById('editarTipoTransacao').value = tipo;
                document.getElementById('editarStatusTransacao').value = status;
            });
        });

        // Seleciona todos os botões que abrem o modal de exclusão
        const excluirButtons = document.querySelectorAll('[data-target="#excluirTransacaoModal"]');
        excluirButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Obtém o ID da transação do botão clicado
                const id = this.getAttribute('data-id');
                // Preenche o campo oculto do modal com o ID da transação
                document.getElementById('excluirTransacaoId').value = id;
            });
        });
    });
</script>