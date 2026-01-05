<?php
$error = '';

// Ambil ID
$id = $_GET['id'];

// Ambil data lama
$sql = "SELECT * FROM kriteria WHERE id = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (isset($_POST['edit'])) {
    $kodeKriteria = $_POST["kode_kriteria"];
    $namaKriteria = $_POST["nama_kriteria"];
    $bobotBaru = floatval($_POST["bobot"]);
    $jenis = $_POST["jenis"];

    // Validasi bobot individual
    if ($bobotBaru < 0 || $bobotBaru > 1) {
        $error = "Bobot harus berada di antara 0 dan 1.";
    } else {
        // Ambil total bobot semua kriteria
        $sqlTotal = "SELECT SUM(bobot) AS total_bobot FROM kriteria";
        $resultTotal = $conn->query($sqlTotal);
        $rowTotal = $resultTotal->fetch_assoc();
        $totalBobot = floatval($rowTotal['total_bobot']);

        // Bobot lama
        $bobotLama = floatval($row['bobot']);

        // Hitung total baru
        $totalBaru = ($totalBobot - $bobotLama) + $bobotBaru;

        if ($totalBaru > 1) {
            $error = "Total bobot kriteria tidak boleh melebihi 1. 
            Sisa bobot tersedia: " . number_format(1 - ($totalBobot - $bobotLama), 2);
        } else {
            // Proses update
            $sql = "UPDATE kriteria SET 
                        nama_kriteria = '$namaKriteria',
                        bobot = '$bobotBaru',
                        jenis = '$jenis'
                    WHERE id = '$id'";

            if ($conn->query($sql) === TRUE) {
                echo "<script>
                    window.location.href='?page=kriteria';
                </script>";
                exit();
            } else {
                $error = "Gagal menyimpan data: " . $conn->error;
            }
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
                <div class="card-header bg-primary text-white">
                    <strong>Edit Data Kriteria</strong>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label>Kode Kriteria</label>
                        <input type="text" name="kode_kriteria" value="<?= $row['kode_kriteria']; ?>" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label>Nama Kriteria</label>
                        <input type="text" name="nama_kriteria" value="<?= $row['nama_kriteria']; ?>" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Bobot <small class="text-muted">(0 - 1)</small></label>
                        <input type="number" name="bobot"
                            value="<?= $row['bobot']; ?>"
                            step="0.01" min="0" max="1"
                            class="form-control" required>
                        <small class="form-text text-muted">
                            Total seluruh bobot kriteria maksimal 1
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Jenis</label>
                        <select name="jenis" class="form-control" required>
                            <option value="Benefit" <?= ($row['jenis'] == 'Benefit') ? 'selected' : ''; ?>>
                                Benefit
                            </option>
                            <option value="Cost" <?= ($row['jenis'] == 'Cost') ? 'selected' : ''; ?>>
                                Cost
                            </option>
                        </select>
                    </div>

                    <button type="submit" name="edit" class="btn btn-primary">Simpan</button>
                    <a href="?page=kriteria" class="btn btn-danger">Batal</a>

                </div>
            </div>
        </form>
    </div>
</div>