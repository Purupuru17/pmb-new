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
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">User :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <select class="select2 width-100" name="user" id="user" data-placeholder="-------> Pilih User <-------">
                                <option value="">  </option>
                                <option class="bolder" value="shop"> Semua Toko </option>
                                <option class="bolder" value="cst"> Semua Pelanggan </option>
                                <?php
                                foreach ($user['data'] as $val) {
                                    echo '<option value="'.encode($val['id_user']).'">'.$val['fullname'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Subject :</label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <input value="<?= ctk($notif['subject_notif']) ?>" type="text" name="subject" id="subject" class="col-xs-12  col-sm-6" placeholder="Subject" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Pesan :</label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <textarea cols="1" rows="5" name="pesan" id="pesan" class="col-xs-12  col-sm-6" placeholder="Pesan"><?= ctk($notif['msg_notif']) ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Link :</label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <input value="<?= ctk($notif['link_notif']) ?>" type="text" name="link" id="link" class="col-xs-12  col-sm-6" placeholder="Link" />
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
<?php load_js(array(
    "backend/assets/js/jquery.validate.js",
    "backend/assets/js/select2.js"
)); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $(".select2").select2({allowClear: true})
            .on('change', function () {
            $(this).closest('form').validate().element($(this));
        });
        $(".select2-chosen").addClass("center");
    });
    $("#validation-form").validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            user: {
                required: true
            },
            subject: {
                required: true
            },
            pesan: {
                required: true
            },
            link: {
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
