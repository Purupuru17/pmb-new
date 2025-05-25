<?php
$this->load->view('sistem/v_breadcrumb');
?>
<style>
    .select2-container{
        padding-left: 0px;
    }
    .select2-chosen{
        text-align: center;
    }
    th, td{
        text-align: center;
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
            <form id="search-form" action="<?= site_url($module.'_do/export') ?>" name="form" class="form-horizontal" method="POST">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right">Periode :</label>
                            <div class="col-xs-12 col-sm-5">
                                <div class="clearfix">
                                    <select class="select2 width-100" name="periode" id="periode" data-placeholder="----> Pilih Periode <----">
                                        <option value=""> </option>
                                        <?php
                                        foreach ($semester['data'] as $val) {
                                            $selected = ($this->session->userdata('idsmt') == $val['id_semester']) ? 'selected' : '';
                                            echo '<option value="' . encode($val['id_semester']) . '" ' . $selected . '>' . $val['nama_semester'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right">Program Studi :</label>
                            <div class="col-xs-12 col-sm-5">
                                <div class="clearfix">
                                    <select class="select2 width-100" name="prodi" id="prodi" data-placeholder="----> Pilih Program Studi <----">
                                        <option value=""> </option>
                                        <?php
                                        foreach ($prodi['data'] as $val) {
                                            echo '<option value="' . encode($val['id_prodi']) . '">' . $val['nama_prodi'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-sm-pull-2">
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-2 no-padding-right">Dosen :</label>
                            <div class="col-xs-12 col-sm-6">
                                <div class="clearfix">
                                    <input value="<?= encode($this->session->userdata('did')); ?>" type="hidden" name="dosen" id="dosen" class="width-100"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-2 no-padding-right">Kelas :</label>
                            <div class="col-xs-12 col-sm-8">
                                <div class="clearfix">
                                    <input type="hidden" name="kelas" id="kelas" class="width-100"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xs-12">
            <div class="space-12"></div>
            <div class="tabbable">
                <ul class="nav nav-tabs padding-18">
                    <li class="active">
                        <a data-toggle="tab" href="#satu" class="bolder">
                            <i class="ace-icon fa fa-paste bigger-120 blue"></i>
                            <span class="title"><?= $title[0] ?></span>
                        </a>
                    </li>
                    <li class="">
                        <a data-toggle="tab" href="#dua" class="">
                            <i class="ace-icon fa fa-bar-chart bigger-120 orange"></i>
                            Grade (Penilaian)
                        </a>
                    </li>
                </ul>
                <div class="tab-content no-border padding-4">
                    <div id="satu" class="tab-pane active in">
                        <div class="row">
                            <div class="col-xs-12">
                                <p id="one-spin" style="display: none" class="bigger-130 blue" align="center"><i class="fa fa-spinner fa-spin fa-fw fa-2x"></i> Loading . . .</p>
                                <div class="widget-box transparent">
                                    <div class="widget-header">
                                        <h5 class="widget-title">
                                            <i class="ace-icon fa fa-list-ul"></i>
                                            Pertemuan
                                        </h5>
                                        <div class="widget-toolbar">
                                            <a href="#" data-action="collapse" class="orange2">
                                                <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                                            </a>
                                        </div>
                                        <div class="widget-toolbar">
                                            <div class="btn-group btn-overlap">
                                                <select class="btn-xs center bolder" id="nomor" data-placeholder="---> Pertemuan Ke ? <---">
                                                    <option value=""> --> Pertemuan Ke ? <-- </option>
                                                    <?php
                                                    for ($val = 1; $val <= 20; $val++) {
                                                        echo '<option value="' . $val . '">Ke - ' . $val . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="btn-group btn-overlap <?= empty($this->session->userdata('did')) ? 'hide':''; ?>">
                                                <button id="btn-input" class="btn btn-white btn-success btn-sm btn-bold">
                                                    <i class="fa fa-plus-square bigger-120"></i> Pertemuan
                                                </button>
                                            </div>
                                        </div>
                                        <div class="widget-toolbar no-border">
                                            <div class="btn-group btn-overlap">
                                                <button class="btn btn-primary btn-white btn-bold btn-sm" name="cari" id="btn-search" type="button">
                                                    <i class="ace-icon fa fa-search-plus bigger-120"></i>
                                                    Tampilkan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main no-padding">
                                            <div class="space-2"></div>
                                            <div class="list-course"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="dua" class="tab-pane fade">
                        <div class="row">
                            <div class="col-xs-12">
                                <p id="two-spin" style="display: none" class="bigger-130 blue" align="center"><i class="fa fa-spinner fa-spin fa-fw fa-2x"></i> Loading . . .</p>
                                <div class="widget-box transparent">
                                    <div class="widget-header">
                                        <h5 class="widget-title">
                                            <i class="ace-icon fa fa-users"></i>
                                            Mahasiswa
                                        </h5>
                                        <div class="widget-toolbar">
                                            <a href="#" data-action="collapse" class="orange2">
                                                <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                                            </a>
                                        </div>
                                        <div class="widget-toolbar no-border">
                                            <div class="btn-group btn-overlap">
                                                <button id="btn-mhs" class="btn btn-white btn-primary btn-sm btn-bold">
                                                    <i class="fa fa-search-plus bigger-120"></i> Lihat Data
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main padding-2 table-responsive">
                                            <table id="mhs-table" class="table table-striped table-bordered table-hover">
                                                <thead class="thead">
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
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
<div id="modal-add" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                    <div align="center" class="bolder bigger-110">Tambah Aktivitas</div>
                </div>
            </div>
            <div class="modal-body padding-10">
                <form id="activ-form" action="#" name="activ" class="form-horizontal" method="POST" enctype="multipart/form-data">
                    <input value="" name="activid" id="activid" type="hidden">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right">Nama</label>
                        <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                                <input type="text" name="nama_activ" id="nama_activ" placeholder="Nama Aktivitas" class="reset"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right">Jenis</label>
                        <div class="col-xs-12 col-sm-5">
                            <div class="clearfix">
                                <select class="select2 width-100 reset" name="jenis_activ" id="jenis_activ" data-placeholder="---> Pilih Jenis <---">
                                    <option value=""> </option>
                                    <option value="MATERI"> MATERI </option>
                                    <option value="FILE"> FILE </option>
                                    <option value="LINK"> LINK </option>
                                    <option value="TUGAS"> PENUGASAN </option>
                                    <option value="QUIZ"> QUIZ </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group div-quiz" style="display: none">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right">Tipe Soal</label>
                        <div class="col-xs-12 col-sm-4">
                            <div class="clearfix">
                                <select class="select2 width-100 reset" name="tipe_quiz" id="tipe_quiz" data-placeholder="---> Pilih Tipe <---">
                                    <option value=""> </option>
                                    <?php
                                    foreach (array('PILIHAN-GANDA', 'KUESIONER', 'ESSAI') as $val) {
                                        echo '<option value="' . $val . '">' . $val . '</option>';
                                    }
                                    ?>
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
    $(document).ready(function() {
        $('[data-rel="tooltip"]').tooltip({placement: 'top'});
        $(".select2").select2({allowClear: true});
        get_select();
    });
    $(document.body).on("click", "#add-btn", function(e) {
        $("#activid").val($(this).attr("itemid"));
        $("#modal-add").modal({backdrop: 'static',keyboard: false});
    });
    $(document.body).on("click", "#submit-btn", function(e) {
        var id = $(this).attr("itemid");
        var name = $(this).attr("itemname");
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan mulai mengerjakan <br/><strong class='blue bigger-130'>" + name + "</strong> sekarang ? </p>";
        bootbox.confirm({
            title: title, message: msg, 
            buttons: {
                cancel: {
                    label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm"
                },
                confirm: {
                    label: "<i class='ace-icon fa fa-check bigger-110'></i> Ya, Mulai", className: "btn btn-sm btn-success"
                }
            },
            callback: function(result) {
                if (result === true) {
                    start_session(id);
                }
            }
        });
    });
    $(document.body).on("click", "#assign-btn", function(e) {
        var id = $(this).attr("itemid");
        var name = $(this).attr("itemname");
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan mulai mengerjakan <br/><strong class='blue bigger-130'>" + name + "</strong> sekarang ? </p>";
        bootbox.confirm({
            title: title, message: msg, 
            buttons: {
                cancel: {
                    label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm"
                },
                confirm: {
                    label: "<i class='ace-icon fa fa-check bigger-110'></i> Ya, Mulai", className: "btn btn-sm btn-success"
                }
            },
            callback: function(result) {
                if (result === true) {
                    start_session(id, 'assign');
                }
            }
        });
    });
    $(document.body).on("click", "#btn-input", function(e) {
        var id = $("#kelas").val();
        var nomor = $("#nomor").val();
        if(id === null || id === ''){
            myNotif('Peringatan', 'Pilih Kelas Kuliah dahulu', 2);
            return;
        }
        if(nomor === null || nomor === ''){
            myNotif('Peringatan', 'Pilih Pertemuan dahulu', 2);
            return;
        }
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan menambahkan Jurnal <br/><strong class='blue bigger-130'>Pertemuan Ke - " + nomor + "</strong> ? </p>";
        bootbox.confirm({
            title: title, message: msg, 
            buttons: {
                cancel: {
                    label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm"
                },
                confirm: {
                    label: "<i class='ace-icon fa fa-check bigger-110'></i> Simpan", className: "btn btn-sm btn-success"
                }
            },
            callback: function(result) {
                if (result === true) {
                    add_jurnal(id, nomor);
                }
            }
        });
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
                    window.open(id, '_blank').focus();
                    setTimeout(function () {
                        load_index();
                    }, 2000);
                }
            }
        });
    });
    $(document.body).on("click", "#delete-activ-btn", function(event) {
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
                    window.open(id, '_blank').focus();
                    setTimeout(function () {
                        load_index();
                    }, 2000);
                }
            }
        });
    });
    $("#dosen,#prodi,#periode").change(function () {
        $("#kelas").select2('val','');
        $(".title").html('Course');
        $(".list-course").html('');
        $(".thead").html('');
        if ($.fn.dataTable.isDataTable('#mhs-table')) {
            $('#mhs-table').DataTable().clear().destroy();
        }
    });
    $("#kelas").change(function () {
        let data = $("#kelas").select2('data');
        $(".title").html(data.text);
        $(".thead").html('');
        if ($.fn.dataTable.isDataTable('#mhs-table')) {
            $('#mhs-table').DataTable().clear().destroy();
        }
        load_index();
    });
    $("#jenis_activ").change(function () {
        if(this.value === 'QUIZ'){
            $(".div-quiz").show();
        }else{
            $(".div-quiz").hide();
        }
    });
    $("#nomor").change(function () {
        load_index();
    });
    $("#btn-search").click(function () {
        load_index();
    });
    $("#btn-mhs").click(function () {
        load_mhs();
    });
    $("button[type='reset'], button.close").click(function () {
        $(".reset").val('').select2('val','');
        $(".div-quiz").hide();
    });
</script>
<script type="text/javascript">
    function load_index() {
        var id = $("#kelas").val();
        if(id === null || id === ''){
            myNotif('Peringatan', 'Pilih Kelas Kuliah dahulu', 2);
            return;
        }
        $("#one-spin").show();
        $(".list-course").html('');
        $.ajax({
            url: module + "/ajax/type/list/source/index",
            type: "POST",
            dataType: "json",
            data: { id: id, no: $("#nomor").val() },
            success: function (rs) {
                if (rs.status) {
                    $(".list-course").html(rs.data.course);
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
    function add_jurnal(id, nomor) {
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
        $.ajax({
            url: module + "_do/ajax/type/action/source/jurnal",
            type: "POST",
            dataType: "json",
            data: { id: id, nomor: nomor },
            success: function (rs) {
                progress.modal("hide");
                if (rs.status) {
                    load_index();
                    
                    myNotif('Informasi', rs.msg, 1);
                } else {
                    myNotif('Peringatan', rs.msg, 2);
                }
            },
            error: function (xhr, ajax, err) {
                progress.modal("hide");
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function start_session(id, url = 'start') {
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
        $.ajax({
            url: module + "_do/ajax/type/action/source/" + url,
            dataType: "json",
            type: "POST",
            data: { id: id },
            success: function (rs) {
                if (rs.status) {
                    setTimeout(function () {
                        progress.modal("hide");
                        window.open(rs.link, '_blank').focus();
                    }, 3000);
                    myNotif('Informasi', rs.msg, 1, 'swal');
                } else {
                    progress.modal("hide");
                    myNotif('Peringatan', rs.msg, 2, 'swal');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                progress.modal("hide");
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function load_mhs() {
        var id = $("#kelas").val();
        if(id === null || id === ''){
            myNotif('Peringatan', 'Pilih Kelas Kuliah dahulu', 2);
            return;
        }
        $("#two-spin").show();
        $(".thead").html('');
        if ($.fn.dataTable.isDataTable('#mhs-table')) {
            $('#mhs-table').DataTable().clear().destroy();
        }
        $.ajax({
            url: module + "/ajax/type/table/source/mahasiswa",
            type: "POST",
            dataType: "json",
            data: { id:id },
            success: function (rs) {
                if (rs.status) {
                    var tr = $('<tr></tr>');
                    tr.append('<th width="4%">#</th><th>Mahasiswa</th>');
                    $.each(rs.data.column, function(key, value) {
                        var tipe = value.is_quiz || 'PENUGASAN';
                        tr.append('<th>['+ value.init_jurnal  +'] '+ value.nama_module + '<br>' +
                            '<small style="font-weight:normal">'+tipe+'</small></th>');
                    });
                    tr.append('<th>TOTAL</th>');
                    $(".thead").html(tr);
                    mhsTable = $("#mhs-table").dataTable({
                        scrollX:true,
                        iDisplayLength: 50, bScrollCollapse: true,
                        bAutoWidth: false, aaSorting: [],
                        aoColumnDefs: [
                            {bSortable: false, aTargets: [0]},
                            {bSearchable: false, aTargets: [0]},
                            {sClass: "center nowrap", aTargets: [0]}
                        ]
                    });
                    $.each(rs.data.table, function (index, value) {
                        mhsTable.fnAddData(value);
                    });
                    mhsTable.fnAdjustColumnSizing();
                } else {
                    myNotif('Peringatan', rs.msg, 2);
                }
                $("#two-spin").hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function get_select() {
        $("#dosen").select2({
            placeholder: "-------> Pilih Dosen <-------",
            allowClear: true,
            ajax: {
                url: module + "/ajax/type/list/source/dosen",
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
                    $.ajax(module + "/ajax/type/list/source/dosen?id=" + id, {
                        dataType: "json"
                    }).done(function(data) { 
                        callback(data[0]);
                    });
                }
            }
        });
        $("#kelas").select2({
            placeholder: "-------> Pilih Kelas Kuliah <-------",
            ajax: {
                url: module + "/ajax/type/list/source/kelas",
                type: "POST",
                dataType: 'json',
                delay: 250,
                data: function (key) {
                    return { key: key, periode:$("#periode").val() ,dosen: $("#dosen").val(), prodi: $("#prodi").val() };
                },
                results: function (data) {
                    return { results: data };
                },
                cache: true
            }
        });
    }
    $("#activ-form").submit(function (e) {
        let valid = $("#activ-form").validate().checkForm();
        if (!valid) { return; }
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
        $.ajax({
            url: module + "_do/ajax/type/action/source/activity",
            type: "POST",
            dataType: "json",
            data: $("#activ-form").serialize(),
            success: function (rs) {
                progress.modal("hide");
                if (rs.status) {
                    load_index();
                    $(".reset").val('').select2('val','');
                    $(".div-quiz").hide();
                    $("#modal-add").modal('hide');
                    
                    myNotif('Informasi', rs.msg, 1);
                } else {
                    myNotif('Peringatan', rs.msg, 2);
                }
            },
            error: function (xhr, ajax, err) {
                progress.modal("hide");
                console.log('Error : ' + xhr.responseText);
            }
        });
        e.preventDefault();
    });
    $("#activ-form").validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            nama_activ: {
                required: true,
                minlength: 3
            },
            jenis_activ: {
                required: true
            },
            tipe_quiz: {
                required: function(e) {
                    return $("#jenis_activ").val() === 'QUIZ';
                }
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