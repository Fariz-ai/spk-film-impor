<?php
if (isset($_POST['edit'])) {
    $kodeKriteria = $_POST["kode_kriteria"];
    $namaKriteria = $_POST["nama_kriteria"];
    $bobot = $_POST["bobot"];
    $jenis = $_POST["jenis"];

    // Proses update data
    $sql = "UPDATE kriteria SET kode_kriteria = '$kodeKriteria', nama_kriteria = '$namaKriteria', bobot = '$bobot', jenis = '$jenis'
            WHERE kode_kriteria = '$kodeKriteria'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            window.location.href='?page=kriteria';
          </script>";
        exit();
    } else {
        $error = "Gagal Menyimpan! Terjadi kesalahan: " . $conn->error;
    }
}

// Mengambil data
$id = $_GET['id'];

$sql = "SELECT * FROM kriteria WHERE id = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
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
                    <div class="card-header bg-primary text-white border-dark"><strong>Edit Data Kriteria</strong></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Kode Kriteria</label>
                            <input type="text" name="kode_kriteria" value="<?php echo $row['kode_kriteria'] ?>" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nama Kriteria</label>
                            <input type="text" name="nama_kriteria" value="<?php echo $row['nama_kriteria'] ?>" maxlength="255" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Bobot <small class="text-muted">(0-1)</small></label>
                            <input type="number" name="bobot" value="<?php echo $row['bobot'] ?>" step="0.01" min="0" max="1" class="form-control" required>
                            <small class="form-text text-muted">Masukkan nilai bobot antara 0 dan 1</small>
                        </div>
                        <div class="form-group">
                            <label>Jenis</label>
                            <select name="jenis" class="form-control chosen" required>
                                <option value="" disabled>Pilih Jenis</option>
                                <option value="benefit" <?php echo ($row['jenis'] == 'benefit') ? 'selected' : ''; ?>>Benefit</option>
                                <option value="cost" <?php echo ($row['jenis'] == 'cost') ? 'selected' : ''; ?>>Cost</option>
                            </select>
                        </div>
                        <input type="submit" value="Simpan" name="edit" class="btn btn-primary">
                        <a href="?page=kriteria" class="btn btn-danger">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>