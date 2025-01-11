<?php
$this->load->view('sistem/v_breadcrumb');

$exp = explode('|', $edit['opsi_prodi']);
$opsi1 = element(0, $exp, ''); 
$opsi2 = element(1, $exp, ''); 
$opsi3 = element(2, $exp, ''); 
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

            <form id="validation-form" action="<?= site_url($action); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <input value="<?= ctk($edit['angkatan']) ?>" type="hidden" name="tahun" id="tahun" />
                <div class="form-group">
                    <label class="blue control-label col-xs-12 col-sm-4 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="row">
                            <div class="label label-lg label-info arrowed-in arrowed-right">
                                <b>Pilihan Program Studi</b>
                            </div>
                        </div>
                        <div>
                            <ul class="list-unstyled spaced">
                                <li class="bolder bigger-130 green">
                                    <i class="ace-icon fa fa-caret-right blue"></i>1. <?= $opsi1 ?>
                                </li>

                                <li class="bolder bigger-110 orange">
                                    <i class="ace-icon fa fa-caret-right blue"></i>2. <?= $opsi2 ?>
                                </li>

                                <li class="bolder orange">
                                    <i class="ace-icon fa fa-caret-right blue"></i>3. <?= $opsi3 ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Program Studi :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <select class="select2 width-100" name="prodi" id="prodi" data-placeholder="-------> Pilih Program Studi <-------">
                                <option value=""> </option>
                                <?php
                                foreach ($prodi['data'] as $val) {
                                    $selected = ($edit['prodi_id'] == $val['id_prodi']) ? 'selected' : '';
                                    $view = $val['nama_prodi'].' - '.$val['kode_prodi'];
                                    echo '<option value="'.encode($val['id_prodi']).'" '.$selected.'>' . $view . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">NIM :</label>
                    <div class="col-xs-12 col-sm-5">
                        <div class="clearfix">
                            <input value="<?= ctk($edit['nim']) ?>" type="text" name="nim" id="nim" class="bolder blue bigger-130 col-xs-12  col-sm-6" placeholder="Nomor Induk Mahasiswa" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-2">
                        <button class="btn btn-success btn-white btn-round" name="btn-nim" id="btn-nim" type="button">
                            <i class="ace-icon fa fa-calculator"></i>
                            Generate NIM
                        </button>
                    </div>
                </div>

                <div class="hr hr2 hr-double"></div>
                <div class="space-6"></div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Kode Registrasi :</label>
                    <div class="col-xs-12 col-sm-5">
                        <div class="clearfix bolder bigger-130" style="margin-top: 5px">
                            #<?= $edit['kode_reg'] ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Nama Lengkap :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix bolder bigger-110" style="margin-top: 7px">
                            <?= $edit['nama_mhs'] ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">NIK :</label>
                    <div class="col-xs-12 col-sm-5">
                        <div class="clearfix bigger-110" style="margin-top: 7px">
                            <?= $edit['nik'] ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Status :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix bigger-130">
                            <?= st_mhs($edit['status_mhs']) ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Terakhir diubah :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="well">
                            <span class="bigger-110 blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $edit['log_mhs'] ?></span><br/>
                            <span class="bigger-110 orange"><i class="ace-icon fa fa-clock-o"></i> &nbsp;&nbsp;<?= format_date($edit['update_mhs'], 0) ?></span>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                    <div class="col-md-offset-4 col-md-4">
                        <button class="btn" type="reset">
                            <i class="ace-icon fa fa-undo bigger-110"></i>
                            Batal
                        </button>
                        &nbsp; &nbsp; &nbsp;
                        <button class="btn btn-success" name="simpan" id="simpan" type="submit">
                            <i class="ace-icon fa fa-check"></i>
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
    "backend/assets/js/select2.js"
));
?>

<script type="text/javascript">
    var module = "<?= site_url($module) ?>";    
    $(document).ready(function () {
        $(".select2").select2({allowClear: true})
            .on('change', function () {
                $(this).closest('form').validate().element($(this));
            });
        $(".select2-chosen").addClass("center");
    });
</script>
<script type="text/javascript">
    $("#btn-nim").click(function () {
        generate_nim();
    });
    function generate_nim() {
        var id = $("select#prodi").val();
        if (id === '') {
            $("select#prodi").select2('open');
            myNotif('Peringatan', 'Pilih Program Studi', 2);
            return;
        } 
        $.ajax({
            url: module + "/ajax/type/action/source/nim",
            type: "POST",
            dataType: "json",
            data: {
                id : id,
                tahun : $("#tahun").val()
            },
            success: function(rs) {
                if (rs.status) {
                    myNotif('Informasi', rs.msg, 1);
                } else {
                    myNotif('Peringatan', rs.msg, 2);
                }
                $("input#nim").val(rs.nim);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                myNotif('Error', 'Kesalahan Jaringan', 3);
            }
        });
    }
    $("#validation-form").validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            prodi: {
                required: true
            },
            nim: {
                required: true,
                digits: true,
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
