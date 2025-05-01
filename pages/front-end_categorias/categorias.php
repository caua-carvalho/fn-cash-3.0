<?php
require_once 'header.php';
require_once 'sidebar.php';
require_once 'contas/funcoes.php';
require_once 'contas/modal.php';
require_once 'dialog.php';
?>

<head>
  <!-- Link CSS -->
  <link rel="stylesheet" href="categorias.css" />
  <!-- Link Script -->
  <link rel="stylesheet" href="categorias.js" />
</head>

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

<!-- Modal Adicionar Categoria -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addCategoryModalLabel">Adicionar Nova Categoria</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="categoryName" class="form-label">Nome da Categoria</label>
                  <input type="text" class="form-control" id="categoryName" placeholder="Ex: Eletrônicos">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="categoryStatus" class="form-label">Status</label>
                  <select class="form-select" id="categoryStatus">
                    <option selected>Ativo</option>
                    <option>Inativo</option>
                    <option>Rascunho</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="categoryDescription" class="form-label">Descrição</label>
              <textarea class="form-control" id="categoryDescription" rows="3" placeholder="Descreva a categoria..."></textarea>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="categoryImage" class="form-label">Imagem da Categoria</label>
                  <input type="file" class="form-control" id="categoryImage">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="categoryBadge" class="form-label">Etiqueta</label>
                  <select class="form-select" id="categoryBadge">
                    <option>Nenhuma</option>
                    <option>Popular</option>
                    <option>Em Alta</option>
                    <option>Destaque</option>
                    <option>Alta Demanda</option>
                    <option>Sazonal</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Opções Adicionais</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="featuredCategory">
                <label class="form-check-label" for="featuredCategory">
                  Destacar na página inicial
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="showInNav">
                <label class="form-check-label" for="showInNav">
                  Mostrar no menu de navegação
                </label>
              </div>
            </div>

            <div class="form-group">
              <label for="categoryParent" class="form-label">Categoria Pai (opcional)</label>
              <select class="form-select" id="categoryParent">
                <option selected>Nenhuma (categoria raiz)</option>
                <option>Eletrônicos</option>
                <option>Moda</option>
                <option>Casa e Decoração</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary">Salvar Categoria</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Detalhes da Categoria -->
  <div class="modal fade" id="categoryDetailModal" tabindex="-1" aria-labelledby="categoryDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="categoryDetailModalLabel">Detalhes da Categoria</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body p-0">
          <div class="category-detail-header">
            <img src="/api/placeholder/100/100" alt="Categoria" class="category-image-large">
            <div class="category-header-info">
              <h3 class="category-header-title">Eletrônicos</h3>
              <div class="category-header-meta">
                <div class="meta-item">
                  <i class="fas fa-box"></i>
                  <span>128 produtos</span>
                </div>
                <div class="meta-item">
                  <i class="fas fa-eye"></i>
                  <span>86% visibilidade</span>
                </div>
                <div class="meta-item">
                  <i class="fas fa-chart-bar"></i>
                  <span>62% conversão</span>
                </div>
                <div class="meta-item">
                  <i class="fas fa-calendar"></i>
                  <span>Criado em: 12/03/2024</span>
                </div>
              </div>
              <span class="category-badge badge-info">Alta Demanda</span>
            </div>
            <div class="category-action-row">
              <button class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Editar
              </button>
              <button class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i> Excluir
              </button>
            </div>
          </div>

          <ul class="nav nav-tabs" id="categoryDetailTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab" aria-controls="products" aria-selected="true">
                <i class="fas fa-box"></i> Produtos
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab" aria-controls="analytics" aria-selected="false">
                <i class="fas fa-chart-line"></i> Analytics
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">
                <i class="fas fa-cog"></i> Configurações
              </button>
            </li>
          </ul>

          <div class="tab-content" id="categoryDetailTabsContent">
            <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="products-tab">
              <div class="d-flex justify-content-between align-items-center mb-4">
                <h5>Produtos na Categoria (128)</h5>
                <button class="btn btn-primary btn-sm">
                  <i class="fas fa-plus"></i> Adicionar Produto
                </button>
              </div>

              <div class="product-list">
                <div class="product-item">
                  <img src="/api/placeholder/50/50" alt="Produto" class="product-image">
                  <div class="product-info">
                    <div class="product-name">Smartphone XS Pro Max</div>
                    <div class="product-meta">SKU: SP-1234 • Em estoque: 24</div>
                  </div>
                  <div class="product-price">R$ 2.499,00</div>
                </div>

                <div class="product-item">
                  <img src="/api/placeholder/50/50" alt="Produto" class="product-image">
                  <div class="product-info">
                    <div class="product-name">Notebook Ultra Slim 15"</div>
                    <div class="product-meta">SKU: NT-5678 • Em estoque: 12</div>
                  </div>
                  <div class="product-price">R$ 4.899,00</div>
                </div>

                <div class="product-item">
                  <img src="/api/placeholder/50/50" alt="Produto" class="product-image">
                  <div class="product-info">
                    <div class="product-name">Fone de Ouvido Wireless Pro</div>
                    <div class="product-meta">SKU: FO-9012 • Em estoque: 36</div>
                  </div>
                  <div class="product-price">R$ 699,00</div>
                </div>

                <div class="product-item">
                  <img src="/api/placeholder/50/50" alt="Produto" class="product-image">
                  <div class="product-info">
                    <div class="product-name">Smart TV 4K 55"</div>
                    <div class="product-meta">SKU: TV-3456 • Em estoque: 8</div>
                  </div>
                  <div class="product-price">R$ 3.299,00</div>
                </div>

                <div class="product-item">
                  <img src="/api/placeholder/50/50" alt="Produto" class="product-image">
                  <div class="product-info">
                    <div class="product-name">Câmera Digital 24MP</div>
                    <div class="product-meta">SKU: CD-7890 • Em estoque: 15</div>
                  </div>
                  <div class="product-price">R$ 1.899,00</div>
                </div>
              </div>

              <div class="d-flex justify-content-center mt-4">
                <button class="btn btn-outline-primary">Ver Todos os Produtos</button>
              </div>
            </div>

            <div class="tab-pane fade" id="analytics" role="tabpanel" aria-labelledby="analytics-tab">
              <h5 class="mb-4">Analytics da Categoria</h5>
              <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Visualização de analytics não disponível nesta versão de demonstração.
              </div>
            </div>

            <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
              <h5 class="mb-4">Configurações da Categoria</h5>
              <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Configurações avançadas não disponíveis nesta versão de demonstração.
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>