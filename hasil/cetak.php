<?php
include_once "../config.php";
require_once '../vendor/autoload.php';

// Filter periode dari URL
$filterPeriode = $_GET['periode'] ?? '';

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'orientation' => 'L',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 48,
    'margin_bottom' => 25,
    'margin_header' => 10,
    'margin_footer' => 10
]);

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

// Judul berdasarkan periode
$judulPeriode = '';
if (!empty($filterPeriode)) {
    $ts = strtotime($filterPeriode . '-01');
    $judulPeriode = ' - ' . $periodeBulan[date('n', $ts)] . ' ' . date('Y', $ts);
}

// Header
$header = '
<table width="100%" style="border-bottom:1px solid #000;padding-bottom:10px;">
    <tr>
        <td align="center">
            <h2 style="margin:5px 0;font-size:18px;">PT. CINEMA MULTIMEDIA</h2>
            <h3 style="margin:5px 0;font-size:14px;">
                LAPORAN HASIL PERANKINGAN' . $judulPeriode . '
            </h3>
            <p style="margin:5px 0;font-size:10px;">
                Tanggal Cetak: ' . date('d/m/Y H:i:s') . '
            </p>
        </td>
    </tr>
</table>';

// Footer
$footer = '
<table width="100%" style="border-top:1px solid #000;padding-top:5px;">
    <tr>
        <td align="center" style="font-size:9px;font-style:italic;">
            Halaman {PAGENO} dari {nbpg}
        </td>
    </tr>
</table>';

$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($footer);

// Query hasil perankingan
$sql = "SELECT 
            hasil.periode,
            hasil.nilai_preferensi,
            hasil.ranking,
            alternatif.kode_alternatif,
            alternatif.judul_film
        FROM hasil
        INNER JOIN alternatif ON hasil.alternatif_id = alternatif.id";

if (!empty($filterPeriode)) {
    $periode = $filterPeriode . '-01';
    $sql .= " WHERE hasil.periode = '$periode'";
}

$sql .= " ORDER BY hasil.ranking ASC";

$result = $conn->query($sql);

// Content
$html = '
<style>
    body { font-family: Arial, sans-serif; }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    th {
        background-color: #C8DCFF;
        border: 1px solid #000;
        padding: 8px 5px;
        font-size: 10px;
        text-align: center;
    }
    td {
        border: 1px solid #000;
        padding: 6px 4px;
        font-size: 9px;
    }
    .text-center { text-align: center; }
    .text-left { text-align: left; }
    .no-data {
        text-align: center;
        font-style: italic;
        color: #666;
    }
    .footer-info {
        margin-top: 15px;
        font-weight: bold;
        font-size: 10px;
    }
</style>

<table>
    <thead>
        <tr>
            <th width="5%">Ranking</th>
            <th width="15%">Kode Alternatif</th>
            <th width="25%">Judul Film</th>
            <th width="20%">Nilai Preferensi</th>
            <th width="20%">Periode</th>
        </tr>
    </thead>
    <tbody>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $ts = strtotime($row['periode']);
        $periodeDisplay = $periodeBulan[date('n', $ts)] . ' ' . date('Y', $ts);

        $html .= '
        <tr>
            <td class="text-center">' . $row['ranking'] . '</td>
            <td class="text-center">' . htmlspecialchars($row['kode_alternatif']) . '</td>
            <td class="text-left">' . htmlspecialchars($row['judul_film']) . '</td>
            <td class="text-center">' . number_format($row['nilai_preferensi'], 4) . '</td>
            <td class="text-center">' . $periodeDisplay . '</td>
        </tr>';
    }

    $totalData = $result->num_rows;
} else {
    $html .= '
        <tr>
            <td colspan="5" class="no-data">Tidak ada data hasil perankingan</td>
        </tr>';
    $totalData = 0;
}

$html .= '
    </tbody>
</table>

<div class="footer-info">
    Total Data: ' . $totalData . ' alternatif
</div>';

$mpdf->WriteHTML($html);
$conn->close();

// Output PDF
$mpdf->Output('Laporan_Hasil_Perankingan_' . date('Ymd_His') . '.pdf', 'I');
exit;
