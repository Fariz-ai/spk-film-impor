<?php
$error = '';

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $alternatifId = $_POST["alternatif_id"];
    $kriteriaId = $_POST["kriteria_id"];
    $subKriteriaId = $_POST["sub_kriteria_id"];
    $periode = $_POST["periode"] . "-01";

    // Validasi
    $sql = "SELECT * FROM penilaian WHERE alternatif_id='$alternatifId' AND kriteria_id='$kriteriaId' AND DATE_FORMAT(periode, '%Y-%m') = '$_POST[periode]' AND id != '$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $error = "Validasi Gagal! Penilaian untuk alternatif, kriteria, dan periode ini sudah ada.";
    } else {
        // Proses update data
        $sql = "UPDATE penilaian SET alternatif_id = '$alternatifId', kriteria_id = '$kriteriaId', sub_kriteria_id = '$subKriteriaId', periode = '$periode' WHERE id = '$id'";
        if ($conn->query($sql) === TRUE) {
            echo "<script>
                window.location.href='?page=penilaian';
              </script>";
            exit();
        } else {
            $error = "Gagal Menyimpan! Terjadi kesalahan: " . $conn->error;
        }
    }
}

// Mengambil data penilaian yang akan diedit
$id = $_GET['id'];
$sql = "SELECT * FROM penilaian WHERE id = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Ambil data alternatif
$sqlAlternatif = "SELECT id, kode_alternatif, judul_film FROM alternatif ORDER BY kode_alternatif ASC";
$resultAlternatif = $conn->query($sqlAlternatif);

// Ambil data kriteria
$sqlKriteria = "SELECT id, kode_kriteria, nama_kriteria FROM kriteria ORDER BY kode_kriteria ASC";
$resultKriteria = $conn->query($sqlKriteria);

// Ambil data sub-kriteria berdasarkan kriteria yang dipilih
$sqlSubKriteria = "SELECT id, nilai, keterangan FROM sub_kriteria WHERE kriteria_id = '{$row['kriteria_id']}' ORDER BY nilai DESC";
$resultSubKriteria = $conn->query($sqlSubKriteria);
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
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <div class="card border-dark">
                <div class="card">
                    <div class="card-header bg-primary text-white border-dark"><strong>Edit Data Penilaian</strong></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Periode Penilaian</label>
                            <input type="month" name="periode" value="<?php echo date('Y-m', strtotime($row['periode'])); ?>" class="form-control" required>
                            <small class="form-text text-muted">Pilih bulan dan tahun penilaian</small>
                        </div>
                        <div class="form-group">
                            <label>Alternatif (Film)</label>
                            <select name="alternatif_id" id="alternatif_id_edit" class="form-control chosen" data-placeholder="Pilih Alternatif" required>
                                <option value="">Pilih Alternatif</option>
                                <?php while ($rowAlt = $resultAlternatif->fetch_assoc()): ?>
                                    <option value="<?php echo $rowAlt['id']; ?>" <?php echo ($row['alternatif_id'] == $rowAlt['id']) ? 'selected' : ''; ?>>
                                        <?php echo $rowAlt['kode_alternatif'] . ' - ' . $rowAlt['judul_film']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kriteria</label>
                            <select name="kriteria_id" id="kriteria_id_edit" class="form-control chosen" data-placeholder="Pilih Kriteria" required>
                                <option value="">Pilih Kriteria</option>
                                <?php while ($rowKrit = $resultKriteria->fetch_assoc()): ?>
                                    <option value="<?php echo $rowKrit['id']; ?>" <?php echo ($row['kriteria_id'] == $rowKrit['id']) ? 'selected' : ''; ?>>
                                        <?php echo $rowKrit['kode_kriteria'] . ' - ' . $rowKrit['nama_kriteria']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nilai Sub-Kriteria</label>
                            <select name="sub_kriteria_id" id="sub_kriteria_id_edit" class="form-control" required>
                                <?php
                                if ($resultSubKriteria->num_rows > 0) {
                                    while ($rowSub = $resultSubKriteria->fetch_assoc()):
                                        $label = "Nilai: " . $rowSub['nilai'];
                                        if (!empty($rowSub['keterangan'])) {
                                            $label .= " - " . $rowSub['keterangan'];
                                        }
                                ?>
                                        <option value="<?php echo $rowSub['id']; ?>" <?php echo ($row['sub_kriteria_id'] == $rowSub['id']) ? 'selected' : ''; ?>>
                                            <?php echo $label; ?>
                                        </option>
                                <?php
                                    endwhile;
                                } else {
                                    echo '<option value="">Tidak ada sub-kriteria</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <input type="submit" value="Simpan" name="edit" class="btn btn-primary">
                        <a href="?page=penilaian" class="btn btn-danger">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>