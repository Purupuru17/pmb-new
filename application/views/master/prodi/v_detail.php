<?php
$this->load->view('sistem/v_breadcrumb');
?>
<div class="page-content">
    <div class="page-header">
        <h1 class="green">
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
        <div class="col-xs-12 col-sm-12">
            <form id="validation-form" action="<?= site_url($module.'/export/'.encode($detail['id_prodi'])) ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Status Mahasiswa :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="status" id="status" data-placeholder="-----> Pilih Status Mahasiswa <-----">
                                <option value=""> </option>
                                <?php
                                foreach ($status as $val) {
                                    echo '<option value="'.$val.'">'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Periode Angkatan :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="angkatan" id="angkatan" data-placeholder="-----> Pilih Angkatan <-----">
                                <option value=""> </option>
                                <?php
                                $awal = intval(date('Y')) - 1;
                                for($thn = $awal; $thn <= $awal + 5; $thn++) {
                                    echo '<option value="'.$thn.'">'.$thn.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                    <div class="col-md-offset-4 col-md-4">
                        <button class="btn btn-success" name="btn-excel" id="btn-excel" type="submit">
                            <i class="ace-icon fa fa-file-excel-o"></i>
                            Export Excel
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xs-12 col-sm-12">
            <div class="widget-box widget-color-green">
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
                                    <th>Angkatan</th>
                                    <th>NIM</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;
                                foreach ($daftar['data'] as $row) {
                            ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td><?= ctk($row['angkatan']); ?></td>
                                    <td><?= ctk($row['nim']); ?></td>
                                    <td><?= ctk($row['nik']); ?></td>
                                    <td><?= ctk($row['nama_mhs']); ?></td>
                                    <td><?= ctk($row['kelamin_mhs']); ?></td>
                                    <td><?= st_mhs($row['status_mhs']); ?></td>
                                    <td nowrap>
                                        <div class="action-buttons">
                                            <a href="<?= site_url('mhs/daftar/detail/'. encode($row['id_mhs'])) ?>" class="tooltip-info btn btn-white btn-info btn-sm" data-rel="tooltip" title="Lihat Data">
                                                <span class="blue"><i class="ace-icon fa fa-eye bigger-130"></i></span>
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
        'backend/assets/js/dataTables/jquery.dataTables.bootstrap.js',
        'backend/assets/js/bootbox.min.js',
        "backend/assets/js/jquery.validate.js",
        "backend/assets/js/select2.js"
    ));
?>
<script type="text/javascript">
    var table;
    $(document).ready(function() {
        $('[data-rel="tooltip"]').tooltip({placement: 'top'});
        $(".select2").select2({allowClear: true})
            .on('change', function() {
                $(this).closest('form').validate().element($(this));
        });
        table = $('#dynamic-table')
            .dataTable({
                bScrollCollapse: true,
                bAutoWidth: false,
                aaSorting: [],
                aoColumnDefs: [
                {
                    bSortable: false,
                    aTargets: [0,7]
                },
                {
                    bSearchable: false,
                    aTargets: [0,7]
                },
                {"sClass": "center", aTargets: [0,1,2,3,4,5,6,7]}
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
<script type="text/javascript">
    $('#validation-form').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            angkatan: {
                required: true
            },
            status: {
                required: true
            }
        },
        messages: {
            angkatan: {
                required: "Pilih Periode Angkatan terlebih dahulu"
            },
            status: {
                required: "Pilih Status Mahasiswa terlebih dahulu"
            }
        },
        highlight: function(e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
        },
        success: function(e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
            $(e).remove();
        },
        errorPlacement: function(error, element) {
            if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                var controls = element.closest('div[class*="col-"]');
                if (controls.find(':checkbox,:radio').length > 1)
                    controls.append(error);
                else
                    error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
            }
            else if (element.is('.select2')) {
                error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
            }
            else if (element.is('.chosen-select')) {
                error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
            }
            else
                error.insertAfter(element.parent());
        }

    });
</script> 
