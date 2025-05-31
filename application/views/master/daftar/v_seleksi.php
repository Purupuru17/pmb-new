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
                            <th>#</th>
                            <th>Sisa Waktu</th>
                            <th>Hasil</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($seleksi->result_array() as $items) {
                            switch ($items['status_jawab']) {
                                case '0': $st_jawab = '<span class="label label-warning arrowed-in-right arrowed">PROGRESS</span>';
                                    break; 
                                case '1': $st_jawab = '<span class="label label-success arrowed-in-right arrowed">SELESAI</span>';
                                    break;
                                default: $st_jawab = '<span class="label label-default arrowed-in-right arrowed">PENDING</span>';
                                    break;
                            }
                            $is_done = ($items['status_jawab'] == '1') ? true : false;
                            $sisa_quiz = range_date(date('Y-m-d H:i:s'), $items['mulai_jawab'], $items['selesai_jawab']);
                            $st_sesi = ($is_done || !$sisa_quiz['st']) ? format_date($items['mulai_jawab'],2).'<br><strong class="green">'.format_date($items['selesai_jawab'],2).'</strong>' :
                                '<strong>'.format_date($items['mulai_jawab'],3).' - '.format_date($items['selesai_jawab'],3).'</strong><br>
                                <span class="label label-danger bolder">'.$sisa_quiz['rs'].'</span>';
                            $json_skor = json_decode($items['skor_jawab'], true);
                            $skor = (int) element('nilai', $json_skor);
                            $jumlah_soal = (int) element('jumlah', $json_skor);
                            $nilai = ($items['is_quiz'] == 'PILIHAN-GANDA' && $jumlah_soal > 0) ? round($skor/$jumlah_soal*100) : $skor;
                        ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= $st_sesi ?></td>
                            <td>
                                <?= '<b>'.$skor.'</b> / '.$jumlah_soal ?><br><b>Nilai</b> : 
                                <span class="bigger-150">[<strong class="blue"><?= $nilai ?></strong>]</span>
                            </td>
                            <td><?= $st_jawab.st_aktif($items['valid_jawab']) ?></td>
                            <td><small><?= ctk($items['note_jawab']) ?></small></td>
                        </tr>
                        <?php $no++; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.col -->
