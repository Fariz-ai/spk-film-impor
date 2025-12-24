<?php
$id = $_GET['id'];

$sql = "DELETE FROM alternatif WHERE id = '$id'";
if ($conn->query($sql) === TRUE) {
    echo "<script>
            window.location.href='?page=alternatif';
          </script>";
    exit();
}
$conn->close();
