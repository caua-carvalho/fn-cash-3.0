<?php
require_once 'header.php';
require_once 'sidebar.php';
require_once 'categoria/funcoes.php';
require_once 'categoria/script.php';
require_once 'categoria/modal.php';
require_once 'dialog.php';
?>

<head>
    <link rel="stylesheet" href="categoria/categoria.css">
</head>

<div class="main-panel">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
          <div class="container-fluid">
            <a class="navbar-brand" href="#">Gerenciamento de Categoria</a>
            <button class="navbar-toggler" type="button" id="sidebar-toggle">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="d-flex align-items-center">
              <div class="search-bar me-3">
                <input type="text" placeholder="Buscar..." />
                <button class="icon-btn">
                  <i class="fas fa-search"></i>
                </button>
              </div>
              <button class="icon-btn me-3">
                <i class="fas fa-th"></i>
              </button>
              <button class="icon-btn me-3">
                <i class="fas fa-bell"></i>
              </button>
              <button class="icon-btn">
                <i class="fas fa-user"></i>
              </button>
            </div>
          </div>
        </nav>
  
        <!-- Content -->
        <div class="content">
          <div class="container-fluid">
            <!-- Header -->
            <div class="category-header">
              <div>
                <h2>Categorias</h2>
                <p class="text-muted">Gerencie todas as categorias do seu site</p>
              </div>
              <div>
                <button class="btn btn-primary btn-icon" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                  <i class="fas fa-plus"></i> Adicionar Categoria
                </button>
              </div>
            </div>
  
            <!-- Stats Row -->
            <div class="row stats-row">
              <div class="col-lg-3 col-md-6">
                <div class="stat-card-new">
                  <div class="stat-info">
                    <h3 class="stat-number">42</h3>
                    <p class="stat-title">TOTAL DE CATEGORIAS</p>
                    <div class="stat-progress">
                      <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 5.45%
                      </span>
                      Desde o último mês
                    </div>
                  </div>
                  <div class="stat-icon red-icon">
                    <i class="fas fa-layer-group"></i>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="stat-card-new">
                  <div class="stat-info">
                    <h3 class="stat-number">584</h3>
                    <p class="stat-title">PRODUTOS</p>
                    <div class="stat-progress">
                      <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 3.2%
                      </span>
                      Desde o último mês
                    </div>
                  </div>
                  <div class="stat-icon orange-icon">
                    <i class="fas fa-box"></i>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="stat-card-new">
                  <div class="stat-info">
                    <h3 class="stat-number">68.5%</h3>
                    <p class="stat-title">TAXA DE ENGAJAMENTO</p>
                    <div class="stat-progress">
                      <span class="stat-change negative">
                        <i class="fas fa-arrow-down"></i> 1.10%
                      </span>
                      Desde a última semana
                    </div>
                  </div>
                  <div class="stat-icon yellow-icon">
                    <i class="fas fa-eye"></i>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6">
                <div class="stat-card-new">
                  <div class="stat-info">
                    <h3 class="stat-number">49.65%</h3>
                    <p class="stat-title">TAXA DE CONVERSÃO</p>
                    <div class="stat-progress">
                      <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> 12%
                      </span>
                      Desde o último mês
                    </div>
                  </div>
                  <div class="stat-icon teal-icon">
                    <i class="fas fa-chart-line"></i>
                  </div>
                </div>
              </div>
            </div>
  
            <!-- Filters -->
            <div class="filters-container">
              <button class="filter-item active">Todas (42)</button>
              <button class="filter-item">Ativas (38)</button>
              <button class="filter-item">Inativas (4)</button>
              <button class="filter-item">Alta Demanda (12)</button>
              <button class="filter-item">Em Promoção (8)</button>
            </div>
  
            <!-- Category Grid -->
            <div class="row">
              <!-- Categoria 1 -->
              <div class="col-lg-4 col-md-6" data-bs-toggle="modal" data-bs-target="#categoryDetailModal">
                <div class="category-card">
                  <img src="/api/placeholder/400/180" alt="Eletrônicos" class="category-image">
                  <div class="category-content">
                    <div class="category-title">
                      <h4 class="category-name">Eletrônicos</h4>
                      <span class="category-badge badge-info">Alta Demanda</span>
                    </div>
                    <p class="category-description">Smartphones, laptops, tablets e outros dispositivos eletrônicos.</p>
                    <div class="category-stats">
                      <div class="stat">
                        <span class="stat-value">128</span>
                        <span class="stat-label">Produtos</span>
                      </div>
                      <div class="stat">
                        <span class="stat-value">86%</span>
                        <span class="stat-label">Visibilidade</span>
                      </div>
                      <div class="stat">
                        <span class="stat-value">62%</span>
                        <span class="stat-label">Conversão</span>
                      </div>
                    </div>
                    <div class="progress">
                      <div class="progress-bar bg-info" style="width: 86%"></div>
                    </div>
                    <div class="category-actions">
                      <button class="action-btn">
                        <i class="fas fa-eye"></i> Ver Detalhes
                      </button>
                      <button class="action-btn">
                        <i class="fas fa-edit"></i> Editar
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Categoria 2 -->
              <div class="col-lg-4 col-md-6" data-bs-toggle="modal" data-bs-target="#categoryDetailModal">
                <div class="category-card">
                  <img src="/api/placeholder/400/180" alt="Moda" class="category-image">
                  <div class="category-content">
                    <div class="category-title">
                      <h4 class="category-name">Moda</h4>
                      <span class="category-badge">Popular</span>
                    </div>
                    <p class="category-description">Roupas, calçados, acessórios e artigos de moda.</p>
                    <div class="category-stats">
                      <div class="stat">
                        <span class="stat-value">215</span>
                        <span class="stat-label">Produtos</span>
                      </div>
                      <div class="stat">
                        <span class="stat-value">78%</span>
                        <span class="stat-label">Visibilidade</span>
                      </div>
                      <div class="stat">
                        <span class="stat-value">54%</span>
                        <span class="stat-label">Conversão</span>
                      </div>
                    </div>
                    <div class="progress">
                      <div class="progress-bar bg-primary" style="width: 78%"></div>
                    </div>
                    <div class="category-actions">
                      <button class="action-btn">
                        <i class="fas fa-eye"></i> Ver Detalhes
                      </button>
                      <button class="action-btn">
                        <i class="fas fa-edit"></i> Editar
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Categoria 3 -->
              <div class="col-lg-4 col-md-6" data-bs-toggle="modal" data-bs-target="#categoryDetailModal">
                <div class="category-card">
                  <img src="/api/placeholder/400/180" alt="Casa e Decoração" class="category-image">
                  <div class="category-content">
                    <div class="category-title">
                      <h4 class="category-name">Casa e Decoração</h4>
                      <span class="category-badge badge-success">Em Alta</span>
                    </div>
                    <p class="category-description">Móveis, decoração, iluminação e artigos para casa.</p>
                    <div class="category-stats">
                      <div class="stat">
                        <span class="stat-value">94</span>
                        <span class="stat-label">Produtos</span>
                      </div>
                      <div class="stat">
                        <span class="stat-value">65%</span>
                        <span class="stat-label">Visibilidade</span>
                      </div>
                      <div class="stat">
                        <span class="stat-value">48%</span>
                        <span class="stat-label">Conversão</span>
                      </div>
                    </div>
                    <div class="progress">
                      <div class="progress-bar bg-success" style="width: 65%"></div>
                    </div>
                    <div class="category-actions">
                      <button class="action-btn">
                        <i class="fas fa-eye"></i> Ver Detalhes
                      </button>
                      <button class="action-btn">
                        <i class="fas fa-edit"></i> Editar
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Categoria 4 -->
              <div class="col-lg-4 col-md-6" data-bs-toggle="modal" data-bs-target="#categoryDetailModal">
                <div class="category-card">
                  <img src="/api/placeholder/400/180" alt="Esportes" class="category-image">
                  <div class="category-content">
                    <div class="category-title">
                      <h4 class="category-name">Esportes e Fitness</h4>
                      <span class="category-badge badge-warning">Sazonal</span>
                    </div>
                    <p class="category-description">Equipamentos esportivos, roupas fitness e acessórios.</p>
                    <div class="category-stats">
                      <div class="stat">
                        <span class="stat-value">76</span>
                        <span class="stat-label">Produtos</span>
                      </div>
                      <div class="stat">
                        <span class="stat-value">59%</span>
                        <span class="stat-label">Visibilidade</span>
                      </div>
                      <div class="stat">
                        <span class="stat-value">41%</span>
                        <span class="stat-label">Conversão</span>
                      </div>
                    </div>
                    <div class="progress">
                      <div class="progress-bar bg-warning" style="width: 59%"></div>
                    </div>
                    <div class="category-actions">
                      <button class="action-btn">
                        <i class="fas fa-eye"></i> Ver Detalhes
                      </button>
                      <button class="action-btn">
                        <i class="fas fa-edit"></i> Editar
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Categoria 5 -->
              <div class="col-lg-4 col-md-6" data-bs-toggle="modal" data-bs-target="#categoryDetailModal">
                <div class="category-card">
                  <img src="/api/placeholder/400/180" alt="Beleza e Saúde" class="category-image">
                  <div class="category-content">
                    <div class="category-title">
                      <h4 class="category-name">Beleza e Saúde</h4>
                      <span class="category-badge">Popular</span>
                    </div>
                    <p class="category-description">Cosméticos, cuidados pessoais e produtos de beleza.</p>
                    <div class="category-stats">
                      <div class="stat">
                        <span class="stat-value">112</span>
                        <span class="stat-label">Produtos</span>
                      </div>
                      <div class="stat">
                        <span class="stat-value">72%</span>
                        <span class="stat-label">Visibilidade</span>
                      </div>
                      <div class="stat">
                        <span class="stat-value">58%</span>
                        <span class="stat-label">Conversão</span>
                      </div>
                    </div>
                    <div class="progress">
                      <div class="progress-bar bg-primary" style="width: 72%"></div>
                    </div>
                    <div class="category-actions">
                      <button class="action-btn">
                        <i class="fas fa-eye"></i> Ver Detalhes
                      </button>
                      <button class="action-btn">
                        <i class="fas fa-edit"></i> Editar
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Categoria 6 -->
              <div class="col-lg-4 col-md-6" data-bs-toggle="modal" data-bs-target="#categoryDetailModal">
                <div class="category-card">
                  <img src="/api/placeholder/400/180" alt="Alimentos" class="category-image">
                  <div class="category-content">
                    <div class="category-title">
                      <h4 class="category-name">Alimentos e Bebidas</h4>
                      <span class="category-badge badge-danger">Destaque</span>
                    </div>
                    <p class="category-description">Alimentos, bebidas, produtos gourmet e delicatessen.</p>
                    <div class="category-stats">
                      <div class="stat">
                        <span class="stat-value">83</span>
                        <span class="stat-label">Produtos</span>
                      </div>
                      <div class="stat">
                        <span class="stat-value">67%</span>
                        <span class="stat-label">Visibilidade</span>
                      </div>
                      <div class="stat">
                        <span class="stat-value">53%</span>
                        <span class="stat-label">Conversão</span>
                      </div>
                    </div>
                    <div class="progress">
                      <div class="progress-bar bg-danger" style="width: 67%"></div>
                    </div>
                    <div class="category-actions">
                      <button class="action-btn">
                        <i class="fas fa-eye"></i> Ver Detalhes
                      </button>
                      <button class="action-btn">
                        <i class="fas fa-edit"></i> Editar
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Paginação -->
            <nav aria-label="Page navigation">
              <ul class="pagination justify-content-center">
                <li class="page-item">
                  <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                  <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
<?php
require_once 'footer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se a ação foi definida
    $acao = $_POST['acao'] ?? null;

    if (!$acao) {
        erro("Ação não definida.");
        exit;
    }

    // Obtém os dados enviados
    $id = $_POST['categoriaId'] ?? null;
    $nome = $_POST['nomeCategoria'] ?? '';
    $tipo = $_POST['tipoCategoria'] ?? '';
    $descricao = $_POST['descricaoCategoria'] ?? '';
    $status = $_POST['statusCategoria'] ?? true;

    // Processa a ação solicitada
    switch ($acao) {
        case 'editarCategoria':
            if (editarCategoria($id, $nome, $tipo, $descricao, $status)) {
                confirmar("Categoria editada com sucesso!", "categorias.php");
            } else {
                erro("Erro ao editar categoria. Verifique os dados e tente novamente.");
            }
            break;

        case 'cadastrarCategoria':
            if (cadastrarCategoria($nome, $tipo, $descricao, $status)) {
                confirmar("Categoria cadastrada com sucesso!", "categorias.php");
            } else {
                erro("Erro ao cadastrar categoria. Verifique os dados e tente novamente.");
            }
            break;
        case 'excluirCategoria':
            if (deletarCategoria($id)) {
                confirmar("Categoria excluída com sucesso!", "categorias.php");
            } else {
                erro("Erro ao excluir categoria. Verifique os dados e tente novamente.");
            }
            break;

        default:
            erro("Ação inválida.");
            break;
    }
}
?>