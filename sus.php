<?php
require_once "config.php"; 

$sql = "SELECT id, username, attempt_time, attempt_count FROM sus ORDER BY attempt_time DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$intruders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intruder Log</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        h2 {
            color: #dc3545;
        }
        table {
            background: white;
        }
        .navbar {
            background-color: #333;
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
        }
        .container {
            margin-top: 20px;
        }
        .lipstick-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .lipstick-item {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .lipstick-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .like-button {
            background-color: #ffc107;
            color: #333;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .liked {
            background-color: #ff5722;
            color: white;
        }
    </style>
</head>
<body>
   
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">Dashboard</a>
    <div class="navbar-nav ml-auto">
    <a class="nav-item nav-link" href="sus.php">warning</a>
        <a class="nav-item nav-link" href="joindata.php">Join Data</a>
        <a class="nav-item nav-link" href="logout.php">Logout</a>
    </div>
</nav>
    <div class="container">
        <h2 class="text-center">Intruder Attempt Logs</h2>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Attempt Count</th>
                    <th>Last Attempt Time</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($intruders) > 0): ?>
                    <?php foreach ($intruders as $intruder): ?>
                        <tr>
                            <td><?= htmlspecialchars($intruder['id']); ?></td>
                            <td><?= htmlspecialchars($intruder['username']); ?></td>
                            <td><?= htmlspecialchars($intruder['attempt_count']); ?></td>
                            <td><?= htmlspecialchars($intruder['attempt_time']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No intruder attempts found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
