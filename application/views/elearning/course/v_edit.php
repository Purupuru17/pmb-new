<?php
$this->load->view('sistem/v_breadcrumb');
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            <?= $edit['nama_module'] ?> <strong>[<?= $edit['jenis_module'].' - '.$edit['is_quiz'] ?>]</strong>
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
            <div class="tabbable">
                <ul class="nav nav-tabs padding-10">
                    <li class="active">
                        <a data-toggle="tab" href="#satu">
                            <i class="ace-icon fa fa-pencil-square-o bigger-120 orange"></i>
                            <?= $title[1] ?>
                        </a>
                    </li>
                    <li class="<?= $is_materi.' '.$is_tugas?>">
                        <a data-toggle="tab" href="#tiga">
                            <i class="ace-icon fa fa-question bigger-120 red"></i>
                            Enrol Pertanyaan | <strong><?= $edit['is_quiz'] ?></strong>
                        </a>
                    </li>
                </ul>
                <div class="tab-content no-border padding-10">
                    <div id="satu" class="tab-pane active in">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 class="lighter center block blue"><?= $title[1] ?></h3>
                                <form id="validation-form" action="<?= site_url($action); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                                    <input value="<?= encode($edit['id_module']) ?>" type="hidden" id="id"/>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-4 no-padding-right">Nama :</label>
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="clearfix">
                                                <input value="<?= $edit['nama_module'] ?>" type="text" name="nama" id="nama" placeholder="Nama Aktivitas" class="col-xs-12  col-sm-6"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group hide">
                                        <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jenis :</label>
                                        <div class="col-xs-12 col-sm-3">
                                            <div class="clearfix">
                                                <select class="select2 width-100" name="jenis" id="jenis" data-placeholder="-------> Pilih Jenis <-------">
                                                    <option value=""> </option>
                                                    <?php
                                                    foreach (array('MATERI','FILE','LINK','TUGAS','QUIZ') as $val) {
                                                        $selected = ($edit['jenis_module'] == $val) ? 'selected' : '';
                                                        echo '<option value="' . $val . '"  ' . $selected . '>' . $val . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group <?= $is_materi ?>">
                                        <label class="control-label col-xs-12 col-sm-4 no-padding-right">Buka Akses :</label>
                                        <div class="col-xs-12 col-sm-4">
                                            <div class="clearfix">
                                                <input value="<?= $edit['buka_module'] ?>" id="buka" name="buka" type="text" class="date-time-picker col-xs-12  col-sm-6" placeholder="Tanggal Buka" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group <?= $is_materi ?>">
                                        <label class="control-label col-xs-12 col-sm-4 no-padding-right">Tutup Akses :</label>
                                        <div class="col-xs-12 col-sm-4">
                                            <div class="clearfix">
                                                <input value="<?= $edit['tutup_module'] ?>" id="tutup" name="tutup" type="text" class="date-time-picker col-xs-12  col-sm-6" placeholder="Tanggal Tutup" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group <?= $is_materi . ' ' . $is_tugas ?> <?= empty($edit['is_quiz']) ? '':'hide' ?>">
                                        <label class="control-label col-xs-12 col-sm-4 no-padding-right">Tipe Soal :</label>
                                        <div class="col-xs-12 col-sm-2">
                                            <div class="clearfix">
                                                <select class="select2 width-100" name="tipe" id="tipe" data-placeholder="---> Pilih Tipe Soal <---">
                                                    <option value=""> </option>
                                                    <?php
                                                    foreach (array('PILIHAN-GANDA', 'KUESIONER', 'ESSAI') as $val) {
                                                        $selected = ($edit['is_quiz'] == $val) ? 'selected' : '';
                                                        echo '<option value="' . $val . '"  ' . $selected . '>' . $val . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group <?= $is_materi . ' ' . $is_tugas ?>">
                                        <label class="control-label col-xs-12 col-sm-4 no-padding-right">Acak Soal :</label>
                                        <div class="col-xs-12 col-sm-7">
                                            <div class="clearfix">
                                                <label class="control-label">
                                                    <input <?= ($edit['is_random'] == '1') ? 'checked' : ''; ?> name="random" value="1" type="radio" class="ace" />
                                                    <span class="lbl"> YA </span>
                                                </label>&nbsp;&nbsp;&nbsp;
                                                <label class="control-label">
                                                    <input <?= ($edit['is_random'] == '0') ? 'checked' : ''; ?> name="random" value="0" type="radio" class="ace" />
                                                    <span class="lbl"> TIDAK </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group <?= $is_materi . ' ' . $is_tugas ?>">
                                        <label class="control-label col-xs-12 col-sm-4 no-padding-right">Durasi Pengerjaan :</label>
                                        <div class="col-xs-12 col-sm-2">
                                            <div class="clearfix">
                                                <input value="<?= $edit['durasi_module'] ?>" type="number" name="durasi" id="durasi" class="col-xs-12  col-sm-6" placeholder="Durasi" />
                                            </div>
                                        </div>
                                        <span class="help-inline col-xs-12 col-sm-2 col-sm-pull-1">
                                            <span class="middle blue"> (Menit)</span>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-4 no-padding-right">Status :</label>
                                        <div class="col-xs-12 col-sm-7">
                                            <div class="clearfix">
                                                <label class="control-label">
                                                    <input <?= ($edit['status_module'] == '1') ? 'checked' : ''; ?> name="status" value="1" type="radio" class="ace" />
                                                    <span class="lbl"> AKTIF </span>
                                                </label>&nbsp;&nbsp;&nbsp;
                                                <label class="control-label">
                                                    <input <?= ($edit['status_module'] == '0') ? 'checked' : ''; ?> name="status" value="0" type="radio" class="ace" />
                                                    <span class="lbl"> TIDAK AKTIF </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-4 no-padding-right">File :</label>
                                        <div class="col-xs-12 col-sm-3">
                                            <div class="clearfix">
                                                <input value="<?= $edit['file_module'] ?>" type="hidden" name="exfile" id="exfile" />
                                                <input value="" type="file" name="file" id="file" placeholder="Upload File" class="col-xs-12  col-sm-6" />
                                            </div>
                                        </div>
                                        <span class="help-inline col-xs-12 col-sm-3">
                                            <?= st_file($edit['file_module'], 1) ?><br/>
                                            <span class="middle blue">* Boleh dikosongkan (Max 10 MB)</span>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-4 no-padding-right">Catatan :</label>
                                        <div class="col-xs-12 col-sm-8">
                                            <div class="clearfix">
                                                <textarea rows="8" cols="1" name="note" id="note" placeholder="Catatan Aktivitas" class="col-xs-12 col-sm-8"><?= $edit['note_module'] ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-4 no-padding-right">Log :</label>
                                        <div class="col-xs-12 col-sm-4">
                                            <div class="well">
                                                <span class="bigger-110 blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $edit['log_module'] ?></span><br/>
                                                <span class="bigger-110 orange"><i class="ace-icon fa fa-pencil-square-o"></i> &nbsp;&nbsp;<?= format_date($edit['update_module'], 0) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix form-actions">
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
                        </div>
                    </div>
                    <div id="tiga" class="tab-pane fade">
                        <p id="two-spin" style="display: none" class="bigger-130 blue" align="center"><i class="fa fa-spinner fa-spin fa-fw fa-2x"></i> Loading . . .</p>
                        <div class="widget-box transparent">
                            <div class="widget-header">
                                <h5 class="widget-title">
                                    <i class="ace-icon fa fa-list-ol"></i>
                                    Pertanyaan
                                </h5>
                                <div class="widget-toolbar">
                                    <a href="#" data-action="collapse" class="">
                                        <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                                    </a>
                                </div>
                                <div class="widget-toolbar no-border">
                                    <div class="btn-group btn-overlap <?= empty($edit['is_quiz']) ? 'hide':'' ?>">
                                        <select class="btn-xs center bolder" id="bank" data-placeholder="--> Pilih Bank <--">
                                            <option value=""> ---> Pilih Bank <--- </option>
                                            <?php
                                            foreach ($bank as $val) {
                                                echo '<option value="' . encode($val['id_bank']) . '">'.$val['nama_bank'].'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="btn-group btn-overlap">
                                        <button onclick="load_soal()" class="btn btn-white btn-primary btn-sm btn-bold">
                                            <i class="fa fa-search-plus bigger-120"></i> Lihat Data
                                        </button>
                                        <button id="btn-save-all" class="btn btn-white btn-success btn-sm btn-bold">
                                            <i class="fa fa-plus-square bigger-120"></i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main padding-2 table-responsive">
                                    <table id="soal-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th width="10%">
                                                    <label class="pos-rel">
                                                        <input type="checkbox" class="ace ace-checkbox-2 input-lg"/>
                                                        <span class="lbl"></span>
                                                    </label>
                                                </th>
                                                <th width="50%">Soal</th>
                                                <th>Tema</th>
                                                <th>Bank</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.row -->
</div><!-- /.page-content -->

<?php 
load_js(array(
    "backend/assets/js/jquery.validate.js",
    "backend/assets/js/select2.js",
    'backend/assets/js/bootbox.min.js',
    "backend/assets/js/date-time/moment.js",
    "backend/assets/js/date-time/bootstrap-datetimepicker.js",
    'backend/assets/js/dataTables/jquery.dataTables.js',
    'backend/assets/js/dataTables/jquery.dataTables.bootstrap.js'
)); 
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    let soalTable;
    $(document).ready(function() {
        $('[data-rel="tooltip"]').tooltip({placement: 'top'});
        $(".select2").select2({allowClear: true})
            .on('change', function() {
            $(this).closest('form').validate().element($(this));
        });
        $(".select2-chosen").addClass("center");
        $(".date-time-picker").datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss'
            }).next().on(ace.click_event, function(){
                $(this).prev().focus();
        });
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
        soal_table();
    });
    $(document.body).on("click", "#delete-btn", function(e) {
        var id = $(this).attr("itemid");
        var name = $(this).attr("itemname");
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan menghapus data <br/><b>" + name + "</b> ? </p>";
        bootbox.confirm({
            title: title, message: msg, 
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
                    enrol_soal(id, 'delete');
                }
            }
        });
    });
    $("#btn-save-all").click(function(e) {
        var rowcollection = soalTable.$("#item_select:checked", {"page": "all"});
        var id = "";
        var qty = 0;
        rowcollection.each(function(index, elem) {
            var checkbox_value = $(elem).val();
            id += checkbox_value + ',';
            qty++;
        });
        if(qty < 1){
            myNotif('Peringatan', 'Tidak ada data yang dipilih', 2, 1);
            return;
        }
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i> " + 
                "<strong class='bigger-130 red'> " + qty + "</strong> <br> Data telah terpilih, klik Simpan untuk mengirim data!</p>";
        bootbox.confirm({ title: title, message: msg, 
            buttons: {
                cancel: {
                    label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm"
                },
                confirm: {
                    label: "<i class='ace-icon fa fa-check bigger-110'></i> Simpan", className: "btn btn-success"
                }
            },
            callback: function(result) {
                if (result === true) {
                    enrol_soal(id);
                }
            }
        });
    });
    $("#soal-table > thead > tr > th input[type=checkbox]").eq(0).on('click', function(){
        var $row = $("#soal-table > tbody > tr > td:first-child input[type='checkbox']");
        if(!this.checked){
            $row.prop('checked', false).closest('tr').removeClass('success');  
        } else {
            $row.prop('checked', true).closest('tr').addClass('success');
        }
    });
    $("#soal-table").on('click', 'td input[type=checkbox]', function () {
        var $row = $(this).closest('tr');
        if (this.checked) {
            $row.addClass('success');
        } else {
            $row.removeClass('success');
        }
    });
    $("#bank").change(function () {
        load_soal();
    });
</script>
<script type="text/javascript">
    function load_soal() {
        $("#two-spin").show();
        $(".ace-checkbox-2").prop('checked', false);
        $.ajax({
            url: module + "/ajax/type/table/source/soal",
            type: "POST",
            dataType: "json",
            data: { id: $("#id").val(), bank: $("#bank").val() },
            success: function (rs) {
                soalTable.fnClearTable();
                if (rs.status) {
                    $.each(rs.data.table, function (index, value) {
                        soalTable.fnAddData(value);
                    });
                } else {
                    myNotif('Peringatan', rs.msg, 2);
                }
                soalTable.fnDraw();
                $("#two-spin").hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function enrol_soal(id, status = '') {
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
        $.ajax({
            url: module + "_do/ajax/type/action/source/enrol",
            dataType: "json",
            type: "POST",
            data: { id: $("#id").val(), soal: id, status:status },
            success: function (rs) {
                if (rs.status) {
                    load_soal();
                    myNotif('Informasi', rs.msg, 1);
                } else {
                    myNotif('Peringatan', rs.msg, 2, 1);
                }
                progress.modal("hide");
            },
            error: function (xhr, ajaxOptions, thrownError) {
                progress.modal("hide");
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function soal_table() {
        soalTable = $("#soal-table")
        .dataTable({
            aLengthMenu: [ [50, 100],[50, 100] ],
            bScrollCollapse: true,
            bAutoWidth: false,
            aaSorting: [],
            aoColumnDefs: [
                {bSortable: false, aTargets: [0]},
                {bSearchable: false, aTargets: [0]},
                {sClass: "center", aTargets: [1,2,3]},
                {sClass: "center nowrap", aTargets: [0]}
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
        soalTable.fnAdjustColumnSizing();
    }
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
            nama: {
                required: true,
                minlength: 3
            },
            jenis: {
                required: true
            },
            buka: {
                required: function(e) {
                    return $("#jenis").val() === 'TUGAS' || $("#jenis").val() === 'QUIZ';
                },
                minlength: 15
            },
            tutup: {
                required: function(e) {
                    return $("#jenis").val() === 'TUGAS' || $("#jenis").val() === 'QUIZ';
                },
                minlength: 15
            },
            tipe: {
                required: function(e) {
                    return $("#jenis").val() === 'QUIZ';
                }
            },
            random: {
                required: function(e) {
                    return $("#jenis").val() === 'QUIZ';
                }
            },
            durasi: {
                required: function(e) {
                    return $("#jenis").val() === 'QUIZ';
                },
                min: 10,
                max: 360
            },
            status: {
                required: true
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
