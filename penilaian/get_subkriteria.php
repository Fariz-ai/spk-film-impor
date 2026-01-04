<?php
include "../config.php";

if (isset($_POST['kriteria_id'])) {
    $kriteriaId = $_POST['kriteria_id'];

    $sql = "SELECT id, nilai, keterangan FROM sub_kriteria WHERE kriteria_id = '$kriteriaId' ORDER BY nilai DESC";
    $result = $conn->query($sql);

    $output = '<option value="" disabled selected>Pilih Nilai</option>';

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $label = "Nilai: " . $row['nilai'];
            if (!empty($row['keterangan'])) {
                $label .= " - " . $row['keterangan'];
            }
            $output .= '<option value="' . $row['id'] . '">' . $label . '</option>';
        }
    } else {
        $output = '<option value="" disabled>Tidak ada sub-kriteria</option>';
    }

    echo $output;
}
