<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php";

$user_id = $_SESSION["id"];
$username = $_SESSION["username"];
$rating_err = "";

// Handle rating submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dance_group_id = $_POST['dance_group_id'];
    $rating = $_POST['rating'];
    $comment = trim($_POST['comment']);

    // Insert rating and comment into the database
    $sql = "INSERT INTO ratings (user_id, dance_group_id, rating, comment) VALUES (:user_id, :dance_group_id, :rating, :comment)";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":dance_group_id", $dance_group_id, PDO::PARAM_INT);
        $stmt->bindParam(":rating", $rating, PDO::PARAM_INT);
        $stmt->bindParam(":comment", $comment, PDO::PARAM_STR);
        if (!$stmt->execute()) {
            $rating_err = "Error submitting rating. Please try again.";
        }
        unset($stmt);
    }
}

// Fetch dance groups
$sql = "SELECT * FROM dance_groups";
$stmt = $pdo->query($sql);
$dance_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background-color: #f9f9f9; color: #333; }
        .navbar { background-color: #333; color: #fff; }
        .navbar a { color: #fff; }
        .container { max-width: 900px; margin-top: 20px; }
        .dance-group { border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; border-radius: 5px; background-color: #fff; }
        .star-rating input { display: none; }
        .star-rating label { font-size: 1.5em; color: #ddd; cursor: pointer; }
        .star-rating label:hover, .star-rating label:hover ~ label, .star-rating input:checked ~ label { color: #ffd700; }
        .comment-box { margin-top: 10px; }
        .rating-display { margin-top: 15px; border-top: 1px solid #eee; padding-top: 15px; }
        .user-rating { font-weight: bold; color: #333; }
        .user-comment { margin-top: 5px; color: #555; }
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
    <h2 class="mt-4">Rate Your Favorite Dance Groups</h2>

    <?php if (!empty($rating_err)) echo '<div class="alert alert-danger">' . $rating_err . '</div>'; ?>

    <?php foreach ($dance_groups as $group): ?>
    <div class="dance-group">
        <h3><?php echo htmlspecialchars($group['name']); ?></h3>
        <p><?php echo htmlspecialchars($group['description']); ?></p>
        <img src="<?php echo htmlspecialchars($group['picture_url']); ?>" alt="Dance Group" class="img-fluid" style="width:100%; max-height:200px;">
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="dance_group_id" value="<?php echo $group['id']; ?>">

            <div class="star-rating">
                <input type="radio" id="5-stars-<?php echo $group['id']; ?>" name="rating" value="5"><label for="5-stars-<?php echo $group['id']; ?>">&#9733;</label>
                <input type="radio" id="4-stars-<?php echo $group['id']; ?>" name="rating" value="4"><label for="4-stars-<?php echo $group['id']; ?>">&#9733;</label>
                <input type="radio" id="3-stars-<?php echo $group['id']; ?>" name="rating" value="3"><label for="3-stars-<?php echo $group['id']; ?>">&#9733;</label>
                <input type="radio" id="2-stars-<?php echo $group['id']; ?>" name="rating" value="2"><label for="2-stars-<?php echo $group['id']; ?>">&#9733;</label>
                <input type="radio" id="1-star-<?php echo $group['id']; ?>" name="rating" value="1"><label for="1-star-<?php echo $group['id']; ?>">&#9733;</label>
            </div>

            <textarea name="comment" class="form-control comment-box" placeholder="Add a comment..."></textarea>
            <button type="submit" class="btn btn-dark mt-2">Submit Rating</button>
        </form>

        <!-- Display existing ratings and comments for this group -->
        <div class="rating-display">
            <h4>Ratings & Comments:</h4>
            <?php
            // Fetch ratings and comments for the current dance group
            $sql = "SELECT u.username, r.rating, r.comment 
                    FROM ratings r 
                    JOIN users u ON r.user_id = u.id 
                    WHERE r.dance_group_id = :dance_group_id 
                    ORDER BY r.created_at DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":dance_group_id", $group['id'], PDO::PARAM_INT);
            $stmt->execute();
            $ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($ratings):
                foreach ($ratings as $rate):
            ?>
            <div class="user-rating"><?php echo htmlspecialchars($rate['username']); ?> - <?php echo $rate['rating']; ?> Stars</div>
            <div class="user-comment"><?php echo htmlspecialchars($rate['comment']); ?></div>
            <?php
                endforeach;
            else:
                echo "<p>No ratings yet. Be the first to rate!</p>";
            endif;
            ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

</body>
</html> 
