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
            <form id="search-form" action="#" name="form" class="form-horizontal" method="POST">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-1 no-padding-right">Kelas :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input type="hidden" name="kelas" id="kelas" class="width-100"/>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xs-12">
            <div class="space-6"></div>
            <div class="tabbable">
                <ul class="nav nav-tabs padding-18">
                    <li class="active">
                        <a data-toggle="tab" href="#satu" class="bolder">
                            <i class="ace-icon fa fa-paste bigger-120 blue"></i>
                            <span class="title">Course</span>
                        </a>
                    </li>
                    <li class="">
                        <a data-toggle="tab" href="#dua" class="">
                            <i class="ace-icon fa fa-bar-chart bigger-120 orange"></i>
                            Grade (Penilaian)
                        </a>
                    </li>
                    <li class="">
                        <a data-toggle="tab" href="#tiga" class="">
                            <i class="ace-icon fa fa-qrcode bigger-120 red"></i>
                            Presensi QRCode
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
                                        <div class="widget-toolbar no-border">
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
                    <div id="tiga" class="tab-pane fade">
                        <div class="space-20"></div>
                        <div align="center">   
                            <button class="scan-qr btn btn-app btn-danger">
                                <i class="ace-icon fa fa-camera bigger-230"></i>
                                Scan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
<?php
    load_js(array(
        'backend/assets/js/dataTables/jquery.dataTables.js',
        'backend/assets/js/dataTables/jquery.dataTables.bootstrap.js',
        'backend/assets/js/bootbox.min.js',
        'backend/assets/js/select2.js'
    ));
?>
<script src="https://unpkg.com/html5-qrcode"></script>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    let mhsTable;
    let htmlscanner;
    let scandialog;
    $(document).ready(function() {
        $("#sidebar").addClass('menu-min');
        $('[data-rel="tooltip"]').tooltip({placement: 'top'});
        $(".select2").select2({allowClear: true});
        get_select();
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
    $("#kelas").change(function () {
        let data = $("#kelas").select2('data');
        $(".title").html(data.text);
        $(".thead").html('');
        if ($.fn.dataTable.isDataTable('#mhs-table')) {
            $('#mhs-table').DataTable().clear().destroy();
        }
        load_index();
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
    $(".scan-qr").click(function(e) {
        scandialog = bootbox.dialog({title: '<h4 class="blue center">Scan QRCode untuk Presensi</h4>',
            message: '<div align="center" id="my-qr-reader"></div>', closeButton: false});
        htmlscanner = new Html5QrcodeScanner("my-qr-reader", { fps: 10, qrbox: 250, 
            facingMode: { exact: "environment"}, 
            //rememberLastUsedCamera: true,
            supportedScanTypes: [ Html5QrcodeScanType.SCAN_TYPE_CAMERA]
        });
        htmlscanner.render(onScanSuccess);
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
    function onScanSuccess(decodeText, decodeResult) {
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
        $.ajax({
            url: module + "_do/ajax/type/action/source/qrcode",
            dataType: "json",
            type: "POST",
            data: { token: decodeText },
            success: function (rs) {
                progress.modal("hide");
                if (rs.status) {
                    myNotif('Informasi', rs.msg, 1, 'popup');
                } else {
                    myNotif('Peringatan', rs.msg, 2, 'popup');
                }
                htmlscanner.clear();
                scandialog.modal("hide");
            },
            error: function (xhr, ajaxOptions, thrownError) {
                progress.modal("hide");
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function get_select() {
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
</script>