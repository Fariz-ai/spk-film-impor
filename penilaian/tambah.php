<?php
$error = '';

if (isset($_POST['simpan'])) {
    $id = uniqid();
    $alternatifId = $_POST["alternatif_id"];
    $kriteriaId = $_POST["kriteria_id"];
    $subKriteriaId = $_POST["sub_kriteria_id"];
    $periode = $_POST["periode"] . "-01";

    // Validasi
    $sql = "SELECT * FROM penilaian
            WHERE alternatif_id='$alternatifId'
            AND kriteria_id='$kriteriaId'
            AND DATE_FORMAT(periode, '%Y-%m') = '$_POST[periode]'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $error = "Validasi Gagal! Penilaian untuk alternatif, kriteria, dan periode ini sudah ada.";
    } else {
        $sql = "INSERT INTO penilaian (id, alternatif_id, kriteria_id, sub_kriteria_id, periode)
                VALUES ('$id', '$alternatifId', '$kriteriaId', '$subKriteriaId', '$periode')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>window.location.href='?page=penilaian';</script>";
            exit();
        } else {
            $error = "Gagal menyimpan data: " . $conn->error;
        }
    }
}

// Ambil data alternatif
$sqlAlternatif = "SELECT id, kode_alternatif, judul_film
                  FROM alternatif ORDER BY kode_alternatif ASC";
$resultAlternatif = $conn->query($sqlAlternatif);

// Ambil data kriteria
$sqlKriteria = "SELECT id, kode_kriteria, nama_kriteria
                FROM kriteria ORDER BY kode_kriteria ASC";
$resultKriteria = $conn->query($sqlKriteria);
?>

<!-- Alert Error -->
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
                    <strong>Tambah Data Penilaian</strong>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <label>Periode Penilaian</label>
                        <input type="month" name="periode" class="form-control" required>
                        <small class="form-text text-muted">
                            Pilih bulan dan tahun penilaian
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Alternatif (Film)</label>
                        <select name="alternatif_id" class="form-control chosen" required>
                            <option value="" disabled selected>Pilih Alternatif</option>
                            <?php while ($row = $resultAlternatif->fetch_assoc()): ?>
                                <option value="<?= $row['id']; ?>">
                                    <?= $row['kode_alternatif'] . ' - ' . $row['judul_film']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kriteria</label>
                        <select name="kriteria_id" id="kriteria_id"
                            class="form-control chosen" required>
                            <option value="" disabled selected>Pilih Kriteria</option>
                            <?php while ($row = $resultKriteria->fetch_assoc()): ?>
                                <option value="<?= $row['id']; ?>">
                                    <?= $row['kode_kriteria'] . ' - ' . $row['nama_kriteria']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nilai Sub-Kriteria</label>
                        <select name="sub_kriteria_id" id="sub_kriteria_id" class="form-control" required>
                            <option value="">Pilih kriteria terlebih dahulu</option>
                        </select>
                        <small class="form-text text-muted">
                            Sub-kriteria akan muncul setelah memilih kriteria
                        </small>
                    </div>

                </div>

                <div class="card-footer bg-light d-flex flex-wrap justify-content-between">
                    <button type="submit" name="simpan" class="btn btn-primary mb-2">
                        Simpan
                    </button>
                    <a href="?page=penilaian" class="btn btn-danger mb-2">
                        Batal
                    </a>
                </div>

            </div>
        </form>

    </div>
</div>