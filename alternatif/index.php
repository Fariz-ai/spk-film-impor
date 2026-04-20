<?php
// Filter Periode
$filterPeriode = $_GET['periode'] ?? '';

if (!empty($filterPeriode) && !preg_match('/^\d{4}-\d{2}$/', $filterPeriode)) {
    $filterPeriode = '';
}

// Pagination
$perPage = 10;
$currentPage = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
if ($currentPage < 1) $currentPage = 1;
$offset = ($currentPage - 1) * $perPage;

// Query data
$where = "";
if (!empty($filterPeriode)) {
    $where = " WHERE DATE_FORMAT(periode_rilis, '%Y-%m') = '$filterPeriode'";
}

$sql = "SELECT * FROM alternatif $where ORDER BY dibuat_pada DESC LIMIT $perPage OFFSET $offset";
$result = $conn->query($sql);

// total data
$sql_total = "SELECT COUNT(*) as total FROM alternatif $where";
$result_total = $conn->query($sql_total);
$totalData = $result_total->fetch_assoc()['total'];
$totalPage = ceil($totalData / $perPage);

// cek data
$ada_data = $totalData > 0;

$sqlPeriode = "SELECT DISTINCT DATE_FORMAT(periode_rilis, '%Y-%m') as periode FROM alternatif ORDER BY periode DESC";
$resultPeriode = $conn->query($sqlPeriode);

$formatter = new IntlDateFormatter(
    'id_ID',
    IntlDateFormatter::NONE,
    IntlDateFormatter::NONE,
    'Asia/Jakarta',
    IntlDateFormatter::GREGORIAN,
    'MMMM yyyy'
);

function buildPaginationUrlAlt($page, $filterPeriode)
{
    $params = ['page' => 'alternatif', 'halaman' => $page];
    if (!empty($filterPeriode)) {
        $params['periode'] = $filterPeriode;
    }
    return '?' . http_build_query($params);
}
?>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white border-dark">
        <strong>Data Alternatif</strong>
    </div>

    <div class="card-body">

        <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">

            <!-- kiri -->
            <div class="d-flex flex-wrap gap-2">
                <a href="?page=alternatif&action=tambah" class="btn btn-primary btn-sm mr-1">
                    <i class="fas fa-plus mr-1"></i> Tambah
                </a>

                <?php
                $cetakUrl = 'alternatif/cetak.php';
                if (!empty($filterPeriode)) {
                    $cetakUrl .= '?periode=' . $filterPeriode;
                }
                ?>

                <a href="<?= $cetakUrl ?>"
                    target="_blank"
                    class="btn btn-<?= $ada_data ? 'success' : 'secondary' ?> btn-sm <?= $ada_data ? '' : 'disabled' ?>">
                    <i class="fas fa-print mr-1"></i> Cetak
                </a>
            </div>

            <form method="GET" class="d-flex align-items-center gap-2">
                <input type="hidden" name="page" value="alternatif">

                <label class="mb-0 mr-1">Periode:</label>
                <select name="periode" class="form-control form-control-sm chosen" onchange="this.form.submit()">
                    <option value="">Semua</option>

                    <?php while ($rowP = $resultPeriode->fetch_assoc()):
                        $ts = strtotime($rowP['periode'] . '-01');
                        $periodeDisplay = $formatter->format($ts);
                    ?>
                        <option value="<?= $rowP['periode'] ?>"
                            <?= $filterPeriode == $rowP['periode'] ? 'selected' : '' ?>>
                            <?= $periodeDisplay ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <?php if (!empty($filterPeriode)): ?>
                    <a href="?page=alternatif" class="btn btn-secondary btn-sm">Reset</a>
                <?php endif; ?>
            </form>

        </div>

        <?php if ($totalData > 0): ?>
            <div class="mb-2 text-muted small">
                Menampilkan <?= $offset + 1 ?>–<?= min($offset + $perPage, $totalData) ?> dari <?= $totalData ?> data
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode Alternatif</th>
                        <th>Judul Film</th>
                        <th>Periode Rilis</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = $offset + 1;

                    if ($result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                            $tanggal = $formatter->format(strtotime($row['periode_rilis']));
                    ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['kode_alternatif']) ?></td>
                                <td><?= htmlspecialchars($row['judul_film']) ?></td>
                                <td class="text-center"><?= $tanggal ?></td>
                                <td class="text-center">
                                    <div class="d-inline-flex">
                                        <a href="?page=alternatif&action=edit&id=<?= $row['id'] ?>"
                                            class="btn btn-warning btn-sm mr-1">Edit</a>
                                        <a href="?page=alternatif&action=hapus&id=<?= $row['id'] ?>"
                                            onclick="return confirm('Yakin hapus data ini?')"
                                            class="btn btn-danger btn-sm">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPage > 1): ?>
            <div class="d-flex justify-content-center mt-3">
                <ul class="pagination pagination-sm">

                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= buildPaginationUrlAlt($currentPage - 1, $filterPeriode) ?>">«</a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="<?= buildPaginationUrlAlt($i, $filterPeriode) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $currentPage >= $totalPage ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= buildPaginationUrlAlt($currentPage + 1, $filterPeriode) ?>">»</a>
                    </li>

                </ul>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php $conn->close(); ?>