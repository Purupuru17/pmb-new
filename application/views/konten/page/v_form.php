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
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Judul :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input value="<?= $edit['judul_page'] ?>" type="text" name="judul" id="judul" class="col-xs-12  col-sm-6" placeholder="Judul Halaman" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Status :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <label class="control-label">
                                <input <?= ($edit['status_page'] == '1') ? 'checked' : '' ; ?> name="status" value="1" type="radio" class="ace" />
                                <span class="lbl"> Aktif</span>
                            </label>&nbsp;&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($edit['status_page'] == '0') ? 'checked' : '' ; ?> name="status" value="0" type="radio" class="ace" />
                                <span class="lbl"> Tidak Aktif</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-6 no-padding-right">Isi Konten</label>
                    <div class="col-xs-12 col-sm-12">
                        <div class="clearfix">
                            <textarea rows="10" cols="1" value="" name="isi" id="isi" class="col-xs-12 col-sm-12"><?= ctk($edit['isi_page'],1) ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Foto :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input value="<?= $edit['foto_page'] ?>" type="hidden" name="exfoto" id="exfoto" />
                            <input accept="image/*" value="" type="file" name="foto" id="foto" placeholder="Foto Halaman" class="col-xs-12  col-sm-6" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-12 col-sm-2">
                        <span class="middle red">* Maksimal 1 MB</span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <img width="200" class="img-thumbnail" src="<?= load_file($edit['foto_page']) ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Terakhir diubah :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="well">
                            <span class="bigger-110 blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $edit['log_page'] ?></span><br/>
                            <span class="bigger-110 orange"><i class="ace-icon fa fa-clock-o"></i> &nbsp;&nbsp;<?= format_date($edit['update_page'],0) ?></span>
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
        "backend/assets/js/select2.js",
        "frontend/ckeditor/ckeditor.js",
        "frontend/ckeditor/adapters/jquery.js",
    )); 
?>

<script type="text/javascript">
    jQuery(function($) {
        var img_ext = ["jpg", "png", "jpeg", "PNG", "JPG"];
        $('#foto').ace_file_input({
            no_file: 'Pilih Foto Halaman...',
            no_icon: 'fa fa-file-image-o',
            icon_remove: 'fa fa-times',
            btn_choose: 'Pilih',
            btn_change: 'Ubah',
            onchange: null,
            allowExt: img_ext,
            maxSize: 1100000
        }).on('file.error.ace', function(ev, info) {
            if(info.error_count['ext']) myNotif('Peringatan!', 'Format gambar harus berupa *.jpg, *.png', 3);
            if(info.error_count['size']) myNotif('Peringatan!', 'Ukuran gambar maksimal 1 MB', 3);
        });
        CKEDITOR.disableAutoInline = true;
        CKEDITOR.replace('isi', {
            uiColor: '#438eb9'
        });
    });
</script>
<script type="text/javascript">
    $('#validation-form').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            judul: {
                required: true,
                minlength: 5
            },
            status: {
                required: true
            }
        },
        messages: {
            judul: {
                required: "Kolom Judul Halaman harus diisi",
                minlength: "Panjang isi kolom minimal 5 karakter"
            },
            status: "Pilih Status Halaman terlebih dahulu"
        },
        highlight: function(e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
        },
        success: function(e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
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
