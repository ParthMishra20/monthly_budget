// Elements for the charts
const yearlyCtx = document.getElementById('yearlySpendingGraph').getContext('2d');
const monthlyCtx = document.getElementById('monthlySpendingPieChart').getContext('2d');

// Initialize empty charts
let yearlyChart, monthlyChart;

// Function to update charts
function updateCharts() {
    fetch('fetch_chart_data.php')
        .then(response => response.json())
        .then(data => {
            if (!data.yearlySpending || !data.monthlySpending) {
                console.error('Invalid data received');
                return;
            }

            // Update Yearly Spending Chart
            if (yearlyChart) {
                yearlyChart.data.datasets[0].data = data.yearlySpending;
                yearlyChart.update();
            } else {
                yearlyChart = new Chart(yearlyCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Yearly Spending',
                            data: data.yearlySpending,
                            borderColor: '#3498db',
                            fill: false,
                        }]
                    }
                });
            }

            // Update Monthly Spending Pie Chart
            if (monthlyChart) {
                monthlyChart.data.datasets[0].data = data.monthlySpending;
                monthlyChart.update();
            } else {
                monthlyChart = new Chart(monthlyCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Food', 'Travel', 'Subscription', 'Unexpected'],
                        datasets: [{
                            data: data.monthlySpending,
                            backgroundColor: ['#2ecc71', '#e74c3c', '#f1c40f', '#8e44ad'],
                        }]
                    }
                });
            }
        })
        .catch(error => console.error('Error fetching chart data:', error));
}

// Initial load
updateCharts();

// Periodic update every 10 seconds
setInterval(updateCharts, 10000);
