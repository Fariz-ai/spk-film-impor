<?php
$id = $_GET['id'];

$sql = "DELETE FROM kriteria WHERE id = '$id'";
if ($conn->query($sql) === TRUE) {
    echo "<script>
            window.location.href='?page=kriteria';
          </script>";
    exit();
}
$conn->close();
