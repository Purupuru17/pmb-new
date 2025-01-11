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
            <h3 class="lighter center block bolder orange"><?= $edit['nama_mhs'].' - #'.$edit['kode_reg'] ?></h3>
            <form id="validation-form" action="<?= site_url($action); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group hide">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">NIM :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input value="<?= $edit['nim'] ?>" type="text" name="nim" id="nim" class="col-xs-12  col-sm-6" placeholder="NIM" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Pengambilan Atribut :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <textarea rows="5" cols="1" name="atribut" id="atribut" class="col-xs-12  col-sm-6" placeholder="Atribut Kampus"><?= ctk($edit['atribut_mhs']) ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Terakhir diubah :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="well">
                            <span class="bigger-110 blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $edit['log_mhs'] ?></span><br/>
                            <span class="bigger-110 orange"><i class="ace-icon fa fa-clock-o"></i> &nbsp;&nbsp;<?= format_date($edit['update_mhs'],0) ?></span>
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
        "backend/assets/js/select2.js"
    )); 
?>
<script type="text/javascript">
    $(document).ready(function () {
        $(".select2").select2({allowClear: true});
        $(".select2-chosen").addClass("center");
    });
</script>
<script type="text/javascript">
    $("#validation-form").validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            atribut: {
                required: true
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
