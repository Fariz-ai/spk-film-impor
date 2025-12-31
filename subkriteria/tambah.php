<?php
$error = '';

if (isset($_POST['simpan'])) {
    $kriteriaId = $_POST["kriteria_id"];
    $nilai = $_POST["nilai"];
    $keterangan = $_POST["keterangan"];

    // Validasi
    $sql = "SELECT * FROM sub_kriteria WHERE kriteria_id='$kriteriaId' AND nilai='$nilai'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $error = "Validasi Gagal! Data sub-kriteria dengan nilai tersebut telah terdaftar untuk kriteria ini.";
    } else {
        // Proses simpan
        $sql = "INSERT INTO sub_kriteria (kriteria_id, nilai, keterangan) 
        VALUES ('$kriteriaId', '$nilai', '$keterangan')";
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
            <div class="card border-dark">
                <div class="card">
                    <div class="card-header bg-primary text-white border-dark"><Strong>Tambah Data Sub-Kriteria</Strong></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Kriteria</label>
                            <select name="kriteria_id" class="form-control chosen" required>
                                <option value="" disabled selected>Pilih Kriteria</option>
                                <?php while ($row = $resultKriteria->fetch_assoc()): ?>
                                    <option value="<?php echo $row['id']; ?>">
                                        <?php echo $row['kode_kriteria'] . ' - ' . $row['nama_kriteria']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nilai</label>
                            <input type="number" step="1" min="1" max="4" class="form-control" name="nilai" placeholder="1-4" required>
                            <small class="form-text text-muted">Masukkan nilai antara 1 dan 4</small>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" />
                        </div>
                        <input type="submit" value="Simpan" name="simpan" class="btn btn-primary">
                        <a href="?page=subkriteria" class="btn btn-danger">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>