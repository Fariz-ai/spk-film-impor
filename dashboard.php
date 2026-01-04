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

<div class="row mb-4">
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

<div class="row">
    <div class="col-xl-12">
        <div class="card shadow-sm border-dark">
            <div class="card-header bg-primary text-white">
                <strong>Hasil Perankingan Terbaru</strong>
                <?php if ($periodeTerbaru): ?>
                    <span class="float-right">
                        Periode: <?= $periodeBulan[(int)date('n', strtotime($periodeTerbaru))] . ' ' . date('Y', strtotime($periodeTerbaru)) ?>
                    </span>
                <?php endif; ?>
            </div>
            <div class="card-body">

                <?php if (!empty($hasilRanking)) : ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="text-center bg-light">
                                <tr>
                                    <th>Ranking</th>
                                    <th>Kode Alternatif</th>
                                    <th>Judul Film</th>
                                    <th>Nilai Preferensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($hasilRanking as $row) : ?>
                                    <tr>
                                        <td class="text-center">
                                            <?= $row['ranking'] ?>
                                        </td>
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
                    <div class="alert alert-warning text-center">
                        Belum ada data hasil perankingan yang tersimpan.
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>