<?php
$error = '';

if (isset($_POST['simpan'])) {
    $kriteriaId = $_POST["kriteria_id"];
    $nilai = $_POST["nilai"];
    $keterangan = $_POST["keterangan"];

    // Validasi data
    $sql = "SELECT * FROM sub_kriteria 
            WHERE kriteria_id='$kriteriaId' AND nilai='$nilai'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $error = "Validasi Gagal! Data sub-kriteria dengan nilai tersebut sudah ada.";
    } else {
        $sql = "INSERT INTO sub_kriteria (kriteria_id, nilai, keterangan)
                VALUES ('$kriteriaId', '$nilai', '$keterangan')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>window.location.href='?page=subkriteria';</script>";
            exit();
        } else {
            $error = "Gagal menyimpan data: " . $conn->error;
        }
    }
}

// Ambil data kriteria
$sqlKriteria = "SELECT id, kode_kriteria, nama_kriteria 
                FROM kriteria ORDER BY kode_kriteria ASC";
$resultKriteria = $conn->query($sqlKriteria);
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
                    <strong>Tambah Data Sub-Kriteria</strong>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <label>Kriteria</label>
                        <select name="kriteria_id" class="form-control chosen" required>
                            <option value="" disabled selected>Pilih Kriteria</option>
                            <?php while ($row = $resultKriteria->fetch_assoc()): ?>
                                <option value="<?= $row['id']; ?>">
                                    <?= $row['kode_kriteria'] . ' - ' . $row['nama_kriteria']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>
                            Nilai <small class="text-muted">(1 - 4)</small>
                        </label>
                        <input type="number" name="nilai" class="form-control" min="1" max="4" step="1" placeholder="Masukkan nilai" required>
                        <small class="form-text text-muted">
                            Nilai minimal 1 dan maksimal 4
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Masukkan keterangan" required>
                    </div>

                </div>

                <div class="card-footer bg-light d-flex flex-wrap justify-content-between">
                    <button type="submit" name="simpan" class="btn btn-primary mb-2">
                        Simpan
                    </button>
                    <a href="?page=subkriteria" class="btn btn-danger mb-2">
                        Batal
                    </a>
                </div>

            </div>
        </form>

    </div>
</div>