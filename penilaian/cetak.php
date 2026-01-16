<?php
include_once "../config.php";
require_once "../vendor/autoload.php";

$filterPeriode = isset($_GET['periode']) ? $_GET['periode'] : '';

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
        LAPORAN DATA PENILAIAN' . $judulPeriode . '
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
    INNER JOIN sub_kriteria ON penilaian.sub_kriteria_id = sub_kriteria.id
";

if (!empty($filterPeriode)) {
    $sql .= " WHERE DATE_FORMAT(penilaian.periode, '%Y-%m') = '$filterPeriode'";
}

$sql .= " ORDER BY penilaian.periode DESC, alternatif.kode_alternatif ASC, kriteria.kode_kriteria ASC";
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
        padding:6px;
        font-size:10px;
        text-align:center;
    }
    td {
        border:1px solid #000;
        padding:5px;
        font-size:9px;
        vertical-align:top;
    }
    .center { text-align:center; }
    .left { text-align:left; }
</style>

<table>
<thead>
<tr>
    <th width="4%">No</th>
    <th width="10%">Periode</th>
    <th width="10%">Kode Alt</th>
    <th width="18%">Judul Film</th>
    <th width="8%">Kode</th>
    <th width="15%">Nama Kriteria</th>
    <th width="6%">Nilai</th>
    <th width="29%">Keterangan</th>
</tr>
</thead>
<tbody>
';

if ($result->num_rows > 0) {
    $no = 1;
    while ($row = $result->fetch_assoc()) {

        $ts = strtotime($row['periode'] . '-01');
        $periodeDisplay = $bulan[date('n', $ts)] . ' ' . date('Y', $ts);

        $html .= '
        <tr>
            <td class="center">' . $no++ . '</td>
            <td class="center">' . $periodeDisplay . '</td>
            <td class="center">' . htmlspecialchars($row['kode_alternatif']) . '</td>
            <td class="left">' . htmlspecialchars($row['judul_film']) . '</td>
            <td class="center">' . htmlspecialchars($row['kode_kriteria']) . '</td>
            <td class="left">' . htmlspecialchars($row['nama_kriteria']) . '</td>
            <td class="center">' . htmlspecialchars($row['nilai']) . '</td>
            <td class="left">' . htmlspecialchars($row['keterangan']) . '</td>
        </tr>';
    }
    $total = $result->num_rows;
} else {
    $html .= '
        <tr>
            <td colspan="8" class="center">Tidak ada data penilaian</td>
        </tr>';
    $total = 0;
}

$html .= '
</tbody>
</table>

<p style="margin-top:10px; font-size:11px; font-weight:bold;">
    Total Data: ' . $total . ' penilaian
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
    'Laporan_Data_Penilaian_' . date('Ymd_His') . '.pdf',
    'I'
);
exit;
