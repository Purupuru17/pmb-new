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
            <div class="widget-box widget-color-red">
                <div class="widget-header">
                    <h5 class="widget-title bigger lighter">
                        <i class="ace-icon fa fa-users"></i>
                        <?= $title[1] ?>
                    </h5>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-2 table-responsive">
                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Group</th>
                                    <th>Keterangan</th>
                                    <th>Super Admin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($group['data'] as $row) {
                                    ?>
                                    <tr>
                                        <td><?= $no; ?></td>
                                        <td><?= $row['nama_group']; ?></td>
                                        <td><?= $row['keterangan_group']; ?></td>
                                        <td><?= st_aktif($row['level'],1) ?></td>
                                        <td nowrap>
                                            <div class="action-buttons">
                                                <a href="<?= site_url($module . '/add/' . encode($row['id_group'])) ?>" class="tooltip-success btn btn-white btn-success btn-sm btn-round" data-rel="tooltip" title="Tambah Hak Akses">
                                                    <span class="green">
                                                        <i class="ace-icon fa fa-plus-circle bigger-130"></i>
                                                    </span>
                                                </a>
                                                <a href="<?= site_url($module . '/edit/' . encode($row['id_group'])) ?>" class="tooltip-warning btn btn-white btn-warning btn-sm btn-round" data-rel="tooltip" title="Ubah Hak Akses">
                                                    <span class="orange">
                                                        <i class="ace-icon fa fa-pencil-square-o bigger-130"></i>
                                                    </span>
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
    'backend/assets/js/dataTables/jquery.dataTables.bootstrap.js'
));
?>
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
                    { bSortable: false, aTargets: [0,4] },
                    { bSearchable: false, aTargets: [0,4] },
                    { sClass: "center", aTargets: [0,1,2,3,4] }
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
