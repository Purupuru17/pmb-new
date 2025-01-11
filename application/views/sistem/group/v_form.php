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
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Nama Group :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input value="<?= ctk($group['nama_group']) ?>" type="text" name="nama" id="nama" class="col-xs-12  col-sm-6" placeholder="Nama Group" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Super Admin :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <label class="control-label">
                                <input <?= ($group['level'] == '1') ? 'checked' : '' ?> name="status" value="1" type="radio" class="ace" />
                                <span class="lbl"> YA </span>
                            </label>&nbsp;&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($group['level'] == '2') ? 'checked' : '' ?> name="status" value="2" type="radio" class="ace" />
                                <span class="lbl"> TIDAK </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Keterangan :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input value="<?= ctk($group['keterangan_group']) ?>" type="text" name="keterangan" id="keterangan" class="col-xs-12  col-sm-6" placeholder="Keterangan" />
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
        <!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
<?php load_js(array("backend/assets/js/jquery.validate.js")); ?>
<script type="text/javascript">
    $('#validation-form').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            nama: {
                required: true
            },
            keterangan: {
                required: true
            },
            status: {
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
