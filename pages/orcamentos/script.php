<script>
document.addEventListener('DOMContentLoaded', function () {
    const editarButtons = document.querySelectorAll('[data-target="#editarOrcamentoModal"]');
    editarButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const categoriaId = this.getAttribute('data-categoria-id');
            const valorPlanejado = this.getAttribute('data-valor-planejado');
            const periodo = this.getAttribute('data-periodo');
            const status = this.getAttribute('data-status');

            document.getElementById('editarOrcamentoId').value = id;
            document.getElementById('editarCategoriaId').value = categoriaId;
            document.getElementById('editarValorPlanejado').value = valorPlanejado;
            document.getElementById('editarPeriodo').value = periodo;
            document.getElementById('editarStatus').value = status;
        });
    });

    const excluirButtons = document.querySelectorAll('[data-target="#excluirOrcamentoModal"]');
    excluirButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            document.getElementById('excluirOrcamentoId').value = id;
        });
    });
});
</script>