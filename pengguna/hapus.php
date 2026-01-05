<?php
$id = $_GET['id'];

$sql = "DELETE FROM pengguna WHERE id = '$id'";
if ($conn->query($sql) === TRUE) {
    echo "<script>
            window.location.href='?page=pengguna';
          </script>";
    exit();
}
$conn->close();
