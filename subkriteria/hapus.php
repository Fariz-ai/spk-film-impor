<?php
$id = $_GET['id'];

$sql = "DELETE FROM sub_kriteria WHERE id = '$id'";
if ($conn->query($sql) === TRUE) {
    echo "<script>
            window.location.href='?page=subkriteria';
          </script>";
    exit();
}
$conn->close();
