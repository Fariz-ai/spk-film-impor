<?php
include_once "../config.php";
require_once "../vendor/autoload.php";

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'orientation' => 'P',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 45,
    'margin_bottom' => 25,
    'margin_header' => 10,
    'margin_footer' => 10
]);

// Header
$header = '
<table width="100%" style="border-bottom:1px solid #000;padding-bottom:10px;">
    <tr>
        <td align="center">
            <h2 style="margin:5px 0;font-size:18px;">PT. CINEMA MULTIMEDIA</h2>
            <h3 style="margin:5px 0;font-size:14px;">LAPORAN DATA PENGGUNA</h3>
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

// Query data pengguna
$sql = "SELECT nama_lengkap, email, role, dibuat_pada FROM pengguna
        ORDER BY 
            CASE 
                WHEN role = 'Admin' THEN 0
                ELSE 1
            END,
            dibuat_pada DESC";

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
        padding: 6px 5px;
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
            <th width="5%">No</th>
            <th width="30%">Nama Lengkap</th>
            <th width="35%">Email</th>
            <th width="15%">Role</th>
            <th width="15%">Tanggal Dibuat</th>
        </tr>
    </thead>
    <tbody>';

if ($result->num_rows > 0) {
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        $html .= '
        <tr>
            <td class="text-center">' . $no++ . '</td>
            <td class="text-left">' . htmlspecialchars($row['nama_lengkap']) . '</td>
            <td class="text-left">' . htmlspecialchars($row['email']) . '</td>
            <td class="text-center">' . htmlspecialchars($row['role']) . '</td>
            <td class="text-center">' . date('d/m/Y', strtotime($row['dibuat_pada'])) . '</td>
        </tr>';
    }
    $totalData = $result->num_rows;
} else {
    $html .= '
        <tr>
            <td colspan="5" class="no-data">Tidak ada data pengguna</td>
        </tr>';
    $totalData = 0;
}

$html .= '
    </tbody>
</table>

<div class="footer-info">
    Total Data: ' . $totalData . ' pengguna
</div>';

$mpdf->WriteHTML($html);
$conn->close();

// Output PDF
$mpdf->Output('Laporan_Data_Pengguna_' . date('Ymd_His') . '.pdf', 'I');
exit;
