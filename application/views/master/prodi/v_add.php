<?php
$this->load->view('sistem/v_breadcrumb');
?>
<div class="page-content">
    <div class="page-header">
        <h1 class=red>
            <?= $title[1] ?>
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                <?= $title[0] ?>
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <?= $this->session->flashdata('notif'); ?>
        </div>
        <div class="col-xs-12 col-sm-12">
            <form id="validation-form" action="#" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Identitas NIM :</label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <input type="text" name="nim" id="nim" class="col-xs-12  col-sm-3" placeholder="Identitas NIM" />
                        </div>
                    </div>
                </div>
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
                        <button class="btn" type="reset">
                            <i class="ace-icon fa fa-undo bigger-110"></i>
                            Batal
                        </button>
                        &nbsp; &nbsp; &nbsp;
                        <button class="btn btn-danger" name="btn-nim" id="btn-nim" type="submit">
                            <i class="ace-icon fa fa-pencil"></i>
                            Buat NIM
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xs-12 col-sm-12">
            <div class="widget-box widget-color-red">
                <div class="widget-header">
                    <h5 class="widget-title bigger lighter">
                        <i class="ace-icon fa fa-pencil"></i>
                        <?= $title[0] ?>
                    </h5>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-2 table-responsive">
                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <label class="pos-rel">
                                            <input type="checkbox" class="ace" id="id-toggle-all" />
                                            <span class="lbl"></span>
                                        </label>
                                    </th>
                                    <th>Tanggal Daftar</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Agama</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;
                                foreach ($daftar['data'] as $row) {
                            ?>
                                <tr>
                                    <td>
                                        <label class="pos-rel">
                                            <input value="<?= encode($row['id_mhs']) ?>" type="checkbox" class="ace" id="checkboxData" name="dataCheckbox[]" />
                                            <span class="lbl"></span>
                                        </label>
                                    </td>
                                    <td><?= format_date($row['tgl_daftar'],2); ?></td>
                                    <td><?= ctk($row['nik']); ?></td>
                                    <td><?= ctk($row['nama_mhs']); ?></td>
                                    <td><?= ctk($row['kelamin_mhs']); ?></td>
                                    <td><?= ctk($row['agama']); ?></td>
                                    <td><?= status_mhs($row['status_mhs']); ?></td>
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
        "backend/assets/js/select2.js",
        "backend/assets/js/dataTables/jquery.dataTables.js",
        "backend/assets/js/dataTables/jquery.dataTables.bootstrap.js",
        "backend/assets/js/bootbox.min.js",
        "backend/assets/js/jquery.validate.js"
    ));
?>
<script type="text/javascript">
    var table;
    var module_ajax = "<?= site_url('non_login/ajax/routing'); ?>";
    
    $(document).ready(function() {
        $(".select2").select2({allowClear: true})
            .on('change', function() {
                $(this).closest('form').validate().element($(this));
        });
        table = $('#dynamic-table')
        .dataTable({
            pageLength: 100,
            aLengthMenu: [[50, 100, -1], [50, 100, "All"]],
            bScrollCollapse: true,
            bAutoWidth: false,
            aaSorting: [],
            aoColumnDefs: [
            {
                bSortable: false,
                aTargets: [0]
            },
            {
                bSearchable: false,
                aTargets: [0]
            },
            {"sClass": "center", aTargets: [0,1,2,3,4,5,6]} ],
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
    function saveAjax(id){
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i>' + 
                    ' Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' + 
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({
            title: title,
            message: msg,
            closeButton: false
        });
        
        var nim = $('input#nim').val();
        var status = $('select#status').val();
        var angkatan = $('select#angkatan').val();
        $.ajax({
            url: module_ajax + "/type/action/source/add_nim",
            dataType: "json",
            type: "POST",
            data: {
                id: id,
                nim: nim, 
                status: status,
                angkatan: angkatan
            },
            success: function(rs) {
                progress.modal("hide");
                if (rs.status) {
                    myNotif('Informasi', rs.msg, 1);
                }else{
                    myNotif('Peringatan', rs.msg, 3);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                myNotif('Peringatan', 'Gagal melakukan koneksi ke database', 3);
                progress.modal("hide");
            }
        });
    }
</script> 
<script type="text/javascript">
    $('#id-toggle-all').click(function(e) {
        var $row = $("tr > td:first-child input[type='checkbox']");
        if($(this).hasClass('checkedAll')) {
            $row.prop('checked', false).closest('tr').removeClass('success');   
            $(this).removeClass('checkedAll');
        } else {
            $row.prop('checked', true).closest('tr').addClass('success');
            $(this).addClass('checkedAll');
        }
    });
    $('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
        var $row = $(this).closest('tr');
        if(this.checked) $row.addClass('success');
        else $row.removeClass('success');
    });
    $('#validation-form').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            nim: {
                required: true,
                digits: true,
                minlength: 9
            },
            angkatan: {
                required: true
            },
            status: {
                required: true
            }
        },
        messages: {
            nim: {
                required: "Kolom Identitas NIM  harus diisi",
                digits: "Format harus berupa angka",
                minlength: "Panjang isi kolom minimal 9 karakter"
            },
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
        },
        submitHandler: function (form) {
            var rowcollection = table.$("#checkboxData:checked", {"page": "all"});
            var id = "";
            rowcollection.each(function(index, elem) {
                var checkbox_value = $(elem).val();
                id += checkbox_value + ',';
            });
            if(id === ""){
                myNotif('Peringatan', 'Tidak ada data Mahasiswa yang dipilih.', 3);
                return;
            }
            var title = '<h4 class="red center"><i class="ace-icon fa fa-exclamation-triangle red"></i>' + 
                    ' Peringatan !</h4>';
            var msg = '<p class="center grey bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' + 
                    ' Apakah anda yakin dengan data - data yang telah anda pilih ?  </p>';
            bootbox.confirm({
                title: title,
                message: msg, 
                buttons: {
                    cancel: {
                        label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal",
                        className: "btn btn-sm"
                    },
                    confirm: {
                        label: "<i class='ace-icon fa fa-check bigger-110'></i> Kirim",
                        className: "btn btn-sm btn-danger"
                    }
                },
                callback: function(result) {
                    if (result === true) {
                        saveAjax(id);
                    }
                }
            });
        }

    });
</script>               
