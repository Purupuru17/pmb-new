<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<footer class="main-footer" style="padding: 10px">
    <div class="container">
        <div class="row">

            <div class="widget col-md-6">
                <div class="">
                    <p style="color: #fff; font-size: 12px">
                        <?= $app_session['deskripsi'] ?>
                        <br/>
                        <b><?= $app_session['cipta'] ?></b> &copy; <?= APP_VER ?> 
                        | <small>{elapsed_time} detik ~ {memory_usage}</small>
                    </p>
                </div>     
            </div>
        </div> 
    </div>
</footer>
