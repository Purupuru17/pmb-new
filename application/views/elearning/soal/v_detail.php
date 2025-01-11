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
            <div class="widget-box transparent">
                <div class="widget-header">
                    <h4 class="widget-title lighter bolder"><?= ctk($detail['nama_bank']) ?></h4>
                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse" class="orange2">
                            <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                        </a>
                    </div>
                    <div class="widget-toolbar no-border">
                        <div class="btn-group btn-overlap">
                            <a href="<?= site_url($module .'/edit/'. encode($detail['id_soal'])) ?>" 
                                class="btn btn-white btn-warning btn-bold btn-sm">
                                <i class="fa fa-pencil-square-o bigger-120"></i> Ubah Data
                            </a>
                            <button itemid="<?= encode($detail['id_soal']) ?>" itemname="<?= limit_text($detail['isi_soal'],100) ?>" id="btn-delete" 
                                class="btn btn-white btn-danger btn-sm btn-bold">
                                <i class="ace-icon fa fa-trash-o"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-6 no-padding-left no-padding-right">
                        <div id="user-profile-1" class="user-profile row">
                            <div class="col-xs-12 col-sm-12">
                                <div class="profile-user-info profile-user-info-striped">
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Isi Soal</div>
                                        <div class="profile-info-value">
                                            <span class="bolder"><?= ($detail['isi_soal']) ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-6"></div>
                            </div>
                            <!-- BAWAH -->
                            <div class="col-xs-12 col-sm-8">
                                <div class="profile-user-info profile-user-info-striped">
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Jenis</div>
                                        <div class="profile-info-value">
                                            <span class="bolder blue"><?= ctk($detail['jenis_bank']) ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Materi</div>
                                        <div class="profile-info-value">
                                            <span class=""><?= ctk($detail['materi_soal']) ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name bolder">Nilai & Opsi</div>
                                        <div class="profile-info-value">
                                            <span class=""></span>
                                        </div>
                                    </div>
                                    <?php
                                    $string = "ABCDEFG";
                                    $index = 0;
                                    foreach ($opsi_array as $item) {
                                        $is_benar = intval(element('nilai', $item)) == 0 ? 'alert alert-danger' : 'alert alert-success';
                                        echo '<div class="profile-info-row '.$is_benar.'">
                                            <div class="profile-info-name">
                                                [ <strong class="orange bigger-120">'.element('nilai', $item).'</strong> ] --> 
                                                <strong class="bigger-120">'. $string[$index] .'</strong>
                                            </div><div class="profile-info-value">
                                                <span class="">'.element('isi', $item).'</span>'
                                                .(element('file', $item)).'</div></div>';
                                        $index++;
                                    }
                                    ?>
                                </div>
                                <div class="space-6"></div>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <div class="profile-user-info profile-user-info-striped">
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Order</div>
                                        <div class="profile-info-value">
                                            <span class="badge badge-info"><?= ctk($detail['order_soal']); ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Status</div>
                                        <div class="profile-info-value">
                                            <?= st_aktif($detail['status_soal']); ?>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Log</div>
                                        <div class="profile-info-value">
                                            <span class="blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= ctk($detail['log_soal']) ?></span><br/>
                                            <span class="orange"><i class="ace-icon fa fa-pencil-square-o"></i> &nbsp;&nbsp;<?= format_date($detail['update_soal'],0) ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">File</div>
                                        <div class="profile-info-value">
                                            <span class=""><?= st_file($detail['file_soal'],1) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
<?php
load_js(array(
    'backend/assets/js/jquery.validate.js',
    'backend/assets/js/select2.js',
    'backend/assets/js/bootbox.min.js'
));
?> 
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    const file_ext = ["jpg", "png", "jpeg", "PNG",
        "mp3","mp4","ogg","mpeg", "JPG","doc","docx","pdf","PDF"];
    $(document).ready(function() {
        $(".select2").select2({allowClear: true});
        $(".select2-chosen").addClass("center");
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
                    $('.img-preview').html('<embed src="'+ e.target.result +'" width="100%" class="blur-up img-thumbnail lazyload">');
                  };
                  reader.readAsDataURL(files[0]);
                  valid = true;
                } else {
                  $('.img-preview').html('');
                }
                return valid;
            }
        }).on('file.error.ace', function(ev, info) {
            if(info.error_count['ext']) myNotif('Peringatan!', 'Format file tidak didukung', 3);
            if(info.error_count['size']) myNotif('Peringatan!', 'Ukuran file maksimal 5 MB', 3);
        }); 
        $('.remove').click(function (e) {
            $('.img-preview').html('');
        });
    });
    $(document.body).on("click", "#btn-delete", function(event) {
    var id = $(this).attr("itemid");
    var name = $(this).attr("itemname");
    var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
    var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
            " Apakah anda yakin akan menghapus data <br/><b>" + name + "</b> ? </p>";
    bootbox.confirm({ title: title, message: msg, 
        buttons: {
            cancel: {
                label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm"
            },
            confirm: {
                label: "<i class='ace-icon fa fa-trash-o bigger-110'></i> Hapus", className: "btn btn-sm btn-danger"
            }
        },
        callback: function(result) {
            if (result === true) {
                window.location.replace(module + "/delete/" + id);
            }
        }
    });
});
</script>
<script type="text/javascript">
    $("#validation-form").validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            isi:{
                //required: true,
                minlength: 5
            },
            nilai:{
                required: true,
                digits: true,
                min: 0,
                max: 10
            },
            skala:{
                required: true,
                digits: true,
                min: 0,
                max: 100
            },
            order: {
                required: true,
                digits: true,
                min: 1,
                max: 10
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
