<?php if ($settings['news']) { ?>
<div id="news"></div>
    <script>
    $.ajax({
        url: "http://cyberbyte.org.uk/hooks/cyberworks/news.php?id=<?php echo $settings['id']?>",
        dataType: 'json',
        complete: function(data) {
            var txt = '<br><br><div class="container-fluid ds"><div class="content-panel"><h3><?php echo $lang['news'] ?></h3>';
            $.each(data['responseJSON'], function(i, item) {
                txt += '<div class="desc"><div class="thumb"><span class="badge bg-theme-01" ><i class="fa fa-clock-o"></i></span></div>';
                txt += '<div class="details"><p><muted>'+item['date']+'</muted><br/><a href="'+item['link']+'">';
                txt += item['title']+'</a> '+item['text']+'<br/></p></div></div>';
            });
            txt += '</div></div>';
            $( "#news" ).append(txt);
        }
    });
    </script>
<?php
}