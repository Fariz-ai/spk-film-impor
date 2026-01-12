<?php
include_once "../config.php";
require_once '../vendor/autoload.php';

// Filter periode dari URL
$filterPeriode = isset($_GET['periode']) ? $_GET['periode'] : '';

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

// Judul berdasarkan filter
$judulPeriode = '';
if (!empty($filterPeriode)) {
    $timestamp = strtotime($filterPeriode . '-01');
    $bulan = (int) date('n', $timestamp);
    $tahun = date('Y', $timestamp);
    $judulPeriode = ' - ' . $periodeBulan[$bulan] . ' ' . $tahun;
}

// Set Header
$header = '
<table width="100%" style="border-bottom: 1px solid #000; padding-bottom: 10px;">
    <tr>
        <td align="center">
            <h2 style="margin: 5px 0; font-size: 18px;">PT. CINEMA MULTIMEDIA</h2>
            <h3 style="margin: 5px 0; font-size: 14px;">LAPORAN DATA PENILAIAN' . $judulPeriode . '</h3>
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
        padding: 8px 5px;
        border: 1px solid #000;
        text-align: center;
        font-size: 9px;
    }
    td {
        padding: 6px 4px;
        border: 1px solid #000;
        font-size: 8px;
        vertical-align: top;
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
        font-size: 10px;
    }
</style>

<table>
    <thead>
        <tr>
            <th width="4%">No.</th>
            <th width="10%">Periode</th>
            <th width="10%">Kode Alternatif</th>
            <th width="18%">Judul Film</th>
            <th width="8%">Kode Kriteria</th>
            <th width="15%">Nama Kriteria</th>
            <th width="6%">Nilai</th>
            <th width="29%">Keterangan</th>
        </tr>
    </thead>
    <tbody>';

if ($result->num_rows > 0) {
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        // Format periode
        $timestamp = strtotime($row['periode'] . '-01');
        $bulan = (int) date('n', $timestamp);
        $tahun = date('Y', $timestamp);
        $periodeDisplay = $periodeBulan[$bulan] . ' ' . $tahun;

        $html .= '
        <tr>
            <td class="text-center">' . $no++ . '</td>
            <td class="text-center">' . $periodeDisplay . '</td>
            <td class="text-center">' . htmlspecialchars($row['kode_alternatif']) . '</td>
            <td class="text-left">' . htmlspecialchars($row['judul_film']) . '</td>
            <td class="text-center">' . htmlspecialchars($row['kode_kriteria']) . '</td>
            <td class="text-left">' . htmlspecialchars($row['nama_kriteria']) . '</td>
            <td class="text-center">' . htmlspecialchars($row['nilai']) . '</td>
            <td class="text-left">' . htmlspecialchars($row['keterangan']) . '</td>
        </tr>';
    }

    $totalData = $result->num_rows;
} else {
    $html .= '
        <tr>
            <td colspan="8" class="no-data">Tidak ada data penilaian</td>
        </tr>';

    $totalData = 0;
}

$html .= '
    </tbody>
</table>

<div class="footer-info">
    Total Data: ' . $totalData . ' penilaian
</div>';

$mpdf->WriteHTML($html);

$conn->close();

// Output PDF
$mpdf->Output('Laporan_Data_Penilaian_' . date('Ymd_His') . '.pdf', 'I');
exit;
