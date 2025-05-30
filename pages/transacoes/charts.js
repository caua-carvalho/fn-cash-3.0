// Gerado pelo Copilot

document.addEventListener('DOMContentLoaded', function() {
    // Configuração do gráfico de evolução
    const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
    const evolutionChart = new Chart(evolutionCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
            datasets: [
                {
                    label: 'Receitas',
                    data: [1500, 2000, 1800, 2200, 1900, 2500],
                    borderColor: 'rgb(7, 163, 98)',
                    backgroundColor: 'rgba(7, 163, 98, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Despesas',
                    data: [1200, 1800, 1600, 1900, 1700, 2000],
                    borderColor: 'rgb(231, 76, 60)',
                    backgroundColor: 'rgba(231, 76, 60, 0.1)',
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

    // Configuração do gráfico de categorias
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Alimentação', 'Transporte', 'Moradia', 'Lazer', 'Saúde'],
            datasets: [{
                data: [300, 150, 800, 200, 250],
                backgroundColor: [
                    'rgb(7, 163, 98)',
                    'rgb(52, 152, 219)',
                    'rgb(231, 76, 60)',
                    'rgb(241, 196, 15)',
                    'rgb(155, 89, 182)'
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

    // Função para atualizar os gráficos com dados do servidor
    function updateCharts() {
        fetch('transacoes/api/charts-data.php')
            .then(response => response.json())
            .then(data => {
                // Atualiza o gráfico de evolução
                evolutionChart.data.labels = data.evolution.labels;
                evolutionChart.data.datasets[0].data = data.evolution.receitas;
                evolutionChart.data.datasets[1].data = data.evolution.despesas;
                evolutionChart.update();

                // Atualiza o gráfico de categorias
                categoryChart.data.labels = data.categories.labels;
                categoryChart.data.datasets[0].data = data.categories.values;
                categoryChart.update();
            })
            .catch(error => console.error('Erro ao atualizar gráficos:', error));
    }

    // Eventos dos botões de filtro
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            // Aqui você pode adicionar a lógica para filtrar a tabela
        });
    });

    // Atualiza os gráficos periodicamente
    setInterval(updateCharts, 300000); // A cada 5 minutos
});
