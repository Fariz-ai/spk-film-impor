<div class="card shadow-sm">
    <div class="card-header bg-primary text-white border-dark">
        <strong>Data Alternatif</strong>
    </div>

    <div class="card-body">

        <!-- Tombol Aksi -->
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="?page=alternatif&action=tambah" class="btn btn-primary btn-sm mr-1">
                <i class="fas fa-plus mr-1"></i> Tambah
            </a>

            <?php
            $sql_count = "SELECT COUNT(*) as total FROM alternatif";
            $result_count = $conn->query($sql_count);
            $row_count = $result_count->fetch_assoc();
            $ada_data = $row_count['total'] > 0;
            ?>

            <a href="alternatif/cetak.php"
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
                        <th>Kode Alternatif</th>
                        <th>Judul Film</th>
                        <th>Periode Rilis</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $sql = "SELECT * FROM alternatif ORDER BY dibuat_pada DESC";
                    $result = $conn->query($sql);

                    $bulan = [
                        1 => 'Januari',
                        'Februari',
                        'Maret',
                        'April',
                        'Mei',
                        'Juni',
                        'Juli',
                        'Agustus',
                        'September',
                        'Oktober',
                        'November',
                        'Desember'
                    ];

                    while ($row = $result->fetch_assoc()) :
                        $tanggal = date('d', strtotime($row["periode_rilis"]));
                        $bulanAngka = date('n', strtotime($row["periode_rilis"]));
                        $tahun = date('Y', strtotime($row["periode_rilis"]));
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-center text-nowrap"><?= $row["kode_alternatif"] ?></td>
                            <td><?= $row["judul_film"] ?></td>
                            <td class="text-center text-nowrap">
                                <?= "$tanggal {$bulan[$bulanAngka]} $tahun" ?>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex justify-content-center">
                                    <a href="?page=alternatif&action=edit&id=<?= $row['id'] ?>"
                                        class="btn btn-warning btn-sm mr-1">
                                        Edit
                                    </a>
                                    <a href="?page=alternatif&action=hapus&id=<?= $row['id'] ?>"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data alternatif ini?')"
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