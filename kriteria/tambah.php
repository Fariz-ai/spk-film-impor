<?php
$error = '';

if (isset($_POST['simpan'])) {
    $kodeKriteria = $_POST["kode_kriteria"];
    $namaKriteria = $_POST["nama_kriteria"];
    $bobot = floatval($_POST["bobot"]);
    $jenis = $_POST["jenis"];

    if ($bobot < 0 || $bobot > 1) {
        $error = "Bobot harus berada di antara 0 dan 1.";
    } else {
        $sql = "SELECT * FROM kriteria WHERE kode_kriteria='$kodeKriteria'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $error = "Validasi gagal! Kode kriteria sudah terdaftar.";
        } else {
            $sqlTotal = "SELECT SUM(bobot) AS total_bobot FROM kriteria";
            $resultTotal = $conn->query($sqlTotal);
            $rowTotal = $resultTotal->fetch_assoc();
            $totalBobot = floatval($rowTotal['total_bobot']);

            if (($totalBobot + $bobot) > 1) {
                $error = "Total bobot kriteria tidak boleh melebihi 1. 
                Sisa bobot tersedia: " . number_format(1 - $totalBobot, 2);
            } else {
                $sql = "INSERT INTO kriteria (kode_kriteria, nama_kriteria, bobot, jenis)
                        VALUES ('$kodeKriteria', '$namaKriteria', '$bobot', '$jenis')";

                if ($conn->query($sql) === TRUE) {
                    echo "<script>window.location.href='?page=kriteria';</script>";
                    exit();
                } else {
                    $error = "Gagal menyimpan data: " . $conn->error;
                }
            }
        }
    }
}
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
                    <strong>Tambah Data Kriteria</strong>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <label>Kode Kriteria</label>
                        <input type="text" name="kode_kriteria" class="form-control" maxlength="10" placeholder="Masukkan kode kriteria" autocomplete="off" required>
                    </div>

                    <div class="form-group">
                        <label>Nama Kriteria</label>
                        <input type="text" name="nama_kriteria" class="form-control" maxlength="255" placeholder="Masukkan nama kriteria" autocomplete="off" required>
                    </div>

                    <div class="form-group">
                        <label>
                            Bobot <small class="text-muted">(0 - 1)</small>
                        </label>
                        <input type="number" name="bobot" class="form-control" step="0.01" min="0" max="1" placeholder="0.00" autocomplete="off" required>
                        <small class="form-text text-muted">
                            Total seluruh bobot kriteria maksimal 1
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Jenis</label>
                        <select name="jenis" class="form-control chosen" required>
                            <option value="" disabled selected>Pilih Jenis</option>
                            <option value="Benefit">Benefit</option>
                            <option value="Cost">Cost</option>
                        </select>
                    </div>

                    <div class="card-footer bg-light d-flex flex-wrap justify-content-between">
                        <button type="submit" name="simpan" class="btn btn-primary mb-2">
                            Simpan
                        </button>
                        <a href="?page=kriteria" class="btn btn-danger mb-2">
                            Batal
                        </a>
                    </div>

                </div>
            </div>
        </form>

    </div>
</div>