<?php
$error = '';

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $alternatifId = $_POST["alternatif_id"];
    $kriteriaId = $_POST["kriteria_id"];
    $subKriteriaId = $_POST["sub_kriteria_id"];
    $periode = $_POST["periode"] . "-01";

    // Validasi
    $sql = "SELECT * FROM penilaian
            WHERE alternatif_id='$alternatifId'
            AND kriteria_id='$kriteriaId'
            AND DATE_FORMAT(periode, '%Y-%m') = '$_POST[periode]'
            AND id != '$id'";
    $cek = $conn->query($sql);

    if ($cek->num_rows > 0) {
        $error = "Validasi gagal! Penilaian untuk alternatif, kriteria, dan periode ini sudah ada.";
    } else {
        $sql = "UPDATE penilaian SET
                    alternatif_id = '$alternatifId',
                    kriteria_id = '$kriteriaId',
                    sub_kriteria_id = '$subKriteriaId',
                    periode = '$periode'
                WHERE id = '$id'";

        if ($conn->query($sql) === TRUE) {
            echo "<script>window.location.href='?page=penilaian';</script>";
            exit();
        } else {
            $error = "Gagal menyimpan data: " . $conn->error;
        }
    }
}

// Ambil data penilaian
$id = $_GET['id'];
$sql = "SELECT * FROM penilaian WHERE id = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Alternatif
$sqlAlternatif = "SELECT id, kode_alternatif, judul_film
                  FROM alternatif ORDER BY kode_alternatif ASC";
$resultAlternatif = $conn->query($sqlAlternatif);

// Kriteria
$sqlKriteria = "SELECT id, kode_kriteria, nama_kriteria
                FROM kriteria ORDER BY kode_kriteria ASC";
$resultKriteria = $conn->query($sqlKriteria);

// Sub-kriteria sesuai kriteria
$sqlSubKriteria = "SELECT id, nilai, keterangan
                   FROM sub_kriteria
                   WHERE kriteria_id = '{$row['kriteria_id']}'
                   ORDER BY nilai DESC";
$resultSubKriteria = $conn->query($sqlSubKriteria);
?>

<!-- Alert Error -->
<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm">
        <?= $error ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

<!-- Form -->
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-sm-12">

        <form method="POST">
            <input type="hidden" name="id" value="<?= $row['id']; ?>">

            <div class="card shadow-sm border-dark">

                <div class="card-header bg-primary text-white text-center">
                    <strong>Edit Data Penilaian</strong>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <label>Periode Penilaian</label>
                        <input type="month" name="periode"
                            value="<?= date('Y-m', strtotime($row['periode'])); ?>"
                            class="form-control" required>
                        <small class="form-text text-muted">
                            Pilih bulan dan tahun penilaian
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Alternatif (Film)</label>
                        <select name="alternatif_id" class="form-control chosen" required>
                            <option value="" disabled>Pilih Alternatif</option>
                            <?php while ($a = $resultAlternatif->fetch_assoc()): ?>
                                <option value="<?= $a['id']; ?>"
                                    <?= $row['alternatif_id'] == $a['id'] ? 'selected' : ''; ?>>
                                    <?= $a['kode_alternatif'] . ' - ' . $a['judul_film']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kriteria</label>
                        <select name="kriteria_id" id="kriteria_id_edit"
                            class="form-control chosen" required>
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
                        <label>Nilai Sub-Kriteria</label>
                        <select name="sub_kriteria_id" id="sub_kriteria_id_edit"
                            class="form-control" required>
                            <?php while ($s = $resultSubKriteria->fetch_assoc()): ?>
                                <option value="<?= $s['id']; ?>"
                                    <?= $row['sub_kriteria_id'] == $s['id'] ? 'selected' : ''; ?>>
                                    Nilai <?= $s['nilai']; ?>
                                    <?= !empty($s['keterangan']) ? ' - ' . $s['keterangan'] : ''; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                </div>

                <div class="card-footer bg-light d-flex flex-wrap justify-content-between">
                    <button type="submit" name="edit" class="btn btn-primary mb-2">
                        Simpan Perubahan
                    </button>
                    <a href="?page=penilaian" class="btn btn-danger mb-2">
                        Batal
                    </a>
                </div>

            </div>
        </form>

    </div>
</div>