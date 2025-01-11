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
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">File :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input value="<?= $detail['file_jawab'] ?>" type="hidden" name="exfile" id="exfile" />
                            <input value="" type="file" name="file" id="file" placeholder="Upload File" class="col-xs-12  col-sm-6" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-12 col-sm-2">
                        <?= st_file($detail['file_jawab'], 1) ?><br/>
                        <span class="middle red">* Maksimal 10 MB</span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Catatan :</label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <textarea rows="8" cols="1" name="note" id="note" placeholder="Catatan" class="col-xs-12 col-sm-8"><?= $detail['note_jawab'] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Log :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="well">
                            <span class="bigger-110 blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $detail['log_jawab'] ?></span><br/>
                            <span class="bigger-110 orange"><i class="ace-icon fa fa-pencil-square-o"></i> &nbsp;&nbsp;<?= format_date($detail['update_jawab'], 0) ?></span>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions <?= ($is_done) ? 'hide':'' ?>">
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
    "backend/assets/js/jquery.validate.js",
    'backend/assets/js/bootbox.min.js'
)); 
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#file").ace_file_input({
            no_file: 'Pilih File...',
            no_icon: 'fa fa-file',
            icon_remove: 'fa fa-times',
            btn_choose: 'Pilih',
            btn_change: 'Ubah',
            onchange: null,
            allowExt: ["pdf", "PDF", "zip"],
            maxSize: 10100000 //10.1 Mb
        }).on('file.error.ace', function(ev, info) {
            if(info.error_count['ext']) myNotif('Peringatan!', 'Format file harus berupa File PDF atau ZIP', 3);
            if(info.error_count['size']) myNotif('Peringatan!', 'Ukuran file maksimal 10 MB', 3);
        });
    });
</script>
<script type="text/javascript">
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
        ignore: "",
        rules: {
            file: {
                required: true,
                minlength: 5
            },
            note: {
                minlength: 5
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