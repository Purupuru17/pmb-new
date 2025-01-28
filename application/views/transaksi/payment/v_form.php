<?php $this->load->view('sistem/v_breadcrumb'); ?>
<style>
    .select2-container{
        padding-left: 0px;
    }
    .select2-chosen{
        text-align: center;
    }
</style>
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
            <form id="validation-form" action="#" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <input value="<?= encode($is_mahasiswa) ?>" type="hidden" name="mhs" id="mhs" class="width-100 bolder bigger-110"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Tagihan :</label>
                    <div class="col-xs-12 col-sm-5">
                        <div class="clearfix">
                            <input type="hidden" name="tagihan" id="tagihan" class="width-100 bolder bigger-110"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Total Pembayaran :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <blockquote style="margin-bottom: 0px" class="middle bolder green bigger-200" id="txt-total"></blockquote>
                            <input type="hidden" name="total" id="total" placeholder="Total Pembayaran" class="center bolder col-xs-12 input-lg"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Transfer Via :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="bank" id="bank" data-placeholder="---> Pilih Bank <---">
                                <option value=""> </option>
                                <?php foreach ($bank as $item) : ?>
                                <option value="<?= $item ?>"> Bank <?= $item ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Virtual Account :</label>
                    <div class="col-xs-12 col-sm-5">
                        <div class="clearfix">
                            <input type="text" name="virtual" id="virtual" placeholder="Rekening Virtual Account" class="bolder blue bigger-120 col-xs-12 col-sm-6"/>
                            <span id="one-spin" class="blue" align="center" style="display: none">
                                <i class="fa fa-spinner fa-spin fa-fw fa-2x"></i>
                            </span> 
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Catatan :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <textarea rows="3" cols="1" name="note" id="note" placeholder="Catatan Pembayaran" class="col-xs-12 col-sm-6"></textarea>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                    <div class="col-xs-offset-4 col-sm-5">
                        <button class="btn" type="reset">
                            <i class="ace-icon fa fa-undo bigger-110"></i>
                            Batal
                        </button>
                        &nbsp; &nbsp; &nbsp;
                        <button class="btn btn-success" name="simpan" type="submit">
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
<?php
load_js(array(
    'backend/assets/js/bootbox.min.js',
    'backend/assets/js/select2.js',
    'backend/assets/js/jquery.validate.js'
));
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    const form = $("#validation-form");
    
    $(document).ready(function () {
        $(".select2").select2({allowClear: false});
        $(".select2-chosen").addClass("center");
        get_select();
    });
    $(document).on("focusin", "#virtual, #total, #note", function() {
        $(this).prop('readonly', true);
    });
    $(document).on("focusout", "#virtual, #total, #note", function() {
        $(this).prop('readonly', false); 
    });
    $("#bank").change(function(){
        get_virtual();
        $(this).closest('form').validate().element($(this));
    });
    $("#tagihan").change(function(){
        let data = $(this).select2('data');
        let total = parseInt(data.total);
        
        $("#total").val(total);
        $("#txt-total").html('Rp ' + total.toLocaleString('id-ID'));
        $("#note").html(data.text);
        
        $(this).closest('form').validate().element($(this));
    });
    form.submit(function(e){
        let valid = form.validate().checkForm();
        let total = $("#total").val();
        let virtual = $("#bank").val() + ' - ' + $("#virtual").val();
        if (!valid) {
            return;
        }
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-150'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
            " Apakah anda yakin akan membuat pembayaran dengan Total : <br/><b class='red bigger-200'>Rp " 
            + to_rupiah(total) + "</b><br><b>" + virtual + "</b></p>";
        bootbox.confirm({ title: title, message: msg, 
            buttons: {
                cancel: { label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm" },
                confirm: { label: "<i class='ace-icon fa fa-check bigger-110'></i> Ya, Buat Sekarang", className: "btn btn-success" }
            },
            callback: function(result) {
                if (result === true) {
                    invoice_add();
                }
            }
        });
        e.preventDefault();
    });
</script>
<script type="text/javascript">
    function invoice_add() {
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title, message: msg, closeButton: false });
        $.ajax({
            url: module + "_do/ajax/type/action/source/invoice",
            dataType: "json",
            type: "POST",
            data: form.serialize(),
            success: function (rs) {
                if (rs.status) {
                    setTimeout(function () {
                        progress.modal("hide");
                        window.location.replace(rs.data);
                    }, 2000);
                    myNotif('Informasi', rs.msg, 1, 'swal');
                } else {
                    myNotif('Peringatan', rs.msg, 2, 'swal');
                    progress.modal("hide");
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log('Error : ' + xhr.responseText);
                progress.modal("hide");
            }
        });
    }
    function get_virtual() {
        $("#virtual").val('');
        $("#one-spin").show();
        $.ajax({
            url: module + "_do/ajax/type/action/source/virtual",
            dataType: "json",
            type: "POST",
            data: {
                id: $("#mhs").val(),
                bank: $("#bank").val()
            },
            success: function (rs) {
                if (rs.status) {
                    $("#virtual").val(rs.data);
                } else {
                    myNotif('Peringatan', rs.msg, 2, 'swal');
                }
                $("#one-spin").hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function get_select() {
        $("#mhs").select2({
            placeholder: "Masukkan Kode Registrasi/Nama",
            minimumInputLength: 3,
            ajax: {
                url: module + "/ajax/type/list/source/mahasiswa",
                type: "POST",
                dataType: 'json',
                delay: 250,
                data: function (key) {
                    return { key: key };
                },
                results: function (data) {
                    return { results: data };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax(module + "/ajax/type/list/source/mahasiswa?id=" + id, {
                        dataType: "json"
                    }).done(function(data) { 
                        callback(data[0]);
                    });
                }
            }
        });
        $("#tagihan").select2({
            placeholder: "---> Pilih Item Pembayaran <---",
            ajax: {
                url: module + "/ajax/type/list/source/tagihan",
                type: "POST",
                dataType: 'json',
                delay: 250,
                data: function (key) {
                    return { key: key };
                },
                results: function (data) {
                    return { results: data };
                },
                cache: true
            }
        });
    }
    form.validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            mhs: {
                required: true
            },
            tagihan: {
                required: true
            },
            total: {
                required: true,
                digits: true,
                min: 10000
            },
            bank: {
                required: true
            },
            virtual: {
                required: true,
                digits: true,
                minlength: 12
            },
            note: {
                required: true,
                minlength: 5
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