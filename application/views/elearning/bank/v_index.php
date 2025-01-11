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
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Dosen :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input value="<?= encode($this->session->userdata('did')); ?>" type="hidden" name="dosen" id="dosen" class="width-100"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Jenis :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="jenis" id="jenis" data-placeholder="---> Pilih Jenis <---">
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
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-6">
                        <button class="btn btn-primary btn-white btn-bold" name="cari" id="btn-search" type="button">
                            <i class="ace-icon fa fa-search-plus"></i>
                            Pencarian
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xs-12">
            <div class="widget-box widget-color-blue2">
                <div class="widget-header">
                    <h5 class="widget-title bigger lighter">
                        <i class="ace-icon fa fa-list"></i>
                        <?= $title[1] ?>
                    </h5>
                    <div class="widget-toolbar no-border">
                        <div class="btn-group btn-overlap">
                            <a href="<?= site_url($module.'/add') ?>" class="btn btn-white btn-primary btn-bold">
                                <i class="fa fa-plus-square bigger-110 blue"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-2 table-responsive">
                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Jumlah Soal</th>
                                    <th>Dosen</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
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
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    let table;
    $(document).ready(function () {
        $('[data-rel="tooltip"]').tooltip({placement: 'top'});
        $(".select2").select2({allowClear: true});
        $(".select2-chosen").addClass("center");
        load_table();
        get_select();
    });
    $(document.body).on("click", "#delete-btn", function(event) {
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
    $("#btn-search").click(function () {
        table.fnDraw();
    });
</script>
<script type="text/javascript">
    function get_select() {
        $("#dosen").select2({
            placeholder: "-------> Pilih Dosen <-------",
            minimumInputLength: 3,
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
                url: module + "/ajax/type/table/source/index",
                type: "POST",
                dataType: "json",
                data: function (val) {
                    val.dosen = $("#dosen").val();
                    val.jenis = $("#jenis").val();
                }
            },
            aaSorting: [],
            aoColumnDefs: [
                    {bSortable: false, aTargets: [0,5]},
                {bSearchable: false, aTargets: [0,5]},
                {sClass: "center", aTargets: [0, 1, 2, 3, 4]},
                {sClass: "center nowrap", aTargets: [5]}
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
</script>                  
