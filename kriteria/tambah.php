<?php
$error = '';

if (isset($_POST['simpan'])) {
    $kodeKriteria = $_POST["kode_kriteria"];
    $namaKriteria = $_POST["nama_kriteria"];
    $bobot = $_POST["bobot"];
    $jenis = $_POST["jenis"];

    // Validasi
    $sql = "SELECT * FROM kriteria WHERE kode_kriteria='$kodeKriteria'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $error = "Validasi Gagal! Data tersebut telah terdaftar.";
    } else {
        // Proses simpan
        $sql = "INSERT INTO kriteria (kode_kriteria, nama_kriteria, bobot, jenis) 
        VALUES ('$kodeKriteria', '$namaKriteria', '$bobot', '$jenis')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>
            window.location.href='?page=kriteria';
          </script>";
            exit();
        } else {
            $error = "Gagal Menyimpan! Terjadi kesalahan: " . $conn->error;
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
                <div class="card">
                    <div class="card-header bg-primary text-white border-dark"><Strong>Tambah Data Kriteria</Strong></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Kode Kriteria</label>
                            <input type="text" class="form-control" name="kode_kriteria" maxlength="10" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Kriteria</label>
                            <input type="text" class="form-control" name="nama_kriteria" maxlength="255" required>
                        </div>
                        <div class="form-group">
                            <label>Bobot <small class="text-muted">(0-1)</small></label>
                            <input type="number" step="0.01" min="0" max="1" class="form-control" name="bobot" placeholder="0.00" required>
                            <small class="form-text text-muted">Masukkan nilai bobot antara 0 dan 1</small>
                        </div>
                        <div class="form-group">
                            <label>Jenis</label>
                            <select name="jenis" class="form-control chosen" required>
                                <option value="" disabled selected>Pilih Jenis</option>
                                <option value="benefit">Benefit</option>
                                <option value="cost">Cost</option>
                            </select>
                        </div>
                        <input type="submit" value="Simpan" name="simpan" class="btn btn-primary">
                        <a href="?page=kriteria" class="btn btn-danger">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>