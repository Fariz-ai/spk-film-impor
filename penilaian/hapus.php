<?php
$id = $_GET['id'];

$sql = "DELETE FROM penilaian WHERE id = '$id'";
if ($conn->query($sql) === TRUE) {
    echo "<script>
            window.location.href='?page=penilaian';
          </script>";
    exit();
}
$conn->close();
