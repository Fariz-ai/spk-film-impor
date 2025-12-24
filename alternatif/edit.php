<?php
if (isset($_POST['edit'])) {
    $kodeAlternatif = $_POST['kode_alternatif'];
    $judulFilm = $_POST['judul_film'];
    $perusahaanProduksi = $_POST['perusahaan_produksi'];

    // Proses update data
    $sql = "UPDATE alternatif SET kode_alternatif = '$kodeAlternatif', judul_film = '$judulFilm', perusahaan_produksi = '$perusahaanProduksi'
            WHERE kode_alternatif = '$kodeAlternatif'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            window.location.href='?page=alternatif';
          </script>";
        exit();
    } else {
        $error = "Gagal Menyimpan! Terjadi kesalahan: " . $conn->error;
    }
}

// Mengambil data
$id = $_GET['id'];

$sql = "SELECT * FROM alternatif WHERE id = '$id'";
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
                    <div class="card-header bg-primary text-white border-dark"><strong>Edit Data Alternatif</strong></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Kode Alternatif</label>
                            <input type="text" name="kode_alternatif" value="<?php echo $row['kode_alternatif'] ?>" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Judul Film</label>
                            <input type="text" name="judul_film" value="<?php echo $row['judul_film'] ?>" maxlength="255" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Perusahaan Produksi</label>
                            <input type="text" name="perusahaan_produksi" value="<?php echo $row['perusahaan_produksi'] ?>" maxlength="255" class="form-control" required>
                        </div>
                        <input type="submit" value="Simpan" name="edit" class="btn btn-primary">
                        <a href="?page=alternatif" class="btn btn-danger">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>