<?php $this->load->view('sistem/v_breadcrumb'); ?>
<style>
    .profile-info-name{
        width: 140px;
    }
</style>
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
                    <h4 class="widget-title lighter bolder"><?= ctk($detail['nama_module']) ?></h4>
                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse" class="orange2">
                            <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                        </a>
                    </div>
                    <div class="widget-toolbar no-border">
                        <div class="btn-group btn-overlap">
                            <a href="<?= site_url($module.'/edit/'.encode($detail['id_module'])) ?>"
                                class="btn btn-white btn-warning btn-sm btn-bold">
                                <i class="fa fa-pencil-square-o bigger-120"></i> Ubah Data
                            </a>
                            <button id="btn-delete" itemid="<?= site_url($module.'/delete/'.encode($detail['id_module'])) ?>" itemname="<?= ctk($detail['nama_module']) ?>"
                                class="btn btn-white btn-danger btn-sm btn-bold">
                                <i class="fa fa-trash bigger-120"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-6 no-padding-left no-padding-right">
                        <div id="user-profile-1" class="user-profile row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="profile-user-info profile-user-info-striped">
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Nama</div>
                                        <div class="profile-info-value">
                                            <span class=""><?= ctk($detail['nama_module']) ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Jenis</div>
                                        <div class="profile-info-value">
                                            <span class="bolder"><?= ctk($detail['jenis_module']) ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Buka Akses</div>
                                        <div class="profile-info-value">
                                            <span class=""><?= format_date($detail['buka_module'],0); ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Tutup Akses</div>
                                        <div class="profile-info-value">
                                            <span class="orange bolder"><?= format_date($detail['tutup_module'],0); ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row <?= empty($detail['is_quiz']) ? 'hide':'' ?>">
                                        <div class="profile-info-name">Tipe Soal</div>
                                        <div class="profile-info-value">
                                            <span class="bolder blue"><?= ctk($detail['is_quiz']); ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row <?= empty($detail['is_quiz']) ? 'hide':'' ?>">
                                        <div class="profile-info-name">Acak Soal :</div>
                                        <div class="profile-info-value">
                                            <?= st_aktif($detail['is_random'], 1)?>
                                        </div>
                                    </div>
                                    <div class="profile-info-row <?= empty($detail['is_quiz']) ? 'hide':'' ?>">
                                        <div class="profile-info-name">Durasi :</div>
                                        <div class="profile-info-value">
                                            <span class="bolder red"><?= ctk($detail['durasi_module']); ?> Menit</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-6"></div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="profile-user-info profile-user-info-striped">
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">File</div>
                                        <div class="profile-info-value">
                                            <?= st_file($detail['file_module'], 1) ?>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Catatan</div>
                                        <div class="profile-info-value">
                                            <span class=""><?= ctk($detail['note_module']); ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Status :</div>
                                        <div class="profile-info-value">
                                            <?= st_aktif($detail['status_module']); ?>
                                        </div>
                                    </div>
                                    <div class="profile-info-row ">
                                        <div class="profile-info-name">Log :</div>
                                        <div class="profile-info-value">
                                            <span class="blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= ctk($detail['log_module']) ?></span><br/>
                                            <span class="orange"><i class="ace-icon fa fa-pencil-square-o"></i> &nbsp;&nbsp;<?= format_date($detail['update_module'],0) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <p id="one-spin" class="bigger-130 blue" style="display: none" align="center"><i class="fa fa-spinner fa-spin fa-fw fa-2x"></i> Loading . . .</p>
            <div class="widget-box transparent">
                <div class="widget-header">
                    <h5 class="widget-title">
                        <i class="ace-icon fa fa-users"></i>
                        Rekap Penilaian
                    </h5>
                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse" class="orange2">
                            <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                        </a>
                    </div>
                    <div class="widget-toolbar no-border">
                        <div class="btn-group btn-overlap">
                            <button id="btn-rekap" class="btn btn-white btn-primary btn-sm btn-bold">
                                <i class="fa fa-search-plus bigger-120"></i> Lihat Data
                            </button>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-2 table-responsive">
                        <table id="mhs-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mahasiswa</th>
                                    <th>Sisa Waktu</th>
                                    <th>Hasil</th>
                                    <th>Catatan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
<form name="form" method="POST">
    <input value="<?= encode($detail['id_module']) ?>" type="hidden" name="id" id="id">
</form>
<div id="modal-edit" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                    <div align="center" class="bolder bigger-110">Pengaturan</div>
                </div>
            </div>
            <div class="modal-body padding-10">
                <form id="edit-form" action="#" name="edit-form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                    <input value="" name="id" id="itemid" type="hidden">
                    <input value="ubah" name="tipe" type="hidden">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right"></label>
                        <div class="col-xs-12 col-sm-7">
                            <div class="clearfix">
                                <label class="control-label">
                                    <input name="opsi" value="ubah" type="radio" class="ace" />
                                    <span class="lbl"> UBAH DATA </span>
                                </label>&nbsp;&nbsp;&nbsp;
                                <label class="control-label">
                                    <input name="opsi" value="tambah" type="radio" class="ace" />
                                    <span class="lbl"> TAMBAH WAKTU </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group is-tambah">
                        <label class="control-label col-xs-12 col-sm-4 no-padding-right">Tambahan Waktu</label>
                        <div class="col-xs-12 col-sm-5">
                            <div class="clearfix">
                                <input type="number" name="waktu" id="waktu" placeholder="? Menit" class="col-xs-12 col-sm-6 reset"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group is-ubah">
                        <label class="control-label col-xs-12 col-sm-4 no-padding-right">Status Pengerjaan</label>
                        <div class="col-xs-12 col-sm-4">
                            <div class="clearfix">
                                <select class="select2 width-100 reset" name="status" id="status" data-placeholder="---> Pilih Status <---">
                                    <option value=""> </option>
                                    <option value="2"> PENDING </option>
                                    <option value="0"> PROSES </option>
                                    <option value="1"> SELESAI </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group is-ubah">
                        <label class="control-label col-xs-12 col-sm-4 no-padding-right">Validasi Penilaian</label>
                        <div class="col-xs-12 col-sm-4">
                            <div class="clearfix">
                                <select class="select2 width-100 reset" name="valid" id="valid" data-placeholder="---> Pilih Opsi <---">
                                    <option value=""> </option>
                                    <option value="1"> AKTIF </option>
                                    <option value="0"> TIDAK AKTIF </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix form-actions">
                        <div class="col-sm-offset-3 col-sm-6">
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
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<?php
load_js(array(
    'theme/aceadmin/assets/js/dataTables/jquery.dataTables.js',
    'theme/aceadmin/assets/js/dataTables/jquery.dataTables.bootstrap.js',
    'theme/aceadmin/assets/js/bootbox.min.js',
    'theme/aceadmin/assets/js/select2.js',
    'theme/aceadmin/assets/js/jquery.validate.js',
));
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    let mhsTable;
    $(document).ready(function () {
        $('[data-rel="tooltip"]').tooltip({placement: 'top'});
        $(".select2").select2({allowClear: true});
        $(".select2-chosen").addClass("center");
        mhs_table();
    });
    $(document.body).on("click", "#edit-btn", function(e) {
        $("#itemid").val($(this).attr("itemid"));
        $("#status").select2('val',$(this).attr("itemname"));
        $("#valid").select2('val',$(this).attr("itemprop"));
        $("#waktu").val('');
        $('input[name="opsi"]').prop('checked', false);
        $(".is-tambah, .is-ubah").hide();
        
        $("#modal-edit").modal({backdrop: 'static',keyboard: false});
    });
    $(document.body).on("click", "#skor-btn", function(e) {
        const formData = { id : $(this).attr("itemid"), tipe: 'hitung' };
        update_skor(formData);
    });
    $(document.body).on("click", "#simpan-btn", function(e) {
        let id = $(this).attr("itemid");
        let skor = $("#skor" + id).val();
        if(skor === '' || skor === null){
            $("#skor" + id).focus();
            myNotif('Peringatan', 'Input Skor terlebih dahulu', 2);
            return;
        }
        const formData = { id : $(this).attr("itemid"), tipe: 'simpan', skor: skor };
        update_skor(formData);
    });
    $(document.body).on("click", "#delete-btn", function(event) {
        var id = $(this).attr("itemid");
        var name = $(this).attr("itemname");
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan menghapus data <br/><b>" + name + "</b> ? </p>";
        bootbox.confirm({title: title, message: msg, 
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
                    update_skor({id : id, tipe: 'hapus'});
                }
            }
        });
    });
    $(document.body).on("click", "#btn-delete", function(event) {
        var id = $(this).attr("itemid");
        var name = $(this).attr("itemname");
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan menghapus data <br/><b>" + name + "</b> ? </p>";
        bootbox.confirm({title: title, message: msg, 
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
                    window.location.replace(id);
                }
            }
        });
    });
    $("#btn-rekap").click(function () {
        load_mhs();
    });
    $('input[name="opsi"]').change(function() {
        var opsi = $('input[name="opsi"]:checked').val();
        if(opsi === 'tambah'){
            $(".is-tambah").show();
            $(".is-ubah").hide();
        }else{
            $(".is-tambah").hide();
            $(".is-ubah").show();
        }
    });
    $("button[type='reset'], button.close").click(function () {
        $(".reset").val('').select2('val','');
        $("#modal-edit").modal('hide');
    });
</script>
<script type="text/javascript">
    function update_skor(formData) {
        $("#one-spin").show();
        $.ajax({
            url: module + "_do/ajax/type/action/source/skor",
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (rs) {
                if (rs.status) {
                    load_mhs();
                    myNotif('Informasi', rs.msg, 1);
                } else {
                    myNotif('Peringatan', rs.msg, 2);
                }
                $("#one-spin").hide();
            },
            error: function (xhr, ajax, err) {
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function load_mhs() {
        $("#one-spin").show();
        $.ajax({
            url: module + "/ajax/type/table/source/rekap",
            type: "POST",
            dataType: "json",
            data: { id : $("#id").val() },
            success: function (rs) {
                mhsTable.fnClearTable();
                if (rs.status) {
                    $.each(rs.data, function (index, value) {
                        mhsTable.fnAddData(value);
                    });
                } else {
                    myNotif('Peringatan', rs.msg, 2);
                }
                mhsTable.fnDraw();
                $("#one-spin").hide();
            },
            error: function (xhr, ajax, err) {
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function mhs_table() {
        mhsTable = $("#mhs-table")
        .dataTable({
            iDisplayLength: 50,
            bScrollCollapse: true,
            bAutoWidth: false,
            aaSorting: [],
            aoColumnDefs: [
                {bSortable: false, aTargets: [0,6]},
                {bSearchable: false, aTargets: [0,6]},
                {sClass: "center", aTargets: [0,1,4]},
                {sClass: "center nowrap", aTargets: [2,3,5,6]}
            ],
            oLanguage: {
                sSearch: "Cari : ",
                sInfoEmpty: "Menampilkan dari 0 sampai 0 dari total 0 data",
                sInfo: "Menampilkan dari _START_ sampai _END_ dari total _TOTAL_ data",
                sLengthMenu: "_MENU_ data per halaman",
                sZeroRecords: "Maaf tidak ada data yang ditemukan",
                sInfoFiltered: "(Menyaring dari _MAX_ total data)"
            }
        });
        mhsTable.fnAdjustColumnSizing();
    }
    $("#edit-form").submit(function (e) {
        let valid = $(this).validate().checkForm();
        if (!valid) { return; }
        update_skor($(this).serialize());
        
        $("#modal-edit").modal('hide');
        e.preventDefault();
    });
    $("#edit-form").validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            waktu: {
                required: function(e) {
                    return $('input[name="opsi"]:checked').val() === 'tambah';
                },
                digits: true,
                min:5,
                max:60
            },
            status: {
                required: true
            },
            valid: {
                required: true
            },
            opsi: {
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
