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
        $sql = "INSERT INTO alternatif (kode_alternatif, judul_film, periode_rilis)
                VALUES ('$kodeAlternatif', '$judulFilm', '$periodeRilis')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>window.location.href='?page=alternatif';</script>";
            exit();
        } else {
            $error = "Gagal Menyimpan! Terjadi kesalahan: " . $conn->error;
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
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <strong>Tambah Data Alternatif</strong>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label>Kode Alternatif</label>
                        <input type="text" class="form-control" name="kode_alternatif" maxlength="10" autocomplete="off" placeholder="Masukkan kode alternatif" required>
                    </div>

                    <div class="form-group">
                        <label>Judul Film</label>
                        <input type="text" class="form-control" name="judul_film" maxlength="255" autocomplete="off" placeholder="Masukkan judul film" required>
                    </div>

                    <div class="form-group">
                        <label>Periode Rilis</label>
                        <input type="date" class="form-control" name="periode_rilis" required>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex flex-wrap justify-content-between">
                    <button type="submit" name="simpan" class="btn btn-primary mb-2">
                        Simpan
                    </button>
                    <a href="?page=alternatif" class="btn btn-danger mb-2">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>