<?php
load_css(array(
    'backend/assets/css/datepicker.css'
));
?>
<div class="col-xs-12">
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
            <div class="col-sm-4">
                <button type="button" id="btn-search" class="btn btn-white btn-primary btn-bold">
                    <i class="fa fa-search-plus blue"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>

    <div class="widget-box transparent">
        <div class="widget-header">
            <h5 class="widget-title bigger lighter orange">
                <i class="ace-icon fa fa-bar-chart"></i>
                Grafik Pengunjung
            </h5>

            <div class="widget-toolbar">
                <a href="#" data-action="collapse" class="orange2">
                    <i class="ace-icon fa fa-chevron-up bigger-110"></i>
                </a>
            </div>
        </div>
        <div class="widget-body">
            <div class="widget-main">
                <div id="container" style="height: 400px; min-width: 380px"></div>
            </div>
        </div>
    </div>
</div>
<div class="col-xs-12">
    <div class="widget-box transparent">
        <div class="widget-header">
            <h5 class="widget-title bigger lighter">
                <i class="ace-icon fa fa-users"></i>
                Pengunjung Website [ <strong id="txt-total"></strong> ]
            </h5>

            <div class="widget-toolbar">
                <a href="#" data-action="collapse" class="orange2">
                    <i class="ace-icon fa fa-chevron-up bigger-110"></i>
                </a>
            </div>
        </div>
        <div class="widget-body">
            <div class="widget-main padding-2 table-responsive">
                <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>IP</th>
                            <th>x Day</th>
                            <th width="25%">Request URL</th>
                            <th>Page Name</th>
                            <th width="25%">Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.col -->
<?php
load_js(array(
    "backend/assets/js/jquery.validate.js",
    "backend/assets/js/bootstrap-colorpicker.js",
    "backend/assets/js/date-time/bootstrap-datepicker.js",
    "backend/assets/js/dataTables/jquery.dataTables.js",
    "backend/assets/js/dataTables/jquery.dataTables.bootstrap.js"
));
?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    var table;
    const module = "<?= site_url($module) ?>";

    $(document).ready(function () {
        load_table();
        $('[data-rel="tooltip"]').tooltip({placement: 'top'});
        $(".input-daterange").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });
    });
    $("#btn-search").click(function () {
        table.fnDraw();
        load_grafik();
    });
    function load_table() {
        table = $("#dynamic-table")
            .dataTable({
                orderCellsTop: true,
                fixedHeader: true,
                bScrollCollapse: true,
                bAutoWidth: false,
                bProcessing: true,
                bServerSide: true,
                ajax: {
                    url: module + "/ajax/type/list/source/visitor",
                    type: "POST",
                    data: function (val) {
                        val.awal = $("#awal").val();
                        val.akhir = $("#akhir").val();
                    }
                },
                aaSorting: [],
                aoColumnDefs: [
                    {bSortable: false, aTargets: [0]},
                    {bSearchable: false, aTargets: [0]},
                    {sClass: "center", aTargets: [0, 1, 2, 3, 4, 5, 6]}
                ],
                oLanguage: {
                    sSearch: "Cari : ",
                    sInfoEmpty: "Menampilkan dari 0 sampai 0 dari total 0 data",
                    sInfo: "Menampilkan dari _START_ sampai _END_ dari total _TOTAL_ data",
                    sLengthMenu: "_MENU_ data per halaman",
                    sZeroRecords: "Maaf tidak ada data yang ditemukan",
                    sInfoFiltered: "(Menyaring dari _MAX_ total data)"
                }
            });
        table.fnAdjustColumnSizing();
    }
    function load_grafik() {
        $.ajax({
            url: module + "/ajax/type/list/source/grafik",
            dataType: "json",
            type: "POST",
            data: $("#grafik-form").serialize(),
            success: function (rs) {
                var visit = [];
                var akses = [];
                rs.data.map((obj) => {
                    visit.push([obj.day, obj.visit]);
                    akses.push([obj.day, obj.akses]);
                });
                
                chart.series[1].update({ data: visit});
                chart.series[0].update({ data: akses});
                chart.setTitle(
                    {text: "Statistik Pengunjung [ Total : " + rs.total + " Orang ]"}
                );
                chart.redraw();
                $("#txt-total").html(rs.total + ' Orang');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                myNotif('Peringatan', 'Gagal memuat data', 3);
            }
        });
    }
    const options = {
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
            name: 'Akses ',
            data: []
        },{
            name: 'Pengunjung (Orang) ',
            marker: {
                symbol: 'square'
            },
            data: []
        }]
    };
    const chart = Highcharts.chart('container', options);
</script>  