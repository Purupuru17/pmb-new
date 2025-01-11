<div class="col-xs-12 col-sm-12">
    <div class="widget-box transparent">
        <div class="widget-header">
            <h5 class="widget-title lighter">
                <i class="ace-icon fa fa-list"></i>
                List Data
            </h5>
            <div class="widget-toolbar">
                <a href="#" data-action="collapse" class="orange2">
                    <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                </a>
            </div>
        </div>
        <div class="widget-body">
            <div class="widget-main padding-2 table-responsive">
                <style>
                    th, td {
                        text-align:center;
                    }
                </style>
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Keterangan</th>
                            <th>Nama</th>
                            <th>Tanggal Lahir</th>
                            <th>Pendidikan</th>
                            <th>Pekerjaan</th>
                            <th>Penghasilan</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($ortu['data'] as $row) {
                            ?>
                            <tr>
                                <td><?= ctk($row['jenis_ortu']); ?></td>
                                <td><strong><?= ctk($row['nama_ortu']); ?></strong><hr class="margin-5">
                                    <?= ctk($row['nik_ortu']); ?>
                                </td>
                                <td><?= format_date($row['lahir_ortu'], 1); ?></td>
                                <td><?= ctk($row['didik_ortu']); ?></td>
                                <td><?= ctk($row['kerja_ortu']); ?></td>
                                <td><?= ctk($row['hasil_ortu']); ?></td>
                                <td><?= ctk($row['telepon_ortu']); ?></td>
                                <td><?= ctk($row['alamat_ortu']); ?></td>
                                <td nowrap>
                                    <div class="action-buttons">
                                        <a target="_blank" href="<?= site_url('mhs/ortu/edit/' . encode($row['id_ortu']) . '/' . encode($detail['id_mhs'])) ?>" class="tooltip-warning btn btn-white btn-warning btn-sm" data-rel="tooltip" title="Ubah Data">
                                            <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-130"></i></span>
                                        </a>
                                        <a href="#" item="<?= encode($detail['id_mhs']) ?>" name="<?= encode($row['id_ortu']) ?>" itemprop="<?= $row['jenis_ortu'] ?>" id="delete-btn" class="tooltip-error btn btn-white btn-danger btn-sm" data-rel="tooltip" title="Hapus Data">
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
<div class="col-xs-12 col-sm-12">
    <h3 class="lighter center block blue">Tambah Data</h3>
    <form id="ortu-form" action="<?= site_url('mhs/ortu_do/add'); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="mid" value="<?= encode($detail['id_mhs']) ?>" />
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-5 no-padding-right">Keterangan :</label>
            <div class="col-xs-12 col-sm-3">
                <div class="clearfix">
                    <select class="select2 width-100" name="keterangan" id="keterangan" data-placeholder="-----> Pilih Keterangan <-----">
                        <option value=""> </option>
                        <?php
                        foreach ($keterangan as $val) {
                            echo '<option value="' . $val . '">' . $val . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-5 no-padding-right">NIK :</label>
            <div class="col-xs-12 col-sm-6">
                <div class="clearfix">
                    <input type="text" name="nik" id="nik" placeholder="NIK" class="col-xs-12  col-sm-6" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-5 no-padding-right">Nama :</label>
            <div class="col-xs-12 col-sm-6">
                <div class="clearfix">
                    <input type="text" name="nama" id="nama" placeholder="Nama" class="col-xs-12  col-sm-6" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-5 no-padding-right">Tanggal Lahir :</label>
            <div class="col-xs-12 col-sm-3">
                <div class="clearfix">
                    <input type="text" name="lahir" id="lahir" placeholder="Tanggal Lahir" data-date-format="yyyy-mm-dd" class="col-xs-12  col-sm-6 date-picker" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-5 no-padding-right">Pendidikan :</label>
            <div class="col-xs-12 col-sm-3">
                <div class="clearfix">
                    <select class="select2 width-100" name="didik" id="didik" data-placeholder="-----> Pilih Pendidikan <-----">
                        <option value=""> </option>
                        <?php
                        foreach ($didik as $val) {
                            echo '<option value="' . $val . '">' . $val . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-5 no-padding-right">Pekerjaan :</label>
            <div class="col-xs-12 col-sm-3">
                <div class="clearfix">
                    <select class="select2 width-100" name="kerja" id="kerja" data-placeholder="-----> Pilih Pekerjaan <-----">
                        <option value=""> </option>
                        <?php
                        foreach ($kerja as $val) {
                            echo '<option value="' . $val . '">' . $val . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-5 no-padding-right">Penghasilan :</label>
            <div class="col-xs-12 col-sm-3">
                <div class="clearfix">
                    <select class="select2 width-100" name="hasil" id="hasil" data-placeholder="-------> Pilih Penghasilan <-------">
                        <option value=""> </option>
                        <?php
                        foreach ($golongan as $val) {
                            echo '<option value="' . $val . '">' . $val . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-5 no-padding-right">Telepon :</label>
            <div class="col-xs-12 col-sm-3">
                <div class="clearfix">
                    <input type="text" name="telepon" id="telepon" class="col-xs-12  col-sm-6" placeholder="Nomor Telepon" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-5 no-padding-right">Alamat :</label>
            <div class="col-xs-12 col-sm-7">
                <div class="clearfix">
                    <textarea rows="2" cols="1" name="alamat" id="alamat" placeholder="Alamat Rumah" class="col-xs-12 col-sm-6"></textarea>
                </div>
            </div>
        </div>
        <div class="clearfix form-actions">
            <div class="col-md-offset-5 col-md-4">
                <button class="btn" type="reset">
                    <i class="ace-icon fa fa-undo bigger-110"></i>
                    Batal
                </button>
                &nbsp; &nbsp; &nbsp;
                <button class="btn btn-success" name="simpan" id="simpan" type="submit">
                    <i class="ace-icon fa fa-save"></i>
                    Simpan
                </button>
            </div>
        </div>
    </form>
</div>

<?php
load_js(array(
    'backend/assets/js/jquery.validate.js',
    'backend/assets/js/select2.js',
    'backend/assets/js/date-time/bootstrap-datepicker.js',
    'backend/assets/js/bootbox.min.js'
));
?>
<script type="text/javascript">
    $(document).ready(function () {
        $(".select2").select2({allowClear: true})
                .on('change', function () {
                    $(this).closest('form').validate().element($(this));
                });
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        })
                .next().on(ace.click_event, function () {
            $(this).prev().focus();
        });
    });
</script>
<script type="text/javascript">
    $(document.body).on("click", "#delete-btn", function (event) {
        var id = $(this).attr("name");
        var name = $(this).attr("itemprop");
        var mhs = $(this).attr("item");
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
                    window.location.replace("<?= site_url('mhs/ortu/delete/'); ?>" + id + '/' + mhs);
                }
            }
        });
    });
</script>
<script type="text/javascript">
    $('#ortu-form').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            keterangan: {
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
            lahir: {
                required: true,
                date: true
            },
            didik: {
                required: true
            },
            kerja: {
                required: true
            },
            hasil: {
                required: true
            },
            telepon: {
                required: true,
                digits: true,
                minlength: 11,
                maxlength: 12
            },
            alamat: {
                required: true,
                minlength: 5
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
