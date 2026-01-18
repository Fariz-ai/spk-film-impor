<?php
$error = '';

if (isset($_POST['edit'])) {
    $kodeAlternatif = $_POST['kode_alternatif'];
    $judulFilm = $_POST['judul_film'];
    $periodeRilis = $_POST['periode_rilis'];

    // Update data
    $sql = "UPDATE alternatif 
            SET judul_film = '$judulFilm',
                periode_rilis = '$periodeRilis'
            WHERE kode_alternatif = '$kodeAlternatif'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.href='?page=alternatif';</script>";
        exit();
    } else {
        $error = "Gagal Menyimpan! Terjadi kesalahan: " . $conn->error;
    }
}

// Ambil data berdasarkan ID
$id = $_GET['id'];
$sql = "SELECT * FROM alternatif WHERE id = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
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
                    <strong>Edit Data Alternatif</strong>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label>Kode Alternatif</label>
                        <input type="text" name="kode_alternatif" value="<?= htmlspecialchars($row['kode_alternatif']) ?>" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label>Judul Film</label>
                        <input type="text" name="judul_film" value="<?= htmlspecialchars($row['judul_film']) ?>" maxlength="255" class="form-control" autocomplete="off" required>
                    </div>

                    <div class="form-group">
                        <label>Periode Rilis</label>
                        <input type="date" name="periode_rilis" value="<?= $row['periode_rilis'] ?>" class="form-control" required>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex flex-wrap justify-content-between">
                    <button type="submit" name="edit" class="btn btn-primary mb-2">
                        Simpan Perubahan
                    </button>
                    <a href="?page=alternatif" class="btn btn-danger mb-2">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>