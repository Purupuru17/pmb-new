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
            <form id="search-form" action="<?= site_url($module.'_do/export') ?>" name="form" class="form-horizontal" method="POST">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Program Studi :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <select class="select2 width-100" name="prodi" id="prodi" data-placeholder="-------> Pilih Program Studi <-------">
                                <option value=""> </option>
                                <?php
                                foreach ($prodi['data'] as $val) {
                                    echo '<option value="'.encode($val['id_prodi']).'">'.$val['nama_prodi'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Angkatan :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="tahun" id="tahun" data-placeholder="---> Pilih Tahun <---">
                                <option value=""> </option>
                                <?php
                                $tahun = (date('m') == '12') ? date('Y') + 1 : date('Y');
                                foreach (load_array('tahun') as $val) {
                                    $selected = ($tahun == $val) ? 'selected' : '';
                                    echo '<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Status :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="status" id="status" data-placeholder="----> Pilih Status <----">
                                <option value=""> </option>
                                <?php
                                foreach (load_array('status') as $val) {
                                    echo '<option value="'.$val.'">'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Jalur Pendaftaran :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="jalur" id="jalur" data-placeholder="-------> Pilih Jalur <-------">
                                <option value=""> </option>
                                <?php
                                foreach (load_array('jalur') as $val) {
                                    echo '<option value="'.$val.'">'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">KIP :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="kip" id="kip" data-placeholder="-------> Pilih Opsi <-------">
                                <option value=""> </option>
                                <?php
                                foreach (load_array('kip') as $val) {
                                    echo '<option value="'.$val.'">'.$val.'</option>';
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
                        &nbsp;&nbsp;
                        <button class="btn btn-success btn-white btn-bold" name="export" id="btn-export" type="submit">
                            <i class="ace-icon fa fa-file-excel-o"></i>
                            Export
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
                                    <th>Pendaftaran</th>
                                    <th>Nama Lengkap</th>
                                    <th>Program Studi</th>
                                    <th>NISN - KIP</th>
                                    <th>Data Diri</th>
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
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->

<?php
    load_js(array(
        'theme/aceadmin/assets/js/dataTables/jquery.dataTables.js',
        'theme/aceadmin/assets/js/dataTables/jquery.dataTables.bootstrap.js',
        'theme/aceadmin/assets/js/select2.js'
    ));
?>
<script type="text/javascript">
    var table;
    const module = "<?= site_url($module) ?>";
    
    $(document).ready(function () {
        $('[data-rel="tooltip"]').tooltip({placement: 'top'});
        $(".select2").select2({allowClear: true});
        $(".select2-chosen").addClass("center");
        
        load_table();
    });
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
                    url: module + "/ajax/type/table/source/mhs",
                    type: "POST",
                    dataType: "json",
                    data: function (val) {
                        val.prodi = $("#prodi").val();
                        val.tahun = $("#tahun").val();
                        val.jalur = $("#jalur").val();
                        val.status = $("#status").val();
                        val.kip = $("#kip").val();
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
                    sInfoFiltered: "(Menyaring dari _MAX_ total data)"
                }
            });
        table.fnAdjustColumnSizing();
    }
    $("#btn-search").click(function () { //button filter event click
        table.fnDraw();  //just reload table
    });
</script>                
