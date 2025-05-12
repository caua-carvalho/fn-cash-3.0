<!-- Adicionar atributos ARIA para acessibilidade -->
<div class="modal fade" id="orcamentoModal" tabindex="-1" 
     aria-labelledby="orcamentoModalLabel" aria-hidden="true" 
     role="dialog" aria-modal="true">
    <!-- Resto do código do modal -->
    
    <!-- Adicionar feedback para campos de formulário -->
    <div class="form-group">
        <label for="titulo" id="label-titulo">Título do Orçamento</label>
        <input type="text" class="form-control" id="titulo" 
               name="titulo" placeholder=" " required
               aria-labelledby="label-titulo"
               aria-describedby="titulo-feedback">
        <div id="titulo-feedback" class="invalid-feedback">
            Por favor, informe um título para o orçamento.
        </div>
    </div>
    
    <!-- Repetir para outros campos, adicionando feedback de validação -->
</div>

<!-- Adicionar animação de transição entre abas -->
<style>
.tab-content {
    transition: opacity 0.3s ease-out;
}
</style>