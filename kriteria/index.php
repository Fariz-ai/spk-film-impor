<div class="card">
    <div class="card-header bg-primary text-white border-dark"><strong>Data Kriteria</strong></div>
    <div class="card-body">
        <a href="?page=alternatif&action=tambah" class="btn btn-primary mb-2"><i class="fas fa-plus mr-2"></i>Tambah</a>
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
                $sql = "SELECT * FROM kriteria ORDER BY kode_kriteria ASC";
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