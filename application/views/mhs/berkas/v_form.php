<?php
$this->load->view('sistem/v_breadcrumb');
?>
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
            <h3 class="lighter center block blue"><?= $title[1] ?></h3>
            <form id="validation-form" action="<?= site_url($action); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jenis Berkas :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <select class="select2 width-100" name="jenis" id="jenis" data-placeholder="-------> Jenis Berkas <-------">
                                <option value=""> </option>
                                <?php
                                foreach ($upload['data'] as $val) {
                                    $selected = ($edit['upload_id'] == $val['id_upload']) ? 'selected' : '';
                                    $view = $val['nama_upload'].' ['.$val['tipe_upload'].']';
                                    echo '<option value="' . encode($val['id_upload']) . '" '.$selected.'>' . $view . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">File Berkas :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input value="<?= $edit['file_berkas'] ?>" type="hidden" name="exfile" id="exfile2" />
                            <input value="" type="file" name="file" id="file" placeholder="Upload File" class="col-xs-12  col-sm-6" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-12 col-sm-2">
                        <span class="middle red">* Maksimal 3 MB</span>
                    </span> 
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix"><?= st_file($edit['file_berkas'],1) ?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Terakhir diubah :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="well">
                            <span class="bigger-110 blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $edit['log_berkas'] ?></span><br/>
                            <span class="bigger-110 orange"><i class="ace-icon fa fa-clock-o"></i> &nbsp;&nbsp;<?= format_date($edit['update_berkas'],0) ?></span>
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
        "theme/aceadmin/assets/js/jquery.validate.js",
        "theme/aceadmin/assets/js/select2.js",
        "theme/aceadmin/assets/js/bootbox.min.js"
    )); 
?>
<script type="text/javascript">
    const jenis = $("#jenis");
    const file_ext = ["jpg", "png", "jpeg", "PNG", "JPG", "pdf", "PDF"];
    const form = $("#validation-form");
        
    $(document).ready(function(){
        $(".select2").select2({allowClear: true})
            .on('change', function() {
            $(this).closest('form').validate().element($(this));
        });
        $(".select2-chosen").addClass("center");
        if(jenis.val() !== ''){
            jenis.select2("readonly", true);
        }
        
        $("#file").ace_file_input({
            no_file: 'Pilih File...',
            no_icon: 'fa fa-file',
            icon_remove: 'fa fa-times',
            btn_choose: 'Pilih',
            btn_change: 'Ubah',
            onchange: null,
            allowExt: file_ext,
            maxSize: 3100000 //3.1 Mb
        }).on('file.error.ace', function(ev, info) {
            if(info.error_count['ext']) jsfNotif('Peringatan!', 'Format file harus berupa File PDF', 3);
            if(info.error_count['size']) jsfNotif('Peringatan!', 'Ukuran file maksimal 3 MB', 3);
        });
        
        
    });
</script>
<script type="text/javascript">
    form.submit(function(){
        if(form.validate().checkForm()){
            var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Menyimpan Data . . . </h4>';
            var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                    ' Mohon menunggu, jangan menutup atau me-refresh halaman ini. <br>Silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
            bootbox.dialog({title: title,message: msg,closeButton: false});
        }
    }); 
    form.validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            jenis: {
                required: true
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
