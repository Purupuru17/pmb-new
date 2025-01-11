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
                        <i class="ace-icon fa fa-list"></i>
                        <?= $title[1] ?>
                    </h5>
                    <div class="widget-toolbar no-border">
                        <div class="btn-group btn-overlap">
                            <a href="<?= site_url($module.'/add') ?>" class="btn btn-white btn-primary btn-bold <?= ($admin != '1') ? 'hide' : '' ?>" style="margin-right: 10px;">
                                <i class="fa fa-plus-square bigger-110 blue"></i> Tambah Data
                            </a>
                            <button id="delete-all" class="btn btn-white btn-danger btn-bold">
                                <i class="fa fa-trash-o bigger-110 red"></i> Hapus Data
                            </button>
                        </div>
                    </div>
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
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Judul</th>
                                    <th width="50%">Pesan</th>
                                    <th>Waktu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;
                                foreach ($notif['data'] as $row) {
                            ?>
                                <tr class="<?= ($row['status_notif'] == '0') ? 'un-read' : '' ?>">
                                    <td>
                                        <label class="pos-rel">
                                            <input value="<?= encode($row['id_notif']) ?>" type="checkbox" class="ace" id="checkboxData" name="dataCheckbox[]" />
                                            <span class="lbl"></span>
                                        </label>
                                    </td>
                                    <td><?= ctk($no); ?></td>
                                    <td><?= ctk($row['fullname']); ?></td>
                                    <td><?= ctk($row['subject_notif']); ?></td>
                                    <td><?= ctk($row['msg_notif']); ?></td>
                                    <td><?= selisih_wkt($row['buat_notif']) ?></td> 
                                    <td nowrap>
                                        <div class="action-buttons">
                                            <?php
                                                if($admin == '1'){
                                            ?>
                                                <a href="<?= site_url($row['link_notif']) ?>" name="<?= encode($row['id_notif']) ?>" class="tooltip-warning btn btn-white btn-warning btn-sm" id="link-btn" data-rel="tooltip" title="Link">
                                                    <span class="orange">
                                                        <i class="ace-icon fa fa-external-link bigger-130"></i>
                                                    </span>
                                                </a>
                                            <?php } ?>
                                            <a href="#" name="<?= encode($row['id_notif']) ?>" itemprop="<?= ctk($row['fullname'].' - '.$row['subject_notif']); ?>" id="delete-btn" class="tooltip-error btn btn-white btn-danger btn-sm" data-rel="tooltip" title="Hapus Data">
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
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
<?php
load_js(array(
    'backend/assets/js/dataTables/jquery.dataTables.js',
    'backend/assets/js/dataTables/jquery.dataTables.bootstrap.js',
    'backend/assets/js/bootbox.min.js'
));
?>
<script type="text/javascript">
    var table;
    var module = "<?= site_url($module); ?>";
    
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
                    window.location.replace("<?= site_url($module . '/delete/'); ?>" + id);
                }
            }
        });
    });
    $(document).on('click', '#link-btn', function(){
        var id = $(this).attr("name");
        notif_user(id);
    });
    $('#id-toggle-all').click(function(e) {
        var $row = $("tr > td:first-child input[type='checkbox']");
        if($(this).hasClass('checkedAll')) {
            $row.prop('checked', false).closest('tr').removeClass('danger');   
            $(this).removeClass('checkedAll');
        } else {
            $row.prop('checked', true).closest('tr').addClass('danger');
            $(this).addClass('checkedAll');
        }
    });
    $('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
        var $row = $(this).closest('tr');
        if(this.checked) $row.addClass('danger');
        else $row.removeClass('danger');
    });
    $('#delete-all').click(function(e) {
        var rowcollection = table.$("#checkboxData:checked", {"page": "all"});
        var id = "";
        rowcollection.each(function(index, elem) {
            var checkbox_value = $(elem).val();
            id += checkbox_value + ',';
        });
        console.log(id);
        if(id === ""){
            myNotif('Peringatan', 'Tidak ada data yang dipilih', 3);
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
                    deleteAll(id);
                }
            }
        });
    });
</script>
<script type="text/javascript">
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
                    aTargets: [0,1,6]
                },
                {
                    bSearchable: false,
                    aTargets: [0,1,6]
                },
                {
                    sClass: "center", aTargets: [0,1,2,3,4,5,6]
                }
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
    
    function deleteAll(id){
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i>' + 
                    ' Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' + 
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({
            title: title,
            message: msg,
            closeButton: false
        });
        $.ajax({
            url: module + "/ajax/type/action/source/delete",
            dataType: "json",
            type: "POST",
            data: {
                id: id
            },
            success: function(rs) {
                progress.modal("hide");
                if (rs.status) {
                    myNotif('Informasi', rs.msg, 1);
                    setInterval(function(){ window.location.replace(module); }, 2000);
                }else{
                    myNotif('Peringatan', rs.msg, 3);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                myNotif('Peringatan', 'Error sangat menghapus', 3);
                progress.modal("hide");
            }
        });
    }
</script>                  
