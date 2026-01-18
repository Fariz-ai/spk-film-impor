<?php
session_start();
require "config.php";

if (isset($_POST["submit"])) {

    $email = $_POST["email"];
    $kataSandi = $_POST["kata_sandi"];

    $sql = "SELECT * FROM pengguna WHERE email = '$email' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($kataSandi, $row['kata_sandi'])) {
            $_SESSION['email'] = $row["email"];
            $_SESSION['nama_lengkap'] = $row["nama_lengkap"];
            $_SESSION['role'] = $row["role"];
            $_SESSION['status'] = "login";

            header("Location: index.php");
            exit();
        } else {
            header("Location: login.php?pesan=gagal");
            exit();
        }
    } else {
        header("Location: login.php?pesan=gagal");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | SPK Film Impor</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body class="bg-light d-flex align-items-center" style="min-height:100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-7 col-lg-4">

                <!-- Alert login gagal -->
                <?php if (isset($_GET['pesan']) && $_GET['pesan'] == "gagal") : ?>
                    <div class="alert alert-danger alert-dismissible fade show text-center">
                        Email atau kata sandi salah.
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white text-center">
                        <strong>Login</strong>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" autocomplete="off" placeholder="Masukkan email" required>
                            </div>

                            <div class="form-group">
                                <label>Kata Sandi</label>
                                <input type="password" name="kata_sandi" class="form-control" autocomplete="off" placeholder="Masukkan kata sandi" required>
                            </div>

                            <button type="submit" name="submit" class="btn btn-primary btn-block">
                                Login
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-3.7.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>