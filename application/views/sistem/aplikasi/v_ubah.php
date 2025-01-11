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
            <form id="validation-form" action="<?= site_url($action . encode($app['id_aplikasi'])); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Judul Aplikasi :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input value="<?= $app['judul'] ?>" type="text" name="judul" id="judul" class="col-xs-12  col-sm-6" placeholder="Judul Aplikasi" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Deskripsi :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <textarea cols="1" rows="3" name="deskrip" id="deskrip" class="col-xs-12  col-sm-6" placeholder="Deskripsi"><?= $app['deskripsi'] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Hak Cipta :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input value="<?= $app['cipta'] ?>" type="text" name="cipta" id="cipta" class="col-xs-12  col-sm-6" placeholder="Hak Cipta" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Tema Website :</label>
                    <div class="col-xs-12 col-sm-1">
                        <div class="clearfix">
                            <div class="bootstrap-colorpicker">
                                <input name="website" value="<?= $theme[10] ?>" id="website" type="text" class="input-small color-pick" placeholder="Warna Utama" />
                            </div>
                        </div>
                    </div>
                    <span class="help-inline col-xs-12 col-sm-2">
                        <span class="middle red">* Warna Utama</span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-1">
                        <div class="clearfix">
                            <div class="bootstrap-colorpicker">
                                <input name="website_dua" value="<?= $theme[11] ?>" id="website_dua" type="text" class="input-small color-pick" placeholder="Warna Kedua" />
                            </div>
                        </div>
                    </div>
                    <span class="help-inline col-xs-12 col-sm-2">
                        <span class="middle red">* Warna Kedua</span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Tema Admin :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <label class="control-label">
                                <input <?= ($theme[0] == 'no-skin') ? 'checked' : ''; ?> name="tema" value="no-skin" type="radio" class="ace" />
                                <span class="lbl"> Biru</span>
                            </label>&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($theme[0] == 'skin-1') ? 'checked' : ''; ?> name="tema" value="skin-1" type="radio" class="ace" />
                                <span class="lbl"> Hitam</span>
                            </label>&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($theme[0] == 'skin-2') ? 'checked' : ''; ?> name="tema" value="skin-2" type="radio" class="ace" />
                                <span class="lbl"> Pink</span>
                            </label>&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($theme[0] == 'skin-3') ? 'checked' : ''; ?> name="tema" value="skin-3" type="radio" class="ace" />
                                <span class="lbl"> Putih</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Background Login :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <label class="control-label">
                                <input <?= ($theme[1] == '1') ? 'checked' : ''; ?> name="back" value="1" type="radio" class="ace" />
                                <span class="lbl"> Dark</span>
                            </label>&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($theme[1] == '2') ? 'checked' : ''; ?> name="back" value="2" type="radio" class="ace" />
                                <span class="lbl"> Blur</span>
                            </label>&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($theme[1] == '3') ? 'checked' : ''; ?> name="back" value="3" type="radio" class="ace" />
                                <span class="lbl"> Light</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group <?= ($is_admin != '1') ? 'hide' : '' ?>">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Pengaturan Tambahan :</label>
                    <div class="col-xs-12 col-sm-5">
                        <div class="clearfix">
                            <div class="checkbox">
                                <label>
                                    <input <?= ($theme[2] == 1) ? 'checked ' : '' ?> value="1" name="navbar" type="checkbox" class="ace" />
                                    <span class="lbl"> Fixed Navbar</span>
                                </label>
                                <label>
                                    <input <?= ($theme[3] == 1) ? 'checked ' : '' ?> value="1" name="sidebar" type="checkbox" class="ace" />
                                    <span class="lbl"> Fixed Sidebar</span>
                                </label>
                                <label>
                                    <input <?= ($theme[4] == 1) ? 'checked ' : '' ?> value="1" name="bread" type="checkbox" class="ace" />
                                    <span class="lbl"> Fixed Breadcrumbs</span>
                                </label>
                                <label>
                                    <input <?= ($theme[5] == 1) ? 'checked ' : '' ?> value="1" name="container" type="checkbox" class="ace" />
                                    <span class="lbl"> Inside Container</span>
                                </label>
                                <label>
                                    <input <?= ($theme[6] == 1) ? 'checked ' : '' ?> value="1" name="hover" type="checkbox" class="ace" />
                                    <span class="lbl"> Submenu Hover</span>
                                </label>
                                <label>
                                    <input <?= ($theme[7] == 1) ? 'checked ' : '' ?> value="1" name="compact" type="checkbox" class="ace" />
                                    <span class="lbl"> Compact Sidebar</span>
                                </label>
                                <label>
                                    <input <?= ($theme[8] == 1) ? 'checked ' : '' ?> value="1" name="horizontal" type="checkbox" class="ace" />
                                    <span class="lbl"> Horizontal Sidebar</span>
                                </label>
                                <label>
                                    <input <?= ($theme[9] == 1) ? 'checked ' : '' ?> value="1" name="item" type="checkbox" class="ace" />
                                    <span class="lbl"> Alt. Active Item</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Logo Aplikasi :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input value="<?= ($app['logo']) ?>" type="hidden" name="exfoto" id="exfoto" />
                            <input value="" accept="image/*" type="file" name="foto" id="foto" placeholder="Foto" class="col-xs-12  col-sm-6" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-12 col-sm-2">
                        <span class="middle red">* Maksimal 1 MB</span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-2">
                        <img class="img-thumbnail" src="<?= load_file($app['logo']) ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Periode Pendaftaran :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input value="<?= $this->session->userdata('periode') ?>" type="number" name="periode" id="periode" class="col-xs-12  col-sm-6" placeholder="Tahun Ajaran" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Terakhir diubah :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="well">
                            <span class="bigger-110 blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $app['session_aplikasi'] ?></span><br/>
                            <span class="bigger-110 orange"><i class="ace-icon fa fa-clock-o"></i> &nbsp;&nbsp;<?= format_date($app['update_aplikasi'],0) ?></span>
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
        
        <?php $this->load->view('sistem/aplikasi/v_visitor'); ?>
    </div><!-- /.row -->
</div><!-- /.page-content -->

<?php 
    load_js(array(
        "backend/assets/js/jquery.validate.js",
        "backend/assets/js/bootstrap-colorpicker.js"
    )); 
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.color-pick').colorpicker();
        var img_ext = ["jpg", "png", "jpeg", "PNG", "JPG"];
        $('#foto').ace_file_input({
            no_file: 'Plih Foto ...',
            no_icon: 'fa fa-file-image-o',
            icon_remove: 'fa fa-times',
            btn_choose: 'Pilih',
            btn_change: 'Ubah',
            droppable: false,
            onchange: null,
            allowExt: img_ext,
            maxSize: 1100000
        }).on('file.error.ace', function(ev, info) {
            if(info.error_count['ext']) myNotif('Peringatan!', 'Format gambar harus berupa *.jpg, *.png', 3);
            if(info.error_count['size']) myNotif('Peringatan!', 'Ukuran gambar maksimal 1 MB', 3);
        });
        
    });
    $("#validation-form").validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            judul: {
                required: true,
                minlength: 5
            },
            cipta: {
                required: true,
                minlength: 5
            },
            deskrip: {
                required: true,
                minlength: 5
            },
            website: {
                required: true
            },
            website_dua: {
                required: true
            },
            tema: {
                required: true
            },
            back: {
                required: true
            },
            periode: {
                required: true,
                digits: true
            }
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
