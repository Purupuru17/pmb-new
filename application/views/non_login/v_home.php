<?php
$this->load->view('sistem/v_breadcrumb');
?>
<div class="page-content">
    <div class="row">
        <div class="col-xs-12">
            <?= $this->session->flashdata('notif'); ?>
        </div><!-- /.col -->
        <div class="col-sm-12 col-xs-12 <?= (empty($groupid) || $groupid === '4') ? 'hide' : '' ; ?>">
            <label>Pilih Tanggal</label>
            <div class="row">
                <form id="grafik-form" method="POST">
                    <div class="col-sm-4">
                        <div class="input-daterange input-group">
                            <input required="" type="text" class="form-control" name="awal" id="awal" placeholder="Tanggal Awal" />
                            <span class="input-group-addon">
                                <i class="fa fa-exchange"></i>
                            </span>
                            <input required="" type="text" class="form-control" name="akhir" id="akhir" placeholder="Tanggal Akhir"/>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <select class="select2 width-100" name="tahun" id="tahun" data-placeholder="---> Pilih Tahun <---">
                            <option value=""> </option>
                            <?php
                            $now = intval(date('Y'));
                            for($i=2020;$i <= $now; $i++) {
                                $selected = ($i == $now) ? 'selected':'';
                                echo '<option value="' . $i . '" '.$selected.'>' . $i . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <button type="button" id="btn-search" class="btn btn-white btn-primary btn-sm btn-bold">
                            <i class="fa fa-search-plus blue bigger-120"></i> Tampilkan
                        </button>
                    </div>
                </form>
            </div>
            <div class="widget-box transparent">
                <div class="widget-header">
                    <h5 class="widget-title blue">
                        <i class="ace-icon fa fa-bar-chart"></i>
                        Grafik Pendaftaran Mahasiswa
                    </h5>

                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse">
                            <i class="ace-icon fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main">
                        <div id="container" style="height: 400px; min-width: 380px"></div>
                    </div>
                </div>
            </div>
            <div class="widget-box transparent">
                <div class="widget-header">
                    <h5 class="widget-title green">
                        <i class="ace-icon fa fa-bar-chart"></i>
                        Grafik Penerimaan Program Studi
                    </h5>

                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse">
                            <i class="ace-icon fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main">
                        <div id="container2" style="height: 400px; min-width: 380px"></div>
                    </div>
                </div>
            </div>
            <div class="widget-box transparent">
                <div class="widget-header">
                    <h5 class="widget-title orange">
                        <i class="ace-icon fa fa-bar-chart"></i>
                        Grafik Asal Sekolah
                    </h5>

                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse">
                            <i class="ace-icon fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main">
                        <div id="container3" style="height: 400px; min-width: 380px"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xs-12 <?= ($groupid !== '4') ? 'hide' : '' ; ?>">
            <div class="widget-box transparent">
                <div class="widget-header widget-header-flat">
                    <h4 class="widget-title lighter red">
                        <i class="ace-icon fa fa-warning bigger-130"></i>
                        Mohon Segera Lengkapi Data - Data Di Bawah Ini !
                    </h4>

                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse">
                            <i class="ace-icon fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main no-padding" style="text-align: center">
                        <p></p>
                        <a href="<?= site_url('mhs/profil') ?>" class="btn btn-default btn-app radius-4 btn-yellow">
                            <i class="ace-icon fa fa-user bigger-230"></i>
                            Profil
                        </a>
                        <a href="<?= site_url('transaksi/payment') ?>" class="btn btn-default btn-app radius-4 btn-danger">
                            <i class="ace-icon fa fa-money bigger-230"></i>
                            Biaya
                        </a>
                        <a href="<?= site_url('mhs/wali') ?>" class="btn btn-default btn-app radius-4 btn-primary">
                            <i class="ace-icon fa fa-users bigger-230"></i>
                            Wali
                        </a>
                        <a href="<?= site_url('mhs/berkas') ?>" class="btn btn-default btn-app radius-4 btn-success">
                            <i class="ace-icon fa fa-upload bigger-230"></i>
                            Berkas
                        </a>
                    </div><!-- /.widget-main -->
                </div><!-- /.widget-body -->
            </div><!-- /.widget-box -->
        </div>
    </div><!-- /.row -->
</div><!-- /.page-content -->
<?php
load_js(array(
    'backend/assets/js/date-time/bootstrap-datepicker.js',
    'backend/assets/js/select2.js'
));
?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";

    $(document).ready(function () {
        $(".select2").select2({allowClear: true});
        $(".select2-chosen").addClass("center");
        $(".input-daterange").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });
    });
    $("#btn-search").click(function () {
        load_grafik();
        load_grafik_prodi();
        load_grafik_sekolah();
    });
    function load_grafik() {
        $.ajax({
            url: module + "/ajax/type/chart/source/maba",
            dataType: "json",
            type: "POST",
            data: $("#grafik-form").serialize(),
            success: function (rs) {
                var maba = [];
                rs.data.map((obj) => {
                    maba.push([obj.day, obj.maba]);
                });
                chart.series[0].update({ data: maba});
                chart.setTitle(
                    {text: "[ "+ rs.total + " Orang Pendaftar Baru ]"}
                );
                chart.setSubtitle(
                    {text: rs.range}
                );
                chart.redraw();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                myNotif('Peringatan', 'Gagal memuat data', 3);
            }
        });
    }
    function load_grafik_prodi() {
        $.ajax({
            url: module + "/ajax/type/chart/source/prodi",
            dataType: "json",
            type: "POST",
            data: $("#grafik-form").serialize(),
            success: function (rs) {
                var maba = [];
                rs.data.map((obj) => {
                    maba.push([obj.prodi, obj.maba]);
                });
                chart_prodi.series[0].update({ data: maba});
                chart_prodi.setTitle(
                    {text: "[ "+ rs.total + " Mahasiswa Baru ]"}
                );
                chart_prodi.setSubtitle(
                    {text: rs.range}
                );
                chart_prodi.redraw();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                myNotif('Peringatan', 'Gagal memuat data', 3);
            }
        });
    }
    function load_grafik_sekolah() {
        $.ajax({
            url: module + "/ajax/type/chart/source/sekolah",
            dataType: "json",
            type: "POST",
            data: $("#grafik-form").serialize(),
            success: function (rs) {
                var maba = [];
                rs.data.map((obj) => {
                    maba.push([obj.npsn, obj.maba]);
                });
                chart_sekolah.series[0].update({ data: maba});
                chart_sekolah.setTitle(
                    {text: "[ "+ rs.total + " Mahasiswa Baru ]"}
                );
                chart_sekolah.setSubtitle(
                    {text: rs.range}
                );
                chart_sekolah.redraw();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                myNotif('Peringatan', 'Gagal memuat data', 3);
            }
        });
    }
    const options_maba = {
        chart: {
            type: 'areaspline',
            zoomType: 'x',
            events: {
                load: load_grafik()
            }
        },
        subtitle: {
            text: 'Website <?= $app['judul'] ?>'
        },
        xAxis: {
            type: 'category',
            tickmarkPlacement: 'on',
            title: {
                enabled: true
            }
        },
        yAxis: {
            title: {
                text: 'Jumlah'
            }
        },
        tooltip: {
            crosshairs: true,
            shared: true
        },
        legend: {
            align: 'center',
            verticalAlign: 'top',
            borderWidth: 0
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5,
                marker: {
                    radius: 4,
                    lineWidth: 1
                },
                dataLabels: {
                    enabled: true
                }
            }
        },
        series: [{
            name: 'Pendaftar ',
            data: []
        }]
    };
    const options_prodi = {
        chart: {
            type: 'areaspline',
            zoomType: 'x',
            events: {
                load: load_grafik_prodi()
            }
        },
        subtitle: {
            text: 'Website <?= $app['judul'] ?>'
        },
        xAxis: {
            type: 'category',
            tickmarkPlacement: 'on',
            title: {
                enabled: true
            }
        },
        yAxis: {
            title: {
                text: 'Jumlah'
            }
        },
        tooltip: {
            crosshairs: true,
            shared: true
        },
        legend: {
            align: 'center',
            verticalAlign: 'top',
            borderWidth: 0
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5,
                marker: {
                    radius: 4,
                    lineWidth: 1
                },
                dataLabels: {
                    enabled: true
                }
            }
        },
        series: [{
            name: 'Mahasiswa ',
            data: []
        }]
    };
    const options_sekolah = {
        chart: {
            type: 'areaspline',
            zoomType: 'x',
            events: {
                load: load_grafik_sekolah()
            }
        },
        subtitle: {
            text: 'Website <?= $app['judul'] ?>'
        },
        xAxis: {
            type: 'category',
            tickmarkPlacement: 'on',
            title: {
                enabled: true
            }
        },
        yAxis: {
            title: {
                text: 'Jumlah'
            }
        },
        tooltip: {
            crosshairs: true,
            shared: true
        },
        legend: {
            align: 'center',
            verticalAlign: 'top',
            borderWidth: 0
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5,
                marker: {
                    radius: 4,
                    lineWidth: 1
                },
                dataLabels: {
                    enabled: true
                }
            }
        },
        series: [{
            name: 'Mahasiswa ',
            data: []
        }]
    };
    const chart = Highcharts.chart('container', options_maba);
    const chart_prodi = Highcharts.chart('container2', options_prodi);
    const chart_sekolah = Highcharts.chart('container3', options_sekolah);
</script> 
