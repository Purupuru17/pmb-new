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
                        <?= $title[0] ?>
                    </h5>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-2 table-responsive">
                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode</th>
                                    <th>Program Studi</th>
                                    <th>Fakultas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;
                                foreach ($list['data'] as $row) {
                            ?>
                                <tr>
                                    <td><?= ctk($no); ?></td>
                                    <td><?= ctk($row['id_prodi']); ?></td>
                                    <td><?= ctk($row['nama_prodi']); ?></td>
                                    <td><?= ctk($row['fakultas']); ?></td>
                                    <td nowrap>
                                        <div class="action-buttons">
                                            <a href="<?= site_url($module .'/add/'. encode($row['id_prodi'])) ?>" class="tooltip-error btn btn-white btn-danger btn-sm" data-rel="tooltip" title="Buat NIM">
                                                <span class="red bigger-110"><i class="ace-icon fa fa-pencil-square-o"></i> Buat NIM</span>
                                            </a>
                                            <a href="<?= site_url($module .'/detail/'. encode($row['id_prodi'])) ?>" class="tooltip-success btn btn-white btn-success btn-sm" data-rel="tooltip" title="Lihat Mahasiswa">
                                                <span class="green"><i class="ace-icon fa fa-external-link bigger-130"></i></span>
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
                {
                    bSortable: false,
                    aTargets: [0,4]
                },
                {
                    bSearchable: false,
                    aTargets: [0,4]
                },
                {"sClass": "center", aTargets: [0,1,2,3,4]}
            ],
            oLanguage: {
                "sSearch": "Cari : ",
                "sInfoEmpty": "Menampilkan dari 0 sampai 0 dari total 0 data",
                "sInfo": "Menampilkan dari _START_ sampai _END_ dari total _TOTAL_ data",
                "sLengthMenu": "_MENU_ data per halaman",
                "sZeroRecords": "Maaf tidak ada data yang ditemukan",
                "sInfoFiltered": "(Menyaring dari _MAX_ total data)"
            }
        });
        table.fnAdjustColumnSizing();
    });
</script>                  
