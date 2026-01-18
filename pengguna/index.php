<div class="card shadow-sm">
    <div class="card-header bg-primary text-white border-dark">
        <strong>Data Pengguna</strong>
    </div>

    <div class="card-body">

        <!-- Tombol Aksi -->
        <div class="d-flex flex-wrap gap-2 mb-3">

            <a href="?page=pengguna&action=tambah" class="btn btn-primary btn-sm mr-1">
                <i class="fas fa-plus mr-1"></i> Tambah
            </a>

            <?php
            // Cek apakah ada data pengguna
            $sql_count = "SELECT COUNT(*) AS total FROM pengguna";
            $result_count = $conn->query($sql_count);
            $row_count = $result_count->fetch_assoc();
            $ada_data = $row_count['total'] > 0;
            ?>

            <a href="pengguna/cetak.php"
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
                        <th style="width:5%">No</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th style="width:10%">Role</th>
                        <th style="width:15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $sql = "SELECT id, nama_lengkap, email, role 
                            FROM pengguna 
                            ORDER BY 
                                CASE WHEN role = 'Admin' THEN 0 ELSE 1 END,
                                dibuat_pada DESC";
                    $result = $conn->query($sql);

                    while ($row = $result->fetch_assoc()):
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-nowrap"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                            <td class="text-nowrap"><?= htmlspecialchars($row['email']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['role']) ?></td>
                            <td class="text-center">
                                <div class="d-inline-flex justify-content-center">
                                    <a href="?page=pengguna&action=edit&id=<?= $row['id'] ?>"
                                        class="btn btn-warning btn-sm mr-1">
                                        Edit
                                    </a>
                                    <a href="?page=pengguna&action=hapus&id=<?= $row['id'] ?>"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data pengguna ini?')"
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