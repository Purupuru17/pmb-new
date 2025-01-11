<style>
    body{
        background:#fff !important;
    }
    .clock-content .val {
        font-weight: bold;
        color: red;
    }
    .clock-content .typ {
        font-weight: bold;
        color: red;
    }
</style>
<div class="banner" style="background-size:auto !important">
    <div class="container">
        <div class="center">
            <!-- 
            <h1 class="title">Ayo Bergabung Bersama UNIMUDA Sorong</h1>
            <p>
                Universitas Pendidikan Muhammadiyah (UNIMUDA) Sorong, merupakan salah satu Perguruan Tinggi Swasta terbesar di wilayah Indonesia Timur. 
                Berawal dari Sekolah Tinggi Keguruan dan Ilmu Pendidikan (STKIP) Muhammadiyah Sorong, 
                pada tahun 2018 resmi berubah bentuk menjadi Universitas Pendidikan Muhammadiyah (UNIMUDA) Sorong. 
                Di usia mudanya, UNIMUDA Sorong telah terakreditasi institusi B (Baik Sekali) dengan mencatatkan berbagai prestasi 
                dan telah menghasilkan lulusan terbaik yang berdaya saing nasional dan internasional.
            </p>
            Clock -->
            <div id="countdown" class="clock clearfix" style="padding-top:120px">
                <div class="clock-item clock-days countdown-time-value ">
                    <div class="wrap">
                        <div class="inner">
                            <div id="canvas-days" class="clock-canvas"><div class="kineticjs-content" role="presentation" style="position: relative; display: inline-block; width: 160px; height: 160px;"><canvas width="160" height="160" style="padding: 0px; margin: 0px; border: 0px; background: transparent; position: absolute; top: 0px; left: 0px; width: 160px; height: 160px;"></canvas></div></div>

                            <div class="clock-content">
                                <p class="val" id="day">0</p>
                                <p class="typ type-days type-time">DAYS</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clock-item clock-hours countdown-time-value ">
                    <div class="wrap">
                        <div class="inner">
                            <div id="canvas-hours" class="clock-canvas"><div class="kineticjs-content" role="presentation" style="position: relative; display: inline-block; width: 160px; height: 160px;"><canvas width="160" height="160" style="padding: 0px; margin: 0px; border: 0px; background: transparent; position: absolute; top: 0px; left: 0px; width: 160px; height: 160px;"></canvas></div></div>

                            <div class="clock-content">
                                <p class="val" id="hour">24</p>
                                <p class="typ type-hours type-time">HOURS</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clock-item clock-minutes countdown-time-value">
                    <div class="wrap">
                        <div class="inner">
                            <div id="canvas-minutes" class="clock-canvas"><div class="kineticjs-content" role="presentation" style="position: relative; display: inline-block; width: 160px; height: 160px;"><canvas width="160" height="160" style="padding: 0px; margin: 0px; border: 0px; background: transparent; position: absolute; top: 0px; left: 0px; width: 160px; height: 160px;"></canvas></div></div>

                            <div class="clock-content">
                                <p class="val" id="minutes">0</p>
                                <p class="typ type-minutes type-time">MINUTES</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clock-item clock-seconds countdown-time-value ">
                    <div class="wrap">
                        <div class="inner">
                            <div id="canvas-seconds" class="clock-canvas"><div class="kineticjs-content" role="presentation" style="position: relative; display: inline-block; width: 160px; height: 160px;"><canvas width="160" height="160" style="padding: 0px; margin: 0px; border: 0px; background: transparent; position: absolute; top: 0px; left: 0px; width: 160px; height: 160px;"></canvas></div></div>

                            <div class="clock-content">
                                <p class="val" id="second">0</p>
                                <p class="typ type-seconds type-time">SECONDS</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>     
    </div>
</div>
<script type="text/javascript">
    // Set the date we're counting down to
    var countDownDate = new Date("Jan 24, 2022 13:00:00").getTime();
    // Update the count down every 1 second
    var x = setInterval(function() {
      // Get today's date and time
      var now = new Date().getTime();
      // Find the distance between now and the count down date
      var distance = countDownDate - now;
      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
      // Output the result in an element with id="demo"
      document.getElementById("day").innerHTML = days;
      document.getElementById("hour").innerHTML = hours;
      document.getElementById("minutes").innerHTML = minutes;
      document.getElementById("second").innerHTML = seconds;
      // If the count down is over, write some text 
      if (distance < 0) {
        clearInterval(x);
        document.getElementById("countdown").innerHTML = "EXPIRED";
      }
    }, 1000);
</script>
