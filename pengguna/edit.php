<?php

// Ambil ID
$id = $_GET['id'] ?? null;

// Ambil data lama
$sql = "SELECT * FROM pengguna WHERE id = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Proses update
if (isset($_POST['edit'])) {
    $namaLengkap = $_POST["nama_lengkap"];
    $email = $_POST["email"];
    $kataSandi = $_POST["kata_sandi"];
    $role = $_POST["role"];

    // Jika password diisi → hash baru
    // Jika kosong → pakai password lama
    if (!empty($kataSandi)) {
        $hashPassword = password_hash($kataSandi, PASSWORD_BCRYPT);
    } else {
        $hashPassword = $row['kata_sandi'];
    }

    $sql = "UPDATE pengguna SET nama_lengkap = '$namaLengkap', email = '$email', kata_sandi = '$hashPassword', role = '$role'
            WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                window.location.href='?page=pengguna';
              </script>";
        exit();
    } else {
        $error = "Gagal Menyimpan! Terjadi kesalahan: " . $conn->error;
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
                    <div class="card-header bg-primary text-white border-dark"><strong>Edit Data Pengguna</strong></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="<?php echo $row['nama_lengkap'] ?>" maxlength="100" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="<?php echo $row['email'] ?>" maxlength="100" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Kata Sandi</label>
                            <input type="password" name="kata_sandi" class="form-control" maxlength="255">
                            <small class="text-muted">
                                Kosongkan jika tidak ingin mengubah kata sandi
                            </small>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control chosen" required>
                                <option value="" disabled>Pilih Role</option>
                                <option value="Admin" <?php echo ($row['role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                                <option value="Karyawan" <?php echo ($row['role'] == 'Karyawan') ? 'selected' : ''; ?>>Karyawan</option>
                            </select>
                        </div>
                        <input type="submit" value="Simpan" name="edit" class="btn btn-primary">
                        <a href="?page=pengguna" class="btn btn-danger">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>