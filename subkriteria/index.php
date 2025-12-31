<div class="card">
    <div class="card-header bg-primary text-white border-dark"><strong>Data Sub-Kriteria</strong></div>
    <div class="card-body">
        <a href="?page=subkriteria&action=tambah" class="btn btn-primary mb-2"><i class="fas fa-plus mr-2"></i>Tambah</a>
        <table class="table table-bordered" id="table">
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th style="width: 15%;">Nama Kriteria</th>
                    <th style="width: 10%;">Nilai</th>
                    <th style="width: 20%;">Keterangan</th>
                    <th style="width: 15%;">Aksi</th>
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
                while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td align="center"><?php echo $no++; ?></td>
                        <td align="center"><?php echo $row["nama_kriteria"]; ?></td>
                        <td align="center"><?php echo $row["nilai"]; ?></td>
                        <td align="center"><?php echo $row["keterangan"]; ?></td>
                        <td align="center">
                            <a class="btn btn-warning" href="?page=subkriteria&action=edit&id=<?php echo $row['id']; ?>">
                                Edit
                            </a>
                            <a onclick="return confirm('Apakah Anda yakin ingin menghapus data sub-kriteria ini?')" class="btn btn-danger" href="?page=subkriteria&action=hapus&id=<?php echo $row['id']; ?>">
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