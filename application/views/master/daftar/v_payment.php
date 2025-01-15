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
                            <th>Invoice</th>
                            <th>Virtual Account</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th width="15%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $piutang = 0;
                        $terbayar = 0;
                        foreach ($payment['data'] as $items) {
                            $is_total = ($items['status_payment'] == '1') ? 'grey' : 'red';
                            $is_paid = empty($items['paid_payment']) ? '' : '<br/><i class="fa fa-calendar-check-o green"></i> 
                                <small>'.format_date($items['paid_payment'],2).'</small>';
                            $is_up = empty($items['update_payment']) ? '' : '<br/><i class="fa fa-calendar grey"></i> 
                                <small>'.format_date($items['update_payment'],2).'</small>';
                            
                            $terbayar += ($items['status_payment'] == '1') ? $items['total_payment'] : 0;
                            $piutang += ($items['status_payment'] == '0') ? $items['total_payment'] : 0;
                            ?>
                            <tr>
                                <td><?= $no; ?></td>
                                <td>
                                    <strong><?= ctk($items['invoice'])?></strong>
                                    <br/><i class="fa fa-calendar-plus-o"></i> 
                                    <small class="grey"><?= format_date($items['buat_payment'],2)?></small>
                                </td>
                                <td>
                                    <strong class="blue bigger-110"> 
                                        <?= ctk($items['va_payment']).'</strong><br/><strong>'.ctk($items['bank_payment'])?> 
                                    </strong>
                                </td>
                                <td><?= '<strong class="'.$is_total.' bigger-110">'.rupiah($items['total_payment']).'</strong>'; ?>
                                </td>
                                <td>
                                    <?= st_aktif($items['status_payment'],null,'pay').$is_paid; ?>
                                </td>
                                <td><?= '<small>'.$items['note_payment'].'</small>'.$is_up; ?></td>
                            </tr>
                            <?php
                            $no++;
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Piutang</th>
                            <th class="red"><?= rupiah($piutang) ?></th>
                            <th colspan="2"></th>
                        </tr>
                        <tr>
                            <th colspan="4">Terbayar</th>
                            <th class="green"><?= rupiah($terbayar) ?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.col -->