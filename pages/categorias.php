<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes da Conta - App Financeiro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <!-- Conteúdo principal -->
    <div class="content">
        <div class="header">
            <h2>Categorias</h2>
            <div class="d-flex">
                <select class="month-selector mr-2 text-white bg-dark">
                    <option value="Jan">Janeiro</option>
                    <option value="Fev">Fevereiro</option>
                    <option value="Mar">Março</option>
                    <option value="Abr">Abril</option>
                    <option value="Mai">Maio</option>
                    <option value="Jun">Junho</option>
                    <option value="Jul">Julho</option>
                    <option value="Ago">Agosto</option>
                    <option value="Set">Setembro</option>
                    <option value="Out">Outubro</option>
                    <option value="Nov">Novembro</option>
                    <option value="Dez">Dezembro</option>
                </select>
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <span>João Vítor</span>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Card Nova Conta -->
            <div class="col-md-4 mb-4">
                <div class="new-account-card" data-toggle="modal" data-target="#novaConta">
                    <div class="add-circle">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div>Nova conta</div>
                </div>
            </div>
            
            <!-- Card Conta Itaú -->
            <div class="col-md-4 mb-4">
                <div class="account-card" data-toggle="modal" data-target="#detalhesModal">
                    <div class="account-header">
                        <div class="bank-logo">itaú</div>
                        <div class="account-name">Conta Itaú</div>
                    </div>
                    <div class="account-balance">
                        <div>Saldo atual</div>
                        <div class="account-value">R$ 0,00</div>
                    </div>
                    <div class="account-balance">
                        <div>Saldo previsto</div>
                        <div class="account-value">R$ 0,00</div>
                    </div>
                    <div class="text-center mt-4">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#despesaModal">ADICIONAR DESPESA</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>