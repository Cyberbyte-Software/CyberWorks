<?php
$sql = "SELECT `sid` FROM `servers` WHERE `use_sq` = 1 AND `sid` = " . $sid . ";";
$result_of_query = $db_connection->query($sql);
if ($result_of_query->num_rows == 1) { ?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?php echo $lang['players']; ?>
            <small><?php echo " " . $lang['overview']; ?></small>
        </h1>
    </div>
</div>
<script>
function kick(id) {
    $.post( "<?php echo $settings['url'] ?>hooks/rcon_kick.php", { id: id, sid: "<?php echo $sid ?>"} );
    getPlayers();
}
function say() {
    var command = 'Say -1 ' + document.getElementById("messageText").value;
    document.getElementById("messageText").value = "";
    $.post( "<?php echo $settings['url'] ?>hooks/rcon_command.php", {  sid: "<?php echo $sid ?>", command: command} );
}
function runCommand() {
    var command = document.getElementById("command").value;
    document.getElementById("command").value = "";
    $.post( "<?php echo $settings['url'] ?>hooks/rcon_command.php", { sid: "<?php echo $sid ?>", command: command} );
}
function getPlayers() {
    $.ajax({
        url: "<?php echo $settings['url'] . 'hooks/rcon_players.php?sid=' . $sid ?>",
        dataType: 'json',
        complete: function(data) {
            console.log(data);
            var txt = '';
            if (data['responseText'] !== '') {
            if (data['responseText'] !== '[]') {
            $.each(data['responseJSON'], function(i, item) {
                txt += '<tr><td>'+item['4'];
                if (typeof item['5'] !== 'undefined') {
                    txt += ' '+item['5'];
                }
                txt += '</td><td>'+item['1'].replace(":2304","")+'</td><td>'+item['2']+'</td>';
                txt += '<td>'+item['3']+'</td><td><button class="btn btn-danger btn-xs fa fa-exclamation-triangle" onclick="kick(this.id)" id="';
                txt += item['0']+'"></button><a style="margin-left: 3px;" href="<?php echo $settings['url'] ?>players/'+item['4']+'" class="btn btn-info btn-xs fa fa-search"></button></td></tr>';
            });
            } else txt = "<center><h2><?php echo $lang['noPlayers'] ?></h2></center>";
            } else txt = "<center><h2><?php echo $lang['cannotConnect'] ?></h2></center>";
            $( "#players" ).html(txt);
        }
    });
    }
</script>
	<div class="content-panel">
		<table class="table table-striped table-advance table-hover">
			<h4>
				<i class="fa fa-child fa-fw"></i><?php echo " " . $lang['players']; ?>
				<div style="float:right; padding-right: 20px;" id="count"></div>
			</h4>
			<hr>
			<thead>
				<tr>
					<th><i class="fa fa-user"></i> <?php echo $lang['name']; ?></th>
					<th><i class="fa fa-signal"></i> IP</th>
					<th><i class="fa fa-heartbeat"></i> Ping</th>
					<th><i class="fa fa-lock"></i> <?php echo $lang['GUID']; ?></th>
					<th><i class="fa fa-exclamation-triangle"></i> Kick</th>
				</tr>
			</thead>
                    <tbody id="players">
                    <script>
                    getPlayers();
                    var count = <?php echo $settings['refresh'] + 1 ?>;
                    setInterval(function () {
                        count--;
                        if (count == 0) {
                            getPlayers();
                            count = <?php echo $settings['refresh'] ?>;
                        }
                        $( "#count" ).html('<?php echo $lang['refresh'] ?>: ' + count);
                    }, 1000);
                    </script>
                    </tbody>
                </table>
                <div class="form-inline" style="text-align:center;">
                    <div class="form-group">
                        <input class='form-control' id='messageText' type='text' name='messageText'>
                        <input class='btn btn-primary' type='button' name='search' onclick='say()' value='<?php echo $lang['say']?>'>
                    </div>
                    <script src="<?php echo $settings['url'] ?>assets/js/awesomplete.min.js" async></script>
                    <div class="form-group">
                        <input class='form-control awesomplete' id='command' type='text' name='command'
                        data-list="loadScripts, MaxPing, Say, #mission, addBan, ban, removeBan">
                        <input class='btn btn-primary' type='button' name='search' onclick='runCommand()' value='<?php echo $lang['execute']?>'>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
} else {
    echo errorMessage(11, $lang);
}
;
