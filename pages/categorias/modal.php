<!-- Modal de Detalhes da Conta -->
<div class="modal fade" id="detalhesModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes da conta</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <button class="carteira-btn">
                            Carteira <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    
                    <div class="text-center mb-3">
                        <div>Saldo atual</div>
                        <h3>R$ 0,00</h3>
                    </div>
                    
                    <div class="text-center mb-4">
                        <button class="btn btn-reajuste">REAJUSTE DE SALDO</button>
                    </div>
                    
                    <div class="detail-row">
                        <div>Tipo de conta</div>
                        <div class="detail-value">Dinheiro</div>
                    </div>
                    
                    <div class="detail-row">
                        <div>Saldo inicial</div>
                        <div class="detail-value">R$ 0,00</div>
                    </div>
                    
                    <div class="detail-row">
                        <div>Quantidade de despesas</div>
                        <a href="#" class="count-link text-orange">
                            0 despesas <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                    
                    <div class="detail-row">
                        <div>Quantidade de receitas</div>
                        <a href="#" class="count-link text-green">
                            0 receitas <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                    
                    <div class="detail-row">
                        <div>Quantidade de transferências</div>
                        <a href="#" class="count-link">
                            0 transferências <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                    
                    <div class="detail-row">
                        <div>Incluir na soma da tela inicial</div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">EDITAR CONTA</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Despesa -->
    <div class="modal fade" id="despesaModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Despesa</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <!-- Campo Valor com input group -->
                        <div class="form-group">
                            <label for="expenseAmount">Valor do gasto</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>
                                <input type="number" class="form-control" id="expenseAmount" placeholder="0,00">
                            </div>
                        </div>
                        <!-- Campo Categoria -->
                        <div class="form-group">
                            <label for="category">Categoria</label>
                            <select class="form-control" id="category">
                                <option>Alimentação</option>
                                <option>Transporte</option>
                                <option>Saúde</option>
                                <option>Lazer</option>
                                <option>Outros</option>
                            </select>
                        </div>
                        <!-- Campo Data -->
                        <div class="form-group">
                            <label for="date">Data</label>
                            <input type="date" class="form-control" id="date">
                        </div>
                        <!-- Campo Descrição -->
                        <div class="form-group">
                            <label for="description">Descrição</label>
                            <textarea class="form-control" id="description" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Nova Conta -->
    <div class="modal fade" id="novaConta" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nova conta</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="accountName">Nome da conta</label>
                            <input type="text" class="form-control" id="accountName" placeholder="Ex: Conta Nubank">
                        </div>
                        <div class="form-group">
                            <label for="accountType">Tipo de conta</label>
                            <select class="form-control" id="accountType">
                                <option>Dinheiro</option>
                                <option>Conta corrente</option>
                                <option>Poupança</option>
                                <option>Investimento</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="initialBalance">Saldo inicial</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>
                                <input type="number" class="form-control" id="initialBalance" placeholder="0,00">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>