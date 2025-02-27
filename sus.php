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
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f1ea; /* Soft beige background */
            font-family: 'Quicksand', sans-serif;
            color: #3e3e3e; /* Dark gray for text */
        }
        .navbar {
            background-color:rgb(102, 102, 102); /* Brown navbar */
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
        }
        .container {
            max-width: 800px; /* Smaller table container */
            margin-top: 50px;
        }
        h2 {
            text-align: center;
            color:rgb(0, 0, 0);
            font-size: 2em;
            margin-bottom: 40px;
        }
        /* Creative table design */
        table {
            background: rgba(255, 255, 255, 0.7); /* Transparent background */
            backdrop-filter: blur(10px); /* Blur effect */
            border-radius: 10px;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.1); /* Shadow effect */
            width: 100%;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.1); /* Soft border */
        }
        .thead-dark th {
            background-color:rgb(0, 0, 0); /* Dark brown header */
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        td, th {
            padding: 12px 20px;
            text-align: left;
            font-size: 16px;
        }
        tr:hover {
            background-color: rgba(161, 165, 223, 0.9); /* Light hover effect */
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        td {
            color:rgb(0, 0, 0); /* Medium brown text */
        }
        .no-record {
            text-align: center;
            font-size: 18px;
            color: #777;
            margin-top: 40px;
        }
        .navbar-nav .nav-item {
            margin-left: 20px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">Dashboard</a>
    <div class="navbar-nav ml-auto">
        <a class="nav-item nav-link" href="sus.php">Warning</a>
      
        <a class="nav-item nav-link" href="logout.php">Logout</a>
    </div>
</nav>

<div class="container">
    <h2>ATTEMPS LOGS</h2>

    <?php if (count($intruders) === 0): ?>
        <div class="no-record">No intruder attempts found.</div>
    <?php else: ?>
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Attempt Count</th>
                    <th>Last Attempt Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($intruders as $intruder): ?>
                    <tr>
                        <td><?= htmlspecialchars($intruder['id']); ?></td>
                        <td><?= htmlspecialchars($intruder['username']); ?></td>
                        <td><?= htmlspecialchars($intruder['attempt_count']); ?></td>
                        <td><?= htmlspecialchars($intruder['attempt_time']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
