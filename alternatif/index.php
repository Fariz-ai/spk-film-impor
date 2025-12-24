<div class="card">
    <div class="card-header bg-primary text-white border-dark"><strong>Data Alternatif</strong></div>
    <div class="card-body">
        <a href="?page=alternatif&action=tambah" class="btn btn-primary mb-2"><i class="fas fa-plus mr-2"></i>Tambah</a>
        <table class="table table-bordered" id="table">
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th style="width: 15%;">Kode Alternatif</th>
                    <th style="width: 25%;">Judul Film</th>
                    <th style="width: 20%;">Perusahaan Produksi</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $sql = "SELECT * FROM alternatif ORDER BY kode_alternatif ASC";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td align="center"><?php echo $no++; ?></td>
                        <td align="center"><?php echo $row["kode_alternatif"]; ?></td>
                        <td align="center"><?php echo $row["judul_film"]; ?></td>
                        <td align="center"><?php echo $row["perusahaan_produksi"]; ?></td>
                        <td align="center">
                            <a class="btn btn-warning" href="?page=alternatif&action=edit&id=<?php echo $row['id']; ?>">
                                Edit
                            </a>
                            <a onclick="return confirm('Apakah Anda yakin ingin menghapus data alternatif ini?')" class="btn btn-danger" href="?page=alternatif&action=hapus&id=<?php echo $row['id']; ?>">
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