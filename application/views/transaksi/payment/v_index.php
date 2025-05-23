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
        <div class="col-xs-12 <?= empty($is_mahasiswa) ? '' : 'hide' ?>">
            <form id="search-form" action="#>" name="form" class="form-horizontal" method="POST">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Program Studi :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <select class="select2 width-100" name="prodi" id="prodi" data-placeholder="------> Pilih Program Studi <------">
                                <option value=""> </option>
                                <?php
                                foreach ($prodi['data'] as $val) {
                                    echo '<option value="'.encode($val['id_prodi']).'">'.$val['nama_prodi'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Angkatan :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="tahun" id="tahun" data-placeholder="---> Pilih Tahun <---">
                                <option value=""> </option>
                                <?php
                                foreach (load_array('tahun') as $val) {
                                    echo '<option value="'.$val.'">'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Bank :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="bank" id="bank" data-placeholder="---> Pilih Bank <---">
                                <option value=""> </option>
                                <?php foreach ($bank as $item) : ?>
                                <option value="<?= $item ?>"> Bank <?= $item ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Status :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="status" id="status" data-placeholder="---> Pilih Status <---">
                                <option value=""> </option>
                                <option value="1"> LUNAS </option>
                                <option value="0"> BELUM LUNAS </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Tagihan :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="tagihan" id="tagihan" data-placeholder="---> Pilih Item <---">
                                <option value=""> </option>
                                <?php
                                foreach ($tagihan->result_array() as $val) {
                                    echo '<option value="'.encode($val['id_item']).'">'.$val['nama_item'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Bulan/Tahun :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input type="text" name="bulan" id="bulan" placeholder="Bulan/Tahun" class="date-picker col-xs-12 col-sm-6"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-6">
                        <button class="btn btn-primary btn-white btn-bold" name="cari" id="btn-search" type="button">
                            <i class="ace-icon fa fa-search-plus"></i>
                            Pencarian
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xs-12">
            <div class="widget-box widget-color-blue2">
                <div class="widget-header">
                    <h5 class="widget-title bigger lighter">
                        <i class="ace-icon fa fa-list"></i>
                        <?= $title[1] ?>
                    </h5>
                    <div class="widget-toolbar no-border">
                        <div class="btn-group btn-overlap">
                            <a href="<?= site_url($module.'/add') ?>" class="btn btn-white btn-primary btn-bold">
                                <i class="fa fa-plus-square bigger-110 blue"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-2 table-responsive">
                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice</th>
                                    <th>Program Studi</th>
                                    <th>Virtual Account</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th width="15%">Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- /.col -->
    </div>
    <div class="row">
        <div class="col-xs-12 <?= empty($is_mahasiswa) ? 'hide' : 'hide' ?>">
            <div class="space-6"></div>
            <p id="dua-spin" class="bigger-130 blue" style="display: none" align="center"><i class="fa fa-spinner fa-spin fa-fw fa-2x"></i> Loading . . .</p>
            <div class="widget-box transparent">
                <div class="widget-header">
                    <h5 class="widget-title bigger">
                        <i class="ace-icon fa fa-bar-chart"></i>
                        Grafik Pembayaran
                    </h5>
                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse">
                            <i class="ace-icon fa fa-chevron-up bigger-120"></i>
                        </a>
                    </div>
                    <div class="widget-toolbar">
                        <div class="btn-group btn-overlap">
                            <select class="btn-xs center bolder" id="chartType" data-placeholder="--> Pilih Tipe <--">
                                <option value="">--> Pilih Tipe <--</option>
                                <option value="line">Line</option>
                                <option value="column">Column</option>
                                <option value="bar">Bar</option>
                                <option value="area">Area</option>
                                <option value="areaspline">Area Spline</option>
                            </select>
                        </div>
                    </div>
                    <div class="widget-toolbar no-border">
                        <div class="btn-group btn-overlap">
<!--                            <button class="btn btn-white btn-warning btn-sm btn-bold">
                                BNI <strong class="bigger-120 isBNI"></strong>
                            </button>
                            <button class="btn btn-white btn-primary btn-sm btn-bold">
                                BRI <strong class="bigger-120 isBRI"></strong>
                            </button>-->
                            <button class="btn btn-white btn-purple btn-sm btn-bold">
                                Muamalat <strong class="bigger-120 isBMI"></strong>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main table-responsive">
                        <div id="container_pay" style="min-height: 600px;min-width: 600px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.row -->
</div><!-- /.page-content -->

<?php
    load_js(array(
        'theme/aceadmin/assets/js/dataTables/jquery.dataTables.js',
        'theme/aceadmin/assets/js/dataTables/jquery.dataTables.bootstrap.js',
        'theme/aceadmin/assets/js/select2.js',
        'theme/aceadmin/assets/js/bootbox.min.js',
        'theme/aceadmin/highcharts.js',
        'theme/aceadmin/assets/js/date-time/bootstrap-datepicker.js'
    ));
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    let table;
    $(document).ready(function () {
        $('[data-rel="tooltip"]').tooltip({placement: 'top'});
        $(".select2").select2({allowClear: true});
        $(".select2-chosen").addClass("center");
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "mm-yyyy",
            startView: "months", 
            minViewMode: "months"
        });
        load_table();
    });
    $(document.body).on("click", "#delete-btn", function(event) {
        var id = $(this).attr("itemid");
        var name = $(this).attr("itemprop");
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan menghapus data <br/><b>" + name + "</b> ? </p>";
        bootbox.confirm({title: title, message: msg, 
            buttons: {
                cancel: {label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal",className: "btn btn-sm"},
                confirm: {label: "<i class='ace-icon fa fa-trash-o bigger-110'></i> Hapus",className: "btn btn-sm btn-danger"}
            },
            callback: function(result) {
                if (result === true) {
                    window.location.replace(module + '/delete/' + id);
                }
            }
        });
    });
    $("#chartType").change(function() {
        var selectedType = $(this).val();
        chart_pay.update({
            chart: {
                type: selectedType
            }
        });
    });
    $("#btn-search").click(function () {
        table.fnDraw();
        //grafik_pay();
    });
</script>
<script type="text/javascript">
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
                url: module + "/ajax/type/table/source/mhs",
                type: "POST",
                dataType: "json",
                data: function (val) {
                    val.prodi = $("#prodi").val();
                    val.tahun = $("#tahun").val();
                    val.bank = $("#bank").val();
                    val.status = $("#status").val();
                    val.tagihan = $("#tagihan").val();
                    val.bulan = $("#bulan").val();
                }
            },
            aaSorting: [],
            aoColumnDefs: [
                {bSortable: false, aTargets: [0,7]},
                {bSearchable: false, aTargets: [0,7]},
                {sClass: "center", aTargets: [0, 2, 5, 6]},
                {sClass: "center nowrap", aTargets: [1, 3, 4, 7]}
            ],
            oLanguage: {
                sSearch: "Cari : ",
                sInfoEmpty: "Menampilkan dari 0 sampai 0 dari total 0 data",
                sInfo: "Menampilkan dari _START_ sampai _END_ dari total _TOTAL_ data",
                sLengthMenu: "_MENU_ data per halaman",
                sZeroRecords: "Maaf tidak ada data yang ditemukan",
                sInfoFiltered: "(Menyaring dari _MAX_ total data)",
                sProcessing: "<i class='fa fa-spinner fa-spin fa-fw fa-2x'></i> Loading . . ."
            }
        });
        table.fnAdjustColumnSizing();
    }
    function grafik_pay() {
        $("#dua-spin").show();
        $.ajax({
            url: module + "/ajax/type/chart/source/payment",
            dataType: "json",
            type: "POST",
            data: $("#search-form").serialize(),
            success: function (rs) {
                var series0 = []; 
                var series1 = [];
                var series2 = [];
                if (rs.status) {
                    rs.data.item.map((obj) => {
                        series0.push([obj.day, obj.bni]);
                        series1.push([obj.day, obj.bri]);
                        series2.push([obj.day, obj.bmi]);
                    });
                }else{
                    myNotif('Peringatan', rs.msg, 2);
                }
                chart_pay.series[0].update({ data: series0});
                //chart_pay.series[1].update({ data: series1});
                //chart_pay.series[2].update({ data: series2});
                chart_pay.setTitle(
                    {text: "Total : <strong class='bigger-110'>[ "+ rs.data.total + " ]</strong>"}
                );
                chart_pay.setSubtitle({text: "<strong class='bigger-110'>"+ rs.data.range + "</strong>"});
                chart_pay.redraw();
                
                $(".isBNI").html('Rp '+to_rupiah(rs.data.Tbni)); 
                $(".isBRI").html('Rp '+to_rupiah(rs.data.Tbri));
                $(".isBMI").html('Rp '+to_rupiah(rs.data.Tbmi));
                $("#dua-spin").hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    const option_pay = {
        chart: {
            type: 'areaspline',
            zoomType: 'x',
                events: {
                    //load: grafik_pay()
                }
        },
        xAxis: {
            type: 'category',
            tickmarkPlacement: 'on',
            title: {
                enabled: true
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total'
            },
            labels: {
                formatter: function() {
                    return 'Rp ' + this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            }
        },
        tooltip: {
            crosshairs: true,
            shared: true,
            pointFormatter: function() {
                return '<span style="color:' + this.series.color + '">\u25CF</span> ' 
                    + this.series.name + ': <b>Rp ' + this.y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '</b><br/>';
            }
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
                    enabled: true,
                    formatter: function() {
                        return 'Rp ' + this.y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            },
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            },
            column: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        series: [
//        {
//            name: 'BNI ',
//            data: []
//        },{
//            name: 'BRI ',
//            data: []
//        },
        {
            name: 'Muamalat ',
            data: []
        }]
    };
    const chart_pay = Highcharts.chart('container_pay', option_pay);
</script>
