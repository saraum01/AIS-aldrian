<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: dashboard.php");
    exit;
}

require_once "config.php";

$username = $password = "";
$username_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id, username, password FROM users WHERE username = :username";

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $param_username = trim($_POST["username"]);

            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row["id"];
                        $username = $row["username"];
                        $hashed_password = $row["password"];
                        if (password_verify($password, $hashed_password)) {
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            resetAttempts($pdo, $username);

                            header("location: dashboard.php");
                            exit;
                        } else {
                            $login_err = "Invalid username or password.";
                            logIntruder($pdo, $username);
                        }
                    }
                } else {
                    $login_err = "Invalid username or password.";
                    logIntruder($pdo, $username);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            unset($stmt);
        }
    }
    unset($pdo);
}

function logIntruder($pdo, $username) {
    $sql = "SELECT attempt_count FROM sus WHERE username = :username";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();
        unset($stmt);

        if ($result) {
            $new_count = $result["attempt_count"] + 1;
            $sql = "UPDATE sus SET attempt_count = :new_count WHERE username = :username";
            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(":new_count", $new_count, PDO::PARAM_INT);
                $stmt->bindParam(":username", $username, PDO::PARAM_STR);
                $stmt->execute();
                unset($stmt);
            }
        } else {
            $sql = "INSERT INTO sus (username, attempt_count) VALUES (:username, 1)";
            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(":username", $username, PDO::PARAM_STR);
                $stmt->execute();
                unset($stmt);
            }
        }
    }
}

function resetAttempts($pdo, $username) {
    $sql = "DELETE FROM sus WHERE username = :username";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();
        unset($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
            background-color:rgb(190, 190, 190); /* Light gray background */
            color: #333; /* Dark gray text */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
            margin: 0;
            background-image: url('background.jpg'); /* Optional background image */
            background-size: cover;
            background-position: center;
        }
        .wrapper {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background-color: rgba(240, 240, 240, 0.8); /* Semi-transparent white */
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            backdrop-filter: blur(5px); /* Blur effect */
            border: 1px solid #ccc; /* Border for the container */
        }
        .form-control {
            background-color:rgb(204, 200, 200); /* Light input field */
            color: #333; /* Dark gray text */
            border: 1px solid #aaa; /* Light border */
            border-radius: 5px; /* Rounded corners */
        }
        .form-control:focus {
            background-color: #fff;
            border-color: #007bff; /* Blue border on focus */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3); /* Light blue shadow */
        }
        .btn-primary {
            background-color: #007bff; /* Blue button */
            border: none;
            color: white;
            width: 100%;
            border-radius: 5px; /* Rounded button */
            transition: background-color 0.3s;
            padding: 10px;
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
        .alert-danger {
            background-color: #ffcccc; /* Light pink for error messages */
            color: #333; /* Dark gray text */
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
        }
        a {
            color: #007bff; /* Blue links */
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline; /* Underline on hover */
        }
    </style> 
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php 
        if (!empty($login_err)) {
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>
