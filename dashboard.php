<?php

// Query untuk menghitung total alternatif
$query_alternatif = mysqli_query($conn, "SELECT COUNT(*) as total FROM alternatif");
$total_alternatif = mysqli_fetch_assoc($query_alternatif)['total'];

// Query untuk menghitung total kriteria
$query_kriteria = mysqli_query($conn, "SELECT COUNT(*) as total FROM kriteria");
$total_kriteria = mysqli_fetch_assoc($query_kriteria)['total'];

// Query untuk menghitung total sub-kriteria
$query_subkriteria = mysqli_query($conn, "SELECT COUNT(*) as total FROM sub_kriteria");
$total_subkriteria = mysqli_fetch_assoc($query_subkriteria)['total'];

// Query untuk menghitung total penilaian
$query_penilaian = mysqli_query($conn, "SELECT COUNT(*) as total FROM penilaian");
$total_penilaian = mysqli_fetch_assoc($query_penilaian)['total'];

// Query untuk mendapatkan hasil perhitungan SAW
$query_hasil = "
SELECT
a.id,
a.kode_alternatif,
a.judul_film,
a.genre,
a.perusahaan_produksi,
SUM(
(sk.nilai /
CASE
WHEN k.jenis = 'benefit' THEN (
SELECT MAX(sk2.nilai)
FROM penilaian p2
JOIN sub_kriteria sk2 ON p2.sub_kriteria_id = sk2.id
WHERE sk2.kriteria_id = k.id
)
ELSE (
SELECT MIN(sk2.nilai)
FROM penilaian p2
JOIN sub_kriteria sk2 ON p2.sub_kriteria_id = sk2.id
WHERE sk2.kriteria_id = k.id
)
END
) * k.bobot
) as nilai_preferensi
FROM alternatif a
LEFT JOIN penilaian p ON a.id = p.alternatif_id
LEFT JOIN kriteria k ON p.kriteria_id = k.id
LEFT JOIN sub_kriteria sk ON p.sub_kriteria_id = sk.id
GROUP BY a.id, a.kode_alternatif, a.judul_film, a.genre, a.perusahaan_produksi
HAVING nilai_preferensi IS NOT NULL
ORDER BY nilai_preferensi DESC
";

$hasil = mysqli_query($conn, $query_hasil);
$data_hasil = [];
$rank = 1;

while ($row = mysqli_fetch_assoc($hasil)) {
    $row['rank'] = $rank++;
    $data_hasil[] = $row;
}

// Film dengan skor tertinggi
$film_terbaik = !empty($data_hasil) ? $data_hasil[0] : null;
?>

<!-- Dashboard Content -->
<div class="row mb-4">
    <!-- Card Statistik -->
    <div class="col-xl-4 col-md-4 mb-3">
        <div class="card border-primary shadow-sm h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-uppercase text-primary font-weight-bold small mb-1">
                            Total Alternatif
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-dark"><?php echo $total_alternatif ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-film fa-2x text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-4 mb-3">
        <div class="card border-success shadow-sm h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-uppercase text-success font-weight-bold small mb-1">
                            Total Kriteria
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-dark"><?php echo $total_kriteria ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-list fa-2x text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-4 mb-3">
        <div class="card border-info shadow-sm h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-uppercase text-info font-weight-bold small mb-1">
                            Total Sub-Kriteria
                        </div>
                        <div class="h4 mb-0 font-weight-bold text-dark"><?php echo $total_subkriteria ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-layer-group fa-2x text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Film Terbaik -->
<?php if ($film_terbaik): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-warning shadow">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-trophy"></i> Film dengan Skor Tertinggi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="font-weight-bold text-primary mb-3"><?php echo $film_terbaik['judul_film']; ?></h4>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="200" class="font-weight-bold">Kode Alternatif</td>
                                    <td>: <?php echo $film_terbaik['kode_alternatif']; ?></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Genre</td>
                                    <td>: <?php echo $film_terbaik['genre'] ?? '-'; ?></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Perusahaan Produksi</td>
                                    <td>: <?php echo $film_terbaik['perusahaan_produksi'] ?? '-'; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4 text-center d-flex flex-column justify-content-center">
                            <div class="display-3 text-warning mb-3">
                                <i class="fas fa-medal"></i>
                            </div>
                            <h2 class="font-weight-bold text-primary">
                                <?php echo number_format($film_terbaik['nilai_preferensi'], 4); ?>
                            </h2>
                            <p class="text-muted mb-2">Nilai Preferensi</p>
                            <div>
                                <span class="badge badge-success badge-pill px-3 py-2">
                                    <i class="fas fa-crown"></i> Peringkat #1
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Tabel Hasil Perankingan -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-chart-bar"></i> Hasil Perankingan Film
                </h6>
                <a href="?page=perhitungan" class="btn btn-sm btn-light">
                    <i class="fas fa-calculator"></i> Lihat Detail Perhitungan
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($data_hasil)): ?>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle fa-3x mb-3 d-block"></i>
                        <h5 class="font-weight-bold">Belum Ada Data Penilaian</h5>
                        <p>Silakan tambahkan data alternatif dan penilaian terlebih dahulu untuk melihat hasil ranking.</p>
                        <a href="?page=alternatif" class="btn btn-primary mt-2">
                            <i class="fas fa-plus"></i> Tambah Alternatif
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="rankingTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="80" class="text-center">Rank</th>
                                    <th width="120">Kode</th>
                                    <th>Judul Film</th>
                                    <th width="150">Genre</th>
                                    <th width="200">Perusahaan Produksi</th>
                                    <th width="150" class="text-center">Nilai Preferensi</th>
                                    <th width="150" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data_hasil as $row): ?>
                                    <tr>
                                        <td class="text-center align-middle">
                                            <?php
                                            if ($row['rank'] == 1) {
                                                echo '<span class="badge badge-warning p-2"><i class="fas fa-trophy"></i> ' . $row['rank'] . '</span>';
                                            } elseif ($row['rank'] == 2) {
                                                echo '<span class="badge badge-secondary p-2"><i class="fas fa-medal"></i> ' . $row['rank'] . '</span>';
                                            } elseif ($row['rank'] == 3) {
                                                echo '<span class="badge badge-info p-2"><i class="fas fa-medal"></i> ' . $row['rank'] . '</span>';
                                            } else {
                                                echo '<span class="badge badge-light border p-2">' . $row['rank'] . '</span>';
                                            }
                                            ?>
                                        </td>
                                        <td class="align-middle"><strong><?php echo $row['kode_alternatif']; ?></strong></td>
                                        <td class="align-middle"><?php echo $row['judul_film']; ?></td>
                                        <td class="align-middle"><?php echo $row['genre'] ?? '-'; ?></td>
                                        <td class="align-middle"><?php echo $row['perusahaan_produksi'] ?? '-'; ?></td>
                                        <td class="text-center align-middle">
                                            <strong class="text-primary">
                                                <?php echo number_format($row['nilai_preferensi'], 4); ?>
                                            </strong>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php
                                            $nilai = $row['nilai_preferensi'];
                                            if ($nilai >= 0.8) {
                                                echo '<span class="badge badge-success p-2">Sangat Layak</span>';
                                            } elseif ($nilai >= 0.6) {
                                                echo '<span class="badge badge-primary p-2">Layak</span>';
                                            } elseif ($nilai >= 0.4) {
                                                echo '<span class="badge badge-warning p-2">Cukup Layak</span>';
                                            } else {
                                                echo '<span class="badge badge-danger p-2">Kurang Layak</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close();
?>

<script>
    $(document).ready(function() {
        $('#rankingTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "pageLength": 10,
            "order": [
                [0, "asc"]
            ],
            "responsive": true
        });
    });
</script>