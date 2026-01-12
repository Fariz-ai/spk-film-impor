<div class="card">
    <div class="card-header bg-primary text-white border-dark"><strong>Data Kriteria</strong></div>
    <div class="card-body">
        <?php
        $sqlTotal = "SELECT SUM(bobot) AS total_bobot FROM kriteria";
        $resultTotal = $conn->query($sqlTotal);
        $rowTotal = $resultTotal->fetch_assoc();
        $totalBobot = floatval($rowTotal['total_bobot']);
        $sisaBobot = 1 - $totalBobot;

        if ($sisaBobot < 0) {
            $sisaBobot = 0;
        }
        ?>
        <div class="alert alert-info shadow-sm">
            <strong>Total Bobot:</strong> <?= number_format($totalBobot, 2); ?>
            &nbsp;|&nbsp;
            <strong>Sisa Bobot:</strong> <?= number_format($sisaBobot, 2); ?>
        </div>
        <?php if ($sisaBobot > 0): ?>
            <a href="?page=kriteria&action=tambah" class="btn btn-primary mb-2">
                <i class="fas fa-plus mr-2"></i>Tambah
            </a>
        <?php else: ?>
            <button class="btn btn-secondary mb-2" disabled>
                <i class="fas fa-lock mr-2"></i>Bobot Penuh
            </button>
        <?php endif; ?>

        <?php
        // Cek apakah ada data kriteria
        $sql_count = "SELECT COUNT(*) as total FROM kriteria";
        $result_count = $conn->query($sql_count);
        $row_count = $result_count->fetch_assoc();
        $ada_data = $row_count['total'] > 0;
        ?>
        <a href="kriteria/cetak.php" class="btn btn-<?php echo $ada_data ? 'success' : 'secondary'; ?> mb-2 <?php echo $ada_data ? '' : 'disabled'; ?>" <?php echo $ada_data ? '' : 'onclick="return false;"'; ?> target="_blank">
            <i class="fas fa-print mr-2"></i>Cetak
        </a>

        <table class="table table-bordered" id="table">
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th style="width: 15%;">Kode Kriteria</th>
                    <th style="width: 20%;">Nama Kriteria</th>
                    <th style="width: 10%;">Bobot</th>
                    <th style="width: 15%;">Jenis</th>
                    <th style="width: 15%;">Aksi</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $sql = "SELECT * FROM kriteria ORDER BY dibuat_pada DESC";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td align="center"><?php echo $no++; ?></td>
                        <td align="center"><?php echo $row["kode_kriteria"]; ?></td>
                        <td align="center"><?php echo $row["nama_kriteria"]; ?></td>
                        <td align="center"><?php echo $row["bobot"]; ?></td>
                        <td align="center"><?php echo $row["jenis"]; ?></td>
                        <td align="center">
                            <a class="btn btn-warning" href="?page=kriteria&action=edit&id=<?php echo $row['id']; ?>">
                                Edit
                            </a>
                            <a onclick="return confirm('Apakah Anda yakin ingin menghapus data kriteria ini?')" class="btn btn-danger" href="?page=kriteria&action=hapus&id=<?php echo $row['id']; ?>">
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