<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="page-content">
    <!-- /section:settings.box -->
    <div class="row">
        <div class="col-xs-12">
            <div class="error-container">
                <div class="well">
                    <h1 class="grey lighter smaller">
                        <span class="blue bigger-125">
                            <i class="ace-icon fa fa-sitemap"></i>
                            404
                        </span>
                        Halaman Tidak Ditemukan
                    </h1>

                    <hr />
                    <h3 class="lighter smaller red">Halaman yang anda maksud tidak ada dalam sistem ini !</h3>

                    <div>
                        <div class="space"></div>
                        <h4 class="smaller">Perhatikan hal - hal berikut :</h4>

                        <ul class="list-unstyled spaced inline bigger-110 margin-15">
                            <li>
                                <i class="ace-icon fa fa-hand-o-right blue"></i>
                                Cek ulang alamat URL
                            </li>

                            <li>
                                <i class="ace-icon fa fa-hand-o-right blue"></i>
                                Baca petunjuk penggunaan
                            </li>

                            <li>
                                <i class="ace-icon fa fa-hand-o-right blue"></i>
                                Hubungi Administrator
                            </li>
                        </ul>
                    </div>

                    <hr />
                    <div class="space"></div>

                    <div class="center">
                        <a href="javascript:history.back()" class="btn btn-grey">
                            <i class="ace-icon fa fa-arrow-left"></i>
                            Kembali
                        </a>

                        <a href="<?= site_url() ?>" class="btn btn-primary">
                            <i class="ace-icon fa fa-home"></i>
                            Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
<script type="text/javascript">
    jQuery(function ($) {
        jQuery(".nav-list").css("display", "none");
        
        return false;
    });
</script>