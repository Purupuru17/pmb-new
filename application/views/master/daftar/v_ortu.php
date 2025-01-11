<div class="col-xs-12 col-sm-12">
    <div class="widget-box transparent">
        <div class="widget-header">
            <h5 class="widget-title">
                <i class="ace-icon fa fa-list"></i>
                List Data
            </h5>
            <div class="widget-toolbar">
                <a href="#" data-action="collapse" class="orange2">
                    <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                </a>
            </div>
        </div>
        <div class="widget-body">
            <div class="widget-main padding-2 table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Keterangan</th>
                            <th>Nama</th>
                            <th>Tanggal Lahir</th>
                            <th>Pendidikan</th>
                            <th>Pekerjaan</th>
                            <th>Penghasilan</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($ortu['data'] as $row) {
                            ?>
                            <tr>
                                <td><?= ctk($row['jenis_ortu']); ?></td>
                                <td><strong><?= ctk($row['nama_ortu']); ?></strong><hr class="margin-5">
                                    <?= ctk($row['nik_ortu']); ?>
                                </td>
                                <td><?= format_date($row['lahir_ortu'], 1); ?></td>
                                <td><?= ctk($row['didik_ortu']); ?></td>
                                <td><?= ctk($row['kerja_ortu']); ?></td>
                                <td><?= ctk($row['hasil_ortu']); ?></td>
                                <td><?= ctk($row['telepon_ortu']); ?></td>
                                <td><?= ctk($row['alamat_ortu']); ?></td>
                            </tr>
                            <?php
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.col -->