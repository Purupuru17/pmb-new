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
            <form id="validation-form" action="<?= site_url($module.'/add'); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="space-6"></div>
                <div class="social-or-login center">
                    <span class="bigger-110 orange">Sarjana (S1) & Pascasarjana (S2)</span>
                </div>
                <div class="space-6"></div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Program Studi :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <select class="select2 width-100 bolder" name="opsi1" id="opsi1" data-placeholder="-------> Pilihan Pertama <-------">
                                <option value=""> </option>
                                <?php
                                foreach ($prodi['data'] as $val) {
                                    echo '<option value="' . encode($val['id_prodi']) . '">' . $val['nama_prodi'] . '</option>';
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
                                    echo '<option value="' . ($val['nama_prodi']) . '">' . $val['nama_prodi'] . '</option>';
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
                                    echo '<option value="' . ($val['nama_prodi']) . '">' . $val['nama_prodi'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jalur Pendaftaran :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100 bolder" name="jalur" id="jalur" data-placeholder="----> Pilih Jalur Pendaftaran <----">
                                <option value=""> </option>
                                <?php
                                $lain = array('Pemda-Misol','Pemda-Wondama','Pemda-RajaAmpat');
                                $jalur_daftar = array_diff(load_array('jalur'), $lain);
                                
                                foreach ($jalur_daftar as $val) {
                                    echo '<option value="'.$val.'">'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <span class="help-inline col-xs-8 col-md-offset-4">
                        <span class="middle blue">* Informasi mengenai Jalur Pendaftaran, 
                            <a class="red bigger-110" href="<?= site_url('pages/info-pendaftaran') ?>" target="_blank">Klik di sini!</a>
                        </span>
                    </span>
                </div>
                <div class="social-or-login center">
                    <span class="bigger-110 orange">Biodata Diri</span>
                </div>
                <div class="space-6"></div>
                
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">NIK :</label>
                    <div class="col-xs-12 col-sm-5">
                        <div class="clearfix">
                            <input type="text" name="nik" id="nik" class="col-xs-12  col-sm-6" placeholder="Nomor Induk Kependudukan" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-8 col-md-offset-4">
                        <span class="middle blue bolder">* NIK harus sesuai Kartu Keluarga</span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Nama Lengkap :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input type="text" name="nama" id="nama" class="col-xs-12  col-sm-6" placeholder="Nama Lengkap" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-8 col-md-offset-4">
                        <span class="middle blue bolder">* Nama harus sesuai IJAZAH</span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Telepon :</label>
                    <div class="col-xs-12 col-sm-5">
                        <div class="clearfix">
                            <input type="text" name="telepon" id="telepon" class="col-xs-12  col-sm-6" placeholder="Telepon Aktif" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-8 col-md-offset-4">
                        <span class="middle blue bolder">* Nomor harus selalu Aktif</span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Ibu Kandung :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input type="text" name="ibu" id="ibu" class="col-xs-12  col-sm-6" placeholder="Nama Ibu Kandung" />
                        </div>
                    </div>
                </div>
                
                <div class="space-6"></div>
                <div class="social-or-login center">
                    <span class="bigger-110 orange">Informasi Akun</span>
                </div>
                <div class="space-6"></div>
                
                <div class="form-group" style="margin-bottom: 0px">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="well well-sm bigger-130">
                            <span class="lbl red"> <b>Kode Registrasi (Username) & Password</b> digunakan saat LOGIN pada Website PMB UNIMUDA Sorong, 
                                harap dicatat agar tidak lupa atau hilang.
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Kode Registrasi (Username) :</label>
                    <div class="col-xs-12 col-sm-5">
                        <div class="clearfix">
                            <input type="text" name="kode" id="kode" readonly="" class="blue bolder bigger-130 col-xs-12  col-sm-6" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-8 col-md-offset-4">
                        <div class="space-2"></div>
                        <button onclick="generate_kode()" class="btn btn-danger btn-white btn-bold btn-sm" id="btn-kode" type="button">
                            <i class="ace-icon fa fa-key"></i>
                            Klik untuk dapatkan Kode Registrasi
                        </button><div class="space-2"></div>
                        <button onclick="set_password()" class="btn btn-default btn-white btn-bold btn-mini" id="btn-set" type="button">
                            <i class="ace-icon fa fa-paste"></i>
                            Klik jadikan Kode Registrasi sebagai Password
                        </button>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Password Baru :</label>
                    <div class="col-xs-12 col-sm-5">
                        <div class="clearfix">
                            <input type="password" name="password" id="password" placeholder="Password Baru" class="col-xs-12  col-sm-6"  />
                        </div>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 2px">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Konfirmasi Password Baru :</label>
                    <div class="col-xs-12 col-sm-5">
                        <div class="clearfix">
                            <input type="password" name="confirm" id="confirm" placeholder="Konfirmasi Password Baru" class="col-xs-12  col-sm-6"  />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="checkbox">
                            <label>
                                <input name="gshow" id="gshow" type="checkbox" value="1" class="ace  ace-checkbox-2">
                                <span id="txtShow" class="lbl"> Show Password</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <?= $captcha ?>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                    <div class="col-md-offset-4 col-md-4">
                        <a class="btn btn-round btn-white btn-" href="<?= site_url('login') ?>">
                            <i class="ace-icon fa fa-undo bigger-110"></i>
                            Batal
                        </a>
                        &nbsp; &nbsp; &nbsp;
                        <button class="btn btn-primary btn-white btn-round btn-lg hide" name="simpan" id="simpan" type="submit">
                            <i class="ace-icon fa fa-send"></i>
                            DAFTAR SEKARANG
                        </button>
                    </div>
                </div>
            </form>
            <div class="alert alert-info">
            <p class="bigger-110"><strong>Perhatian !</strong><br/>
                Mohon mengisi data ini dengan sebenar-benarnya karena data ini bersifat penting!
            </p>
        </div>
        </div>
    </div><!-- /.row -->
</div><!-- /.page-content -->

<?php
    echo $script_captcha;
    load_js(array(
        "backend/assets/js/jquery.validate.js",
        "backend/assets/js/select2.js",
        "backend/assets/js/bootbox.min.js"
    ));
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    const form = $("#validation-form");
    
    $(document).ready(function() {
        $(".select2").select2({allowClear: true})
            .on('change', function () {
                $(this).closest('form').validate().element($(this));
            });
        $(".select2-chosen").addClass("center");
        $(".nav-list").css("display", "none");
        $.validator.addMethod("recaptchaValid", function(value, element, params) {
            const response = grecaptcha.getResponse(); // Ambil token reCAPTCHA
            return response.length > 0; // Valid jika ada token
        }, "Centang atau selesaikan CAPTCHA terlebih dahulu");
    });
    $("#opsi1").change(function () {
        let data = $("#opsi1").select2('data');
        let prodi = ['S2 Ilmu Manajemen', 'S1 Pendidikan Profesi Guru'];
        
        if(data !== null && (prodi.includes(data.text))){
            $(".opsi").addClass('hide');
            $("#opsi2,#opsi3").val(data.text).trigger('change');
        }else{
            $(".opsi").removeClass('hide');
            $("#opsi2,#opsi3").val(null).trigger('change');
        }
    });
    $("#gshow").on("click", function(e) {
        var pass = $("#password");
        var confirm = $("#confirm");
        if(this.checked) {
            pass.attr('type','text');
            confirm.attr('type','text');
            $("#txtShow").html(' Hide Password');
        }else{
            pass.attr('type','password');
            confirm.attr('type','password');
            $("#txtShow").html(' Show Password');
        }
    });
</script>
<script type="text/javascript">
    function set_password() {
        let kode = $("#kode").val();
        $("#password, #confirm").val(kode);
    }
    function generate_kode() {
        $("#kode").val('');
        $.ajax({
            url: module + "/ajax/type/action/source/kode",
            dataType: "json",
            type: "POST",
            data: $("#validation-form").serialize(),
            success: function (rs) {
                if(rs.status){
                    $("#kode").val(rs.data);
                    $("#simpan").removeClass('hide');
                    
                    myNotif('Informasi', rs.msg, 1);
                }else {
                    myNotif('Peringatan', rs.msg, 2);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                myNotif('Error', 'Kesalahan jaringan. Mohon ulangi proses', 3); 
            }
        });
    }
    form.submit(function(){
        if(form.validate().checkForm()){
            var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Menyimpan Data . . . </h4>';
            var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                    ' Mohon menunggu, jangan menutup atau me-refresh halaman ini. <br>Silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
            bootbox.dialog({ title: title, message: msg, closeButton: false });
        }
    });
    form.validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            kode: {
                required: true,
                minlength: 5
            },
            opsi1: {
                required: true
            },
            opsi2: {
                required: true
            },
            opsi3: {
                required: true
            },
            jalur: {
                required: true
            },
            nik: {
                required: true,
                digits: true,
                minlength: 16,
                maxlength: 16
            },
            nama: {
                required: true,
                minlength: 5
            },
            ibu: {
                required: true,
                minlength: 3
            },
            telepon: {
                required: true,
                digits: true,
                minlength: 11,
                maxlength: 12
            },
            password: {
                required: {
                    depends:function(){
                        $(this).val($(this).val().replace(/\s/g, ''));
                        return true;
                    }
                },
                minlength: 5
            },
            confirm: {
                required: {
                    depends:function(){
                        $(this).val($(this).val().replace(/\s/g, ''));
                        return true;
                    }
                },
                minlength: 5,
                equalTo: "#password"
            },
            "g-recaptcha-response": {
                recaptchaValid: true
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
