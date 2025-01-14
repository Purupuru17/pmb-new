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
                            <th>Ayah/Suami</th>
                            <th>Pendidikan</th>
                            <th>Pekerjaan</th>
                            <th>Ibu/Istri</th>
                            <th>Pekerjaan</th>
                            <th>Telepon</th>
                            <th width="10%">Alamat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($ortu['data'] as $row) {
                            ?>
                            <tr>
                                <td><strong><?= ctk($row['nama_ayah']); ?></strong><br>
                                    <?= ctk($row['nik_ayah']); ?><br>
                                    <?= format_date($row['lahir_ayah'],1); ?>
                                </td>
                                <td><?= ctk($row['didik_ayah']); ?></td>
                                <td><?= ctk($row['kerja_ayah']); ?><br>
                                    <?= ctk($row['hasil_ayah']); ?>
                                </td>
                                <td><strong><?= ctk($row['nama_ibu']); ?></strong><br>
                                    <?= ctk($row['nik_ibu']); ?><br>
                                    <?= format_date($row['lahir_ibu'],1); ?>
                                </td>
                                <td><?= ctk($row['kerja_ibu']); ?></td>
                                <td><?= ctk($row['telepon_ortu']); ?></td>
                                <td><small><?= ctk($row['alamat_ortu']); ?></small></td>
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