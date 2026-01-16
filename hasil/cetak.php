<?php
include_once "../config.php";
require_once '../vendor/autoload.php';

$filterPeriode = $_GET['periode'] ?? '';

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'orientation' => 'P',
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 45,
    'margin_bottom' => 35
]);

$bulan = [
    1 => 'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
];

$judulPeriode = '';
if (!empty($filterPeriode)) {
    $ts = strtotime($filterPeriode . '-01');
    $judulPeriode = ' - ' . $bulan[date('n', $ts)] . ' ' . date('Y', $ts);
}

$logoPath = __DIR__ . '/../assets/images/logo.png';

$header = '
<div style="padding:5px 10px 0 10px;">

    <div style="text-align:center;">
        <img src="' . $logoPath . '" width="120">
    </div>

    <div style="text-align:center; font-size:10px; margin-top:2px;">
        Gedung Kopi, Jl. RP Soeroso No.20 9, RT.9/RW.5, Cikini,<br>
        Kec. Menteng, Jakarta, Daerah Khusus Ibukota Jakarta 10330
    </div>

    <div style="text-align:center; font-size:14px; font-weight:bold; margin-top:6px;">
        LAPORAN HASIL PERANKINGAN' . $judulPeriode . '
    </div>

</div>
';

$mpdf->SetHTMLHeader($header);

$mpdf->SetHTMLFooter('
<div style="border-top:1px solid #000; text-align:center; font-size:10px; padding-top:4px;">
    Halaman {PAGENO} dari {nbpg}
</div>
');

$sql = "
    SELECT 
        hasil.periode,
        hasil.ranking,
        hasil.nilai_preferensi,
        alternatif.kode_alternatif,
        alternatif.judul_film
    FROM hasil
    INNER JOIN alternatif ON hasil.alternatif_id = alternatif.id
";

if (!empty($filterPeriode)) {
    $sql .= " WHERE hasil.periode = '" . $filterPeriode . "-01'";
}

$sql .= " ORDER BY hasil.ranking ASC";
$result = $conn->query($sql);

$html = '
<style>
    body { font-family: Arial, sans-serif; }
    table {
        width:100%;
        border-collapse:collapse;
        margin-top:8px;
    }
    th {
        background:#CFE2FF;
        border:1px solid #000;
        padding:7px;
        font-size:11px;
        text-align:center;
    }
    td {
        border:1px solid #000;
        padding:6px;
        font-size:10px;
    }
    .center { text-align:center; }
    .left { text-align:left; }
</style>

<table>
<thead>
<tr>
    <th width="7%">Ranking</th>
    <th width="18%">Kode Alternatif</th>
    <th width="35%">Judul Film</th>
    <th width="20%">Nilai Preferensi</th>
    <th width="20%">Periode</th>
</tr>
</thead>
<tbody>
';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $ts = strtotime($row['periode']);
        $periodeDisplay = $bulan[date('n', $ts)] . ' ' . date('Y', $ts);

        $html .= '
        <tr>
            <td class="center">' . $row['ranking'] . '</td>
            <td class="center">' . htmlspecialchars($row['kode_alternatif']) . '</td>
            <td class="left">' . htmlspecialchars($row['judul_film']) . '</td>
            <td class="center">' . number_format($row['nilai_preferensi'], 4) . '</td>
            <td class="center">' . $periodeDisplay . '</td>
        </tr>';
    }
    $total = $result->num_rows;
} else {
    $html .= '
        <tr>
            <td colspan="5" class="center">Tidak ada data hasil perankingan</td>
        </tr>';
    $total = 0;
}

$html .= '
</tbody>
</table>

<p style="margin-top:10px; font-size:11px; font-weight:bold;">
    Total Data: ' . $total . ' alternatif
</p>
';

$tanggalCetak = date('d') . ' ' . $bulan[date('n')] . ' ' . date('Y');

$html .= '
<div style="margin-top:45px; width:100%;">
    <div style="width:40%; float:right; text-align:right; font-size:12px;">
        <div>Jakarta, ' . $tanggalCetak . '</div>

        <div style="height:80px;"></div>

        <div style="font-weight:bold; text-decoration:underline;">
            Inne Fadlianty
        </div>
        <div>Staff</div>
    </div>
</div>
';

$mpdf->WriteHTML($html);
$conn->close();

$mpdf->Output(
    'Laporan_Hasil_Perankingan_' . date('Ymd_His') . '.pdf',
    'I'
);
exit;
