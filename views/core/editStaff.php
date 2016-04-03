<?php
$sql = 'SELECT * FROM `users` WHERE `user_id` ="' . $uId . '";';
$result_of_query = $db_connection->query($sql);

if ($result_of_query->num_rows > 0) {
    $user = $result_of_query->fetch_object();
	
	if($_SESSION['user_level'] >= $user->user_level) {

    if (isset($_POST["staffName"])) {
        if (formtoken::validateToken($_POST)) {
            $staffName = $_POST['staffName'];
            $staffEmail = $_POST['staffEmail'];
            $staffPID = $_POST['staffPID'];
            $permissions = include 'config/permissions.php';
            if (isset($_POST['ban'])) {
				if ($user->user_level == 5 && $_SESSION['user_level'] < 5) { $staffRank = 5; } else { $staffRank = 0; }
			} else {
				if ($_POST['staffRank'] > $_SESSION['user_level'] || $user->user_level > $_SESSION['user_level']) { $staffRank = $user->user_level; } else { $staffRank = $_POST['staffRank']; }
			}
	    	$userPerms = json_encode($permissions[$staffRank]);
	
            $sql = "UPDATE `users` SET `user_name`='" . $staffName . "',`user_email`='" . $staffEmail . "',`playerid`='" . $staffPID . "',`user_level`='" . $staffRank . "', `permissions`='" . $userPerms . "' WHERE `user_id` ='" . $uId . "';";
            $result_of_query = $db_connection->query($sql);
			if ($user->user_level != $_POST['staffRank']) logAction($_SESSION['user_name'], $lang['edited'] . " " . $_POST['staffName'] . "\'s " . $lang['staff'] . " " . $lang['rank'] . " " . $lang['from'] . " (" . $settings['ranks'][$user->user_level] . ") " . $lang['to'] . " (" . $settings['ranks'][$_POST['staffRank']] . ")", 2);
			if ($user->user_name != $_POST['staffName']) logAction($_SESSION['user_name'], $lang['edited'] . " " . $user->user_name . "\'s " . strtolower($lang['name']) . " " . $lang['to'] . " " . $_POST['staffName'] . "", 2);
			if ($user->playerid != $_POST['staffPID']) logAction($_SESSION['user_name'], $lang['edited'] . " " . $_POST['staffName'] . "\'s " . $lang['player'] . " " . $lang['id'] . " " . $lang['from'] . " (" . $user->playerid . ") " . $lang['to'] . " (" . $_POST['staffPID'] . ")", 2);
			if ($user->user_email != $_POST['staffEmail']) logAction($_SESSION['user_name'], $lang['edited'] . " " . $user->user_name . "\'s " . strtolower($lang['email']) . " " . $lang['from'] . " (" . $user->user_email . ") " . $lang['to'] . " (" . $_POST['staffEmail'] . ")", 2);
			
            message(ucfirst($_POST['staffName']) . ' ' . $lang['updated']);
        } else message($lang['expired']);
    }
    if (isset($_POST["viewPlayer"])) {
        if (formtoken::validateToken($_POST)) {
            if ($user->user_level == 5) {
                $permissions['super_admin'] = 1;
            } else {
                $permissions['super_admin'] = 0;
            }
	
            $permissions['permissions']['view'] = $_POST['viewPerms'];
            $permissions['permissions']['edit'] = $_POST['editPerms'];
	
            $permissions['view']['staff'] = $_POST['viewStaff'];
            $permissions['view']['update'] = '1';
            $permissions['view']['vehicles'] = $_POST['viewVehicles'];
            $permissions['view']['houses'] = $_POST['viewHouses'];
            $permissions['view']['gangs'] = $_POST['viewGangs'];
            $permissions['view']['wanted'] = $_POST['viewWanted'];
            $permissions['view']['steam'] = $_POST['viewSteam'];
            $permissions['view']['player'] = $_POST['viewPlayer'];
            $permissions['view']['licences'] = $_POST['viewLic'];
            $permissions['view']['messages'] = $_POST['viewMSG'];
            $permissions['view']['notes'] = $_POST['viewNotes'];
            $permissions['view']['logs'] = $_POST['viewLogs'];
            $permissions['view']['curplayer'] = $_POST['viewCurPlayer'];
            $permissions['view']['gamesrv'] = $_POST['gamesrv'];
            $permissions['view']['gamesrvAdmin'] = $_POST['gamesrvAdmin'];
	
            $permissions['edit']['staff'] = $_POST['editStaff'];
            $permissions['edit']['update'] = '1';
            $permissions['edit']['vehicles'] = $_POST['editVehicles'];
            $permissions['edit']['houses'] = $_POST['editHouses'];
            $permissions['edit']['gangs'] = $_POST['editGangs'];
            $permissions['edit']['wanted'] = $_POST['editWanted'];
            $permissions['edit']['steam'] = $_POST['viewSteam'];
            $permissions['edit']['player'] = $_POST['editPlayer'];
            $permissions['edit']['licences'] = $_POST['editLIC'];
            $permissions['edit']['inventory'] = $_POST['editINV'];
            $permissions['edit']['server'] = $_POST['editServer'];
            $permissions['edit']['ranks'] = $_POST['editRanks'];
            $permissions['edit']['bank'] = $_POST['editBank'];
            $permissions['edit']['ignLVL'] = $_POST['editignLVL'];
            $permissions['edit']['notes'] = $_POST['addNote'];
	
            $userPerms = json_encode($permissions);
	
            $sql = "UPDATE `users` SET `permissions`='" . $userPerms . "' WHERE `user_id` ='" . $uId . "';";
            $result_of_query = $db_connection->query($sql);
            message("Permissions Updated");
            session_destroy();
            session_start();
        } else message($lang['expired']);
    }
    ?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <?php echo $lang['staff']; ?>
                <small> <?php echo $lang['editor']; ?></small>
            </h1>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-users fa-fw"></i><?php echo " " . $lang['staff']; ?></h3>
            </div>
            <div class="panel-body">
                <?php
                echo '<form method="post" action="' . $settings['url'] . 'editStaff/' . $uId . '" name="editform">';
                echo formtoken::getField();
				
                echo "<center>";
                echo "<h4>" . $lang['name'] . ":  <input id='staffName' class='form-control' name='staffName' type='text' value='" . $user->user_name . "'></h4>";
                echo "<h4>" . $lang['emailAdd'] . ": <input id='staffEmail' class='form-control' name='staffEmail' type='text' value='" . $user->user_email . "'></h4>";
				echo "<h4>" . $lang['rank'] . ": ";
				echo "<select id='staffRank' class='form-control' name='staffRank'>";
				
				for ($lvl = 0; $lvl <= $_SESSION['user_level']; $lvl++) {
					echo '<option value="' . $lvl . '"' . select($lvl, $user->user_level) . '>' . $settings['ranks'][$lvl] . '</option>';
				}
				
                echo "</select></h4>";
                echo "<h4>" . $lang['playerID'] . ":  <input id='staffPID' class='form-control' name='staffPID' type='text' value='" . $user->playerid . "'></h4>";
                echo "</center>";

                echo "<input id='user_id' type='hidden' name='user_id' value='" . $uId . "'>";
                echo "<center><input class='btn btn-lg btn-primary'  type='submit'  name='edit' value='" . $lang['subChange'] . "'>";
                if ($_SESSION['user_id'] <> $uId) {
                                    echo "  <input class='btn btn-lg btn-danger' type='submit'  name='ban' value='" . $lang['ban'] . "'>";
                }
                ?>
                </center>
                </form>
            </div>
        </div>
    </div>
    <?php if ($_SESSION['permissions']['permissions']['view']) { ?>
	<div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-users fa-fw"></i><?php echo $lang['perms']; ?></h3>
            </div>
            <div class="panel-body">
				<?php echo "<form method='post' action='" . $settings['url'] . 'editStaff/' . $uId . "' name='permsUpdate'>";
                $permissions = json_decode($user->permissions,true);
                ?>
				<div class="col-md-4" style='padding-right: 0px; padding-left: 0px;'>
					<div class="panel-heading">
						<div class="pull-left"><h5><i class="fa fa-tasks"></i><?php echo ' '.$lang['admin'].' '.$lang['perms']; ?></h5></div>
							<br>
						</div>
						<div class="panel-body">
							<div class="task-content">
								<ul id="sortable" class="task-list ui-sortable">
									<?php if (select('1', $permissions['permissions']['view'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewPerms" id="viewPerms">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewPerms" id="viewPerms">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } if (select('1', $permissions['permissions']['edit'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_edit']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editPerms" id="editPerms">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_edit']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editPerms" id="editPerms">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['view']['notes'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_notes']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewNotes" id="viewNotes">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_notes']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewNotes" id="viewNotes">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['edit']['notes'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_edit_notes']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="addNote" id="addNote">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_edit_notes']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="addNote" id="addNote">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['view']['logs'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_logs']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewLogs" id="viewLogs">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_logs']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewLogs" id="viewLogs">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['edit']['server'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_edit_server']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editServer" id="editServer">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_edit_server']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editServer" id="editServer">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['edit']['staff'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_edit_staff']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editStaff" id="editStaff">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_edit_staff']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editStaff" id="editStaff">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['view']['staff'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_staff']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewStaff" id="viewStaff">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_staff']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewStaff" id="viewStaff">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>


									<?php if (select('1', $permissions['view']['gamesrvAdmin'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_gamesrvAdmin']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="gamesrvAdmin" id="gamesrvAdmin">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_gamesrvAdmin']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="gamesrvAdmin" id="gamesrvAdmin">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>
								</ul>
							</div>
						</div>


				</div>
				<div class="col-md-4" style='padding-right: 0px; padding-left: 0px;'>
					<div class="panel-heading">
						<div class="pull-left"><h5><i class="fa fa-tasks"></i><?php echo ' ' . $lang['perm_view']; ?></h5></div>
							<br>
						</div>
						<div class="panel-body">
							<div class="task-content">
								<ul id="sortable" class="task-list ui-sortable">
									<?php if (select('1', $permissions['view']['player'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_player']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewPlayer" id="viewPlayer">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_player']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewPlayer" id="viewPlayer">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['view']['vehicles'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_vehicles']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewVehicles" id="viewVehicles">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_vehicles']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewVehicles" id="viewVehicles">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['view']['houses'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_houses']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewHouses" id="viewHouses">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_houses']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewHouses" id="viewHouses">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['view']['gangs'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_gangs']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewGangs" id="viewGangs">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_gangs']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewGangs" id="viewGangs">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['view']['wanted'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_wanted']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewWanted" id="viewWanted">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_wanted']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewWanted" id="viewWanted">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['view']['licences'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_licences']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewLic" id="viewLic">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_licences']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewLic" id="viewLic">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['view']['messages'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_messages']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewMSG" id="viewMSG">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_messages']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewMSG" id="viewMSG">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['view']['curplayer'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_curplayer']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewCurPlayer" id="viewCurPlayer">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_curplayer']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewCurPlayer" id="viewCurPlayer">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['view']['steam'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_steam']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewSteam" id="viewSteam">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_steam']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="viewSteam" id="viewSteam">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['view']['gamesrv'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_gamesrv']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="gamesrv" id="gamesrv">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo $lang['perm_view_gamesrv']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="gamesrv" id="gamesrv">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>
								</ul>
							</div>
						</div>
				</div>
				<div class="col-md-4" style='padding-right: 0px; padding-left: 0px;'>
					<div class="panel-heading">
						<div class="pull-left"><h5><i class="fa fa-tasks"></i><?php echo ' ' . $lang['perm_edit']; ?></h5></div>
							<br>
						</div>
						<div class="panel-body">
							<div class="task-content">
								<ul id="sortable" class="task-list ui-sortable">
									<?php if (select('1', $permissions['edit']['player'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_player']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editPlayer" id="editPlayer">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_player']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editPlayer" id="editPlayer">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['edit']['vehicles'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_vehicles']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editVehicles" id="editVehicles">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_vehicles']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editVehicles" id="editVehicles">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['edit']['houses'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_houses']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editHouses" id="editHouses">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_houses']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editHouses" id="editHouses">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['edit']['gangs'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_gangs']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editGangs" id="editGangs">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_gangs']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editGangs" id="editGangs">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['edit']['wanted'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_wanted']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editWanted" id="editWanted">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_wanted']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editWanted" id="editWanted">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['edit']['licences'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_licences']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editLIC" id="editLIC">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_licences']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editLIC" id="editLIC">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>

									<?php if (select('1', $permissions['edit']['inventory'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_inventory']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editINV" id="editINV">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_inventory']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editINV" id="editINV">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>
									<?php if (select('1', $permissions['edit']['ranks'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_ranks']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editRanks" id="editRanks">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_ranks']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editRanks" id="editRanks">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>
									<?php if (select('1', $permissions['edit']['bank'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_bank']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editBank" id="editBank">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_bank']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editBank" id="editBank">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>
									<?php if (select('1', $permissions['edit']['ignLVL'])) { ?>
									<li class="list-success">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_ignLVL']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editignLVL" id="editignLVL">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } else {?>
									<li class="list-danger">
										<i class=" fa fa-ellipsis-v"></i>
										<div class="task-title">
											<span class="task-title-sp"><?php echo ' ' . $lang['perm_edit_ignLVL']; ?></span>
											<?php if ($_SESSION['permissions']['permissions']['edit']) { ?>
											<div class="pull-right hidden-phone" style="padding-right:5px;">
												<select name="editignLVL" id="editignLVL">
													<option value="1">Yes</option>
													<option value="0" selected>No</option>
												</select>
											</div>
											<?php } ?>
										</div>
									</li>
									<?php } ?>
								</ul>
							</div>
							<center><input class='btn btn-lg btn-primary'  type='submit'  name='edit' value='<?php echo $lang['subChange']; ?>'>
							<?php echo formtoken::getField() ?>
						</div>
					</div>
                </form>
            </div>
        </div>
    </div>
    <?php } ?>
<?php
} else {
	echo '<h3>' . errorMessage(5, $lang) . '</h3>';
}

} else {
    echo errorMessage(3, $lang);
}
