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
                                    <th>Nama Berkas</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Tipe</th>
                                    <th>Ukuran</th>
                                    <th>File</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;
                                foreach ($list['data'] as $row) {
                            ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td>
                                        <strong><?= ctk($row['nama_upload']); ?></strong>
                                    </td>
                                    <td><?= ctk($row['tipe_upload']); ?></td>
                                    <td><?= st_span($row['status_berkas']); ?></td>
                                    <td><?= ctk($row['tipe_berkas']); ?></td>
                                    <td><?= ctk($row['size_berkas']); ?></td>
                                    <td><?= st_file($row['file_berkas'],1) ?></td>
                                    <td nowrap>
                                        <div class="action-buttons <?= ($row['status_berkas'] == '1') ? 'hide' : '' ?>">
                                            <a href="<?= site_url($module . '/edit/' . encode($row['id_berkas'])) ?>" class="tooltip-warning btn btn-white btn-warning btn-round btn-sm" data-rel="tooltip" title="Ubah Data">
                                                <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span>
                                            </a>
                                            <a href="#" name="<?= encode($row['id_berkas']) ?>" itemprop="<?= $row['nama_upload'] ?>" id="delete-btn" class="tooltip-error btn btn-white btn-danger btn-round btn-sm" data-rel="tooltip" title="Hapus Data">
                                                <span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span>
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
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->

<?php
    load_js(array(
        'theme/aceadmin/assets/js/dataTables/jquery.dataTables.js',
        'theme/aceadmin/assets/js/dataTables/jquery.dataTables.bootstrap.js',
        'theme/aceadmin/assets/js/bootbox.min.js'
    ));
?>
<script type="text/javascript">
    $(document.body).on("click", "#delete-btn", function (event) {
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
            callback: function (result) {
                if (result === true) {
                    window.location.replace("<?= site_url($module . '/delete/'); ?>" + id);
                }
            }
        });
    });
</script>
<script type="text/javascript">
    var table;
    $(document).ready(function() {
        $('[data-rel="tooltip"]').tooltip({placement: 'top'});
        table = $('#dynamic-table')
        .dataTable({
            bScrollCollapse: true,
            bAutoWidth: false,
            aaSorting: [],
            aoColumnDefs: [
                { bSortable: false, aTargets: [0,7] },
                { bSearchable: false, aTargets: [0,7]},
                { sClass: "center", aTargets: [0,1,2,3,4,5,6,7]}
            ],
            oLanguage: {
                sSearch: "Cari : ",
                sInfoEmpty: "Menampilkan dari 0 sampai 0 dari total 0 data",
                sInfo: "Menampilkan dari _START_ sampai _END_ dari total _TOTAL_ data",
                sLengthMenu: "Menampilkan _MENU_ data per halaman",
                sZeroRecords: "Maaf tidak ada data yang ditemukan",
                sInfoFiltered: "(Menyaring dari _MAX_ total data)"
            }
        });
        table.fnAdjustColumnSizing();
    });
</script>                  
