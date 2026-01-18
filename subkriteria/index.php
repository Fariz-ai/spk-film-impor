<div class="card shadow-sm">
    <div class="card-header bg-primary text-white border-dark">
        <strong>Data Sub-Kriteria</strong>
    </div>

    <div class="card-body">

        <!-- Tombol Aksi -->
        <div class="d-flex flex-wrap gap-2 mb-3">

            <a href="?page=subkriteria&action=tambah" class="btn btn-primary btn-sm mr-1">
                <i class="fas fa-plus mr-1"></i> Tambah
            </a>

            <?php
            // Cek apakah ada data sub-kriteria
            $sql_count = "SELECT COUNT(*) as total FROM sub_kriteria";
            $result_count = $conn->query($sql_count);
            $row_count = $result_count->fetch_assoc();
            $ada_data = $row_count['total'] > 0;
            ?>

            <a href="subkriteria/cetak.php"
                target="_blank"
                class="btn btn-<?= $ada_data ? 'success' : 'secondary' ?> btn-sm <?= $ada_data ? '' : 'disabled' ?>"
                <?= $ada_data ? '' : 'onclick="return false;"' ?>>
                <i class="fas fa-print mr-1"></i> Cetak
            </a>

        </div>

        <!-- Tabel -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" id="table">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Kriteria</th>
                        <th>Nilai</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $sql = "SELECT sub_kriteria.*, kriteria.nama_kriteria, kriteria.kode_kriteria
                            FROM sub_kriteria
                            INNER JOIN kriteria ON sub_kriteria.kriteria_id = kriteria.id
                            ORDER BY kriteria.kode_kriteria ASC, sub_kriteria.nilai DESC";
                    $result = $conn->query($sql);

                    while ($row = $result->fetch_assoc()):
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-nowrap"><?= $row['nama_kriteria'] ?></td>
                            <td class="text-center"><?= $row['nilai'] ?></td>
                            <td><?= $row['keterangan'] ?></td>
                            <td class="text-center">
                                <div class="d-inline-flex justify-content-center">
                                    <a href="?page=subkriteria&action=edit&id=<?= $row['id'] ?>"
                                        class="btn btn-warning btn-sm mr-1">
                                        Edit
                                    </a>
                                    <a href="?page=subkriteria&action=hapus&id=<?= $row['id'] ?>"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data sub-kriteria ini?')"
                                        class="btn btn-danger btn-sm">
                                        Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile;
                    $conn->close(); ?>
                </tbody>
            </table>
        </div>

    </div>
</div>