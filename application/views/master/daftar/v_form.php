<?php
$this->load->view('sistem/v_breadcrumb');

$exp = explode('|', $edit['opsi_prodi']);
$opsi2 = element(0, $exp, ''); 
$opsi3 = element(1, $exp, ''); 

?>
<style>
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
            <h3 class="lighter center block blue"><?= $title[1] ?></h3>
            <form id="validation-form" action="<?= site_url($action); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Pilihan Program Studi :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <select class="select2 width-100" name="opsi1" id="opsi1" data-placeholder="-------> Pilihan Pertama <-------">
                                <option value=""> </option>
                                <?php
                                foreach ($prodi['data'] as $val) {
                                    $selected = ($edit['prodi_id'] == $val['id_prodi']) ? 'selected' : '';
                                    echo '<option value="' . encode($val['id_prodi']) . '" '.$selected.'>' . $val['nama_prodi'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group opsi">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <select class="select2 width-100" name="opsi2" id="opsi2" data-placeholder="-------> Pilihan Kedua <-------">
                                <option value=""> </option>
                                <?php
                                foreach ($prodi['data'] as $val) {
                                    $selected = ($opsi2 == $val['nama_prodi']) ? 'selected' : '';
                                    echo '<option value="' . ($val['nama_prodi']) . '" '.$selected.'>' . $val['nama_prodi'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group opsi">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <select class="select2 width-100" name="opsi3" id="opsi3" data-placeholder="-------> Pilihan Ketiga <-------">
                                <option value=""> </option>
                                <?php
                                foreach ($prodi['data'] as $val) {
                                    $selected = ($opsi3 == $val['nama_prodi']) ? 'selected' : '';
                                    echo '<option value="' . ($val['nama_prodi']) . '" '.$selected.'>' . $val['nama_prodi'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Periode Masuk :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="tahun" id="tahun" data-placeholder="---> Pilih Tahun <---">
                                <option value=""> </option>
                                <?php
                                foreach (load_array('tahun') as $val) {
                                    $selected = ($edit['angkatan'] == $val) ? 'selected' : '';
                                    echo '<option value="'.$val.'"  '.$selected.'>'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jalur Pendaftaran :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <select class="select2 width-100" name="jalur" id="jalur" data-placeholder="-------> Pilih Jalur Pendaftaran <-------">
                                <option value=""> </option>
                                <?php
                                foreach (load_array('jalur') as $val) {
                                    $selected = ($edit['jalur_mhs'] == $val) ? 'selected' : '';
                                    echo '<option value="'.$val.'"  '.$selected.'>'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Status Mahasiswa :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="status" id="status" data-placeholder="---> Pilih Status <---">
                                <option value=""> </option>
                                <?php
                                $st_nim = array('AKTIF');
                                $stt = empty($edit['nim']) ? array_diff(load_array('status'), $st_nim) : $st_nim;
                                foreach ($stt as $val) {
                                    $selected = ($edit['status_mhs'] == $val) ? 'selected' : '';
                                    echo '<option value="'.$val.'"  '.$selected.'>'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Atribut Diambil :</label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <textarea rows="3" cols="1" name="atribut" id="atribut" class="col-xs-12  col-sm-6" placeholder="Pengambilan Atribut Mahasiswa"><?= ctk($edit['atribut_mhs']) ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group <?= empty($edit['id_mhs']) ? 'hide' : '' ?>">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">KIP :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="kip" id="kip" data-placeholder="-----> Pilih Opsi  <-----">
                                <option value=""> </option>
                                <?php
                                foreach (load_array('kip') as $val) {
                                    $selected = ($edit['kip_mhs'] == $val) ? 'selected' : '';
                                    echo '<option value="'.$val.'"  '.$selected.'>'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="space-6"></div>
                <div class="social-or-login center">
                    <span class="bigger-110 bolder">Form Riwayat Pendidikan</span>
                </div>
                <div class="space-6"></div>
                
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">NISN :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['nisn'] ?>" type="text" name="nisn" id="nisn" class="col-xs-12  col-sm-6" placeholder="Nomor Induk Siswa Nasional" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-8 col-md-offset-4">
                        <span class="middle">
                            <a class="red" target="_blank" href="https://nisn.data.kemdikbud.go.id/index.php/Cindex/formcaribynama">
                                <i class="fa fa-external-link bigger-120"></i> Klik untuk cek NISN
                            </a>
                        </span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Asal Sekolah :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['sekolah'] ?>" type="text" name="sekolah" id="sekolah" class="col-xs-12  col-sm-6" placeholder="Asal Sekolah (SMA/MA/SMK)" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">NPSN :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['npsn'] ?>" type="text" name="npsn" id="npsn" class="col-xs-12  col-sm-6" placeholder="Nomor NPSN Sekolah" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-8 col-md-offset-4">
                        <span class="middle">
                            <a class="red" target="_blank" href="https://referensi.data.kemdikbud.go.id/index11.php">
                                <i class="fa fa-external-link bigger-120"></i> Klik untuk cek NPSN Sekolah
                            </a>
                        </span>
                    </span>
                </div>
                
                <div class="space-6"></div>
                <div class="social-or-login center">
                    <span class="bigger-110 bolder">Form Data Diri</span>
                </div>
                <div class="space-6"></div>
                
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">NIK :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['nik'] ?>" type="text" name="nik" id="nik" class="col-xs-12  col-sm-6" placeholder="Nomor Induk Kependudukan" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-8 col-md-offset-4">
                        <span class="middle blue">* NIK harus sesuai Kartu Keluarga</span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Nama Lengkap :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['nama_mhs'] ?>" type="text" name="nama" id="nama" class="col-xs-12  col-sm-6" placeholder="Nama Lengkap" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-8 col-md-offset-4">
                        <span class="middle blue">* Nama harus sesuai IJAZAH</span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Ibu Kandung :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['ibu_kandung'] ?>" type="text" name="ibu" id="ibu" class="col-xs-12  col-sm-6" placeholder="Nama Ibu Kandung" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Tempat Lahir :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input value="<?= $edit['tempat_lahir'] ?>" type="text" name="tempat" id="tempat" class="col-xs-12  col-sm-6" placeholder="Tempat Lahir" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Tanggal Lahir :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input value="<?= $edit['tgl_lahir'] ?>" type="text" name="lahir" id="lahir" class="col-xs-12  col-sm-6 date-picker" placeholder="Tanggal Lahir" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jenis Kelamin :</label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <label class="control-label">
                                <input <?= ($edit['kelamin_mhs'] == 'Laki-Laki') ? 'checked' : '' ; ?> name="kelamin" value="Laki-Laki" type="radio" class="ace" />
                                <span class="lbl"> Laki-Laki</span>
                            </label>&nbsp;&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($edit['kelamin_mhs'] == 'Perempuan') ? 'checked' : '' ; ?> name="kelamin" value="Perempuan" type="radio" class="ace" />
                                <span class="lbl"> Perempuan</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Agama :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="agama" id="agama" data-placeholder="---> Pilih Agama <---">
                                <option value=""> </option>
                                <?php
                                foreach (load_array('agama') as $val) {
                                    $selected = ($edit['agama'] == $val) ? 'selected' : '';
                                    echo '<option value="'.$val.'"  '.$selected.'>'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Telepon :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input value="<?= $edit['telepon_mhs'] ?>" type="text" name="telepon" id="telepon" class="col-xs-12  col-sm-6" placeholder="Telepon Aktif" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-8 col-md-offset-4">
                        <span class="middle blue">* Nomor harus selalu Aktif</span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Email :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['email_mhs'] ?>" type="text" name="email" id="email" class="col-xs-12  col-sm-6" placeholder="Email Aktif" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Alamat di Sorong :</label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <textarea rows="2" cols="1" name="alamat" id="alamat" placeholder="Alamat Tinggal di Sorong" class="col-xs-12 col-sm-6"><?= ctk($edit['alamat_mhs']) ?></textarea>
                        </div>
                    </div>
                    <span class="help-inline col-xs-8 col-md-offset-4">
                        <span class="middle blue">* Tulis lengkap berupa nama Jalan, RT/RW, Kelurahan dan Kecamatan</span>
                    </span>
                </div>
                
                <div class="space-6"></div>
                <div class="social-or-login center">
                    <span class="bigger-110 bolder">Form Alamat Sesuai KTP</span>
                </div>
                <div class="space-6"></div>
                
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jalan :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['jalan'] ?>" type="text" name="jalan" id="jalan" class="col-xs-12  col-sm-6" placeholder="Nama Jalan & Nomor Rumah" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">RT/RW :</label>
                    <div class="col-xs-12 col-sm-8">
                        <span class="input-icon">
                            <input value="<?= $edit['rt'] ?>" name="rt" id="rt" placeholder="RT" type="number" class="col-sm-6">
                        </span>

                        <span class="input-icon">
                            <input value="<?= $edit['rw'] ?>" name="rw" id="rw" placeholder="RW" type="number" class="col-sm-6">
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Kelurahan :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['kelurahan'] ?>" type="text" name="lurah" id="lurah" class="col-xs-12  col-sm-6" placeholder="Kelurahan" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Kecamatan :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['kecamatan'] ?>" type="hidden" name="camat" id="camat" class="col-xs-12  col-sm-6" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Kota/Kabupaten :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['kabupaten'] ?>" type="hidden" name="bupati" id="bupati" class="col-xs-12  col-sm-6" />
                        </div>
                    </div>
                </div>
                
                <div class="hr dotted hr-double hide"></div>
                
                <div class="form-group hide">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Foto :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input value="<?= $edit['foto_mhs'] ?>" type="hidden" name="exfoto" id="exfoto" />
                            <input accept="image/*" value="" type="file" name="foto" id="foto" placeholder="Foto" class="col-xs-12  col-sm-6" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-12 col-sm-2">
                        <span class="middle red">* Maksimal 1 MB</span>
                    </span>
                </div>
                <div class="form-group hide">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <img width="200" src="<?= load_file($edit['foto_mhs'],1) ?>" class="img-thumbnail" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Terakhir diubah :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="well">
                            <span class="bigger-110 blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $edit['log_mhs'] ?></span><br/>
                            <span class="bigger-110 green"><i class="ace-icon fa fa-pencil"></i> &nbsp;&nbsp;<?= format_date($edit['tgl_daftar'],0) ?></span><br/>
                            <span class="bigger-110 orange"><i class="ace-icon fa fa-clock-o"></i> &nbsp;&nbsp;<?= format_date($edit['update_mhs'],0) ?></span>
                        </div>
                    </div>
                </div>
                <div class="form-group <?= is_null($edit['id_mhs']) ? '' : 'hide' ?>">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="alert alert-info center">
                            <p class="bigger-110">Mohon Perhatian! <br/>Akun Mahasiswa Baru yang dibuat oleh Panitia PMB memiliki ketentuan : </p>
                            <span class="lbl red"> <b>Username = Kode Registrasi<br>Password = Kode Registrasi</b> <br>
                                Digunakan saat LOGIN pada Website PMB UNIMUDA Sorong, 
                                harap dicatat agar tidak lupa atau hilang.
                            </span>
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
                        <button class="btn btn-success" name="simpan" id="simpan" type="submit">
                            <i class="ace-icon fa fa-check"></i>
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div><!-- /.row -->
</div><!-- /.page-content -->

<?php 
    load_js(array(
        "backend/assets/js/jquery.validate.js",
        "backend/assets/js/select2.js",
        "backend/assets/js/date-time/bootstrap-datepicker.js"
    )); 
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";  
    const img_ext = ["jpg", "png", "jpeg", "PNG", "JPG"];
        
    $(document).ready(function() {
        load_wilayah();
        $(".select2").select2({allowClear: true})
            .on('change', function () {
                $(this).closest('form').validate().element($(this));
        });
        $(".date-picker").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            clearBtn: true,
            endDate: "-15y",
            startDate: "-60y"
        }).next().on(ace.click_event, function () {
            $(this).prev().focus();
        });
        $("#foto").ace_file_input({
            no_file: 'Pilih Foto...',
            no_icon: 'fa fa-file-image-o',
            icon_remove: 'fa fa-times',
            btn_choose: 'Pilih',
            btn_change: 'Ubah',
            onchange: null,
            allowExt: img_ext,
            maxSize: 1100000
        }).on('file.error.ace', function(ev, info) {
            if(info.error_count['ext']) myNotif('Peringatan!', 'Format gambar harus berupa *.jpg, *.png', 3);
            if(info.error_count['size']) myNotif('Peringatan!', 'Ukuran gambar maksimal 1 MB', 3);
        });
    });
    $("#opsi1").change(function () {
        let data = $("#opsi1").select2('data');
        if(data !== null && data.text === 'S2 Ilmu Manajemen'){
            $(".opsi").addClass('hide');
            $("#opsi2,#opsi3").val(data.text).trigger('change');
        }else{
            $(".opsi").removeClass('hide');
            $("#opsi2,#opsi3").val(null).trigger('change');
        }
    });
</script>
<script type="text/javascript">
    function load_wilayah() {
        $("#camat").select2({
            placeholder: "------> Pilih Kecamatan <------",
            minimumInputLength: 3,
            allowClear: true,
            ajax: { 
                url: module + "/ajax/type/table/source/wilayah",
                type: "POST",
                dataType: 'json',
                delay: 250,
                data: function (term, page) {
                    return { q: term };
                },
                results: function (data, page) {
                    return { results: data };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax(module + "/ajax/type/table/source/wilayah?id=" + id, {
                        dataType: "json"
                    }).done(function(data) { 
                        callback(data[0]);
                    });
                }
            }
        });
        $("#bupati").select2({
            placeholder: "------> Pilih Kabupaten <------",
            minimumInputLength: 3,
            allowClear: true,
            ajax: { 
                url: module + "/ajax/type/table/source/wilayah",
                type: "POST",
                dataType: 'json',
                delay: 250,
                data: function (term, page) {
                    return { q: term };
                },
                results: function (data, page) {
                    return { results: data };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    $.ajax(module + "/ajax/type/table/source/wilayah?id=" + id, {
                        dataType: "json"
                    }).done(function(data) { 
                        callback(data[0]);
                    });
                }
            }
        });
    }
    $("#validation-form").validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            opsi1: {
                required: true
            },
            opsi2: {
                required: true
            },
            opsi3: {
                required: true
            },
            tahun: {
                required: true
            },
            jalur: {
                required: true
            },
            status: {
                required: true
            },
            //Data Pendidikan
            nisn: {
//                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            sekolah: {
//                required: true,
                minlength: 5
            },
            npsn: {
//                required: true,
                digits: true,
                minlength: 8,
                maxlength: 10
            },
            //Data Diri
            nik: {
                required: true,
                digits: true,
                minlength: 16,
                maxlength: 16
            },
            nama: {
                required: true,
                minlength: 3
            },
            ibu: {
                required: true,
                minlength: 3
            },
            tempat: {
                required: true,
                minlength: 3
            },
            lahir: {
                required: true,
                date: true
            },
            kelamin: {
                required: true
            },
            agama: {
                required: true
            },
            telepon: {
                required: true,
                digits: true,
                minlength: 11,
                maxlength: 12
            },
            email: {
                required: true,
                minlength: 5,
                email:true
            },
            alamat: {
                required: true,
                minlength: 10
            },
            //Data KTP
            jalan: {
                required: true,
                minlength: 5
            },
            rt: {
                required: true,
                digits: true,
                min: 0,
                max: 20
            },
            rw: {
                required: true,
                digits: true,
                min: 0,
                max: 20
            },
            lurah: {
                required: true,
                minlength: 3
            },
            camat: {
                required: true,
                digits: true,
                minlength: 3
            },
            bupati: {
                required: true,
                digits: true,
                minlength: 3
            }
        },
        highlight: function(e) {
            $(e).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function(e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
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
        invalidHandler: function(form) {
        }
    });
</script>
