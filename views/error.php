<?php
include("views/templates/head.php"); ?>

<body onload="getTime()">

<div class="container">

    <div class="col-lg-4 col-lg-offset-4">
        <div class="lock-screen">
            <?php
            if ($login->messages) {
                foreach ($login->messages as $message) {
                    echo '<div style="margin-top: 200px;" class="alert alert-info animated infinite bounce" role="alert">' . $message . '</div>';
                }
            } elseif ($login->errors) {
                foreach ($login->errors as $error) {
                    echo '<div style="margin-top: 200px;" class="alert alert-danger animated infinite bounce" role="alert">' . $error . '</div>';
                }
            } else echo '<div style="margin-top: 270px;"></div>' ?>
        </div>
    </div>
</div>

<!-- js placed at the end of the document so the pages load faster -->
<script src="<?php echo $settings['url'] ?>assets/js/jquery.js"></script>
<script src="<?php echo $settings['url'] ?>assets/js/bootstrap.min.js"></script>

<!--BACKSTRETCH-->
<!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
<script type="text/javascript" src="<?php echo $settings['url'] ?>assets/js/jquery.backstretch.min.js"></script>
<script>
    $.backstretch([
        "<?php echo $settings['url'] ?>assets/img/bg/1.jpg",
        "<?php echo $settings['url'] ?>assets/img/bg/2.jpg",
        "<?php echo $settings['url'] ?>assets/img/bg/3.jpg",
        "<?php echo $settings['url'] ?>assets/img/bg/4.jpg",
        "<?php echo $settings['url'] ?>assets/img/bg/5.jpg"
    ], {duration: 10000, fade: 900});
</script>

<script>
    function getTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        // add a zero in front of numbers<10
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('showtime').innerHTML = h + ":" + m + ":" + s;
        t = setTimeout(function () {
            getTime()
        }, 500);
    }

    function checkTime(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }
</script>

</body>
</html>

