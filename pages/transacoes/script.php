<script>
    // funcao para forms dinamico de cadastro
    document.addEventListener('DOMContentLoaded', function () {
        const tipoTransacao = document.getElementById('tipoTransacao');
        const contaDestinatariaGroup = document.getElementById('contaDestinataria').parentElement;

        // Exibe ou oculta o campo "Conta Destinatária" com base no tipo de transação
        tipoTransacao.addEventListener('change', function () {
            if (this.value === 'Transferência') {
                contaDestinatariaGroup.style.display = 'block';
                document.getElementById('contaDestinataria').required = true;
            } else {
                contaDestinatariaGroup.style.display = 'none';
                document.getElementById('contaDestinataria').required = false;
            }
        });

        // Inicializa o estado do campo ao carregar a página
        if (tipoTransacao.value !== 'Transferência') {
            contaDestinatariaGroup.style.display = 'none';
            document.getElementById('contaDestinataria').required = false;
        }
    });
</script>