<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

// Get KPI data
$query = "SELECT 
    process_type,
    SUM(volume) as total_volume,
    AVG(doh_value) as avg_doh,
    SUM(stock_level) as total_stock
FROM pac_data 
GROUP BY process_type";

$stmt = $db->prepare($query);
$stmt->execute();
$kpi_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - SEBN-MA PAC System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">SEBN-MA PAC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Tableau de bord</a>
                    </li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/users.php">Gestion utilisateurs</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Tableau de bord</h1>
        
        <!-- KPI Cards -->
        <div class="row mb-4">
            <?php foreach ($kpi_data as $kpi): ?>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo ucfirst($kpi['process_type']); ?></h5>
                        <p class="card-text">
                            Volume total: <?php echo number_format($kpi['total_volume']); ?><br>
                            DOH moyen: <?php echo number_format($kpi['avg_doh'], 2); ?><br>
                            Stock total: <?php echo number_format($kpi['total_stock']); ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Volume par Processus</h5>
                        <canvas id="volumeChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Répartition du Stock</h5>
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Prepare data for charts
        const processTypes = <?php echo json_encode(array_column($kpi_data, 'process_type')); ?>;
        const volumes = <?php echo json_encode(array_column($kpi_data, 'total_volume')); ?>;
        const stocks = <?php echo json_encode(array_column($kpi_data, 'total_stock')); ?>;

        // Volume Chart
        new Chart(document.getElementById('volumeChart'), {
            type: 'bar',
            data: {
                labels: processTypes,
                datasets: [{
                    label: 'Volume par Processus',
                    data: volumes,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Stock Chart
        new Chart(document.getElementById('stockChart'), {
            type: 'doughnut',
            data: {
                labels: processTypes,
                datasets: [{
                    data: stocks,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
</body>
</html> 