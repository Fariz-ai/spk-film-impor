<?php
$error = '';

if (isset($_POST['simpan'])) {
    $namaLengkap = $_POST["nama_lengkap"];
    $email = $_POST["email"];
    $kataSandi = $_POST["kata_sandi"];
    $hashPassword = password_hash($kataSandi, PASSWORD_BCRYPT);
    $role = $_POST["role"];

    // Validasi email
    $sql = "SELECT * FROM pengguna WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $error = "Validasi Gagal! Email tersebut telah terdaftar.";
    } else {
        $sql = "INSERT INTO pengguna (nama_lengkap, email, kata_sandi, role)
                VALUES ('$namaLengkap', '$email', '$hashPassword', '$role')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>window.location.href='?page=pengguna';</script>";
            exit();
        } else {
            $error = "Gagal menyimpan data: " . $conn->error;
        }
    }
}
?>

<!-- Pesan Error -->
<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?= $error ?>
    </div>
<?php endif; ?>

<!-- Form -->
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-sm-12">

        <form action="" method="POST">
            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white text-center">
                    <strong>Tambah Data Pengguna</strong>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" maxlength="100" placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" maxlength="100" placeholder="Masukkan email" required>
                    </div>

                    <div class="form-group">
                        <label>Kata Sandi</label>
                        <input type="password" name="kata_sandi" class="form-control" placeholder="Masukkan kata sandi" required>
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control chosen" required>
                            <option value="" disabled selected>Pilih Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Karyawan">Karyawan</option>
                        </select>
                    </div>

                </div>

                <div class="card-footer bg-light d-flex flex-wrap justify-content-between">
                    <button type="submit" name="simpan" class="btn btn-primary mb-2">
                        Simpan
                    </button>
                    <a href="?page=pengguna" class="btn btn-danger mb-2">
                        Batal
                    </a>
                </div>

            </div>
        </form>

    </div>
</div>