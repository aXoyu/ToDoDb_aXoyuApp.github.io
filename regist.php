<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
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

        /* Media query for smaller screens */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                /* Adjust padding for smaller screens */
            }

            .form-group {
                margin-bottom: 20px;
                /* Adjust margin for smaller screens */
            }

            .form-btn input[type="submit"] {
                padding: 8px 16px;
                /* Adjust button padding for smaller screens */
                font-size: 14px;
                /* Adjust button font size for smaller screens */
            }

            div p a {
                font-size: 12px;
                /* Reduce font size for registration link on smaller screens */
            }

            /* Reduce font size for form input fields on smaller screens */
            input[type="text"],
            input[type="email"],
            input[type="password"] {
                font-size: 14px;
            }

            .maulogin {
                font-size: 14px;
            }

            .maulogin a {
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

            .blockquote-footer {
                font-size: 10px;
            }
        }
    </style>


</head>

<body>
    <div class="container">
        <?php
        include 'dbconn.php';

        if (isset($_POST["submit"])) {
            $fullname = $_POST["fullname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $repeatpw = $_POST["repeat_pw"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $errors = array();

            if (empty($fullname) || empty($password) || empty($repeatpw)) {
                array_push($errors, "Pastikan seluruh data telah diisi");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email tidak valid");
            }
            if (strlen($password) < 8) {
                array_push($errors, "Password setidaknya terdiri dari 8 character");
            }
            if ($password !== $repeatpw) {
                array_push($errors, "Password Tidak Sama!");
            }

            $sql_check_email = "SELECT * FROM user WHERE email ='$email'";
            $result_check_email = mysqli_query($conn, $sql_check_email);
            $rowCount = mysqli_num_rows($result_check_email);
            if ($rowCount > 0) {
                array_push($errors, "Email sudah ada");
            }

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                $sql = "INSERT INTO user (full_name, email, password) VALUES (?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, "sss", $fullname, $email, $passwordHash);
                    mysqli_stmt_execute($stmt);
                    echo "<div class='alert alert-success'>Berhasil terdaftar!</div>";
                } else {
                    echo "<div class='alert alert-danger'>Gagal menambahkan data</div>";
                }
            }
        }
        ?>
        <form action="regist.php" method="post">
            <h3>
                Form
                <small class="text-muted">Registrasi</small>
            </h3>
            <br>
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Nama Lengkap">
            </div>

            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email">
            </div>

            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>

            <div class="form-group">
                <input type="password" class="form-control" name="repeat_pw" placeholder="Ulangi Password">
            </div>

            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>
        <br>
        <div class="maulogin">
            <p>Sudah memiliki akun? <a href="index.php">Login disini</a></p>
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