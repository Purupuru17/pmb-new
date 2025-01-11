<?php $this->load->view('sistem/v_breadcrumb'); ?>
<style>
    .profile-info-name{
        width: 160px;
    }
</style>
<div class="page-content">
    <div class="page-header">
        <h1>
            <?= $title[1] ?>
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                <?= $detail['nama_matkul'] ?>
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <?= $this->session->flashdata('notif'); ?>
        </div>
        <div class="col-xs-12">
            <div class="tabbable">
                <ul class="nav nav-tabs padding-18">
                    <li class="">
                        <a data-toggle="tab" href="#satu" class="">
                            <i class="ace-icon fa fa-paste bigger-120 blue"></i>
                            Detail Jurnal
                        </a>
                    </li>
                    <li class="active">
                        <a data-toggle="tab" href="#dua" class="">
                            <i class="ace-icon fa fa-pencil-square-o bigger-120 green"></i>
                            Input Presensi
                        </a>
                    </li>
                    <li class="">
                        <a id="btn-qr" href="#" class="">
                            <i class="ace-icon fa fa-qrcode bigger-120 red"></i>
                            Presensi QRCode
                        </a>
                    </li>
                </ul>
                <div class="tab-content no-border padding-4">
                    <div id="satu" class="tab-pane fade">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="widget-box transparent">
                                    <div class="widget-header">
                                        <h5 class="widget-title bolder"><?= $detail['nama_matkul'] ?></h5>
                                        <div class="widget-toolbar">
                                            <a href="#" data-action="collapse" class="orange2">
                                                <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main padding-6 no-padding-left no-padding-right">
                                            <div id="user-profile-1" class="user-profile row">
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="profile-user-info profile-user-info-striped">
                                                        <div class="profile-info-row">
                                                            <div class="profile-info-name"> Semester </div>
                                                            <div class="profile-info-value">
                                                                <span><?= is_periode($detail['id_semester'], 1) ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="profile-info-row">
                                                            <div class="profile-info-name"> Program Studi </div>
                                                            <div class="profile-info-value">
                                                                <span><?= $detail['nama_prodi'] ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="profile-info-row">
                                                            <div class="profile-info-name"> Kode MK </div>
                                                            <div class="profile-info-value">
                                                                <span class="bolder"><?= $detail['kode_matkul'] ?></span>
                                                                <small>[<?= $detail['jenis_matkul'] ?>]</small>
                                                            </div>
                                                        </div>
                                                        <div class="profile-info-row">
                                                            <div class="profile-info-name"> Nama MK </div>
                                                            <div class="profile-info-value">
                                                                <span class="bolder blue"><?= $detail['nama_matkul'] ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="profile-info-row">
                                                            <div class="profile-info-name"> Semester </div>
                                                            <div class="profile-info-value">
                                                                <span class="bolder bigger-120"><?= $detail['semester_kelas'] ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="profile-info-row">
                                                            <div class="profile-info-name"> Nama Kelas </div>
                                                            <div class="profile-info-value">
                                                                <span class="bolder bigger-120"><?= $detail['nama_kelas'] ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="profile-info-row">
                                                            <div class="profile-info-name"> Bobot MK</div>
                                                            <div class="profile-info-value">
                                                                <span class="bolder red bigger-130"><?= $detail['sks_matkul'] ?></span> sks
                                                            </div>
                                                        </div>
                                                        <div class="profile-info-row">
                                                            <div class="profile-info-name"> Dosen Pengampu </div>
                                                            <div class="profile-info-value">
                                                                <span class="bolder"><?= $detail['nama_dosen'] ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="profile-info-row">
                                                            <div class="profile-info-name"> Peserta Kelas </div>
                                                            <div class="profile-info-value">
                                                                <span id="txt-peserta" class="bolder"><?= $detail['jumlah_mhs'] ?></span>  Mahasiswa
                                                            </div>
                                                        </div>
                                                        <div class="profile-info-row hide">
                                                            <div class="profile-info-name"> ***</div>
                                                            <div class="profile-info-value">
                                                                <span class="">
                                                                    Apabila Status Presensi : <strong class="orange">PENDING</strong>, maka Mahasiswa tersebut belum memvalidasi KRS. 
                                                                    Tolong segera temui Dosen PA.
                                                                    Apabila <strong class="red">TIDAK HADIR (APLHA)</strong>, silahkan dikosongkan saja tanpa ubah status
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="space-4"></div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="profile-user-info profile-user-info-striped">
                                                        <div class="profile-info-row">
                                                            <div class="profile-info-name"> Pertemuan </div>
                                                            <div class="profile-info-value">
                                                                <span class="bolder blue bigger-120">Ke - <?= $detail['init_jurnal'] ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="profile-info-row">
                                                            <div class="profile-info-name"> Tanggal Kuliah </div>
                                                            <div class="profile-info-value">
                                                                <span class="bolder"><?= format_date($detail['tgl_jurnal']) ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="profile-info-row">
                                                            <div class="profile-info-name"> Mode Kuliah </div>
                                                            <div class="profile-info-value">
                                                                <span class=""><?= st_mhs($detail['mode_jurnal']) ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="profile-info-row">
                                                            <div class="profile-info-name"> Ruang Kuliah </div>
                                                            <div class="profile-info-value">
                                                                <span class=""><?= ctk($detail['ruang_jurnal']) ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="profile-info-row">
                                                            <div class="profile-info-name"> Waktu Kuliah </div>
                                                            <div class="profile-info-value">
                                                                <span class=""><?= ctk($detail['waktu_jurnal']) ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="profile-info-row">
                                                            <div class="profile-info-name"> Status </div>
                                                            <div class="profile-info-value">
                                                                <span class=""><?= st_aktif($detail['status_jurnal']) ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="profile-info-row">
                                                            <div class="profile-info-name"> Catatan </div>
                                                            <div class="profile-info-value">
                                                                <span class=""><?= $detail['note_jurnal'] ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="space-4"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="dua" class="tab-pane active in">
                        <div class="row">
                            <div class="col-xs-12">
                                <p id="one-spin" class="bigger-130 blue" style="display: none" align="center"><i class="fa fa-spinner fa-spin fa-fw fa-2x"></i> Loading . . .</p>
                                <div class="widget-box transparent">
                                    <div class="widget-header">
                                        <h5 class="widget-title red">
                                            <i class="ace-icon fa fa-calendar-times-o"></i>
                                            Batas Presensi :: <strong> 
                                                <?= format_date(date('Y-m-d', strtotime('+100 day', strtotime($detail['tgl_jurnal'])))) ?>
                                            </strong> (100 Hari)
                                        </h5>
                                        <div class="widget-toolbar">
                                            <a href="#" data-action="collapse" class="orange2">
                                                <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                                            </a>
                                        </div>
                                        <div class="widget-toolbar">
                                            <div class="btn-group btn-overlap">
                                                <select class="btn-xs center bolder" name="status_all" id="status_all" data-placeholder="--> Pilih Status <--">
                                                    <option value=""> --> Pilih Status <-- </option>
                                                    <?php
                                                    foreach (array('HADIR', 'IZIN', 'SAKIT') as $val) {
                                                        echo '<option value="' . encode($val) . '"> ' . $val . ' </option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="btn-group btn-overlap">
                                                <button id="btn-input" class="btn btn-sm btn-bold btn-success btn-white">
                                                    <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                    <span class="">Input Presensi Kolektif</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="widget-toolbar no-border">
                                            <div class="btn-group btn-overlap">
                                                <button id="btn-rekap" onclick="load_mhs()" class="btn btn-white btn-primary btn-sm btn-bold">
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
                                                        <th width="5%">
                                                            <label class="pos-rel">
                                                                <input type="checkbox" class="ace ace-checkbox-2 input-lg"/>
                                                                <span class="lbl"></span>
                                                            </label>
                                                        </th>
                                                        <th>Mahasiswa</th>
                                                        <th>Program Studi</th>
                                                        <th>Status Presensi</th>
                                                        <th width="7%">Aksi</th>
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
            </div>
        </div><!-- /.col -->
        <!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
<form id="submit-form" method="POST">
    <input value="<?= encode($detail['id_jurnal']) ?>" type="hidden" id="id">
</form>
<?php
load_js(array(
    'backend/assets/js/dataTables/jquery.dataTables.js',
    'backend/assets/js/dataTables/jquery.dataTables.bootstrap.js',
    'backend/assets/js/bootbox.min.js',
    'backend/assets/js/select2.js'
));
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    let mhsTable;
    $(document).ready(function () {
        $(".select2").select2({allowClear: true});
        $(".select2-chosen").addClass("center");
        mhs_table();
    });
    $(document.body).on("click", "#ubah-btn", function(event) {
        let id = $(this).attr("itemid");
        let status = $("#status" + id).val();
        if(status === '' || status === null){
            myNotif('Peringatan', 'Input status terlebih dahulu', 2);
            return;
        }
        config_presensi(id, status);
        $(this).attr('disabled','disabled');
    });
    $(document.body).on("click", "#delete-btn", function(event) {
        let ths = $(this);
        let id = $(this).attr("itemid");
        let name = $(this).attr("itemname");
        if(id === ""){
            myNotif('Peringatan', 'Tidak ada data yang terpilih', 2);
            return;
        }
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
                    config_presensi(id, 'delete');
                    ths.attr('disabled','disabled');
                }
            }
        });
    });
    $("#btn-input").click(function(e) {
        let status = $("#status_all").val();
        var rowcollection = mhsTable.$("#input-mhs:checked", {"page": "all"});
        var id = "";
        var qty = 0;
        rowcollection.each(function(index, elem) {
            var checkbox_value = $(elem).val();
            id += checkbox_value + ',';
            qty++;
        });
        if(id === ""){
            myNotif('Peringatan', 'Pilih Mahasiswa terlebih dahulu', 2);
            return;
        }
        if(status === '' || status === null){
            myNotif('Peringatan', 'Pilih status terlebih dahulu', 2);
            return;
        }
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i> " + 
                "<strong class='bigger-130 red'> " + qty + "</strong><br/> Data Mahasiswa telah terpilih, klik Simpan untuk memperbarui status presensi." + 
                " Harap diperhatikan dengan baik!</p>";
        bootbox.confirm({title: title, message: msg, 
            buttons: {
                cancel: {
                    label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm"
                },
                confirm: {
                    label: "<i class='ace-icon fa fa-paper-plane bigger-110'></i> Simpan", className: "btn btn-sm btn-success"
                }
            },
            callback: function(result) {
                if (result === true) {
                    config_presensi(id, status);
                }
            }
        });
    });
    $("#mhs-table > thead > tr > th input[type=checkbox]").eq(0).on('click', function(){
        var $row = $("#mhs-table > tbody > tr > td:first-child input[type='checkbox']");
        if(!this.checked){
            $row.prop('checked', false).closest('tr').removeClass('danger');  
        } else {
            $row.prop('checked', true).closest('tr').addClass('danger');
        }
    });
    $("#mhs-table").on('click', 'td input[type=checkbox]' , function(){
        var $row = $(this).closest('tr');
        if(this.checked) $row.addClass('danger');
        else $row.removeClass('danger');
    });
    $("#btn-qr").click(function(e) {
        let qrdialog = bootbox.dialog({title: '<h4 class="blue center">QRCode untuk Presensi<br><small>Scan Menu : UNIMUDA > Perkuliahan</small></h4>',
            message: '<div align="center"><strong class="timeleft red bigger-200">???</strong><br>'+
                '<div class="img-thumbnail" id="qr-code"></div><p id="two-spin" class="bigger-130 blue" align="center">'+
                '<i class="fa fa-spinner fa-spin fa-fw fa-2x"></i> Loading . . .</p></div>', closeButton: false});
        $.ajax({
            url: module + "_do/ajax/type/action/source/qrcode",
            dataType: "json",
            type: "POST",
            data: { id: $("#id").val() },
            success: function (rs) {
                if (rs.status) {
                    let qrCode = new QRCode("qr-code", { width: 250, height: 250,
                        colorDark: "#000000", colorLight: "#ffffff", correctLevel: QRCode.CorrectLevel.H
                    });
                    qrCode.clear();
                    qrCode.makeCode(rs.data);
                    $("#two-spin").hide();
                    let timeLeft = 180;
                    const countdownTimer = setInterval(() => {
                        timeLeft--;
                        $(".timeleft").html(timeLeft);
                        if (timeLeft <= 0) {
                            clearInterval(countdownTimer);
                            qrdialog.modal("hide");
                        }
                    }, 1000);
                } else {
                    qrdialog.modal("hide");
                    myNotif('Peringatan', rs.msg, 2);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                qrdialog.modal("hide");
                console.log('Error : ' + xhr.responseText);
            }
        });
    });
</script>
<script type="text/javascript">
    function config_presensi(id, status = null){
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
        $.ajax({
            url: module + "_do/ajax/type/action/source/presensi",
            dataType: "json",
            type: "POST",
            data: {
                id: $("#id").val(),
                mhs: id,
                status: status
            },
            success: function (rs) {
                progress.modal("hide");
                if (rs.status) {
                    myNotif('Informasi', rs.msg, 1);
                } else {
                    myNotif('Peringatan', rs.msg, 2);
                }
                $("#status_all").val('');
                load_mhs();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                progress.modal("hide");
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function load_mhs() {
        $("#one-spin").show();
        $.ajax({
            url: module + "/ajax/type/table/source/mhs",
            type: "POST",
            dataType: "json",
            data: { id: $("#id").val() },
            success: function (rs) {
                mhsTable.fnClearTable();
                if (rs.status) {
                    $.each(rs.data, function (index, value) {
                        mhsTable.fnAddData(value);
                    });
                    $("#txt-peserta").html(rs.data.length);
                } else {
                    myNotif('Peringatan', rs.msg, 2);
                }
                mhsTable.fnDraw();
                $("#one-spin").hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
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
                {bSortable: false, aTargets: [0,4]},
                {bSearchable: false, aTargets: [0,4]},
                {sClass: "center", aTargets: [0,1,2,3]},
                {sClass: "center nowrap", aTargets: [4]}
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
</script>