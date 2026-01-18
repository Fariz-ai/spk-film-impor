<?php
// Filter periode
$filterPeriode = isset($_GET['periode']) ? $_GET['periode'] : '';

// Query data penilaian
$sql = "SELECT 
            penilaian.id,
            penilaian.alternatif_id,
            penilaian.kriteria_id,
            penilaian.sub_kriteria_id,
            penilaian.periode,
            alternatif.kode_alternatif,
            alternatif.judul_film,
            kriteria.kode_kriteria,
            kriteria.nama_kriteria,
            sub_kriteria.nilai,
            sub_kriteria.keterangan
        FROM penilaian
        INNER JOIN alternatif ON penilaian.alternatif_id = alternatif.id
        INNER JOIN kriteria ON penilaian.kriteria_id = kriteria.id
        INNER JOIN sub_kriteria ON penilaian.sub_kriteria_id = sub_kriteria.id";

if (!empty($filterPeriode)) {
    $sql .= " WHERE DATE_FORMAT(penilaian.periode, '%Y-%m') = '$filterPeriode'";
}

$sql .= " ORDER BY penilaian.periode DESC, alternatif.kode_alternatif ASC, kriteria.kode_kriteria ASC";
$result = $conn->query($sql);

// Cek apakah ada data penilaian
$sql_count = "SELECT COUNT(*) as total FROM penilaian";
if (!empty($filterPeriode)) {
    $sql_count .= " WHERE DATE_FORMAT(periode, '%Y-%m') = '$filterPeriode'";
}
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$ada_data = $row_count['total'] > 0;

// Query periode
$sqlPeriode = "SELECT DISTINCT DATE_FORMAT(periode, '%Y-%m') AS periode_format FROM penilaian ORDER BY periode DESC";
$resultPeriode = $conn->query($sqlPeriode);

$periodeBulan = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
];
?>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white border-dark">
        <strong>Data Penilaian</strong>
    </div>

    <div class="card-body">

        <!-- Tombol Aksi + Filter -->
        <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">

            <!-- Tombol Kiri -->
            <div class="d-flex flex-wrap gap-2">
                <a href="?page=penilaian&action=tambah" class="btn btn-primary btn-sm mr-1">
                    <i class="fas fa-plus mr-1"></i> Tambah
                </a>

                <?php
                $cetakUrl = 'penilaian/cetak.php';
                if (!empty($filterPeriode)) {
                    $cetakUrl .= '?periode=' . $filterPeriode;
                }
                ?>

                <a href="<?= $cetakUrl ?>"
                    target="_blank"
                    class="btn btn-<?= $ada_data ? 'success' : 'secondary' ?> btn-sm <?= $ada_data ? '' : 'disabled' ?>"
                    <?= $ada_data ? '' : 'onclick="return false;"' ?>>
                    <i class="fas fa-print mr-1"></i> Cetak
                </a>
            </div>

            <!-- Filter Kanan -->
            <form method="GET" class="d-flex flex-wrap align-items-center gap-2">
                <input type="hidden" name="page" value="penilaian">

                <label class="mb-0 mr-1">Periode:</label>
                <select name="periode" class="form-control form-control-sm chosen"
                    onchange="this.form.submit()">
                    <option value="">Semua</option>

                    <?php
                    $resultPeriode->data_seek(0);
                    while ($rowPeriode = $resultPeriode->fetch_assoc()):
                        $timestamp = strtotime($rowPeriode['periode_format'] . '-01');
                        $bulan = (int) date('n', $timestamp);
                        $tahun = date('Y', $timestamp);
                        $periodeDisplay = $periodeBulan[$bulan] . ' ' . $tahun;
                    ?>
                        <option value="<?= $rowPeriode['periode_format'] ?>"
                            <?= $filterPeriode == $rowPeriode['periode_format'] ? 'selected' : '' ?>>
                            <?= $periodeDisplay ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <?php if (!empty($filterPeriode)): ?>
                    <a href="?page=penilaian" class="btn btn-secondary btn-sm">
                        Reset
                    </a>
                <?php endif; ?>
            </form>

        </div>

        <!-- Tabel -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" id="dataTable">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Periode</th>
                        <th>Kode Alternatif</th>
                        <th>Judul Film</th>
                        <th>Kode Kriteria</th>
                        <th>Nama Kriteria</th>
                        <th>Nilai</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if ($result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                            $timestamp = strtotime($row['periode'] . '-01');
                            $bulan = (int) date('n', $timestamp);
                            $tahun = date('Y', $timestamp);
                            $periodeDisplay = $periodeBulan[$bulan] . ' ' . $tahun;
                    ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="text-nowrap"><?= $periodeDisplay ?></td>
                                <td class="text-center"><?= $row['kode_alternatif'] ?></td>
                                <td><?= $row['judul_film'] ?></td>
                                <td class="text-center"><?= $row['kode_kriteria'] ?></td>
                                <td><?= $row['nama_kriteria'] ?></td>
                                <td class="text-center"><?= $row['nilai'] ?></td>
                                <td><?= $row['keterangan'] ?></td>
                                <td class="text-center">
                                    <div class="d-inline-flex justify-content-center">
                                        <a href="?page=penilaian&action=edit&id=<?= $row['id'] ?>"
                                            class="btn btn-warning btn-sm mr-1">
                                            Edit
                                        </a>
                                        <a href="?page=penilaian&action=hapus&id=<?= $row['id'] ?>"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data penilaian ini?')"
                                            class="btn btn-danger btn-sm">
                                            Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>