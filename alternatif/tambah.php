<?php
$error = '';

if (isset($_POST['simpan'])) {
    $kodeAlternatif = $_POST["kode_alternatif"];
    $judulFilm = $_POST["judul_film"];
    $periodeRilis = $_POST["periode_rilis"];

    // Validasi
    $sql = "SELECT * FROM alternatif WHERE kode_alternatif='$kodeAlternatif'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $error = "Validasi Gagal! Data tersebut telah terdaftar.";
    } else {
        // Proses simpan
        $sql = "INSERT INTO alternatif (kode_alternatif, judul_film, periode_rilis) 
        VALUES ('$kodeAlternatif', '$judulFilm', '$periodeRilis')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>
            window.location.href='?page=alternatif';
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
                    <div class="card-header bg-primary text-white border-dark"><Strong>Tambah Data Alternatif</Strong></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Kode Alternatif</label>
                            <input type="text" class="form-control" name="kode_alternatif" maxlength="10" required>
                        </div>
                        <div class="form-group">
                            <label>Judul Film</label>
                            <input type="text" class="form-control" name="judul_film" maxlength="255" required>
                        </div>
                        <div class="form-group">
                            <label>Periode Rilis</label>
                            <input type="date" class="form-control" name="periode_rilis" required>
                        </div>
                        <input type="submit" value="Simpan" name="simpan" class="btn btn-primary">
                        <a href="?page=alternatif" class="btn btn-danger">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>