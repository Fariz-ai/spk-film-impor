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

// Query untuk periode terbaru dari tabel hasil
$queryPeriodeTerbaru = mysqli_query($conn, "SELECT MAX(periode) AS periode_terbaru FROM hasil");
$periodeTerbaru = mysqli_fetch_assoc($queryPeriodeTerbaru)['periode_terbaru'];

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


// Query untuk hasil perankingan berdasarkan periode terbaru
$hasilRanking = [];
if ($periodeTerbaru) {
    $queryHasil = mysqli_query($conn, "SELECT hasil.ranking, alternatif.kode_alternatif, alternatif.judul_film, hasil.nilai_preferensi
                                    FROM hasil JOIN alternatif ON hasil.alternatif_id = alternatif.id WHERE hasil.periode = '$periodeTerbaru'
                                    ORDER BY hasil.ranking ASC");
    while ($row = mysqli_fetch_assoc($queryHasil)) {
        $hasilRanking[] = $row;
    }
}
?>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card border-primary shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-uppercase text-primary small fw-bold">
                        Total Alternatif
                    </div>
                    <div class="h4 fw-bold mb-0"><?= $total_alternatif ?></div>
                </div>
                <i class="fas fa-film fa-2x text-muted"></i>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card border-success shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-uppercase text-success small fw-bold">
                        Total Kriteria
                    </div>
                    <div class="h4 fw-bold mb-0"><?= $total_kriteria ?></div>
                </div>
                <i class="fas fa-list fa-2x text-muted"></i>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card border-info shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-uppercase text-info small fw-bold">
                        Total Sub-Kriteria
                    </div>
                    <div class="h4 fw-bold mb-0"><?= $total_subkriteria ?></div>
                </div>
                <i class="fas fa-layer-group fa-2x text-muted"></i>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-dark">
            <div class="card-header bg-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <strong>Hasil Perankingan Terbaru</strong>

                <?php if ($periodeTerbaru): ?>
                    <span class="small mt-1 mt-md-0">
                        Periode:
                        <?= $periodeBulan[(int)date('n', strtotime($periodeTerbaru))] . ' ' . date('Y', strtotime($periodeTerbaru)) ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="card-body">

                <?php if (!empty($hasilRanking)) : ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Ranking</th>
                                    <th>Kode</th>
                                    <th>Judul Film</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($hasilRanking as $row) : ?>
                                    <tr>
                                        <td class="text-center fw-bold"><?= $row['ranking'] ?></td>
                                        <td class="text-center"><?= $row['kode_alternatif'] ?></td>
                                        <td><?= $row['judul_film'] ?></td>
                                        <td class="text-center">
                                            <?= number_format($row['nilai_preferensi'], 4) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="alert alert-warning text-center mb-0">
                        Belum ada data hasil perankingan.
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>