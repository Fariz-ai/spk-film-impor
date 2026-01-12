<?php
include_once "../config.php";
require_once '../vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'orientation' => 'P',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 48,
    'margin_bottom' => 25,
    'margin_header' => 10,
    'margin_footer' => 10
]);

// Set Header
$header = '
<table width="100%" style="border-bottom: 1px solid #000; padding-bottom: 10px;">
    <tr>
        <td align="center">
            <h2 style="margin: 5px 0; font-size: 18px;">PT. CINEMA MULTIMEDIA</h2>
            <h3 style="margin: 5px 0; font-size: 14px;">LAPORAN DATA ALTERNATIF</h3>
            <p style="margin: 5px 0; font-size: 10px;">Tanggal Cetak: ' . date('d/m/Y H:i:s') . '</p>
        </td>
    </tr>
</table>';

// Set Footer
$footer = '
<table width="100%" style="border-top: 1px solid #000; padding-top: 5px;">
    <tr>
        <td align="center" style="font-size: 9px; font-style: italic;">
            Halaman {PAGENO} dari {nbpg}
        </td>
    </tr>
</table>';

$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($footer);

// Query data
$sql = "SELECT * FROM alternatif ORDER BY dibuat_pada DESC";
$result = $conn->query($sql);

// Array nama bulan
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

// Content
$html = '
<style>
    body {
        font-family: Arial, sans-serif;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    th {
        background-color: #C8DCFF;
        color: #000;
        font-weight: bold;
        padding: 10px 8px;
        border: 1px solid #000;
        text-align: center;
        font-size: 11px;
    }
    td {
        padding: 8px;
        border: 1px solid #000;
        font-size: 10px;
    }
    .text-center {
        text-align: center;
    }
    .text-left {
        text-align: left;
    }
    .no-data {
        font-style: italic;
        color: #666;
        text-align: center;
    }
    .footer-info {
        margin-top: 15px;
        font-weight: bold;
        font-size: 11px;
    }
</style>

<table>
    <thead>
        <tr>
            <th width="8%">No.</th>
            <th width="20%">Kode Alternatif</th>
            <th width="42%">Judul Film</th>
            <th width="30%">Periode Rilis</th>
        </tr>
    </thead>
    <tbody>';

if ($result->num_rows > 0) {
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        // Format tanggal
        $tanggal = date('d', strtotime($row["periode_rilis"]));
        $bulanAngka = date('n', strtotime($row["periode_rilis"]));
        $tahun = date('Y', strtotime($row["periode_rilis"]));
        $periodeRilis = "$tanggal {$bulan[$bulanAngka]} $tahun";

        $html .= '
        <tr>
            <td class="text-center">' . $no++ . '</td>
            <td class="text-center">' . htmlspecialchars($row['kode_alternatif']) . '</td>
            <td class="text-left">' . htmlspecialchars($row['judul_film']) . '</td>
            <td class="text-center">' . $periodeRilis . '</td>
        </tr>';
    }

    $totalData = $result->num_rows;
} else {
    $html .= '
        <tr>
            <td colspan="4" class="no-data">Tidak ada data alternatif</td>
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
$mpdf->Output('Laporan_Data_Alternatif_' . date('Ymd_His') . '.pdf', 'I');
exit;
