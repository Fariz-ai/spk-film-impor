<div class="card">
    <div class="card-header bg-primary text-white border-dark"><strong>Data Pengguna</strong></div>
    <div class="card-body">
        <a href="?page=pengguna&action=tambah" class="btn btn-primary mb-2"><i class="fas fa-plus mr-2"></i>Tambah</a>
        <!-- Cek apakah ada data -->
        <?php
        $sql_count = "SELECT COUNT(*) AS total FROM pengguna";
        $result_count = $conn->query($sql_count);
        $row_count = $result_count->fetch_assoc();
        $ada_data = $row_count['total'] > 0;
        ?>
        <a href="pengguna/cetak.php"
            class="btn btn-<?php echo $ada_data ? 'success' : 'secondary'; ?> mb-2 ml-1 <?php echo $ada_data ? '' : 'disabled'; ?>"
            <?php echo $ada_data ? '' : 'onclick="return false;"'; ?>
            target="_blank">
            <i class="fas fa-print mr-2"></i>Cetak
        </a>
        <table class="table table-bordered" id="table">
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th style="width: 15%;">Nama Lengkap</th>
                    <th style="width: 20%;">Email</th>
                    <th style="width: 10%;">Role</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $sql = "SELECT id, nama_lengkap, email, role FROM pengguna ORDER BY 
                        CASE 
                            WHEN role = 'Admin' THEN 0
                            ELSE 1
                        END,
                        dibuat_pada DESC";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td align="center"><?php echo $no++; ?></td>
                        <td align="center"><?php echo $row["nama_lengkap"]; ?></td>
                        <td align="center"><?php echo $row["email"]; ?></td>
                        <td align="center"><?php echo $row["role"]; ?></td>
                        <td align="center">
                            <a class="btn btn-warning" href="?page=pengguna&action=edit&id=<?php echo $row['id']; ?>">
                                Edit
                            </a>
                            <a onclick="return confirm('Apakah Anda yakin ingin menghapus data pengguna ini?')" class="btn btn-danger" href="?page=pengguna&action=hapus&id=<?php echo $row['id']; ?>">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>