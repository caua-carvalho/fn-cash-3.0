document.addEventListener('DOMContentLoaded', function() {
    // Configuração do gráfico de Receitas vs Despesas
    const financeCtx = document.getElementById('financeChart').getContext('2d');

    const despesaGradient = financeCtx.createLinearGradient(0, 0, 0, 350);
    despesaGradient.addColorStop(0, 'rgba(231, 43, 22, 0.88)'); // Vermelho com transparência
    despesaGradient.addColorStop(1, 'rgba(231, 76, 60, 0)');   // Vermelho totalmente transparente

    // Criando gradientes para as áreas do gráfico
    const receitaGradient = financeCtx.createLinearGradient(0, 0, 0, 350);
    receitaGradient.addColorStop(0, 'rgba(7, 163, 98, 0.6)');  // Verde com transparência
    receitaGradient.addColorStop(1, 'rgba(7, 163, 98, 0)');    // Verde totalmente transparente

    const financeChart = new Chart(financeCtx, {
        type: 'line',
        data: {
            labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho'],
            datasets: [
                {
                    label: 'Despesas',
                    data: [2000, 2100, 1800, 2200, 2000, 3000],
                    backgroundColor: despesaGradient,
                    borderColor: '#e74c3c',  // Vermelho para despesas
                    borderWidth: 2,
                    pointBackgroundColor: '#e74c3c',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Receitas',
                    data: [3000, 3200, 2800, 3500, 3100, 600],
                    backgroundColor: receitaGradient,
                    borderColor: '#07a362',  // Verde FnCash
                    borderWidth: 2,
                    pointBackgroundColor: '#07a362',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Configuração do gráfico de Categorias
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Alimentação', 'Saúde', 'Entretenimento', 'Outros'],
            datasets: [{
                data: [200, 300, 50, 150],
                backgroundColor: [
                    '#07a362',  // Verde FnCash para Alimentação
                    '#e74c3c',  // Vermelho para Saúde
                    '#3498db',  // Azul para Entretenimento
                    '#f39c12'   // Amarelo para Outros
                ],
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});