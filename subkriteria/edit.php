<?php
$error = '';

if (isset($_POST['edit'])) {
    $kriteriaId = $_POST["kriteria_id"];
    $nilai = $_POST["nilai"];
    $keterangan = $_POST["keterangan"];

    // Ambil ID sub-kriteria
    $id = $_POST['id'];

    // Validasi - cek apakah kombinasi kriteria_id dan nilai sudah ada (kecuali data yang sedang diedit)
    $sql = "SELECT * FROM sub_kriteria WHERE kriteria_id='$kriteriaId' AND nilai='$nilai' AND id != '$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $error = "Validasi Gagal! Data sub-kriteria dengan nilai tersebut telah terdaftar untuk kriteria ini.";
    } else {
        // Proses update data
        $sql = "UPDATE sub_kriteria SET kriteria_id = '$kriteriaId', nilai = '$nilai', keterangan = '$keterangan'
                WHERE id = '$id'";
        if ($conn->query($sql) === TRUE) {
            echo "<script>
                window.location.href='?page=subkriteria';
              </script>";
            exit();
        } else {
            $error = "Gagal Menyimpan! Terjadi kesalahan: " . $conn->error;
        }
    }
}

// Mengambil data sub-kriteria yang akan diedit
$id = $_GET['id'];
$sql = "SELECT * FROM sub_kriteria WHERE id = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Ambil data kriteria
$sqlKriteria = "SELECT id, kode_kriteria, nama_kriteria FROM kriteria ORDER BY kode_kriteria ASC";
$resultKriteria = $conn->query($sqlKriteria);
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
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <div class="card border-dark">
                <div class="card">
                    <div class="card-header bg-primary text-white border-dark"><strong>Edit Data Sub-Kriteria</strong></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Kriteria</label>
                            <select name="kriteria_id" class="form-control chosen" required>
                                <option value="" disabled>Pilih Kriteria</option>
                                <?php while ($rowKriteria = $resultKriteria->fetch_assoc()): ?>
                                    <option value="<?php echo $rowKriteria['id']; ?>" <?php echo ($row['kriteria_id'] == $rowKriteria['id']) ? 'selected' : ''; ?>>
                                        <?php echo $rowKriteria['kode_kriteria'] . ' - ' . $rowKriteria['nama_kriteria']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nilai</label>
                            <input type="number" step="1" min="1" max="4" class="form-control" name="nilai" value="<?php echo $row['nilai']; ?>" required>
                            <small class="form-text text-muted">Masukkan nilai antara 1 dan 4</small>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" value="<?php echo $row['keterangan']; ?>">
                        </div>
                        <input type="submit" value="Simpan" name="edit" class="btn btn-primary">
                        <a href="?page=subkriteria" class="btn btn-danger">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>