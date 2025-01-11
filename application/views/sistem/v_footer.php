<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="footer">
    <div class="footer-inner">
        <!-- #section:basics/footer -->
        <div class="footer-content">
            <span class="">
                <span class="blue"><?= $app['cipta'] ?> &copy; 2020</span>
                <small class="grey">{elapsed_time} detik ~ {memory_usage}</small>
            </span>
            
        </div>
        <!-- /section:basics/footer -->
    </div>
</div>
<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
    <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
</a>
<script type="text/javascript">
    var module_ajax = "<?= site_url('non_login/ajax/routing'); ?>";
<?php
if (!empty($this->session->userdata('logged'))) {
    ?>
        function update_session() {
            var action = '';
            $.ajax({
                url: module_ajax + "/type/action/source/session",
                type: "POST",
                dataType: "json",
                data: {action: action},
                success: function(rs) {
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError);
                }
            });
        }
        function notif_user(notif_id = '') {
            $.ajax({
                url: module_ajax + "/type/list/source/notif",
                type: "POST",
                dataType: "json",
                data: {id: notif_id},
                success: function(rs) {
                    if (rs.status) {
                        $.each(rs.data, function(key, value) {
                            $('#li-notif').append('<li id="' + value.id + '" class="' + value.status + '"><a href="' + value.link + '" class="clearfix">' +
                                    '<span class="msg-body" style="margin-left:5px">' +
                                    '<span class="msg-title">' +
                                    '<span class="blue bigger-120 bolder">' + value.subject + '</span><br/><span class="grey">' + value.msg +
                                    '</span></span>' +
                                    '<span class="msg-time">' +
                                    '<i class="smaller-90 ace-icon fa fa-clock-o"></i>' +
                                    '<span class=""> ' + value.time + '</span>' +
                                    '</span>' +
                                    '</span>' +
                                    '</a></li>');
                        });
                    }
                    $('span#item-notif').html(rs.item);
                    $('span#new-notif').html(rs.item);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError);
                }
            });
        }
        $(document).on('click', '.un-read', function() {
            var id = $(this).attr("id");
            notif_user(id);
        });

        $(document).ready(function() {
//            notif_user();
            update_session();
        });
    <?php
}
?>
</script>
<script language="javascript">
    $(document).ready(function() {
        setInterval(function timer() {
            now = new Date();
            if (now.getTimezoneOffset() == 0)
                (a = now.getTime() + (7 * 60 * 60 * 1000))
            else
                (a = now.getTime());
            now.setTime(a);
            var tahun = now.getFullYear()
            var hari = now.getDay()
            var bulan = now.getMonth()
            var tanggal = now.getDate()
            var hariarray = new Array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu")
            var bulanarray = new Array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember")

            var waktu = hariarray[hari] + ", " + tanggal + " " + bulanarray[bulan] + " " + tahun + " | " + (((now.getHours() < 10) ? "0" : "") + now.getHours() + ":" + ((now.getMinutes() < 10) ? "0" : "") + now.getMinutes() + ":" + ((now.getSeconds() < 10) ? "0" : "") + now.getSeconds() + (" WIT "));
            $(".jam").html(waktu);
        }, 1000);
    });
</script>
