<?php
include_once "../config.php";
require_once "../vendor/autoload.php";

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
        LAPORAN DATA PENGGUNA
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
    SELECT nama_lengkap, email, role, dibuat_pada
    FROM pengguna
    ORDER BY 
        CASE WHEN role = 'Admin' THEN 0 ELSE 1 END,
        dibuat_pada DESC
";
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
        padding:8px;
        font-size:11px;
        text-align:center;
    }
    td {
        border:1px solid #000;
        padding:7px;
        font-size:10px;
    }
    .center { text-align:center; }
    .left { text-align:left; }
</style>

<table>
<thead>
<tr>
    <th width="7%">No</th>
    <th width="30%">Nama Lengkap</th>
    <th width="33%">Email</th>
    <th width="15%">Role</th>
    <th width="15%">Tanggal Dibuat</th>
</tr>
</thead>
<tbody>
';

if ($result->num_rows > 0) {
    $no = 1;
    while ($row = $result->fetch_assoc()) {

        $tgl = strtotime($row['dibuat_pada']);
        $tglDisplay = date('d', $tgl) . ' ' .
            $bulan[date('n', $tgl)] . ' ' .
            date('Y', $tgl);

        $html .= '
        <tr>
            <td class="center">' . $no++ . '</td>
            <td class="left">' . htmlspecialchars($row['nama_lengkap']) . '</td>
            <td class="left">' . htmlspecialchars($row['email']) . '</td>
            <td class="center">' . htmlspecialchars($row['role']) . '</td>
            <td class="center">' . $tglDisplay . '</td>
        </tr>';
    }
    $total = $result->num_rows;
} else {
    $html .= '
        <tr>
            <td colspan="5" class="center">Tidak ada data pengguna</td>
        </tr>';
    $total = 0;
}

$html .= '
</tbody>
</table>

<p style="margin-top:10px; font-size:11px; font-weight:bold;">
    Total Data: ' . $total . ' pengguna
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
    'Laporan_Data_Pengguna_' . date('Ymd_His') . '.pdf',
    'I'
);
exit;
