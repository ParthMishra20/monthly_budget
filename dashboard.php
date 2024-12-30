<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
include 'includes/db.php';
$user_id = $_SESSION['user_id'];

// Fetch total income
$income_result = $conn->query("SELECT SUM(income) AS total_income FROM monthly_income WHERE user_id = $user_id");
$income = $income_result->fetch_assoc()['total_income'] ?? 0;

// Fetch total spending
$spend_result = $conn->query("SELECT SUM(amount) AS total_spend FROM transactions WHERE user_id = $user_id AND type = 'spend'");
$spend = $spend_result->fetch_assoc()['total_spend'] ?? 0;

// Fetch total receiving
$receive_result = $conn->query("SELECT SUM(amount) AS total_receive FROM transactions WHERE user_id = $user_id AND type = 'receive'");
$receive = $receive_result->fetch_assoc()['total_receive'] ?? 0;

// Calculate current balance
$current_balance = $income - $spend + $receive;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/dashboard.js"></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Welcome to Your Dashboard</h1>
        </header>

        <div class="charts">
    <div class="chart-container">
        <h2>Yearly Spending</h2>
        <canvas id="yearlySpendingGraph"></canvas>
    </div>
    <div class="chart-container">
        <h2>Monthly Spending</h2>
        <canvas id="monthlySpendingPieChart"></canvas>
    </div>
</div>

<script>
// Function to fetch data and update charts
function fetchChartData() {
    fetch('fetch_chart_data.php')
        .then(response => response.json())
        .then(data => {
            // Update the yearly spending graph
            var yearlySpendingCtx = document.getElementById('yearlySpendingGraph').getContext('2d');
            var yearlySpendingChart = new Chart(yearlySpendingCtx, {
                type: 'line',
                data: {
                    labels: data.yearly.labels, // The months
                    datasets: [{
                        label: 'Yearly Spending',
                        data: data.yearly.spending, // The spending amounts
                        borderColor: 'rgba(75, 192, 192, 1)',
                        fill: false
                    }]
                }
            });

            // Update the monthly spending pie chart
            var monthlySpendingCtx = document.getElementById('monthlySpendingPieChart').getContext('2d');
            var monthlySpendingChart = new Chart(monthlySpendingCtx, {
                type: 'pie',
                data: {
                    labels: data.monthly.labels, // The tags (e.g., food, travel, etc.)
                    datasets: [{
                        data: data.monthly.spending, // Spending amounts per tag
                        backgroundColor: ['red', 'blue', 'green', 'yellow'],
                    }]
                }
            });
        })
        .catch(error => console.error('Error fetching chart data:', error));
}

// Fetch chart data when the page loads
window.onload = fetchChartData;
</script>


        <section class="income">
            <form method="post" action="update_income.php">
                <label for="monthlyIncome">Enter Your Monthly Income:</label>
                <input type="number" id="monthlyIncome" name="income" required>
                <button type="submit">Submit</button>
            </form>
        </section>

        <section class="transactions">
            <h2>Manage Transactions</h2>
            <form method="post" action="add_transaction.php">
                <select name="type">
                    <option value="spend">Spend</option>
                    <option value="receive">Receive</option>
                </select>
                <input type="date" name="date" required>
                <input type="number" name="amount" required>
                <input type="text" name="description" placeholder="Description">
                <select name="tag">
                    <option value="food">Food</option>
                    <option value="travel">Travel</option>
                    <option value="subscription">Subscription</option>
                    <option value="unexpected">Unexpected</option>
                </select>
                <button type="submit">Add Transaction</button>
            </form>
        </section>

        <section class="balance">
            <h3>Current Balance: 
                <?php echo $current_balance; ?>
            </h3>
        </section>
        <div>
    <h2>Transaction History</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Tag</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch transactions from the database
            $query = $conn->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC");
            $query->bind_param("i", $user_id);
            $query->execute();
            $result = $query->get_result();

            // Display each transaction
            while ($row = $result->fetch_assoc()) {
                $type_class = ($row['type'] == 'spend') ? 'spend' : 'receive';
                echo "<tr class='$type_class'>";
                echo "<td>" . $row['date'] . "</td>";
                echo "<td>" . ucfirst($row['type']) . "</td>";
                echo "<td>" . ($row['type'] == 'spend' ? '-' : '+') . " " . $row['amount'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
                echo "<td>" . $row['tag'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

    </div>
</body>
</html>
