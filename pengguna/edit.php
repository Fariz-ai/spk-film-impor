<?php
$error = '';

// Ambil ID
$id = $_GET['id'] ?? null;

// Ambil data pengguna
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

    $sql = "UPDATE pengguna SET
                nama_lengkap = '$namaLengkap',
                email = '$email',
                kata_sandi = '$hashPassword',
                role = '$role'
            WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.href='?page=pengguna';</script>";
        exit();
    } else {
        $error = "Gagal menyimpan data: " . $conn->error;
    }
}
?>

<!-- Alert Error -->
<?php if (!empty($error)) : ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <?= $error ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

<!-- Form -->
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-sm-12">

        <form method="POST">
            <div class="card shadow-sm border-dark">

                <div class="card-header bg-primary text-white text-center">
                    <strong>Edit Data Pengguna</strong>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" maxlength="100" value="<?= htmlspecialchars($row['nama_lengkap']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" maxlength="100" value="<?= htmlspecialchars($row['email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Kata Sandi</label>
                        <input type="password" name="kata_sandi" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                        <small class="form-text text-muted">
                            Kosongkan jika tidak ingin mengubah kata sandi
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control chosen" required>
                            <option value="" disabled>Pilih Role</option>
                            <option value="Admin" <?= $row['role'] == 'Admin' ? 'selected' : ''; ?>>
                                Admin
                            </option>
                            <option value="Karyawan" <?= $row['role'] == 'Karyawan' ? 'selected' : ''; ?>>
                                Karyawan
                            </option>
                        </select>
                    </div>

                </div>

                <div class="card-footer bg-light d-flex flex-wrap justify-content-between">
                    <button type="submit" name="edit" class="btn btn-primary mb-2">
                        Simpan Perubahan
                    </button>
                    <a href="?page=pengguna" class="btn btn-danger mb-2">
                        Batal
                    </a>
                </div>

            </div>
        </form>

    </div>
</div>