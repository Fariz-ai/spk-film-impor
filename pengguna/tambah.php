<?php
$error = '';

if (isset($_POST['simpan'])) {
    $namaLengkap = $_POST["nama_lengkap"];
    $email = $_POST["email"];
    $kataSandi = $_POST["kata_sandi"];
    $hashPassword = password_hash($kataSandi, PASSWORD_BCRYPT);
    $role = $_POST["role"];

    // Validasi
    $sql = "SELECT * FROM pengguna WHERE email='$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $error = "Validasi Gagal! Email tersebut telah terdaftar.";
    } else {
        // Proses simpan
        $sql = "INSERT INTO pengguna (nama_lengkap, email, kata_sandi, role) 
        VALUES ('$namaLengkap', '$email', '$hashPassword', '$role')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>
            window.location.href='?page=pengguna';
          </script>";
            exit();
        } else {
            $error = "Gagal Menyimpan! Terjadi kesalahan: " . $conn->error;
        }
    }
}
?>

<!-- Pesan error -->
<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<!-- Form -->
<div class="row">
    <div class="col-sm-12">
        <form action="" method="POST">
            <div class="card border-dark">
                <div class="card">
                    <div class="card-header bg-primary text-white border-dark"><Strong>Tambah Data Pengguna</Strong></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama_lengkap" maxlength="100" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" maxlength="100" required>
                        </div>
                        <div class="form-group">
                            <label>Kata Sandi</label>
                            <input type="password" class="form-control" name="kata_sandi" maxlength="255" required>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control chosen" required>
                                <option value="" disabled selected>Pilih Role</option>
                                <option value="Admin">Admin</option>
                                <option value="Karyawan">Karyawan</option>
                            </select>
                        </div>
                        <input type="submit" value="Simpan" name="simpan" class="btn btn-primary">
                        <a href="?page=pengguna" class="btn btn-danger">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>