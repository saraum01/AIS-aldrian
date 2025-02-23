<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php";

// Define variables to hold table results
$join_table = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['right_join'])) {
        // Perform RIGHT JOIN operation
        $sql = "SELECT u.username, d.name as dance_group, r.rating, r.comment
                FROM ratings r
                RIGHT JOIN users u ON r.user_id = u.id
                RIGHT JOIN dance_groups d ON r.dance_group_id = d.id";
    } elseif (isset($_POST['left_join'])) {
        // Perform LEFT JOIN operation
        $sql = "SELECT u.username, d.name as dance_group, r.rating, r.comment
                FROM ratings r
                LEFT JOIN users u ON r.user_id = u.id
                LEFT JOIN dance_groups d ON r.dance_group_id = d.id";
    }

    $stmt = $pdo->query($sql);
    $join_table = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Join Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background-color: #f9f9f9; color: #333; }
        .navbar { background-color: #333; color: #fff; }
        .navbar a { color: #fff; }
        .container { max-width: 900px; margin-top: 20px; }
        .table-container { margin-top: 20px; }
        .table th, .table td { text-align: center; }
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
    <h2 class="mt-4">Join Data</h2>

    <!-- Buttons for LEFT JOIN and RIGHT JOIN -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <button type="submit" name="right_join" class="btn btn-primary mr-2">Right Join</button>
        <button type="submit" name="left_join" class="btn btn-success">Left Join</button>
    </form>

    <!-- Display the results of the JOIN operation -->
    <?php if (!empty($join_table)): ?>
    <div class="table-container">
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Dance Group</th>
                    <th>Rating</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($join_table as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['dance_group']); ?></td>
                        <td><?php echo $row['rating'] ? $row['rating'] : "No Rating"; ?></td>
                        <td><?php echo htmlspecialchars($row['comment']) ? htmlspecialchars($row['comment']) : "No Comment"; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

</div>

</body>
</html>
