<?php
$this->load->view('sistem/v_breadcrumb');
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            <?= $title[1] ?>
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                <?= ctk($detail['jenis_bank']) ?>
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <?= $this->session->flashdata('notif'); ?>
        </div>
        <div class="col-xs-12">
            <form id="validation-form" action="<?= site_url($action); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <input value="<?= encode($detail['id_bank']) ?>" id="id" type="hidden" >
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">File Soal :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input type="file" name="file" id="file" placeholder="Upload File" class="col-xs-12  col-sm-6" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-12 col-sm-3">
                        <span class="middle red">* Maksimal 5 MB</span><br>
                        <a href="<?= base_url('theme/img/bank.xls') ?>">Template &nbsp;<i class="fa fa-download red bigger-120"></i></a>
                    </span> 
                </div>
                <div class="clearfix form-actions">
                    <div class="col-md-offset-4 col-md-5">
                        <button class="btn hide" type="reset">
                            <i class="ace-icon fa fa-undo bigger-110"></i>
                            Batal
                        </button>
                        &nbsp; &nbsp; &nbsp;
                        <button class="btn btn-success btn-sm" name="simpan" id="simpan" type="submit">
                            <i class="ace-icon fa fa-upload"></i>
                            Upload
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xs-12">
            <p id="soal-spin" class="bigger-130 blue" align="center"><i class="fa fa-spinner fa-spin fa-fw fa-2x"></i> Loading . . .</p>
            <div class="widget-box transparent">
                <div class="widget-header">
                    <h5 class="widget-title">
                        <i class="ace-icon fa fa-list-ol"></i>
                        Soal <?= $title[1] ?>
                    </h5>
                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse" class="orange2">
                            <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                        </a>
                    </div>
                    <div class="widget-toolbar no-border">
                        <div class="btn-group btn-overlap">
                            <button onclick="load_data()" class="btn btn-white btn-primary btn-sm btn-bold">
                                <i class="fa fa-search-plus bigger-120"></i> Lihat Data
                            </button>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-2 table-responsive">
                        <table id="soal-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Isi</th>
                                    <th>Materi</th>
                                    <th>Opsi A</th>
                                    <th>Opsi B</th>
                                    <th>Opsi C</th>
                                    <th>Opsi D</th>
                                    <th>Opsi E</th>
                                    <th width="5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
<?php
load_js(array(
    'theme/aceadmin/assets/js/bootbox.min.js',
    'theme/aceadmin/assets/js/dataTables/jquery.dataTables.js',
    'theme/aceadmin/assets/js/dataTables/jquery.dataTables.bootstrap.js',
));
?> 
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    let soalTable;
    $(document).ready(function () {
        $("#file").ace_file_input({
            no_file: 'Pilih File. . .',
            no_icon: 'fa fa-file-excel-o',
            icon_remove: 'fa fa-times',
            btn_choose: 'Pilih',
            btn_change: 'Ubah',
            onchange: null,
            allowExt: ["xls","xlsx"],
            maxSize: 5100000 //10.1 Mb
        }).on('file.error.ace', function(ev, info) {
            if(info.error_count['ext']) myNotif('Peringatan!', 'Format file harus berupa excel', 3);
            if(info.error_count['size']) myNotif('Peringatan!', 'Ukuran file maksimal 5 MB', 3);
        });
        load_table();
        $("#soal-spin").hide();
    });
</script>
<script type="text/javascript">
    function load_data() {
        $("#soal-spin").show();
        $.ajax({
            url: module + "/ajax/type/table/source/soal",
            type: "POST",
            dataType: "json",
            data: { id: $("#id").val() },
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
                $("#soal-spin").hide();
            },
            error: function (xhr, ajax, err) {
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function load_table() {
        soalTable = $("#soal-table")
        .dataTable({
            bScrollCollapse: true,
            bAutoWidth: false,
            aaSorting: [],
            aoColumnDefs: [
                {bSortable: false, aTargets: [0,8]},
                {bSearchable: false, aTargets: [0,8]},
                {sClass: "center", aTargets: [1,2,3,4,5,6,7]},
                {sClass: "center nowrap", aTargets: [0,8]}
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
</script>                  
