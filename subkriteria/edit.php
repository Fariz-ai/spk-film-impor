<?php
$error = '';

$id = $_GET['id'];

// Ambil data sub-kriteria
$sql = "SELECT * FROM sub_kriteria WHERE id = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Ambil data kriteria
$sqlKriteria = "SELECT id, kode_kriteria, nama_kriteria 
                FROM kriteria ORDER BY kode_kriteria ASC";
$resultKriteria = $conn->query($sqlKriteria);

if (isset($_POST['edit'])) {
    $kriteriaId = $_POST["kriteria_id"];
    $nilai = $_POST["nilai"];
    $keterangan = $_POST["keterangan"];

    // Validasi
    $sql = "SELECT * FROM sub_kriteria 
            WHERE kriteria_id='$kriteriaId' 
            AND nilai='$nilai' 
            AND id != '$id'";
    $cek = $conn->query($sql);

    if ($cek->num_rows > 0) {
        $error = "Validasi gagal! Sub-kriteria dengan nilai tersebut sudah ada.";
    } else {
        $sql = "UPDATE sub_kriteria SET
                    kriteria_id = '$kriteriaId',
                    nilai = '$nilai',
                    keterangan = '$keterangan'
                WHERE id = '$id'";

        if ($conn->query($sql) === TRUE) {
            echo "<script>window.location.href='?page=subkriteria';</script>";
            exit();
        } else {
            $error = "Gagal menyimpan data: " . $conn->error;
        }
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
                    <strong>Edit Data Sub-Kriteria</strong>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <label>Kriteria</label>
                        <select name="kriteria_id" class="form-control chosen" required>
                            <option value="" disabled>Pilih Kriteria</option>
                            <?php while ($k = $resultKriteria->fetch_assoc()): ?>
                                <option value="<?= $k['id']; ?>"
                                    <?= $row['kriteria_id'] == $k['id'] ? 'selected' : ''; ?>>
                                    <?= $k['kode_kriteria'] . ' - ' . $k['nama_kriteria']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>
                            Nilai <small class="text-muted">(1 - 4)</small>
                        </label>
                        <input type="number" name="nilai" class="form-control" min="1" max="4" step="1" value="<?= $row['nilai']; ?>" required>
                        <small class="form-text text-muted">
                            Nilai minimal 1 dan maksimal 4
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" value="<?= htmlspecialchars($row['keterangan']); ?>" required>
                    </div>

                </div>

                <div class="card-footer bg-light d-flex flex-wrap justify-content-between">
                    <button type="submit" name="edit" class="btn btn-primary mb-2">
                        Simpan Perubahan
                    </button>
                    <a href="?page=subkriteria" class="btn btn-danger mb-2">
                        Batal
                    </a>
                </div>

            </div>
        </form>

    </div>
</div>