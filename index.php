<?php
session_start(); // Start session

if (isset($_SESSION['id'])) {
    header("Location: todo.php"); // Redirect to todo if user is already logged in
    exit();
}

include 'dbconn.php';

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "<div class='alert alert-danger'>Database error!</div>";
    } else {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['id'] = $row['id']; // Store user ID in session
                header("Location: todo.php");
                exit();
            } else {
                // echo "<div class='alert alert-danger'>Incorrect password!</div>";
            }
        } else {
            // echo "<div class='alert alert-danger'>Email not found!</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Form</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <style>
            body {
                padding: 50px;
            }

            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 50px;
                box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
            }

            .form-group {
                margin-bottom: 30px;
            }

            @media (max-width: 768px) {
                .container {
                    width: 100%;
                    padding: 20px;
                    /* Adjust container padding for smaller screens */
                    justify-content: center;
                    align-items: center;
                }

                /* Adjust font sizes for smaller screens */
                .form-group input {
                    font-size: 14px;
                }

                /* Adjust margin for the form */
                .form-group {
                    margin-bottom: 20px;
                }

                /* Adjust button padding and font size */
                .form-btn input[type="submit"] {
                    padding: 8px 16px;
                    font-size: 14px;
                }

                /* Adjust the size of the registration link text */
                div p a {
                    font-size: 14px;
                }

                .mauregist {
                    font-size: 14px;
                }
                .mauregist a {
                    font-size: 14px;
                }

                figure.text-center {
                    margin-top: 5px;
                    /* Adjust top margin */
                }

                blockquote.blockquote {
                    font-size: 10px;
                    /* Adjust font size */
                }

                .blockquote-footer{
                    font-size: 10px;
                }
            }
        </style>
    </head>
</head>

<body>
    <div class="container">
        <?php
        if (isset($_POST["login"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            require_once "dbconn.php";
            $sql = "SELECT * FROM user WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_assoc($result);
            if ($user) {
                if (password_verify($password, $user["password"])) {
                    header("Location: todo.php");
                    die();
                } else {
                    echo "<div class='alert alert-danger'>Password salah!</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Email tidak ditemukan!</div>";
            }
        }
        ?>

        <form action="index.php" method="post">
            <h3>
                Form
                <small class="text-muted">Login</small>
            </h3>
            <br>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Masukkan Email">
            </div>

            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Masukkan Password">
            </div>

            <div class="form-btn">
                <input type="submit" value="login" name="login" class="btn btn-primary">
            </div>
        </form>
        <br>
        <div class="mauregist">
            <p>Belum terdaftar? <a href="regist.php">Daftar disini</a></p>
        </div>
        <br>
        <figure class="text-center">
            <blockquote class="blockquote">
                <p>Inspired By World, Dedicated For U ♥︎</p>
            </blockquote>
            <figcaption class="blockquote-footer">
                &copy;ℼyuu <cite title="Source Title">2024</cite>
            </figcaption>
        </figure>
    </div>
</body>

</html>