<div class="card shadow-sm">
    <div class="card-header bg-primary text-white border-dark">
        <strong>Data Kriteria</strong>
    </div>

    <div class="card-body">

        <?php
        $sqlTotal = "SELECT SUM(bobot) AS total_bobot FROM kriteria";
        $resultTotal = $conn->query($sqlTotal);
        $rowTotal = $resultTotal->fetch_assoc();
        $totalBobot = floatval($rowTotal['total_bobot']);
        $sisaBobot = 1 - $totalBobot;
        if ($sisaBobot < 0) $sisaBobot = 0;
        ?>

        <!-- Info Bobot -->
        <div class="alert alert-info shadow-sm mb-3">
            <strong>Total Bobot:</strong> <?= number_format($totalBobot, 2); ?>
            &nbsp;|&nbsp;
            <strong>Sisa Bobot:</strong> <?= number_format($sisaBobot, 2); ?>
        </div>

        <!-- Tombol Aksi -->
        <div class="d-flex flex-wrap gap-2 mb-3">

            <?php if ($sisaBobot > 0): ?>
                <a href="?page=kriteria&action=tambah" class="btn btn-primary btn-sm mr-1">
                    <i class="fas fa-plus mr-1"></i> Tambah
                </a>
            <?php else: ?>
                <button class="btn btn-secondary btn-sm mr-1" disabled>
                    <i class="fas fa-lock mr-1"></i> Bobot Penuh
                </button>
            <?php endif; ?>

            <?php
            $sql_count = "SELECT COUNT(*) as total FROM kriteria";
            $result_count = $conn->query($sql_count);
            $row_count = $result_count->fetch_assoc();
            $ada_data = $row_count['total'] > 0;
            ?>

            <a href="kriteria/cetak.php"
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
                        <th>Kode Kriteria</th>
                        <th>Nama Kriteria</th>
                        <th>Bobot</th>
                        <th>Jenis</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $sql = "SELECT * FROM kriteria ORDER BY dibuat_pada DESC";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()):
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-center text-nowrap"><?= $row['kode_kriteria'] ?></td>
                            <td><?= $row['nama_kriteria'] ?></td>
                            <td class="text-center"><?= $row['bobot'] ?></td>
                            <td class="text-center"><?= $row['jenis'] ?></td>
                            <td class="text-center">
                                <div class="d-inline-flex justify-content-center">
                                    <a href="?page=kriteria&action=edit&id=<?= $row['id'] ?>"
                                        class="btn btn-warning btn-sm mr-1">
                                        Edit
                                    </a>
                                    <a href="?page=kriteria&action=hapus&id=<?= $row['id'] ?>"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data kriteria ini?')"
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