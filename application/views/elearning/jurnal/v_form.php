<?php $this->load->view('sistem/v_breadcrumb'); ?>
<style>
    .select2-container{
        padding-left: 0px;
    }
    .select2-chosen{
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
            <h3 class="lighter center block blue"><?= $title[0] ?></h3>
            <form id="validation-form" action="<?= site_url($action); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Kelas Kuliah :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input value="<?= encode($edit['kelas_id']) ?>" <?= !empty($edit['id_jurnal']) ? 'readonly' : '' ?> type="hidden" name="kelas" id="kelas" class="width-100"/>
                        </div>
                    </div>
                    <span class="help-inline col-xs-6 col-md-offset-4">
                        <span class="middle blue bolder" id="txt-kelas"></span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Pertemuan Ke ? :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100 bolder" name="init" id="init" data-placeholder="---> Pilih Opsi <---">
                                <option value=""> </option>
                                <?php
                                $init = !empty($edit['id_jurnal']) ? intval($edit['init_jurnal']) : 20;
                                for ($val = 1; $val <= $init; $val++) {
                                    $selected = (intval($edit['init_jurnal']) == $val) ? 'selected' : '';
                                    echo '<option value="' .$val. '" '.$selected.'>Ke - ' . $val . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Tanggal Kuliah <small>(maks. H+5 hari)</small> :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input value="<?= $edit['tgl_jurnal'] ?>" type="text" name="tanggal" id="tanggal" placeholder="Tanggal Kuliah" class="date-picker col-xs-12  col-sm-6">
                        </div>
                    </div>
                    <span class="help-inline col-xs-6 col-md-offset-4">
                        <span class="middle blue bolder" id="txt-tgl"><?= format_date($edit['tgl_jurnal'],1) ?></span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Mode Kuliah :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="mode" id="mode" data-placeholder="---> Pilih Mode <---">
                                <option value=""> </option>
                                <?php
                                foreach ($mode_jurnal as $val) {
                                    $selected = ($edit['mode_jurnal'] == $val) ? 'selected' : '';
                                    echo '<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Ruangan Kuliah :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input value="<?= $edit['ruang_jurnal'] ?>" type="text" name="ruang" id="ruang" placeholder="ex: MM Lantai 1" class="col-xs-12  col-sm-6"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Waktu Kuliah :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input value="<?= $edit['waktu_jurnal'] ?>" type="text" name="waktu" id="waktu" placeholder="ex: 06:00 - 18:00" class="col-xs-12  col-sm-6"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Status :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <label class="control-label">
                                <input <?= ($edit['status_jurnal'] == '1') ? 'checked' : '' ; ?> name="status" value="1" type="radio" class="ace" />
                                <span class="lbl"> AKTIF </span>
                            </label>&nbsp;&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($edit['status_jurnal'] == '0') ? 'checked' : '' ; ?> name="status" value="0" type="radio" class="ace" />
                                <span class="lbl"> TIDAK AKTIF </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Catatan :</label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <textarea rows="4" name="note" id="note" placeholder="Catatan Jurnal" class="col-sm-6 col-xs-12"><?= $edit['note_jurnal'] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Terakhir diubah :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="well">
                            <span class="bigger-110 blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $edit['log_jurnal'] ?></span><br/>
                            <span class="bigger-110 orange"><i class="ace-icon fa fa-clock-o"></i> &nbsp;&nbsp;<?= format_date($edit['update_jurnal'],0) ?></span>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                    <div class="col-sm-offset-4 col-sm-5">
                        <button class="btn" type="reset">
                            <i class="ace-icon fa fa-undo bigger-110"></i>
                            Batal
                        </button>
                        &nbsp; &nbsp; &nbsp;
                        <button class="btn btn-success" name="simpan" type="submit">
                            <i class="ace-icon fa fa-check"></i>
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
<?php
load_js(array(
    'backend/assets/js/bootbox.min.js',
    'backend/assets/js/select2.js',
    'backend/assets/js/jquery.validate.js',
    'backend/assets/js/date-time/bootstrap-datepicker.js'
));
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    const startDate = "<?= $start_date ?>";
    
    $(document).ready(function () {
        $(".select2").select2({allowClear: true});
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd',
            clearBtn: true,
            endDate: "+6d",
            startDate: startDate
        }).next().on(ace.click_event, function () {
           $(this).prev().focus();
        });
        get_select();
    });
    $("#kelas").change(function () {
        let data = $("#kelas").select2('data');
        $("#txt-kelas").html(data.text);
    });
    $("#tanggal").change(function () {
        if(this.value === '' || this.value === null){
            $("#txt-tgl").html('');
            return;
        }
        const tgl = this.value.split("-");
        var bulan = new Array(null,"Januari", "Februari", "Maret", "April", "Mei", "Juni", 
        "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        
        $("#txt-tgl").html(tgl[2]+' '+bulan[parseInt(tgl[1])]+' '+tgl[0]);
    });
</script>
<script type="text/javascript">
    function get_select() {
        $("#kelas").select2({
            placeholder: "-------> Pilih Kelas Kuliah <-------",
            ajax: {
                url: module + "/ajax/type/list/source/kelas",
                type: "POST",
                dataType: 'json',
                delay: 250,
                data: function (key) {
                    return { key: key, dosen: $("#dosen").val() };
                },
                results: function (data) {
                    return { results: data };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax(module + "/ajax/type/list/source/kelas?id=" + id, {
                        dataType: "json"
                    }).done(function(data) { 
                        callback(data[0]);
                        $("#txt-kelas").html(data[0].text);
                    });
                }
            }
        });
    }
    $("#validation-form").validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            kelas: {
                required: true
            },
            init: {
                required: true
            },
            tanggal: {
                required: true,
                minlength: 10,
                date: true
            },
            mode: {
                required: true
            },
            ruang: {
                required: true
            },
            waktu: {
                required: true
            },
            status: {
                required: true
            },
            note: {
                //required: true,
                minlength: 10
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