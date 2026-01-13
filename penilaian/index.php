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

<div class="row">
    <div class="col-sm-12">
        <div class="card border-dark">
            <div class="card-header bg-primary text-white border-dark">
                <strong>Data Penilaian</strong>
            </div>
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <a href="?page=penilaian&action=tambah" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Tambah Data
                        </a>

                        <?php
                        $cetakUrl = 'penilaian/cetak.php';
                        if (!empty($filterPeriode)) {
                            $cetakUrl .= '?periode=' . $filterPeriode;
                        }
                        ?>
                        <a href="<?php echo $cetakUrl; ?>"
                            class="btn btn-<?php echo $ada_data ? 'success' : 'secondary'; ?> <?php echo $ada_data ? '' : 'disabled'; ?>"
                            <?php echo $ada_data ? '' : 'onclick="return false;"'; ?>
                            target="_blank">
                            <i class="fas fa-print"></i> Cetak
                        </a>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" class="form-inline float-right">
                            <input type="hidden" name="page" value="penilaian">
                            <label class="mr-2">Filter Periode:</label>
                            <select name="periode" class="form-control chosen" onchange="this.form.submit()">
                                <option value="">Semua Periode</option>

                                <?php
                                // Reset pointer untuk select periode
                                $resultPeriode->data_seek(0);
                                while ($rowPeriode = $resultPeriode->fetch_assoc()):
                                ?>
                                    <?php
                                    $timestamp = strtotime($rowPeriode['periode_format'] . '-01');
                                    $bulan = (int) date('n', $timestamp);
                                    $tahun = date('Y', $timestamp);
                                    $periodeDisplay = $periodeBulan[$bulan] . ' ' . $tahun;
                                    ?>
                                    <option value="<?php echo $rowPeriode['periode_format']; ?>"
                                        <?php echo ($filterPeriode == $rowPeriode['periode_format']) ? 'selected' : ''; ?>>
                                        <?php echo $periodeDisplay; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>

                            <?php if (!empty($filterPeriode)): ?>
                                <a href="?page=penilaian" class="btn btn-secondary ml-1">Reset</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="dataTable">
                        <thead class="bg-light">
                            <tr>
                                <th>No.</th>
                                <th>Periode</th>
                                <th>Kode Alternatif</th>
                                <th width="15%">Judul Film</th>
                                <th>Kode Kriteria</th>
                                <th width="15%">Nama Kriteria</th>
                                <th>Nilai</th>
                                <th width="15%">Keterangan</th>
                                <th width="20%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {

                                    $timestamp = strtotime($row['periode'] . '-01');
                                    $bulan = (int) date('n', $timestamp);
                                    $tahun = date('Y', $timestamp);
                                    $periodeDisplay = $periodeBulan[$bulan] . ' ' . $tahun;
                            ?>
                                    <tr>
                                        <td align="center"><?php echo $no++; ?></td>
                                        <td><?php echo $periodeDisplay; ?></td>
                                        <td><?php echo $row['kode_alternatif']; ?></td>
                                        <td><?php echo $row['judul_film']; ?></td>
                                        <td><?php echo $row['kode_kriteria']; ?></td>
                                        <td><?php echo $row['nama_kriteria']; ?></td>
                                        <td align="center"><?php echo $row['nilai']; ?></td>
                                        <td><?php echo $row['keterangan']; ?></td>
                                        <td align="center">
                                            <a href="?page=penilaian&action=edit&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                                Edit
                                            </a>
                                            <a href="?page=penilaian&action=hapus&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data penilaian ini?')">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>