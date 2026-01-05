<?php
$successAlert = '';
$data = [];
$preferensi = [];
$maxNilai = [];
$minNilai = [];

$filterPeriode = $_GET['periode'] ?? $_POST['periode'] ?? '';

// Ambil periode
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

// Ambil bobot
$bobot = [];
$qBobot = $conn->query("SELECT kode_kriteria, bobot FROM kriteria");
while ($b = $qBobot->fetch_assoc()) {
    $bobot[$b['kode_kriteria']] = $b['bobot'];
}

// Ambil data penilaian
if (!empty($filterPeriode)) {

    $sql = "SELECT 
            alternatif.id AS alt_id,
            alternatif.kode_alternatif,
            alternatif.judul_film,
            kriteria.kode_kriteria,
            kriteria.jenis,
            sub_kriteria.nilai
        FROM penilaian
        JOIN alternatif ON penilaian.alternatif_id = alternatif.id
        JOIN kriteria ON penilaian.kriteria_id = kriteria.id
        JOIN sub_kriteria ON penilaian.sub_kriteria_id = sub_kriteria.id
        WHERE DATE_FORMAT(penilaian.periode, '%Y-%m') = '$filterPeriode'
        ORDER BY alternatif.kode_alternatif, kriteria.kode_kriteria";

    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;

        $k = $row['kode_kriteria'];
        $n = $row['nilai'];

        $maxNilai[$k] = max($maxNilai[$k] ?? $n, $n);
        $minNilai[$k] = min($minNilai[$k] ?? $n, $n);
    }
}

// Proses perhitungan
if (!empty($data)) {
    foreach ($data as $row) {

        $alt   = $row['kode_alternatif'];
        $krt   = $row['kode_kriteria'];
        $nilai = $row['nilai'];

        $normalisasi = ($row['jenis'] === 'Benefit')
            ? $nilai / $maxNilai[$krt]
            : $minNilai[$krt] / $nilai;

        $preferensi[$alt] = ($preferensi[$alt] ?? 0)
            + ($normalisasi * $bobot[$krt]);
    }

    arsort($preferensi);
}

// Proses simpan
if (isset($_POST['simpan']) && !empty($preferensi)) {

    $periode = $filterPeriode . '-01';

    $conn->query("DELETE FROM hasil WHERE periode='$periode'");

    $rank = 1;
    foreach ($preferensi as $kodeAlt => $nilai) {

        $qAlt = $conn->query("SELECT id FROM alternatif WHERE kode_alternatif='$kodeAlt'");
        $alt = $qAlt->fetch_assoc();

        if ($alt) {
            $conn->query("INSERT INTO hasil (periode, alternatif_id, nilai_preferensi, ranking)
            VALUES ('$periode', '{$alt['id']}', '$nilai', '$rank')");
            $rank++;
        }
    }

    $successAlert = '
    <div class="alert alert-success alert-dismissible fade show">
        <strong>Berhasil!</strong> Hasil perankingan berhasil disimpan.
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>';
}
?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <strong>Normalisasi & Hasil Perankingan</strong>
    </div>
    <div class="card-body">

        <?= $successAlert ?>

        <form method="GET" class="form-inline mb-3">
            <input type="hidden" name="page" value="hasil">
            <label class="mr-2">Periode:</label>
            <select name="periode" class="form-control" onchange="this.form.submit()">
                <option value="">Pilih Periode</option>
                <?php while ($p = $resultPeriode->fetch_assoc()) :
                    $ts = strtotime($p['periode_format'] . '-01');
                ?>
                    <option value="<?= $p['periode_format'] ?>"
                        <?= $filterPeriode == $p['periode_format'] ? 'selected' : '' ?>>
                        <?= $periodeBulan[date('n', $ts)] . ' ' . date('Y', $ts) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <h5>Normaliasi Data</h5>
        <table class="table table-bordered">
            <thead class="text-center">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Film</th>
                    <th>Kriteria</th>
                    <th>Nilai</th>
                    <th>Normalisasi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data)) :
                    $no = 1;
                    foreach ($data as $row) :
                        $k = $row['kode_kriteria'];
                        $n = $row['nilai'];
                        $norm = ($row['jenis'] === 'Benefit')
                            ? $n / $maxNilai[$k]
                            : $minNilai[$k] / $n;
                ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $row['kode_alternatif'] ?></td>
                            <td><?= $row['judul_film'] ?></td>
                            <td class="text-center"><?= $k ?></td>
                            <td class="text-center"><?= $n ?></td>
                            <td class="text-center"><?= number_format($norm, 3) ?></td>
                        </tr>
                    <?php endforeach;
                else : ?>
                    <tr>
                        <td colspan="6" class="text-center">Pilih periode</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if (!empty($preferensi)) : ?>
            <h5>Hasil Perankingan</h5>
            <table class="table table-bordered">
                <thead class="text-center">
                    <tr>
                        <th>Ranking</th>
                        <th>Kode</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $r = 1;
                    foreach ($preferensi as $k => $v) : ?>
                        <tr>
                            <td class="text-center"><?= $r++ ?></td>
                            <td class="text-center"><?= $k ?></td>
                            <td class="text-center"><?= number_format($v, 4) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <form method="POST">
                <input type="hidden" name="periode" value="<?= $filterPeriode ?>">
                <button type="submit" name="simpan" class="btn btn-success">
                    Simpan Hasil
                </button>
            </form>
        <?php endif; ?>

    </div>
</div>