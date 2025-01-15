<?php
$this->load->view('sistem/v_breadcrumb');
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            <?= $group['nama_group'] ?>
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
            <form id="validation-form" action="<?= site_url($action); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="group" value="<?= encode($group['id_group']) ?>">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">User Aplikasi :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input required="" type="hidden" name="user" id="user" class="width-100"/>
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
        <div class="col-xs-12">
            <div class="widget-box transparent">
                <div class="widget-body">
                    <div class="widget-main padding-2 table-responsive">
                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Lengkap</th>
                                    <th>Username</th>
                                    <th>Group Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;
                                foreach ($role['data'] as $row) {
                            ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td><?= $row['fullname']; ?></td>
                                    <td><?= $row['username']; ?></td>
                                    <td><?= $row['nama_group']; ?></td>
                                    <td nowrap>
                                        <div class="action-buttons">
                                            <a href="#" name="<?= encode($row['group_id']) ?>" itemprop="<?= encode($row['user_id']) ?>" id="delete-btn" class="tooltip-error btn btn-white btn-danger btn-sm btn-round" data-rel="tooltip" title="Hapus Data">
                                                <span class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i></span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                $no++;
                                }
                            ?>
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
    const module = "<?= site_url($module) ?>";
    let table;
    $(document).ready(function() {
        $('[data-rel="tooltip"]').tooltip({placement: 'top'});
        $(".select2").select2({allowClear: true});
        
        $("#user").select2({
            placeholder: "-------> Pilih User Aplikasi <-------",
            ajax: {
                url: module + "/ajax/type/list/source/user",
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
            }
        });
        table = $('#dynamic-table')
        .dataTable({
            bScrollCollapse: true,
            bAutoWidth: false,
            aaSorting: [],
            aoColumnDefs: [
                {bSortable: false, aTargets: [0,4]},
                {bSearchable: false, aTargets: [0,4]},
                {sClass: "center", aTargets: [1, 2, 3]},
                {sClass: "center nowrap", aTargets: [4]}
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
    });
    $(document.body).on("click", "#delete-btn", function(event) {
        var id = $(this).attr("name");
        var name = $(this).attr("itemprop");
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i>" + 
                " Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan menghapus data ? </p>";
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
                    window.location.replace(module + "/delete/" + id + "/" + name);
                }
            }
        });
    });
</script>                    
