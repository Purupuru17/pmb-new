<?php
$this->load->view('sistem/v_breadcrumb');
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            <?= $title[0] ?>
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                <?= $title[1] ?>
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <?= $this->session->flashdata('notif'); ?>
        </div>
        <div class="col-xs-12">
            <h3 class="lighter center block blue"><?= $title[1] ?></h3>
            <form id="ortu-form" action="<?= site_url($action); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="social-or-login center">
                    <span class="bigger-110 bolder">Data Kepala Keluarga (Ayah/Suami)</span>
                </div>
                <div class="space-6"></div>
                
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">NIK :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input type="text" value="<?= $edit['nik_ayah'] ?>" name="nik" id="nik" placeholder="NIK" class="col-xs-12  col-sm-6" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Nama :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input type="text" value="<?= $edit['nama_ayah'] ?>" name="nama" id="nama" placeholder="Nama Lengkap" class="col-xs-12  col-sm-6" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Tanggal Lahir :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input type="text" value="<?= $edit['lahir_ayah'] ?>" name="lahir" id="lahir" placeholder="Tanggal Lahir" data-date-format="yyyy-mm-dd" class="col-xs-12  col-sm-6 date-picker" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-6 col-sm-offset-4">
                        <span class="middle blue bolder" id="txt-tgl"><?= format_date($edit['lahir_ayah'],1) ?></span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Pendidikan :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="didik" id="didik" data-placeholder="-----> Pilih Pendidikan <-----">
                                <option value=""> </option>
                                <?php
                                foreach ($didik as $val) {
                                    $selected = ($edit['didik_ayah'] == $val) ? 'selected' : '';
                                    echo '<option value="'.$val.'"  '.$selected.'>'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Pekerjaan :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="kerja" id="kerja" data-placeholder="-----> Pilih Pekerjaan <-----">
                                <option value=""> </option>
                                <?php
                                foreach ($kerja as $val) {
                                    $selected = ($edit['kerja_ayah'] == $val) ? 'selected' : '';
                                    echo '<option value="'.$val.'"  '.$selected.'>'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Penghasilan :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="hasil" id="hasil" data-placeholder="-----> Pilih Penghasilan <-----">
                                <option value=""> </option>
                                <?php
                                foreach ($golongan as $val) {
                                    $selected = ($edit['hasil_ayah'] == $val) ? 'selected' : '';
                                    echo '<option value="'.$val.'"  '.$selected.'>'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="space-6"></div>
                <div class="social-or-login center">
                    <span class="bigger-110 bolder">Data Ibu/Istri</span>
                </div>
                <div class="space-6"></div>
                
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">NIK :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input type="text" value="<?= $edit['nik_ibu'] ?>" name="nikB" id="nikB" placeholder="NIK" class="col-xs-12  col-sm-6" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Nama :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input type="text" value="<?= $edit['nama_ibu'] ?>" name="namaB" id="namaB" placeholder="Nama Lengkap" class="col-xs-12  col-sm-6" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Tanggal Lahir :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input type="text" value="<?= $edit['lahir_ibu'] ?>" name="lahirB" id="lahirB" placeholder="Tanggal Lahir" data-date-format="yyyy-mm-dd" class="col-xs-12  col-sm-6 date-picker" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-6 col-sm-offset-4">
                        <span class="middle blue bolder" id="txt-tglB"><?= format_date($edit['lahir_ibu'],1) ?></span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Pekerjaan :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="kerjaB" id="kerjaB" data-placeholder="-----> Pilih Pekerjaan <-----">
                                <option value=""> </option>
                                <?php
                                foreach ($kerja as $val) {
                                    $selected = ($edit['kerja_ibu'] == $val) ? 'selected' : '';
                                    echo '<option value="'.$val.'"  '.$selected.'>'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="space-6"></div>
                <div class="social-or-login center">
                    <span class="bigger-110 bolder">Data Lainnya</span>
                </div>
                <div class="space-6"></div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Telepon :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input value="<?= $edit['telepon_ortu'] ?>" type="text" name="telepon" id="telepon" placeholder="Nomor Telepon Aktif" class="col-xs-12  col-sm-6"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Alamat :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <textarea rows="2" cols="1" name="alamat" id="alamat" placeholder="Alamat Rumah" class="col-xs-12 col-sm-6"><?= $edit['alamat_ortu'] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Terakhir diubah :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="well">
                            <span class="bigger-110 blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $edit['log_ortu'] ?></span><br/>
                            <span class="bigger-110 orange"><i class="ace-icon fa fa-clock-o"></i> &nbsp;&nbsp;<?= format_date($edit['update_ortu'],0) ?></span>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                    <div class="col-md-offset-5 col-md-4">
                        <button class="btn" type="reset">
                            <i class="ace-icon fa fa-undo bigger-110"></i>
                            Batal
                        </button>
                        &nbsp; &nbsp; &nbsp;
                        <button class="btn btn-success" name="simpan" id="simpan" type="submit">
                            <i class="ace-icon fa fa-save"></i>
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div><!-- /.row -->
</div><!-- /.page-content -->

<?php 
    load_js(array(
        "backend/assets/js/jquery.validate.js",
        "backend/assets/js/select2.js",
        "backend/assets/js/date-time/bootstrap-datepicker.js"
    )); 
?>
<script type="text/javascript">
    const bulan = new Array(null,"Januari", "Februari", "Maret", "April", "Mei", "Juni", 
        "Juli", "Agustus", "September", "Oktober", "November", "Desember");
    $(document).ready(function() {
        $(".select2").select2({allowClear: true})
            .on('change', function() {
            $(this).closest('form').validate().element($(this));
        });
        $(".select2-chosen").addClass("center");
        $(".date-picker").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            clearBtn: true,
            endDate: "-15y",
            startDate: "-80y"
        }).next().on(ace.click_event, function(){
            $(this).prev().focus();
        });
    });
    $("#lahir").change(function () {
        let tgl = this.value.split("-");
        $("#txt-tgl").html(tgl[2]+' '+bulan[parseInt(tgl[1])]+' '+tgl[0]);
    });
    $("#lahirB").change(function () {
        let tgl = this.value.split("-");
        $("#txt-tglB").html(tgl[2]+' '+bulan[parseInt(tgl[1])]+' '+tgl[0]);
    });
</script>
<script type="text/javascript">
    $("#ortu-form").validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            nik: {
                required: true,
                digits: true,
                minlength: 16,
                maxlength: 16
            },
            nikB: {
                //required: true,
                digits: true,
                minlength: 16,
                maxlength: 16
            },
            nama: {
                required: true,
                minlength: 3
            },
            namaB: {
                //required: true,
                minlength: 3
            },
            lahir: {
                required: true,
                date: true
            },
            lahirB: {
                //required: true,
                date: true
            },
            didik: {
                required: true
            },
            kerja: {
                required: true
            },
            kerjaB: {
                //required: true
            },
            hasil: {
                required: true
            },
            telepon: {
                required: true,
                digits: true,
                minlength: 11,
                maxlength: 12
            },
            alamat: {
                required: true,
                minlength: 30
            }
        },
        highlight: function (e) {
            $(e).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function (e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
            $(e).remove();
        },
        errorPlacement: function (error, element) {
            if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                var controls = element.closest('div[class*="col-"]');
                if (controls.find(':checkbox,:radio').length > 1)
                    controls.append(error);
                else
                    error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
            } else if (element.is('.select2')) {
                error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
            } else if (element.is('.chosen-select')) {
                error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
            } else
                error.insertAfter(element.parent());
        },
        invalidHandler: function (form) {
        }
    });
</script>
