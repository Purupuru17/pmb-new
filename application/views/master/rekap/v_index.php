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
            <form id="search-form" action="<?= site_url($module.'/export') ?>" name="form" class="form-horizontal" method="POST">
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
                                foreach (load_array('tahun') as $val) {
                                    $selected = (date('Y') == $val) ? 'selected' : '';
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
                <div class="form-group hide">
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
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Jenis Berkas :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="berkas" id="berkas" data-placeholder="-------> Pilih Jenis Berkas <-------">
                                <option value=""> </option>
                                <?php
                                foreach ($berkas['data'] as $val) {
                                    echo '<option value="'.encode($val['id_upload']).'">'.$val['nama_upload'].'</option>';
                                }
                                ?>
                            </select>
                            <input type="hidden" name="title" id="title"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Validasi Berkas :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="valid" id="valid" data-placeholder="----> Pilih Opsi <----">
                                <option value=""> </option>
                                <option value="0"> PENDING </option>
                                <option value="1"> VALID </option>
                                <option value="2"> REUPLOAD </option>
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
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-2 table-responsive">
                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mahasiswa</th>
                                    <th>Pendaftaran</th>
                                    <th>Status</th>
                                    <th>Berkas</th>
                                    <th>File</th>
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
<div id="modal-edit" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button onclick="reset_all()" type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                    <div align="center" class="bolder bigger-110">Validasi Berkas</div>
                </div>
            </div>
            <div class="modal-body padding-10">
                <form id="validation-form" action="<?= site_url($action); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="berkasid" id="berkasid" class="reset"/>                    
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-4 no-padding-right">Nama Berkas :</label>
                        <div class="col-xs-12 col-sm-6">
                            <div class="clearfix">
                                <label id="txt-berkas" class="bolder bigger-120"></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-4 no-padding-right">Status :</label>
                        <div class="col-xs-12 col-sm-8">
                            <div class="clearfix">
                                <label class="control-label">
                                    <input name="status" value="1" type="radio" class="ace" />
                                    <span class="lbl"> VALID </span>
                                </label>&nbsp;&nbsp;&nbsp;
                                <label class="control-label">
                                    <input name="status" value="2" type="radio" class="ace" />
                                    <span class="lbl"> REUPLOAD </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-sm-12">
                            <button  onclick="reset_all()" class="btn" type="button" data-dismiss="modal">
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
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<?php
    load_js(array(
        'theme/aceadmin/assets/js/dataTables/jquery.dataTables.js',
        'theme/aceadmin/assets/js/dataTables/jquery.dataTables.bootstrap.js',
        'theme/aceadmin/assets/js/select2.js',
        'theme/aceadmin/assets/js/jquery.validate.js'
    ));
?>
<script type="text/javascript">
    var table;
    const module = "<?= site_url($module) ?>";
    
    $(document).ready(function () {
        load_table();
        $('[data-rel="tooltip"]').tooltip({placement: 'top'});
        $(".select2").select2({allowClear: true});
        $(".select2-chosen").addClass("center");
    });
    $(document.body).on("click", "#edit-btn", function(event) {
        var nama = $(this).attr("itemprop");
        var id = $(this).attr("itemid");
        
        $("#berkasid").val(id);
        $("#txt-berkas").html(nama);
        $("#modal-edit").modal({
            backdrop: 'static',
            keyboard: false
        });
    });
    function reset_all() {
        $(".reset").val('');
        $(".clear").html('');
        $("input[type=radio]").prop('checked', false);
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
                url: module + "/ajax/type/table/source/rekap",
                type: "POST",
                dataType: "json",
                data: function (val) {
                    val.prodi = $("#prodi").val();
                    val.tahun = $("#tahun").val();
                    val.jalur = $("#jalur").val();
                    val.status = $("#status").val();
                    val.berkas = $("#berkas").val();
                    val.valid = $("#valid").val();
                }
            },
            initComplete: function () {
                this.api().columns.adjust();
            },
            aaSorting: [],
            aoColumnDefs: [
                {bSortable: false, aTargets: [0,6]},
                {bSearchable: false, aTargets: [0,6]},
                {sClass: "center", aTargets: [0, 1, 2, 3, 4, 5]},
                {sClass: "center nowrap", aTargets: [6]}
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
    }
    $("#btn-search").click(function () { //button filter event click
        table.fnDraw();  //just reload table
    });
    $("#berkas").change(function () {
        let data = $("#berkas").select2('data');
        $("#title").val(data.text);
    });
    $("#validation-form").validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            status: {
                required: true
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
