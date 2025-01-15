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
        </div><!-- /.col -->
        <div class="col-xs-12">
            <form id="search-form" action="#" name="form" class="form-horizontal" method="POST">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-1 no-padding-right">Group :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="group" id="group" data-placeholder="-------> Pilih Group <-------">
                                <option value=""> </option>
                                <?php
                                foreach ($group['data'] as $val) {
                                    echo '<option value="'.$val['id_group'].'">'.$val['nama_group'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-1 col-md-4">
                        <button class="btn btn-primary btn-white" name="cari" id="btn-search" type="button">
                            <i class="ace-icon fa fa-search-plus"></i>
                            Pencarian
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xs-12">
            <div class="widget-box widget-color-red">
                <div class="widget-header">
                    <h5 class="widget-title bigger lighter">
                        <i class="ace-icon fa fa-list"></i>
                        <?= $title[1] ?>
                    </h5>
                    <div class="widget-toolbar no-border">
                        <div class="btn-group btn-overlap">
                            <a href="<?= site_url($module.'/add') ?>" class="btn btn-white btn-primary btn-bold">
                                <i class="fa fa-plus-square bigger-120 blue"></i> Tambah Data
                            </a>
                            <button onclick="confirm()" type="button" class="btn btn-white btn-danger btn-bold">
                                <i class="fa fa-refresh bigger-120 red"></i> Reset Log
                            </button>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-2 table-responsive">
                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Lengkap</th>
                                    <th>Username</th>
                                    <th>Group</th>
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                    <th width="20%">Terakhir Login</th>
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
<script type="text/javascript">
    var module = "<?= site_url($module) ?>";
    var table;
    
    $(document).ready(function () {
        load_table();
        $('[data-rel="tooltip"]').tooltip({placement: 'top'});
        $(".select2").select2({allowClear: true});
        $(".select2-chosen").addClass("center");
    });
    $(document.body).on("click", "#delete-btn", function(event) {
        var id = $(this).attr("name");
        var name = $(this).attr("itemprop");
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i>" + 
                " Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan menghapus data <br/><b>" + name + "</b> ? </p>";
        bootbox.confirm({
            title: title,
            message: msg, 
            buttons: {
                cancel: {
                    label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal",
                    className: "btn btn-sm"
                },
                confirm: {
                    label: "<i class='ace-icon fa fa-trash-o bigger-110'></i> Hapus",
                    className: "btn btn-sm btn-danger"
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
    function confirm(){
        bootbox.confirm({
            title: '<h4 class="red center"><i class="ace-icon fa fa-exclamation-triangle red"></i> Hapus beberapa hari yang lalu ? </h4>',
            message: '<input type="number" name="hari" id="hari" placeholder="Input hari" class="width-100">',
            buttons: {
              confirm: {label: '<i class="ace-icon fa fa-trash bigger-110"></i> Hapus',className: 'btn-danger btn-sm'
              },
              cancel: { label: '<i class="ace-icon fa fa-times bigger-110"></i> Batal', className: "btn-sm"
              }
            },
            callback: function(result) {
                if (result === true) {
                    var hari = $('#hari').val();
                    if(hari === "" || hari === null){
                        myNotif('Peringatan!','Kolom Input Hari harus diisi', 3);
                        return;
                    }else{
                        reset_log(hari);
                    }
                }
            }
        });
    }
    function reset_log(hari) {
        $.ajax({
            url: module + "/ajax/type/reset/source/log",
            type: "POST",
            dataType: "json",
            data: {hari: hari},
            success: function(rs) {
                if(rs.status){
                    myNotif('Informasi!','Log user berhasil dihapus. Total log ' + rs.data, 1);
                }else{
                    myNotif('Informasi!','Tidak ada log yang terhapus');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                myNotif('Peringatan!','Koneksi Gagal',3);
            }
        });
    }
    function load_table() {
        table = $("#dynamic-table")
            .dataTable({
                orderCellsTop: true,
                fixedHeader: true,
                bScrollCollapse: true,
                bAutoWidth: false,
                bProcessing: true,
                bServerSide: true,
                ajax: {
                    url: module + "/ajax/type/list/source/user",
                    type: "POST",
                    dataType: "json",
                    data: function (val) {
                        val.group = $("#group").val();
                    }
                },
                aaSorting: [],
                aoColumnDefs: [
                    {bSortable: false, aTargets: [0,7]},
                    {bSearchable: false, aTargets: [0,7]},
                    {sClass: "center", aTargets: [0, 1, 2, 3, 4, 5, 6]},
                    {sClass: "center nowrap", aTargets: [7]}
                ],
                oLanguage: {
                    sSearch: "Cari : ",
                    sInfoEmpty: "Menampilkan dari 0 sampai 0 dari total 0 data",
                    sInfo: "Menampilkan dari _START_ sampai _END_ dari total _TOTAL_ data",
                    sLengthMenu: "_MENU_ data per halaman",
                    sZeroRecords: "Maaf tidak ada data yang ditemukan",
                    sInfoFiltered: "(Menyaring dari _MAX_ total data)",
                    sProcessing: "<i class='fa fa-spinner fa-spin fa-fw fa-2x'></i> Loading . . ."
                }
            });
        table.fnAdjustColumnSizing();
    }
    $('#btn-search').click(function () { //button filter event click
        table.fnDraw();  //just reload table
    });
</script>