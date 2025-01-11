<div class="col-xs-12 col-sm-12">
    <div class="widget-box transparent">
        <div class="widget-header">
            <h5 class="widget-title">
                <i class="ace-icon fa fa-list"></i>
                List Data
            </h5>
            <div class="widget-toolbar">
                <a href="#" data-action="collapse" class="orange2">
                    <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                </a>
            </div>
        </div>
        <div class="widget-body">
            <div class="widget-main padding-2 table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Berkas</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Info</th>
                            <th>File</th>
                            <th>Log</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($berkas['data'] as $row) {
                            ?>
                            <tr>
                                <td><?= $no; ?></td>
                                <td>
                                    <strong><?= ctk($row['nama_upload']); ?></strong>
                                </td>
                                <td><?= ctk($row['tipe_upload']); ?></td>
                                <td><?= st_span($row['status_berkas']); ?></td>
                                <td><?= ctk($row['tipe_berkas']).'<br>'.ctk($row['size_berkas']); ?></td>
                                <td><?= st_file($row['file_berkas'], 1) ?></td>
                                <td class="smaller-80"><?= format_date($row['update_berkas'],2).'<br>'.ctk($row['log_berkas']); ?></td>
                                <td nowrap>
                                    <div class="action-buttons <?= ($row['status_berkas'] == '1') ? 'hide' : '' ?>">
                                        <a href="#" itemid="<?= encode($row['id_berkas']) ?>" itemprop="<?= $row['nama_upload'] ?>" id="edit-btn" class="tooltip-warning btn btn-white btn-warning btn-round btn-sm" data-rel="tooltip" title="Ubah Data">
                                            <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.col -->
<div id="modal-edit" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button onclick="reset_all()" type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                    <div align="center" class="bolder bigger-110">Validasi Berkas</div>
                </div>
            </div>
            <div class="modal-body padding-10">
                <form id="validation-form" action="<?= site_url($act_berkas); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="berkas" id="berkas" class="reset"/>                    
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-4 no-padding-right">Nama Berkas :</label>
                        <div class="col-xs-12 col-sm-6">
                            <div class="clearfix">
                                <label id="txt-berkas" class="control-label bolder bigger-120"></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-4 no-padding-right">Status :</label>
                        <div class="col-xs-12 col-sm-8">
                            <div class="clearfix">
                                <label class="control-label">
                                    <input name="status" value="1" type="radio" class="ace" />
                                    <span class="lbl"> VALID </span>
                                </label>&nbsp;&nbsp;&nbsp;
                                <label class="control-label">
                                    <input name="status" value="2" type="radio" class="ace" />
                                    <span class="lbl"> REUPLOAD </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-sm-12">
                            <button  onclick="reset_all()" class="btn" type="button" data-dismiss="modal">
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
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<?php
    load_js(array(
        'backend/assets/js/jquery.validate.js',
    ));
?>
<script type="text/javascript">
    $(document.body).on("click", "#edit-btn", function(event) {
        var nama = $(this).attr("itemprop");
        var id = $(this).attr("itemid");
        
        $("#berkas").val(id);
        $("#txt-berkas").html(nama);
        $("#modal-edit").modal({
            backdrop: 'static',
            keyboard: false
        });
    });
    function reset_all() {
        $(".reset").val('');
        $(".clear").html('');
        $("input[type=radio]").prop('checked', false);
    }
    $("#validation-form").validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            status: {
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
