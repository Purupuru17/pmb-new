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
            <form id="validation-form" action="<?= site_url($action); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Bank :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="bank" id="bank" data-placeholder="-------> Pilih Bank <-------">
                                <option value=""> </option>
                                <?php
                                foreach ($bank['data'] as $val) {
                                    $selected = ($edit['bank_id'] == $val['id_bank']) ? 'selected' : '';
                                    echo '<option value="' . encode($val['id_bank']) . '"  ' . $selected . '>'.$val['nama_bank'].' ('.$val['jenis_bank'].')</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Materi :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input value="<?= $edit['materi_soal'] ?>" type="text" name="materi" id="materi" class="col-xs-12  col-sm-6" placeholder="Materi Soal" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Nomor Urut :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <input value="<?= $edit['order_soal'] ?>" type="number" name="order" id="order" class="col-xs-12  col-sm-6" placeholder="1 ~ 100" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Status :</label>
                    <div class="col-xs-12 col-sm-5">
                        <div class="clearfix">
                            <label class="control-label">
                                <input <?= ($edit['status_soal'] == '1') ? 'checked' : ''; ?> name="status" value="1" type="radio" class="ace" />
                                <span class="lbl"> AKTIF </span>
                            </label>&nbsp;&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($edit['status_soal'] == '0') ? 'checked' : ''; ?> name="status" value="0" type="radio" class="ace" />
                                <span class="lbl"> TIDAK AKTIF </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 col-sm-offset-2 no-padding-right">Deskripsi Soal</label>
                    <div class="col-xs-12 col-sm-12">
                        <div class="clearfix">
                            <textarea rows="2" cols="1" name="isi" id="isi" placeholder="Deskripsi Soal" class="col-xs-12"><?= ctk($edit['isi_soal'],1) ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">File :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input value="<?= $edit['file_soal'] ?>" type="hidden" name="exfoto" id="exfoto" />
                            <input value="" type="file" name="foto" id="foto" placeholder="Upload File" class="col-xs-12  col-sm-6" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-12 col-sm-3">
                        <?= st_file($edit['file_soal'], 1) ?><br/>
                        <span class="middle blue">* Boleh dikosongkan (Max 5 MB)</span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-5">
                        <div class="clearfix">
                            <div class="img-preview width-100 reset blur-up lazyload"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group is-opsi">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jumlah Opsi Jawaban :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="jumlah" id="jumlah" data-placeholder="---> Pilih Jumlah <---">
                                <option value=""> </option>
                                <?php
                                for ($val = 1;$val <= 5;$val++) {
                                    $selected = (count($opsi_array) == $val) ? 'selected' : '';
                                    echo '<option value="' . $val . '" '.$selected.'>' . $val . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <?php
                $string = "ABCDEFG";
                for ($index = 0;$index <= 4;$index++) {
                    $opsi = element($index, $opsi_array);
                    $nilai = '';
                    $isi = '';
                    if(is_array($opsi) && count($opsi) > 0){
                        $nilai = element('nilai', $opsi, '');
                        $isi = element('isi', $opsi);
                    }
                ?>
                <div class="form-group opsi-<?= $index+1 ?>">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">
                        Opsi <strong class="bigger-120 blue"><?= $string[$index] ?></strong>
                    </label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <select class="center bolder bigger-120" name="nilai<?=$string[$index]?>" id="nilai<?=$string[$index]?>" data-placeholder="--> Pilih Skor Opsi <?=$string[$index]?> <--">
                                <option value=""> --> Pilih Skor Opsi <?=$string[$index]?> <-- </option>
                                <?php
                                for($vl = 0; $vl <= 5; $vl++){
                                    $vl_new = ($vl == 0) ? ' -> [*SALAH*]':'';
                                    $selected = ($nilai != '' && $nilai == $vl) ? 'selected':'';
                                    echo '<option value="'.$vl.'" '.$selected.'> '.$vl.$vl_new.' </option>';
                                }
                                ?>
                            </select>
                            <small class="red">Pilih 0 untuk jawaban Salah</small>
                            <div class="space-2"></div>
                            <textarea rows="6" cols="1" name="opsi<?=$string[$index]?>" id="opsi<?=$string[$index]?>" placeholder="Tulis opsi jawaban <?=$string[$index]?> disini" class="col-xs-12"><?= $isi ?></textarea>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Log :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="well">
                            <span class="bigger-110 blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $edit['log_soal'] ?></span><br/>
                            <span class="bigger-110 orange"><i class="ace-icon fa fa-pencil-square-o"></i> &nbsp;&nbsp;<?= format_date($edit['update_soal'],0) ?></span>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                    <div class="col-md-offset-4 col-md-5">
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
    "theme/aceadmin/assets/js/jquery.validate.js",
    "theme/aceadmin/assets/js/select2.js",
    "theme/ckeditor/ckeditor.js",
    "theme/ckeditor/adapters/jquery.js",
    "theme/aceadmin/assets/js/bootbox.min.js"
)); 
?>
<script type="text/javascript">
const file_ext = ["jpg", "png", "jpeg", "PNG",
    "mp3","mp4","ogg","mpeg", "JPG","doc","docx","pdf","PDF"];
$(document).ready(function() {
    $(".select2").select2({allowClear: true});
    $(".select2-chosen").addClass("center");
    $('.remove').click(function (e) {
        $('.img-preview').html('');
    });
    is_opsi();
    show_opsi($("#jumlah").val());
    $("#bank").change(function () {
        is_opsi();
    });
    $("#jumlah").change(function () {
        show_opsi(this.value);
    });
    var editorConfig = {
        uiColor: '#438eb9',
        toolbar: [
            { name: 'document', items: [ 'Source', '-', 'Preview', 'Print' ] },
            //{ name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', '-', 'Undo', 'Redo' ] },
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike'] },
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent','-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
            { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
            { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'PageBreak' ] },
            { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
            { name: 'colors', items: [ 'TextColor', 'BGColor' ] }
        ]
    };
    CKEDITOR.disableAutoInline = true;
    $("textarea#isi").each(function() {
        CKEDITOR.replace(this.id, editorConfig);
    });
    $("#foto").ace_file_input({
        no_file: 'Pilih File. . .',
        no_icon: 'fa fa-file-pdf-o',
        icon_remove: 'fa fa-times',
        btn_choose: 'Pilih',
        btn_change: 'Ubah',
        allowExt: file_ext,
        maxSize: 5100000,
        before_change: function(files, dropped){
            var valid = false;
            if(files && files[0]) {
              var reader = new FileReader();
              reader.onload = function(e) {
                $('.img-preview').html('<embed src="'+ e.target.result +'" width="800" height="500" style="aspect-ratio: 4/3" class="img-thumbnail">');
              };
              reader.readAsDataURL(files[0]);
              valid = true;
            } else {
              $('.img-preview').html('');
            }
            return valid;
        }
    }).on('file.error.ace', function(ev, info) {
        if(info.error_count['ext']) jsfNotif('Peringatan!', 'Format file tidak didukung', 3);
        if(info.error_count['size']) jsfNotif('Peringatan!', 'Ukuran file maksimal 5 MB', 3);
    });
});
</script>
<script type="text/javascript">
function is_opsi() {
    let str = $("#bank").select2('data');
    if(str === '' || str === null){
        return;
    }
    let jenis = str.text.replace(/.*\(([^)]+)\).*/, '$1');
    if(jenis === 'ESSAI'){
        $("#jumlah").select2('val','');
        show_opsi(0);
        $(".is-opsi").addClass('hide');
    }else{
        $(".is-opsi").removeClass('hide');
    }
}
function show_opsi(val) {
    $("div[class*='opsi-']").addClass('hide');
    for (let i = 1; i <= parseInt(val); i++) {
        $(".opsi-" + i).removeClass('hide');
    }
}
$("#validation-form").submit(function (e) {
    let valid = $("#validation-form").validate().checkForm();
    if (!valid) { return; }
    var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
    var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
            ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
    var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
    //e.preventDefault();
});
$("#validation-form").validate({
    errorElement: 'div',
    errorClass: 'help-block',
    focusInvalid: false,
    ignore: [],
    debug: false,
    rules: {
        bank: {
            required: true
        },
        materi: {
            minlength: 5
        },
        isi: {
            required: function() {
                CKEDITOR.instances.isi.updateElement();
            },
            minlength: 5
        },
        order: {
            required: true,
            min: 1,
            max: 100
        },
        status: {
            required: true
        },
        opsiA: {
            required: function(e) {
                return parseInt($("#jumlah").val()) >= 1;
            }
        },
        opsiB: {
            required: function(e) {
                return parseInt($("#jumlah").val()) >= 2;
            }
        },
        opsiC: {
            required: function(e) {
                return parseInt($("#jumlah").val()) >= 3;
            }
        },
        opsiD: {
            required: function(e) {
                return parseInt($("#jumlah").val()) >= 4;
            }
        },
        opsiE: {
            required: function(e) {
                return parseInt($("#jumlah").val()) >= 5;
            }
        }
    },
    highlight: function(e) {
        $(e).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(e) {
        $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
        $(e).remove();
    },
    errorPlacement: function(error, element) {
        if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
            var controls = element.closest('div[class*="col-"]');
            if (controls.find(':checkbox,:radio').length > 1)
                controls.append(error);
            else
                error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
        }
        else if (element.is('.select2')) {
            error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
        }
        else if (element.is('.chosen-select')) {
            error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
        }
        else
            error.insertAfter(element.parent());
    },
    invalidHandler: function(form) {
    }
});
</script>
