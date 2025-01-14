<?php $this->load->view('sistem/v_breadcrumb'); ?>
<style>
    .profile-info-name{
        width: 160px;
    }
    th, td{
        text-align: center;
    }
</style>
<div class="page-content">
    <div class="page-header">
        <h1>
            <?= $title[1] ?>
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                <?= $title[0] ?>
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <?= $this->session->flashdata('notif'); ?>
        </div>
        <div class="col-xs-12">
            <div class="widget-box transparent">
                <div class="widget-header">
                    <h4 class="widget-title lighter bolder"><?= ctk($detail['invoice']) ?></h4>
                    <small>[<?= $detail['nama_mhs']?>]</small>
                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse" class="orange2">
                            <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                        </a>
                    </div>
                    <div class="widget-toolbar no-border">
                        <div class="btn-group btn-overlap">
                            <a target="_blank" href="<?= site_url($module.'/cetak/'. encode($detail['id_payment'])) ?>" class="btn btn-white btn-default btn-sm btn-bold">
                                <i class="fa fa-print bigger-120"></i> Cetak Invoice
                            </a>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-6 no-padding-left no-padding-right">
                        <div id="user-profile-1" class="user-profile row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="profile-user-info profile-user-info-striped">
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Bank</div>
                                        <div class="profile-info-value">
                                            <span class="bolder"><?= ctk($detail['bank_payment']) ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Virtual Account</div>
                                        <div class="profile-info-value">
                                            <span class="bolder blue"><?= ctk($detail['va_payment']) ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Status VA</div>
                                        <div class="profile-info-value">
                                            <span class=""><?= st_aktif($detail['status_inquiry']) ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Jatuh Tempo</div>
                                        <div class="profile-info-value">
                                            <span class="">
                                                <?= empty($detail['expired_payment']) ? '' : '<i class="fa fa-calendar-times-o red"></i> '
                                                .format_date($detail['expired_payment'],2); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name bolder">Total</div>
                                        <div class="profile-info-value">
                                            <span class="bolder red bigger-110"><?= rupiah($detail['total_payment']) ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name bolder"> Pembayaran</div>
                                        <div class="profile-info-value">
                                            <span class="bolder green"><?= st_aktif($detail['status_payment'],null,'pay') ?><br/> 
                                            <?= empty($detail['paid_payment']) ? '' : '<i class="fa fa-calendar-check-o green"></i> '
                                                .format_date($detail['paid_payment'],2); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row hide">
                                        <div class="profile-info-name">Saldo</div>
                                        <div class="profile-info-value">
                                            <span class="bolder"><?= rupiah($detail['saldo_payment']) ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Keterangan</div>
                                        <div class="profile-info-value">
                                            <span class=""><?= ctk($detail['note_payment']) ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-6"></div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="profile-user-info profile-user-info-striped">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Kode Registrasi </div>
                                        <div class="profile-info-value">
                                            <span class="bolder blue"><?= $detail['kode_reg'] ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> NIM </div>
                                        <div class="profile-info-value">
                                            <span class="bolder"><?= $detail['nim'] ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Nama </div>
                                        <div class="profile-info-value">
                                            <span class="bolder"><?= $detail['nama_mhs'] ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Angkatan </div>
                                        <div class="profile-info-value">
                                            <span><?= $detail['angkatan'] ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row hide">
                                        <div class="profile-info-name"> Status </div>
                                        <div class="profile-info-value">
                                            <?= st_mhs($detail['status_mhs']) ?>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Log</div>
                                        <div class="profile-info-value">
                                            <span>
                                                <span class="blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= ctk($detail['log_payment']) ?></span><br/>
                                                <span class="green"><i class="ace-icon fa fa-pencil"></i> &nbsp;&nbsp;<?= format_date($detail['buat_payment'],0) ?></span><br/>
                                                <span class="orange"><i class="ace-icon fa fa-clock-o"></i> &nbsp;&nbsp;<?= format_date($detail['update_payment'],0) ?></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
<div id="modal-view" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body row">
                <div class="col-xs-12 pricing-box" style="height: 650px; overflow: visible; overflow-y: scroll;">
                    <div class="widget-box widget-color-green invoice">
                        <div class="widget-header">
                            <h4 class="widget-title">Tagihan #<?= ctk($detail['invoice']) ?></h4>
                        </div>
                        <div class="widget-body table-responsive">
                            <div class="widget-main">
                                <div class="profile-user-info profile-user-info-striped">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Mahasiswa </div>
                                        <div class="profile-info-value">
                                            <?= $detail['kode_reg'] ?><br/>
                                            <span class="bolder"><?= $detail['nama_mhs'] ?></span><br/>
                                            <?= $detail['nim'] ?>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Invoice</div>
                                        <div class="profile-info-value">
                                            <span class="bolder"><?= ctk($detail['invoice']) ?></span><br/>
                                            <i class="fa fa-calendar-plus-o green"></i>
                                            <small class="grey"> <?= format_date($detail['buat_payment'],2)?> </small>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Bank Tujuan</div>
                                        <div class="profile-info-value">
                                            <span class="bolder"><?= ctk($detail['bank_payment']) ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Virtual Account</div>
                                        <div class="profile-info-value">
                                            <span style="letter-spacing: 2px;" class="bolder red bigger-150">
                                                <?= ctk($detail['va_payment']) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name bolder">Total Tagihan</div>
                                        <div class="profile-info-value">
                                            <span class="bolder bigger-120"><?= rupiah($detail['total_payment']) ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Keterangan</div>
                                        <div class="profile-info-value">
                                            <small><?= ctk($detail['note_payment']) ?></small>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name red">***</div>
                                        <div class="profile-info-value">
                                            <ul class="list-unstyled">
                                                <li>
                                                    <i class="ace-icon fa fa-check green"></i>
                                                    <small>Total Tagihan tidak termasuk Biaya Admin</small>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-check green"></i>
                                                    <small>Pembayaran harus sesuai dengan <b>Bank Tujuan</b></small>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-check green"></i>
                                                    <small>Pembayaran harus sesuai dengan <b>Total Tagihan</b></small>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-check green"></i>
                                                    <small>Pembayaran hanya dilayani pada saat jam kerja <b>(08.00 WIT s/d 17.00 WIT)</b></small>
                                                </li>
                                                <li>
                                                    <i class="ace-icon fa fa-check green"></i>
                                                    <small>Segera selesaikan pembayaran dalam 3x24 jam</small>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="price bolder bigger-250 red">
                                    <?= rupiah($detail['total_payment']) ?>
                                </div>
                            </div>
                            <div>
                                <a target="_blank" href="<?= site_url($module.'/cetak/'.encode($detail['id_payment'])) ?>" id="btn-print" class="btn btn-block btn-success btn-sm">
                                    <i class="ace-icon fa fa-print bigger-110"></i>
                                    <span>Print</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<?php
load_js(array(
    'backend/assets/js/bootbox.min.js'
));
?> 
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    const is_modal = "<?= $is_modal ?>";
    
    $(document).ready(function () {
        $('[data-rel="tooltip"]').tooltip({placement: 'top'});
        if(is_modal === '' || is_modal === null){
            $("#modal-view").modal({});
        }
    });
    $(document.body).on("click", "#delete-btn", function(event) {
        var id = $(this).attr("itemid");
        var name = $(this).attr("itemprop");
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan menghapus data <br/><b>" + name + "</b> ? </p>";
        bootbox.confirm({
            title: title, message: msg, 
            buttons: {
                cancel: { label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm" },
                confirm: { label: "<i class='ace-icon fa fa-trash-o bigger-110'></i> Hapus", className: "btn btn-sm btn-danger" }
            },
            callback: function(result) {
                if (result === true) {
                    window.location.replace(module + '_do/delete/' + id);
                }
            }
        });
    });
    $(document.body).on("click", "#btn-valid", function(event) {
        var id = $(this).attr("itemid");
        var name = $(this).attr("itemprop");
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan memvalidasi pembayaran <br/><b>" + name + "</b> ? </p>";
        bootbox.confirm({
            title: title, message: msg, 
            buttons: {
                cancel: { label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm" },
                confirm: { label: "<i class='ace-icon fa fa-check bigger-110'></i> Ya, Valid", className: "btn btn-sm btn-success" }
            },
            callback: function(result) {
                if (result === true) {
                    window.location.replace(module + '_do/add/' + id);
                }
            }
        });
    });
    $(document.body).on("click", "#btn-print", function(event) {
        $("#modal-view").modal("hide");
    });
</script>
