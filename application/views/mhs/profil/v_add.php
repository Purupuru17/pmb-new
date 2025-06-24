<?php
$this->load->view('sistem/v_breadcrumb');
?>
<style>
    .control-label{
        margin-right: 6px !important;
    }
    .profile-info-name{
        width: 60px;
        text-align: center;
    }
</style>
<div class="page-content">
    <div class="row">
        <div class="col-xs-12">
            <?= $this->session->flashdata('notif'); ?>
        </div>
        <div class="col-xs-12">
            <div class="row">
                <div class="col-sm-9 col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h4 id="text-header" class="is-reset widget-title bolder"></h4>
                            <div class="widget-toolbar">
                                Diperbarui : 
                                <span id="span-update" class="is-reset label label-grey label-sm"></span>
                                <a href="#" data-action="collapse" class="orange2">
                                    <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main padding-6 no-padding-left no-padding-right">
                                <div id="content-soal" class="is-reset well well-sm"></div>
                                <div class="user-profile row">
                                    <div class="col-xs-12 col-sm-12">
                                        <div id="content-opsi" class="is-reset profile-user-info profile-user-info-striped"></div>
                                    </div>
                                </div>
                                <div class="space-10"></div>
                                <div class="is-done center">
                                    <button id="btn-prev" type="button" class="is-done btn btn-white btn-info btn-round" style="display: none">
                                        <i class="ace-icon fa fa-arrow-left bigger-110"></i> Sebelumnya
                                    </button>
                                    <button id="btn-ragu" align="center" type="button" class="is-done btn btn-white btn-warning btn-round">
                                        <i class="ace-icon fa fa-question bigger-110"></i> Ragu - Ragu
                                    </button>
                                    <button id="btn-next" type="button" class="is-done btn btn-white btn-info btn-round">
                                        Selanjutnya &nbsp;<i class="ace-icon fa fa-arrow-right bigger-110"></i>
                                    </button>
                                </div>
                                <div class="space-4"></div>
                                <div class="center">
                                    <button id="btn-reload" class="btn btn-white btn-default btn-round btn-sm">
                                        <i class="ace-icon fa fa-refresh"></i> Muat Ulang (Refresh)
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h5 class="widget-title">
                                <i class="ace-icon fa fa-list-ol"></i>
                                Timer & Nomor Soal
                            </h5>
                            <div class="widget-toolbar">
                                <a href="#" data-action="collapse" class="orange2">
                                    <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main padding-6 no-padding-left no-padding-right">
                                <div class="center well well-sm">
                                    <span class="label label-danger label-xlg bolder">
                                        <span id="timer" class="bigger-120"><i class="ace-icon fa fa fa-spin fa-spinner"></i></span>
                                    </span>
                                    <div class="social-or-login center">
                                        <span class="bigger-110 bolder"><i class="fa fa-clock-o bigger-110"></i></span>
                                    </div>
                                    <div class="space-2"></div>
                                    <button id="btn-selesai" class="is-done btn btn-lg btn-success btn-round btn-white">
                                        <i class="ace-icon fa fa-check-square-o"></i>
                                        AKHIRI SESI
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-xs btn-default"> KOSONG </button>
                                    <button class="btn btn-xs btn-info"> YAKIN </button>
                                    <button class="btn btn-xs btn-yellow"> RAGU </button>
                                    <button class="btn btn-xs btn-success hide"> BENAR </button>
                                    <button class="btn btn-xs btn-danger hide"> SALAH </button>
                                </div>
                                <div class="space-2"></div>
                                <div id="content-nomor"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.row -->
</div><!-- /.page-content -->
<form id="submit-form" method="POST">
    <input id="jawab_id" value="<?= encode($detail['id_jawab']) ?>" type="hidden"/>
    <input id="soal_id" value="" type="hidden"/>
</form>
<?php
load_js(array(
    'theme/aceadmin/assets/js/bootbox.min.js',
));
?> 
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    $(document).ready(function () {
        $("#sidebar").hide().removeClass('sidebar');
        $("#menu-toggler, #navbar, .navbar-brand").hide();
        list_soal();
    });
    $(document.body).on("click", "#nomor-btn, #btn-next, #btn-prev", function(e) {
        list_soal($(this).attr("itemid"));
    });
    $(document.body).on("click", "#opsi-check", function(event) {
        update_jawab();
    });
    $(document.body).on("click", "#btn-simpan", function(event) {
        var essay = $("#essay").val();
        if(essay === null || essay === ''){
            myNotif('Peringatan', 'Jawaban anda masih kosong', 2, 'swal');
            return;
        }
        update_jawab();
    });
    $(document.body).on("click", "#btn-ragu", function(event) {
        update_jawab('ragu');
    });
    $(document.body).on("click", "#btn-zoom", function(event) {
        $(".is-embed").css('width','100%');
    });
    $("#btn-selesai").click(function(){
        tanya_pertama();
    });
    $("#btn-reload").click(function(){
        list_soal($("#btn-ragu").attr("itemid"));
    });
</script>
<script type="text/javascript">
    function list_soal(no = 1) {
        let nomor = Math.abs(no);
        if(nomor === '' || nomor === 0){
            return;
        }
        $(".is-reset").html('<i class="ace-icon fa fa fa-spin fa-spinner bigger-150"></i>');
        $(".is-done").attr("disabled","disabled");
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Memuat Pertanyaan . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
        $.ajax({
            url: module + "/ajax/type/list/source/soal",
            dataType: "json",
            type: "POST",
            data: { id: $("#jawab_id").val(), no: nomor },
            success: function (rs) {
                if (rs.status) {
                    $("#soal_id").val(rs.data.id);
                    $("#text-header").html('No. '+ rs.data.soal.order_quiz +
                        '  <span class="orange bigger-110">[' + rs.data.soal.materi_soal + '] </span>');
                    $("#content-soal").html(rs.data.content_soal);
                    $("#content-opsi").html(rs.data.content_opsi);
                    $("#content-nomor").html(rs.data.content_nomor);
                    $("#span-update").html(rs.data.waktu);
                    
                    $("#btn-prev").attr("itemid", rs.data.prev);
                    $("#btn-ragu").attr("itemid", rs.data.nomor);
                    $("#btn-next").attr("itemid", rs.data.next);
                    
                    (rs.data.prev === '') ? $("#btn-prev").hide() : $("#btn-prev").show();
                    (rs.data.next === '') ? $("#btn-next").hide() : $("#btn-next").show();
                    count_down(rs.data);
                } else {
                    $(".is-reset").html("");
                    myNotif('Peringatan', rs.msg, 2, 'swal');
                }
                setTimeout(function () {
                    progress.modal("hide");
                }, 1000);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log('Error : ' + xhr.responseText);
                progress.modal("hide");
            }
        });
    }
    function update_jawab(status = 'valid') {
        $.ajax({
            url: module + "_do/ajax/type/action/source/update",
            dataType: "json",
            type: "POST",
            data: { 
                id: $("#jawab_id").val(), soal: $("#soal_id").val(),
                opsi: $("input[name='opsi']:checked").val(),
                essay: $("#essay").val(), status: status
            },
            success: function (rs) {
                if (rs.status) {
                    list_soal($("#btn-next").attr("itemid"));
                    $("#span-update").html(rs.waktu);
                    
                    myNotif('Informasi', rs.msg, 1);
                } else {
                    myNotif('Peringatan', rs.msg, 2, 'swal');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function done_jawab() {
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({ title: title, message: msg, closeButton: false });
        $.ajax({
            url: module + "_do/ajax/type/action/source/done",
            dataType: "json",
            type: "POST",
            data: { 
                id: $("#jawab_id").val(), soal: $("#soal_id").val()
            },
            success: function (rs) {
                if (rs.status) {
                    myNotif('Informasi', rs.msg, 1, 'swal');
                    setTimeout(function () {
                        progress.modal("hide");
                        window.location.replace(module);
                    }, 3000);
                } else {
                    progress.modal("hide");
                    myNotif('Peringatan', rs.msg, 2, 'swal');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log('Error : ' + xhr.responseText);
                progress.modal("hide");
            }
        });
    }
    function count_down(data) {
        $("#submit-form").attr("itemid", data.limit_time);
        $("#submit-form").attr("itemname", data.is_done);
        // Update the count down every 1 second
        const timer = setInterval(function () {
            // Set the date we're counting down to
            var countDownDate = new Date($("#submit-form").attr("itemid")).getTime();
            var isDone = $("#submit-form").attr("itemname");
            // Get today's date and time
            var now = new Date().getTime();
            // Find the distance between now and the count down date
            var distance = countDownDate - now;
            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            // Display the result in the element with id="demo"
            var count = hours + " Jam - " + minutes + " Menit - " + seconds + " Detik";
            $("#timer").html(count);
            // If the count down is finished, write some text
            if (isDone === "true" || isDone === true) {
                clearInterval(timer);
                $(".is-done").attr("disabled","disabled");
                $("#timer").html("----------------");
                $("#content-soal, #content-opsi").addClass('hide');
            } else if (distance < 0) {
                clearInterval(timer);
                $(".is-done").attr("disabled","disabled");
                $("#timer").html("Waktu Pengerjaan Telah Selesai");
                $("#content-soal, #content-opsi").addClass('hide');
                done_jawab();
            } else{
                $(".is-done").removeAttr("disabled");
                $("#content-soal, #content-opsi").removeClass('hide');
            }
        }, 1000);
    }
    function tanya_pertama(){
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan mengakhiri sesi ini ? </p>";
        bootbox.confirm({ title: title, message: msg, 
            buttons: {
                cancel: {
                    label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm"
                },
                confirm: {
                    label: "<i class='ace-icon fa fa-check bigger-110'></i> Iya, Akhiri Sesi Ini (1)", className: "btn btn-sm btn-danger"
                }
            },
            callback: function(result) {
                if (result === true) {
                   tanya_kedua();
                }
            }
        });
    }
    function tanya_kedua(){
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan mengakhiri sesi ini ? </p>";
        bootbox.confirm({ title: title, message: msg, 
            buttons: {
                cancel: {
                    label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm"
                },
                confirm: {
                    label: "<i class='ace-icon fa fa-check bigger-110'></i> Iya, Akhiri Sesi Ini (2)", className: "btn btn-danger"
                }
            },
            callback: function(result) {
                if (result === true) {
                   tanya_akhir();
                }
            }
        });
    }
    function tanya_akhir(){
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan mengakhiri sesi ini ? </p>";
        bootbox.confirm({ title: title, message: msg, 
            buttons: {
                cancel: {
                    label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm"
                },
                confirm: {
                    label: "<i class='ace-icon fa fa-check bigger-110'></i> Iya, Akhiri Sesi Ini (3)", className: "btn btn-lg btn-danger"
                }
            },
            callback: function(result) {
                if (result === true) {
                   done_jawab();
                }
            }
        });
    }
</script>