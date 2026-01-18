<?php
$error = '';

// Ambil ID
$id = $_GET['id'];

// Ambil data lama
$sql = "SELECT * FROM kriteria WHERE id = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (isset($_POST['edit'])) {
    $namaKriteria = $_POST["nama_kriteria"];
    $bobotBaru = floatval($_POST["bobot"]);
    $jenis = $_POST["jenis"];

    if ($bobotBaru < 0 || $bobotBaru > 1) {
        $error = "Bobot harus berada di antara 0 dan 1.";
    } else {
        // Total bobot
        $sqlTotal = "SELECT SUM(bobot) AS total_bobot FROM kriteria";
        $resultTotal = $conn->query($sqlTotal);
        $rowTotal = $resultTotal->fetch_assoc();
        $totalBobot = floatval($rowTotal['total_bobot']);

        $bobotLama = floatval($row['bobot']);
        $totalBaru = ($totalBobot - $bobotLama) + $bobotBaru;

        if ($totalBaru > 1) {
            $error = "Total bobot kriteria tidak boleh melebihi 1. 
            Sisa bobot tersedia: " . number_format(1 - ($totalBobot - $bobotLama), 2);
        } else {
            $sql = "UPDATE kriteria SET
                        nama_kriteria = '$namaKriteria',
                        bobot = '$bobotBaru',
                        jenis = '$jenis'
                    WHERE id = '$id'";

            if ($conn->query($sql) === TRUE) {
                echo "<script>window.location.href='?page=kriteria';</script>";
                exit();
            } else {
                $error = "Gagal menyimpan data: " . $conn->error;
            }
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
                    <strong>Edit Data Kriteria</strong>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <label>Kode Kriteria</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($row['kode_kriteria']) ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Nama Kriteria</label>
                        <input type="text" name="nama_kriteria" class="form-control" value="<?= htmlspecialchars($row['nama_kriteria']) ?>" autocomplete="off" required>
                    </div>

                    <div class="form-group">
                        <label>Bobot <small class="text-muted">(0 - 1)</small></label>
                        <input type="number" name="bobot" class="form-control" step="0.01" min="0" max="1" value="<?= $row['bobot'] ?>" autocomplete="off" required>
                        <small class="form-text text-muted">
                            Total seluruh bobot kriteria maksimal 1
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Jenis</label>
                        <select name="jenis" class="form-control chosen" required>
                            <option value="Benefit" <?= $row['jenis'] == 'Benefit' ? 'selected' : '' ?>>
                                Benefit
                            </option>
                            <option value="Cost" <?= $row['jenis'] == 'Cost' ? 'selected' : '' ?>>
                                Cost
                            </option>
                        </select>
                    </div>

                </div>

                <div class="card-footer bg-light d-flex flex-wrap justify-content-between">
                    <button type="submit" name="edit" class="btn btn-primary mb-2">
                        Simpan Perubahan
                    </button>
                    <a href="?page=kriteria" class="btn btn-danger mb-2">
                        Batal
                    </a>
                </div>
            </div>
        </form>

    </div>
</div>