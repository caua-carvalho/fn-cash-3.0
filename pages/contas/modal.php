<?php
require '../header.php';
require 'contas/script.php';
?>

<script src="contas/modal/modal.js"></script>

<!-- Link para FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
