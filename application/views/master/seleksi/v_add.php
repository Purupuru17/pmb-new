<?php $this->load->view('sistem/v_breadcrumb'); ?>
<style>
    .profile-info-name{
        width: 160px;
    }
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
            <h4 class="blue bolder bigger-150">
                <?= $detail['nama_mhs'] ?>
            </h4>
            <div id="user-profile-1" class="user-profile row">
                <div class="col-xs-12 col-sm-6">
                    <div class="profile-user-info profile-user-info-striped">
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Kode Registrasi </div>
                            <div class="profile-info-value">
                                <span class="bolder bigger-110">#<?= $detail['kode_reg'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> NIM </div>
                            <div class="profile-info-value">
                                <span class="bolder green"><?= $detail['nim'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Angkatan </div>
                            <div class="profile-info-value">
                                <span><?= $detail['angkatan'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Status </div>
                            <div class="profile-info-value">
                                <?= st_mhs($detail['status_mhs']) ?>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Jalur Pendaftaran </div>
                            <div class="profile-info-value">
                                <span class="bolder"><?= $detail['jalur_mhs'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Program Studi </div>
                            <?php
                                $opsi_prodi = explode('|', $detail['opsi_prodi']);
                            ?>
                            <div class="profile-info-value">
                                <span class="bolder blue bigger-110"><?= element('nama_prodi', $detail, '') ?></span><br/>
                                Pilihan 2 : <small class="bolder"><?= element(0, $opsi_prodi, '') ?></small><br/>
                                Pilihan 3 : <small class="bolder grey"><?= element(1, $opsi_prodi, '') ?></small>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Nama Lengkap </div>
                            <div class="profile-info-value">
                                <span class="bolder blue bigger-110"><?= $detail['nama_mhs'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> NIK </div>
                            <div class="profile-info-value">
                                <span class="bolder blue"><?= $detail['nik'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Ibu Kandung </div>
                            <div class="profile-info-value">
                                <span class="bolder blue"><?= $detail['ibu_kandung'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Tempat Lahir </div>
                            <div class="profile-info-value">
                                <span class="bolder blue"><?= $detail['tempat_lahir'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Tanggal Lahir </div>
                            <div class="profile-info-value">
                                <span class="bolder blue">
                                <?= format_date($detail['tgl_lahir'],1) ?>
                                </span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Jenis Kelamin </div>
                            <div class="profile-info-value">
                                <span class="bolder green"><?= $detail['kelamin_mhs'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Agama </div>
                            <div class="profile-info-value">
                                <span class="bolder green"><?= $detail['agama'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Telepon </div>
                            <div class="profile-info-value">
                                <span><?= $detail['telepon_mhs'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Email </div>
                            <div class="profile-info-value">
                                <span><?= $detail['email_mhs'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Alamat di Sorong </div>
                            <div class="profile-info-value">
                                <span><?= $detail['alamat_mhs'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Alamat KTP </div>
                            <div class="profile-info-value">
                                <span>Jln. <?= $detail['jalan'] ?>
                                    RT <?= $detail['rt'] ?> RW <?= $detail['rw'] ?>
                                </span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Kelurahan </div>
                            <div class="profile-info-value">
                                <span><?= $detail['kelurahan'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Kecamatan </div>
                            <div class="profile-info-value">
                                <span><?= $detail['kecamatan'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Kota/Kabupaten </div>
                            <div class="profile-info-value">
                                <span><?= $detail['kabupaten'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> NISN </div>
                            <div class="profile-info-value">
                                <span><?= $detail['nisn'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Asal Sekolah </div>
                            <div class="profile-info-value">
                                <span><?= $detail['sekolah'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> NPSN </div>
                            <div class="profile-info-value">
                                <span><?= $detail['npsn'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Tanggal Daftar </div>
                            <div class="profile-info-value">
                                <span><?= format_date($detail['tgl_daftar'], 0) ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row ">
                            <div class="profile-info-name">Log :</div>
                            <div class="profile-info-value">
                                <span>
                                    <span class="blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $detail['log_mhs'] ?></span><br/>
                                    <span class="orange"><i class="ace-icon fa fa-pencil-square-o"></i> &nbsp;&nbsp;<?= selisih_wkt($detail['update_mhs'],0) ?></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="space-4"></div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <form id="validation-form" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input value="<?= encode($detail['id_mhs']) ?>" type="hidden" name="mid" id="mid">
                        
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-4 no-padding-right">Program Studi :</label>
                            <div class="col-xs-12 col-sm-7">
                                <div class="clearfix">
                                    <select class="select2 width-100 bolder" name="prodi" id="prodi" data-placeholder="-------> Pilih Program Studi <-------">
                                        <option value=""> </option>
                                        <?php
                                        foreach ($prodi['data'] as $val) {
                                            $selected = ($detail['prodi_id'] == $val['id_prodi']) ? 'selected' : '';
                                            $view = $val['nama_prodi'].' - '.$val['kode_prodi'];
                                            echo '<option value="'.encode($val['id_prodi']).'" '.$selected.'>' . $view . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-4 no-padding-right">Angkatan :</label>
                            <div class="col-xs-12 col-sm-4">
                                <div class="clearfix">
                                    <select class="select2 width-100" name="tahun" id="tahun" data-placeholder="---> Pilih Tahun <---">
                                        <option value=""> </option>
                                        <?php
                                        foreach (load_array('tahun') as $val) {
                                            $selected = ($detail['angkatan'] == $val) ? 'selected' : '';
                                            echo '<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-4 no-padding-right">NIM Tersimpan :</label>
                            <div class="col-xs-12 col-sm-7">
                                <div class="clearfix">
                                    <input value="<?= ctk($detail['nim']) ?>" readonly="" type="text" class="bolder col-sm-6 col-xs-12" placeholder="Nomor Induk Mahasiswa" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-4 no-padding-right">NIM Generate :</label>
                            <div class="col-xs-12 col-sm-7">
                                <div class="clearfix">
                                    <input value="<?= ctk($detail['nim']) ?>" type="text" name="nim" id="nim" class="bolder blue col-sm-6 col-xs-12" placeholder="Nomor Induk Mahasiswa" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-4 no-padding-right">Periode :</label>
                            <div class="col-xs-12 col-sm-5">
                                <div class="clearfix">
                                    <select class="select2 width-100" name="periode" id="periode" data-placeholder="---> Pilih Periode <---">
                                        <option value=""> </option>
                                        <?php
                                        foreach (load_array('periode') as $val) {
                                            $tahun = substr($val,0,4);
                                            $tipe = substr($val,4,1);
                                            $semester = $tahun.'/'.($tahun + 1);
                                            switch ($tipe) {
                                                case '1':
                                                    $semester .= ' Ganjil';
                                                    break;
                                                case '2':
                                                    $semester .= ' Genap';
                                                    break;
                                                default:
                                                    $semester .= ' Pendek';
                                                    break;
                                            }
                                            $selected = ($this->config->item('app.periode') == $val) ? 'selected' : '';
                                            echo '<option value="'.$val.'" '.$selected.'>'.$semester.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jenis Daftar :</label>
                            <div class="col-xs-12 col-sm-5">
                                <div class="clearfix">
                                    <select class="select2 width-100" name="jenis" id="jenis" data-placeholder="---> Pilih Jenis <---">
                                        <option value=""> </option>
                                        <?php
                                        foreach (load_array('jenis_daftar') as $val) {
                                            $selected = ($this->config->item('app.jenis_daftar') == $val['id']) ? 'selected' : '';
                                            echo '<option value="'.$val['id'].'" '.$selected.'>'.$val['text'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-4 no-padding-right">Tanggal Masuk :</label>
                            <div class="col-xs-12 col-sm-7">
                                <div class="clearfix">
                                    <input value="<?= $this->config->item('app.tanggal') ?>" type="text" name="tanggal" id="tanggal" class="col-sm-6 col-xs-12 date-picker" placeholder="Tanggal Masuk" />
                                </div>
                            </div>
                        </div>
                        <div class="clearfix form-actions">
                            <div class="col-md-offset-4 col-md-8">
                                <button class="btn btn-danger btn-white btn-round" name="btn-nim" id="btn-nim" type="button">
                                    <i class="ace-icon fa fa-check-square-o"></i>
                                    Generate NIM
                                </button>
                                <a target="_blank" href="<?= site_url('master/daftar/edit/'. encode($detail['id_mhs'])) ?>" class="btn btn-warning btn-white btn-round btn-mini" >
                                    <i class="ace-icon fa fa-pencil-square-o"></i>
                                    Ubah Data
                                </a>
                            </div>
                        </div>
                    </form>
                    <div class="space-4"></div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="profile-user-info profile-user-info-striped">
                        <div class="profile-info-row">
                            <div class="profile-info-name"> ID Biodata </div>
                            <div class="profile-info-value">
                                <span id="span-bio" class="bolder"><?= $detail['id_bio'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> ID Histori Pendidikan </div>
                            <div class="profile-info-value">
                                <span id="span-reg" class="bolder"><?= $detail['id_reg'] ?></span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> </div>
                            <div class="profile-info-value">
                                <button onclick="load_mhs()" id="btn-feeder" class="btn btn-block btn-bold btn-default btn-white">
                                    <i class="ace-icon fa fa-search-plus bigger-110"></i>
                                    <span class="">Cek Data PDDikti</span>
                                </button>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> </div>
                            <div class="profile-info-value">
                                <button id="btn-bio" class="btn btn-bold btn-info btn-white btn-block" type="button">
                                <i class="ace-icon fa fa-send bigger-110"></i>
                                    <span class="">Insert Biodata</span>
                            </button>
                        </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> </div>
                            <div class="profile-info-value">
                                <button id="btn-reg" class="btn btn-bold btn-success btn-white btn-block" type="button">
                                    <i class="ace-icon fa fa-send bigger-110"></i>
                                    <span class="">Insert NIM (Riwayat Pendidikan)</span>
                                </button>
                            </div>
                        </div>
                        <div class="profile-info-row hide">
                            <div class="profile-info-name"> </div>
                            <div class="profile-info-value">
                                <button id="btn-del" class="btn btn-bold btn-danger btn-white btn-block" type="button">
                                    <i class="ace-icon fa fa-trash-o bigger-110"></i>
                                    <span class="">Delete NIM Feeder</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="space-4"></div>
                    <div class="profile-user-info profile-user-info-striped">
                        <div class="profile-info-row">
                            <div class="profile-info-name" style="width: 10px"></div>
                            <div class="profile-info-value">
                                <p id="span-mhs"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
<?php
load_js(array(
    "theme/aceadmin/assets/js/jquery.validate.js",
    "theme/aceadmin/assets/js/select2.js",
    "theme/aceadmin/assets/js/bootbox.min.js",
    "theme/aceadmin/assets/js/date-time/bootstrap-datepicker.js",
));
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    
    $(document).ready(function () {
        $(".select2").select2({allowClear: true});
        $(".select2-chosen").addClass("center");
        $(".date-picker").datepicker({
            format: 'yyyy-mm-dd', autoclose: true,
            todayHighlight: true,clearBtn: true
        });
    });
    $("#btn-bio").click(function () {
        insert_bio();
        $("#btn-bio").attr('disabled','disabled');
    });
    $("#btn-nim").click(function () {
        generate_nim();
        $("#btn-nim").attr('disabled','disabled');
    });
    $("#btn-reg").click(function () {
        insert_nim();
        $("#btn-reg").attr('disabled','disabled');
    });
    $("#btn-del").click(function () {
        delete_nim();
        $("#btn-del").attr('disabled','disabled');
    });
</script>
<script type="text/javascript">
    function load_mhs() {
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
        $.ajax({
            url: module + "/ajax/type/table/source/feeder",
            type: "POST",
            dataType: "json",
            data: {
                id : $("#mid").val(),
                nim: $("#nim").val()
            },
            success: function(rs) {
                progress.modal("hide");
                if (rs.status) {
                    $("#span-bio").html(rs.data.id_mahasiswa);
                    $("#span-reg").html(rs.data.id_registrasi_mahasiswa);
                    
                    myNotif('Informasi', rs.msg, 1);
                } else {
                    $("#span-mhs").html(rs.msg);
                    myNotif('Peringatan', rs.msg, 2);
                }
                show_data(rs.data);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                progress.modal("hide");
                myNotif('Error', 'Kesalahan Jaringan', 3);
            }
        });
    }
    function insert_bio() {
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
        $.ajax({
            url: module + "_do/ajax/type/action/source/insert",
            dataType: "json",
            type: "POST",
            data: {
                id: $("#mid").val()
            },
            success: function (rs) {
                progress.modal("hide");
                if (rs.status) {
                    $("#span-bio").html(rs.data.id_mahasiswa);
                    show_data(rs.data);
                    
                    myNotif('Informasi', rs.msg, 1);
                } else {
                    myNotif('Peringatan', rs.msg, 2);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                progress.modal("hide");
                myNotif('Error', 'Kesalahan Jaringan', 3);
            }
        });
    }
    function insert_nim() {
        var nim = $("#nim").val();
        if(nim === '' || nim === null){
            myNotif('Peringatan', 'Generate NIM mahasiswa', 2);
            return;
        }
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
        $.ajax({
            url: module + "_do/ajax/type/action/source/update",
            dataType: "json",
            type: "POST",
            data: $("#validation-form").serialize(),
            success: function (rs) {
                progress.modal("hide");
                if (rs.status) {
                    $("#span-reg").html(rs.data.id_registrasi_mahasiswa);
                    myNotif('Informasi', rs.msg, 1);
                } else {
                    myNotif('Peringatan', rs.msg, 2);
                }
                show_data(rs.data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                progress.modal("hide");
                myNotif('Error', 'Kesalahan Jaringan', 3);
            }
        });
    }
    function delete_nim() {
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
        $.ajax({
            url: module + "_do/ajax/type/action/source/delete",
            dataType: "json",
            type: "POST",
            data: $("#validation-form").serialize(),
            success: function (rs) {
                progress.modal("hide");
                if (rs.status) {
                    $("#span-reg").html('');
                    myNotif('Informasi', rs.msg, 1);
                } else {
                    myNotif('Peringatan', rs.msg, 2);
                }
                show_data(rs.data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                progress.modal("hide");
                myNotif('Error', 'Kesalahan Jaringan', 3);
            }
        });
    }
    function generate_nim() {
        var id = $("select#prodi").val();
        if (id === '') {
            $("select#prodi").select2('open');
            myNotif('Peringatan', 'Pilih Program Studi', 2);
            return;
        }
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
        $.ajax({
            url: module + "/ajax/type/table/source/nim",
            type: "POST",
            dataType: "json",
            data: $("#validation-form").serialize(),
            success: function(rs) {
                progress.modal("hide");
                if (rs.status) {
                    myNotif('Informasi', rs.msg, 1);
                } else {
                    myNotif('Peringatan', rs.msg, 2);
                }
                $("input#nim").val(rs.nim);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                progress.modal("hide");
                myNotif('Error', 'Kesalahan Jaringan', 3);
            }
        });
    }
    function show_data(data){
        var str = '';
        $.each(data, function(key, value) {
            str += key + ' : <b>' + value + '</b><br>';
        });
        $("#span-mhs").html(str);
    }
</script>
